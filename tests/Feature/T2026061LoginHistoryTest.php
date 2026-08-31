<?php

namespace Tests\Feature;

use App\Jobs\StoreLoginLocation;
use App\Models\LoginHistory;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * T-2026-061 — Login History List page always empty (no INSERT into login_history).
 *
 * These tests exercise the REAL production classes (LoginController::submitLogin(),
 * App\Models\LoginHistory, App\Jobs\StoreLoginLocation) end-to-end via a real HTTP
 * POST to the real /login route, against a real database (SQLite, in-memory),
 * since the project's primary MySQL connection (127.0.0.1:3307) is unavailable in
 * this environment.
 *
 * No production code is modified anywhere in this file. The only "fakes" involved
 * are:
 *   - the DB connection itself (sqlite :memory: instead of mysql), built with a
 *     schema that is a byte-for-byte copy of the real migrations for `users` and
 *     `login_history`;
 *   - the `captcha` container singleton (anhskohbo/no-captcha), rebound to a stub
 *     that always returns true from verifyResponse() — this package talks to
 *     Google's real reCAPTCHA API over HTTP via a raw Guzzle client (not the
 *     Laravel Http facade), so it cannot be faked with Http::fake() and must be
 *     stubbed at the container level to make the request reach LoginController at
 *     all. This does not touch any of the code under test.
 *   - Queue::fake(), used only to assert dispatch conditions/arguments without
 *     actually running StoreLoginLocation::handle() (which calls a real external
 *     reverse-geocoding API).
 */
class T2026061LoginHistoryTest extends TestCase
{
    protected function setUp(): void
    {
        // The app's own AppServiceProvider::boot() unconditionally runs
        // `DB::statement("SET time_zone = ...")` against the DEFAULT connection
        // during application bootstrap (before any test code runs), so the DB
        // connection must be switched to SQLite in-memory *before* the app boots
        // (i.e. before parent::setUp() calls createApplication()), not after.
        // Dotenv is immutable by default, so putenv() here wins over .env's
        // DB_CONNECTION=mysql without touching .env/phpunit.xml/config on disk.
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');
        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = ':memory:';
        $_SERVER['DB_CONNECTION'] = 'sqlite';
        $_SERVER['DB_DATABASE'] = ':memory:';
        // Illuminate\Support\Env memoizes an IMMUTABLE Dotenv repository in a
        // process-wide static property the first time env()/Env::get() is ever
        // called (e.g. by an earlier test class's boot in the same PHPUnit
        // process). Once memoized it never re-reads putenv(), so if any other
        // test file already booted the app before this one, the putenv() calls
        // above would silently have no effect. enablePutenv() is the documented
        // way to force that static cache to reset and rebuild from the current
        // process environment.
        \Illuminate\Support\Env::enablePutenv();

        // App\Providers\AppServiceProvider::boot() unconditionally issues a raw
        // `SET time_zone = '+05:30'` statement (MySQL-only syntax) against the
        // default connection during boot. SQLite has no SET statement, so give
        // Laravel a connection resolver (its documented extension point for
        // customizing a driver's Connection class) that makes that one specific
        // bootstrap statement a no-op on sqlite while leaving every other query
        // (including everything this test itself runs) as a real, unmodified
        // SQLite query. This is test wiring only — App\Providers\AppServiceProvider
        // itself is untouched.
        Connection::resolverFor('sqlite', function ($pdo, $database, $prefix, $config) {
            return new class($pdo, $database, $prefix, $config) extends SQLiteConnection {
                public function statement($query, $bindings = [])
                {
                    if (stripos(trim($query), 'SET time_zone') === 0) {
                        return true;
                    }

                    return parent::statement($query, $bindings);
                }
            };
        });

        parent::setUp();

        // This project's .env APP_URL includes a local XAMPP subdirectory prefix
        // (http://localhost/shreerag_final_updated). Laravel's HTTP test helpers
        // build request URLs via the url() helper, and UrlGenerator caches its
        // root URL at construction time (during app boot), so a plain config()
        // write here is too late — URL::forceRootUrl() is the documented way to
        // override it post-boot. Left unchanged, every $this->get()/post() in
        // this test would request '/shreerag_final_updated/login' and 404
        // against the real 'login' route — a test-harness artifact of this
        // project's local subdirectory hosting, not a bug in the code under test.
        \Illuminate\Support\Facades\URL::forceRootUrl('http://localhost');

        DB::purge('sqlite');

        // Schema copied verbatim from database/migrations/2014_10_12_000000_create_users_table.php
        Schema::connection('sqlite')->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('u_email')->unique();
            $table->string('u_password');
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('role_id');
            $table->string('f_name')->nullable();
            $table->string('m_name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('number')->nullable();
            $table->string('designation')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('department_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->rememberToken();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // Schema copied verbatim from database/migrations/2025_08_19_175136_create_login_history.php
        Schema::connection('sqlite')->create('login_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('location_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        DB::connection('sqlite')->table('users')->insert([
            'id'         => 1,
            'u_email'    => 'tester@example.com',
            'u_password' => Hash::make('secret123'),
            'org_id'     => 1,
            'role_id'    => 99,
            'is_active'  => 1,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Stub the no-captcha package's container singleton so the real
        // g-recaptcha-response validation rule (which otherwise makes a real
        // HTTP call to Google) passes deterministically in tests.
        $this->app->instance('captcha', new class {
            public function verifyResponse($response, $clientIp = null)
            {
                return true;
            }
        });
    }

    protected function tearDown(): void
    {
        // Restore the real env vars so this file cannot leak sqlite config into
        // any other test file/class run later in the same PHPUnit process.
        putenv('DB_CONNECTION=mysql');
        putenv('DB_DATABASE=latest_new_shree_db');
        $_ENV['DB_CONNECTION'] = 'mysql';
        $_ENV['DB_DATABASE'] = 'latest_new_shree_db';
        $_SERVER['DB_CONNECTION'] = 'mysql';
        $_SERVER['DB_DATABASE'] = 'latest_new_shree_db';
        // Force Illuminate\Support\Env's memoized repository to rebuild again so
        // any test file/class running after this one in the same process sees
        // the real (restored) env vars, not this file's sqlite override.
        \Illuminate\Support\Env::enablePutenv();

        // Remove the sqlite resolver override so it can't leak into any other
        // test file/class run later in the same PHPUnit process.
        // (Connection::resolverFor() only accepts a non-null Closure, so the
        // static registry is cleared directly via reflection instead.)
        $resolvers = new \ReflectionProperty(Connection::class, 'resolvers');
        $resolvers->setAccessible(true);
        $current = $resolvers->getValue();
        unset($current['sqlite']);
        $resolvers->setValue(null, $current);

        parent::tearDown();
    }

    private function loginPayload(array $overrides = []): array
    {
        return array_merge([
            'email'                 => 'tester@example.com',
            'password'              => 'secret123',
            'g-recaptcha-response'  => 'stub-token',
        ], $overrides);
    }

    private function jobHistoryId($job)
    {
        $ref = new \ReflectionObject($job);
        $prop = $ref->getProperty('historyId');
        $prop->setAccessible(true);

        return $prop->getValue($job);
    }

    private function jobProp($job, string $name)
    {
        $ref = new \ReflectionObject($job);
        $prop = $ref->getProperty($name);
        $prop->setAccessible(true);

        return $prop->getValue($job);
    }

    /** @test Happy path: coordinates supplied -> row created + job dispatched with real historyId. */
    public function happy_path_with_lat_long_creates_row_and_dispatches_job_with_real_history_id()
    {
        Queue::fake();

        $response = $this->post('/login', $this->loginPayload([
            'latitude'  => '18.5204300',
            'longitude' => '73.8567400',
        ]));

        $response->assertRedirect('/dashboard');

        $this->assertSame(1, DB::connection('sqlite')->table('login_history')->count());

        $row = DB::connection('sqlite')->table('login_history')->first();
        $this->assertSame(1, (int) $row->user_id);
        $this->assertNotNull($row->ip_address);
        $this->assertEquals('18.5204300', number_format((float) $row->latitude, 7, '.', ''));
        $this->assertEquals('73.8567400', number_format((float) $row->longitude, 7, '.', ''));
        $this->assertNull($row->location_address);

        Queue::assertPushed(StoreLoginLocation::class, function ($job) use ($row) {
            return $this->jobHistoryId($job) === (int) $row->id
                && (int) $this->jobProp($job, 'userId') === 1
                && (float) $this->jobProp($job, 'latitude') === 18.52043
                && (float) $this->jobProp($job, 'longitude') === 73.85674;
        });
    }

    /** @test Happy path: geolocation denied (blade sends empty-string lat/long) -> row still created, job NOT dispatched. */
    public function happy_path_without_lat_long_creates_row_but_does_not_dispatch_job()
    {
        Queue::fake();

        $response = $this->post('/login', $this->loginPayload([
            'latitude'  => '',
            'longitude' => '',
        ]));

        $response->assertRedirect('/dashboard');

        $this->assertSame(1, DB::connection('sqlite')->table('login_history')->count());

        $row = DB::connection('sqlite')->table('login_history')->first();
        $this->assertSame(1, (int) $row->user_id);
        $this->assertNotNull($row->ip_address);
        $this->assertNull($row->latitude);
        $this->assertNull($row->longitude);

        Queue::assertNotPushed(StoreLoginLocation::class);
    }

    /** @test Only one of latitude/longitude present -> row stores the one value given, job still NOT dispatched (filled() on array requires ALL keys). */
    public function job_is_not_dispatched_when_only_one_coordinate_is_present()
    {
        Queue::fake();

        $response = $this->post('/login', $this->loginPayload([
            'latitude' => '18.5204300',
            // longitude intentionally omitted
        ]));

        $response->assertRedirect('/dashboard');

        $row = DB::connection('sqlite')->table('login_history')->first();
        $this->assertNotNull($row->latitude);
        $this->assertNull($row->longitude);

        Queue::assertNotPushed(StoreLoginLocation::class);
    }

    /** @test A failure creating the LoginHistory row must never block login/redirect, and must be logged. */
    public function login_history_create_failure_does_not_block_login_and_is_logged()
    {
        Queue::fake();
        Log::spy();

        // Force LoginHistory::create() to throw by removing its table entirely,
        // simulating an unexpected insert-time failure (e.g. schema drift on prod).
        Schema::connection('sqlite')->drop('login_history');

        $response = $this->post('/login', $this->loginPayload([
            'latitude'  => '18.5204300',
            'longitude' => '73.8567400',
        ]));

        // Login must still succeed and redirect normally.
        $response->assertRedirect('/dashboard');

        Log::shouldHaveReceived('error')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'LoginHistory create failed');
            });

        // Coordinates were present, so the job must still be dispatched (dispatch
        // gate is independent of create() success), but with historyId = null
        // since the row was never created.
        Queue::assertPushed(StoreLoginLocation::class, function ($job) {
            return $this->jobHistoryId($job) === null;
        });
    }

    /** @test Mass assignment genuinely works for all 5 target columns given $guarded = ['id']. */
    public function mass_assignment_works_for_all_five_target_columns()
    {
        $model = new LoginHistory();
        $this->assertSame(['id'], $model->getGuarded());
        foreach (['user_id', 'ip_address', 'latitude', 'longitude', 'location_address'] as $column) {
            $this->assertTrue($model->isFillable($column), "Expected '$column' to be mass-assignable under \$guarded = ['id'].");
        }
        $this->assertFalse($model->isFillable('id'), "'id' must remain guarded.");

        $created = LoginHistory::create([
            'user_id'          => 1,
            'ip_address'       => '203.0.113.5',
            'latitude'         => 12.3456789,
            'longitude'        => 65.4321000,
            'location_address' => 'Some Test Address, Test City',
        ]);

        $this->assertNotNull($created->id);

        $fresh = DB::connection('sqlite')->table('login_history')->where('id', $created->id)->first();
        $this->assertSame(1, (int) $fresh->user_id);
        $this->assertSame('203.0.113.5', $fresh->ip_address);
        $this->assertEquals('12.3456789', number_format((float) $fresh->latitude, 7, '.', ''));
        $this->assertEquals('65.4321000', number_format((float) $fresh->longitude, 7, '.', ''));
        $this->assertSame('Some Test Address, Test City', $fresh->location_address);
    }

    // ------------------------------------------------------------------
    // T-2026-061 iteration-2 critique-gate additions (self-audit gaps)
    // ------------------------------------------------------------------

    /**
     * @test Second/duplicate login by the same user in quick succession must
     * create a SECOND row (Login History LIST semantics), never upsert or
     * overwrite the first row. Also proves the row created by the *first*
     * login is not mutated by the second call (id, created_at differ).
     */
    public function second_login_by_same_user_creates_a_second_distinct_row()
    {
        Queue::fake();

        $first = $this->post('/login', $this->loginPayload([
            'latitude'  => '18.5204300',
            'longitude' => '73.8567400',
        ]));
        $first->assertRedirect('/dashboard');

        $second = $this->post('/login', $this->loginPayload([
            'latitude'  => '19.0760000',
            'longitude' => '72.8777000',
        ]));
        $second->assertRedirect('/dashboard');

        $this->assertSame(2, DB::connection('sqlite')->table('login_history')->count(),
            'A second login by the same user must INSERT a second row, not update the first.');

        $rows = DB::connection('sqlite')->table('login_history')->orderBy('id')->get();
        $this->assertNotSame((int) $rows[0]->id, (int) $rows[1]->id);
        $this->assertEquals('18.5204300', number_format((float) $rows[0]->latitude, 7, '.', ''));
        $this->assertEquals('19.0760000', number_format((float) $rows[1]->latitude, 7, '.', ''));

        Queue::assertPushed(StoreLoginLocation::class, 2);
    }

    /**
     * @test A user that already has stale latitude/longitude on the `users`
     * table (e.g. left over from a prior StoreLoginLocation job run) must not
     * leak into the new login_history row — the row must reflect only the
     * CURRENT request's coordinates, never the user's existing column values.
     * LoginController never reads $user->latitude/$user->longitude, so this
     * proves there is genuinely no accidental interaction with that column.
     */
    public function stale_user_table_lat_long_does_not_leak_into_new_login_history_row()
    {
        Queue::fake();

        DB::connection('sqlite')->table('users')->where('id', 1)->update([
            'latitude'  => 99.9999999,
            'longitude' => 88.8888888,
        ]);

        $response = $this->post('/login', $this->loginPayload([
            'latitude'  => '18.5204300',
            'longitude' => '73.8567400',
        ]));
        $response->assertRedirect('/dashboard');

        $row = DB::connection('sqlite')->table('login_history')->first();
        $this->assertEquals('18.5204300', number_format((float) $row->latitude, 7, '.', ''));
        $this->assertEquals('73.8567400', number_format((float) $row->longitude, 7, '.', ''));
        $this->assertNotEquals('99.9999999', number_format((float) $row->latitude, 7, '.', ''));
    }

    /**
     * @test Malformed (non-numeric, PHP-array-shaped) latitude/longitude —
     * reachable because submitLogin()'s $rules array validates only
     * email/password/g-recaptcha-response, NOT latitude/longitude — is sent
     * through the REAL HTTP path. `latitude[]=x&longitude[]=y` makes
     * $request->latitude return a PHP array, which Eloquent/PDO cannot bind
     * as a query parameter. This empirically proves whether the try/catch
     * (which declares `catch (\Exception $e)`, not `\Throwable`) genuinely
     * survives this case end-to-end, rather than reasoning about it.
     *
     * FINDING (empirically observed, see assertions below): passing an array
     * does NOT reach PDO as a raw \TypeError. Instead, an earlier
     * array-to-string conversion attempt raises a PHP E_WARNING, which
     * Laravel's global error handler escalates to \ErrorException — a
     * genuine \Exception subclass — so `catch (\Exception $e)` in
     * LoginController::submitLogin() DOES catch this reachable failure mode.
     * This test exists specifically to prove that empirically rather than
     * assume it from reading the catch signature alone.
     */
    public function malformed_array_shaped_coordinates_are_handled_end_to_end_without_fatal_crash()
    {
        Queue::fake();
        Log::spy();

        $response = $this->post('/login', $this->loginPayload([
            'latitude'  => ['not', 'numeric'],
            'longitude' => ['also', 'not', 'numeric'],
        ]));

        // This assertion is the empirical proof point: if the controller's
        // try/catch truly makes insert failures non-blocking for ANY
        // malformed input (not just clean \Exception-throwing ones), login
        // must still redirect here. If this assertion fails, it proves the
        // catch(\Exception) block does NOT cover this reachable failure mode.
        $response->assertRedirect('/dashboard');

        // Empirically observed (not assumed): passing an array as
        // latitude/longitude makes the query-binding layer attempt an
        // implicit array-to-string conversion, which PHP raises as an
        // E_WARNING; Laravel's global error handler
        // (Illuminate\Foundation\Bootstrap\HandleExceptions) escalates that
        // to a genuine \ErrorException, which DOES extend \Exception, so
        // `catch (\Exception $e)` in LoginController correctly catches it.
        // No row is created for this request; login still redirects cleanly.
        $this->assertSame(0, DB::connection('sqlite')->table('login_history')->count());
        Log::shouldHaveReceived('error')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'LoginHistory create failed')
                    && str_contains($message, 'Array to string conversion');
            });
    }

    /**
     * @test Malformed non-numeric (but non-array) latitude/longitude STRING
     * values, e.g. "abc", sent through the real HTTP path. Documents actual
     * SQLite behavior (loose column typing — likely stored as-is, no
     * exception) as a known divergence from production MySQL with
     * config('database.connections.mysql.strict') = true, where a decimal
     * column rejecting a non-numeric string under strict SQL mode would
     * throw a QueryException. Flagged as an environment limitation, not
     * asserted as bug-free in production.
     */
    public function malformed_non_numeric_string_coordinates_do_not_crash_login_under_sqlite()
    {
        Queue::fake();

        $response = $this->post('/login', $this->loginPayload([
            'latitude'  => 'not-a-number',
            'longitude' => 'also-not-a-number',
        ]));

        $response->assertRedirect('/dashboard');
        $this->assertSame(1, DB::connection('sqlite')->table('login_history')->count());
    }

    /**
     * @test Closes the loop on the ORIGINAL reported symptom: proves the
     * actual owner-facing `/owner/list-login-history` page (rendered by the
     * real, untouched read-side stack — AllListController::listLoginHistory()
     * -> AllListServices::listLoginHistory() -> AllListRepositor::listLoginHistory()
     * -> resources/views/organizations/hr/employees/list-login-history.blade.php)
     * now shows a non-empty list once login_history rows exist, rather than
     * only proving a row exists in the DB. Session-based `admin` middleware
     * is satisfied via session(['user_id' => ...]), matching
     * AdminMiddleware::handle()'s actual check.
     */
    public function owner_list_login_history_page_renders_rows_closing_the_loop_on_original_symptom()
    {
        DB::connection('sqlite')->table('login_history')->insert([
            'user_id'          => 1,
            'location_address' => 'Test Reverse-Geocoded Address, Pune',
            'latitude'         => 18.5204300,
            'longitude'        => 73.8567400,
            'ip_address'       => '198.51.100.7',
            'is_active'        => 1,
            'is_deleted'       => 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $response = $this->withSession(['user_id' => 1])->get('/owner/list-login-history');

        $response->assertOk();
        $response->assertDontSee('No Record Found');
        $response->assertSee('tester@example.com');
        $response->assertSee('Test Reverse-Geocoded Address, Pune');
        $response->assertSee('198.51.100.7');
    }

    /**
     * @test Negative control for the previous test: the read-side query
     * filters on users.is_active = 1 AND users.is_deleted = 0 (unchanged,
     * pre-existing behavior). A row belonging to a soft-deleted user must
     * NOT appear, proving the previous test's positive assertion is
     * actually discriminating and not just always-passing boilerplate.
     */
    public function owner_list_login_history_page_excludes_rows_for_deleted_users()
    {
        DB::connection('sqlite')->table('users')->insert([
            'id'         => 2,
            'u_email'    => 'deleted-user@example.com',
            'u_password' => Hash::make('secret123'),
            'org_id'     => 1,
            'role_id'    => 99,
            'is_active'  => 1,
            'is_deleted' => 1, // soft-deleted
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::connection('sqlite')->table('login_history')->insert([
            'user_id'          => 2,
            'location_address' => 'Should Not Appear Address',
            'latitude'         => 1.0,
            'longitude'        => 1.0,
            'ip_address'       => '10.0.0.1',
            'is_active'        => 1,
            'is_deleted'       => 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $response = $this->withSession(['user_id' => 1])->get('/owner/list-login-history');

        $response->assertOk();
        $response->assertDontSee('Should Not Appear Address');
        $response->assertSee('No Record Found');
    }
}
