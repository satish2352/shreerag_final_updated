<?php

namespace Tests\Feature;

use Illuminate\Database\Connection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DispatchFinanceUpdatedAtTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');
        $_ENV['DB_CONNECTION'] = $_SERVER['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = $_SERVER['DB_DATABASE'] = ':memory:';
        \Illuminate\Support\Env::enablePutenv();

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
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        putenv('DB_CONNECTION=mysql');
        putenv('DB_DATABASE=latest_new_shree_db');
        $_ENV['DB_CONNECTION'] = $_SERVER['DB_CONNECTION'] = 'mysql';
        $_ENV['DB_DATABASE'] = $_SERVER['DB_DATABASE'] = 'latest_new_shree_db';
        \Illuminate\Support\Env::enablePutenv();

        $resolvers = new \ReflectionProperty(Connection::class, 'resolvers');
        $resolvers->setAccessible(true);
        $current = $resolvers->getValue();
        unset($current['sqlite']);
        $resolvers->setValue(null, $current);
    }

    /** @test */
    public function received_from_finance_list_shows_the_finance_handoff_date_and_time_in_ist(): void
    {
        $record = (object) [
            'id' => 7,
            'business_details_id' => 11,
            'created_at' => Carbon::parse('2026-01-01 00:00:00', 'UTC'),
            'finance_updated_at' => Carbon::parse('2026-08-31 05:10:15', 'UTC'),
            'project_name' => 'Demo Project',
            'customer_po_number' => 'PO-123',
            'product_name' => 'Impact Bar',
            'quantity' => 3,
            'cumulative_completed_quantity' => 3,
            'remaining_quantity' => 0,
            'from_place' => 'Nashik',
            'to_place' => 'Gujarat',
            'truck_no' => 'MH 15 AB 1234',
            'transport_name' => 'Demo Transport',
            'vehicle_name' => 'Truck',
        ];

        $html = view('organizations.dispatch.dispatchdept.list-business-received-from-fianance', [
            'data_output' => collect([$record]),
        ])->render();

        $this->assertStringContainsString('Finance Updated Date &amp; Time', $html);
        $this->assertStringContainsString('31-08-2026 10:40 AM IST', $html);
        $this->assertStringNotContainsString('01-01-2026', $html);
    }
}
