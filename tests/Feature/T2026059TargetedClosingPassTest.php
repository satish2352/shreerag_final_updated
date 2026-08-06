<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\BusinessApplicationProcesses;
use App\Models\Requisition;
use App\Models\NotificationStatus;
use App\Models\DesignModel;
use App\Http\Controllers\Organizations\Purchase\AllListController as PurchaseAllListController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Services\Admin\Dashboard\DashboardService;

/**
 * T-2026-059 — TARGETED CLOSING PASS (module_tester, GENUINE, post-iteration-6).
 *
 * Per code_reviewer's iteration-6 explicit recommendation (a full re-covering pass of the
 * whole T-2026-059 surface is not strictly necessary given the narrow 2-file diff and the
 * reviewer's own already-thorough independent execution-based verification), this test
 * targets exactly:
 *   1. The "Accept and Send For Purchase" button's real href, fed through Laravel's REAL
 *      router, for a no-production-row project (the exact class of row iteration 6 fixed)
 *      AND a with-production-row project (regression — this case already worked).
 *   2. The Purchase dashboard "Received Requisition Request" unread-badge count, traced
 *      through DashboardController::getNotification() (not just the raw DB column), before
 *      and after "viewing" a no-production-row project via the real controller call.
 *
 * Deliberately constructed independently of the reviewer's own iteration-6 fixture script
 * (different fixture-building helper, different assertion order, isolates via a unique
 * `search` term rather than assuming page 1) to triangulate rather than reuse verbatim.
 */
class T2026059TargetedClosingPassTest extends TestCase
{
    use DatabaseTransactions;

    private PurchaseAllListController $purchaseController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->purchaseController = new PurchaseAllListController();
    }

    /**
     * Builds a "sent to Purchase" project fixture: businesses + businesses_details +
     * design + business_application_processes (store_status_id = the exact constant the
     * Purchase repository filters on) + requisition + a NotificationStatus row shaped
     * exactly like StoreController::storeShortageRequisition()'s own real side effect
     * (off_canvas_status=16, purchase_is_view=0). Optionally also creates a `production`
     * row when $withProduction=true.
     */
    private function makeSentToPurchaseProject(string $uniqueTag, bool $withProduction): array
    {
        $storeStatusSentForPurchase = config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');

        $bizId = DB::table('businesses')->insertGetId([
            'organization_id'          => 1,
            'project_name'             => 'Targeted-' . $uniqueTag . ' Project',
            'customer_po_number'       => 'PO-T2026059TARGETED-' . uniqid(),
            'title'                    => $uniqueTag,
            'po_validity'              => date('Y-m-d'),
            'customer_payment_terms'   => 'NA',
            'customer_terms_condition' => 'NA',
            'is_active'                => 1,
            'is_deleted'               => 0,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        $bdId = DB::table('businesses_details')->insertGetId([
            'business_id'  => $bizId,
            'product_name' => $uniqueTag,
            'description'  => 'T-2026-059 targeted-closing-pass fixture',
            'quantity'     => 1,
            'rate'         => 0,
            'is_active'    => 1,
            'is_deleted'   => 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $design = DesignModel::create([
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'design_image'        => 'fixture.png',
            'trolley_qty'         => 4,
            'is_approve'          => 1,
            'is_active'           => 1,
            'is_deleted'          => 0,
        ]);

        $requisition = Requisition::create([
            'req_name'             => 'Targeted-' . $uniqueTag,
            'business_id'          => $bizId,
            'business_details_id'  => $bdId,
            'design_id'            => $design->id,
            'production_id'        => 0,
            'req_date'             => date('Y-m-d'),
            'is_active'            => 1,
            'is_deleted'           => 0,
        ]);

        $bap = BusinessApplicationProcesses::create([
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'design_id'           => $design->id,
            'production_id'       => 0,
            'store_status_id'     => $storeStatusSentForPurchase,
            'requisition_id'      => $requisition->id,
            'off_canvas_status'   => 16,
            'is_active'           => 1,
            'is_deleted'          => 0,
        ]);

        $notification = NotificationStatus::create([
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'off_canvas_status'   => 16,
            'purchase_is_view'    => 0,
            'is_active'           => 1,
            'is_deleted'          => 0,
        ]);

        $productionId = null;
        if ($withProduction) {
            $productionId = DB::table('production')->insertGetId([
                'business_details_id' => $bdId,
                'business_id'         => $bizId,
                'design_id'           => $design->id,
                'is_approve'          => 1,
                'is_active'           => 1,
                'is_deleted'          => 0,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        return [
            'bizId'          => $bizId,
            'bdId'           => $bdId,
            'designId'       => $design->id,
            'requisitionId'  => $requisition->id,
            'notificationId' => $notification->id,
            'productionId'   => $productionId,
        ];
    }

    /**
     * Renders the real Purchase listing view (isolated to a single fixture row via a
     * unique `search` term so pagination/other live DB rows cannot hide it), and returns
     * the raw href attribute of that row's "Accept and Send For Purchase" button.
     */
    private function renderAndExtractButtonHref(string $searchTerm): string
    {
        request()->merge(['search' => $searchTerm, 'page' => 1]);

        $view = $this->purchaseController->getAllListMaterialReceivedForPurchase();
        $this->assertNotNull($view, 'Controller must return a real view, not silently swallow an exception.');

        $html = $view->render();

        $this->assertStringContainsString(
            'Accept and Send For Purchase',
            $html,
            'Fixture row must actually surface in the rendered listing (search isolation failed or fixture does not match the repository query).'
        );

        // Extract the href of the (single, search-isolated) "Accept and Send For Purchase" anchor.
        $matched = preg_match('/<a\s+href="([^"]*list-purchase-order[^"]*)"[^>]*>\s*<button[^>]*Accept and Send For\s*Purchase/s', $html, $m);
        if (!$matched) {
            // Fallback: looser pattern in case of attribute-order/whitespace differences.
            $matched = preg_match('/href="([^"]*list-purchase-order\/[^"]*)"/', $html, $m);
        }
        $this->assertEquals(1, $matched, "Could not extract the button's href from the rendered HTML. Rendered HTML snippet:\n" . substr($html, 0, 4000));

        return trim(html_entity_decode($m[1]));
    }

    /**
     * Feeds a real (possibly fully-qualified, APP_URL-prefixed) href through Laravel's
     * REAL router matcher and returns the matched Route.
     */
    private function matchHrefAgainstRealRouter(string $href): \Illuminate\Routing\Route
    {
        $path = parse_url($href, PHP_URL_PATH) ?? $href;

        // This project's .env APP_URL bakes in a subdirectory
        // (http://localhost/shreerag_final_updated) that is NOT part of the actual
        // routed path once served (documented in this task's own prior iteration-2/6
        // tester/reviewer entries) — strip it before feeding to the router, mirroring
        // real serving behaviour.
        $appUrlPath = rtrim(parse_url(config('app.url'), PHP_URL_PATH) ?? '', '/');
        if ($appUrlPath !== '' && str_starts_with($path, $appUrlPath)) {
            $path = substr($path, strlen($appUrlPath));
        }
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . ltrim($path, '/');
        }

        $request = Request::create($path, 'GET');
        $router = app('router');

        return $router->getRoutes()->match($request);
    }

    public function test_button_navigates_correctly_for_no_production_row_project(): void
    {
        $tag = 'T2026059Targeted-NoProd-' . uniqid();
        $fixture = $this->makeSentToPurchaseProject($tag, withProduction: false);

        // Sanity: confirm the repository's own join really does leave business_details_id
        // null for this row (the exact fragile field the iteration-5/6 fixes work around).
        $rawRow = DB::table('business_application_processes')
            ->leftJoin('production', 'business_application_processes.business_details_id', '=', 'production.business_details_id')
            ->where('business_application_processes.business_details_id', $fixture['bdId'])
            ->select('production.business_details_id as prod_bdid')
            ->first();
        $this->assertNull($rawRow->prod_bdid ?? null, 'Sanity check: no production row must mean the LEFT JOIN leaves business_details_id null for this fixture.');

        $href = $this->renderAndExtractButtonHref($tag);

        $route = $this->matchHrefAgainstRealRouter($href);

        $this->assertEquals(
            \App\Http\Controllers\Organizations\Purchase\PurchaseOrderController::class . '@index',
            $route->getActionName(),
            'Button href must resolve to PurchaseOrderController@index, not 404 or some other route.'
        );

        $params = $route->parameters();
        $this->assertArrayHasKey('requistition_id', $params);
        $this->assertArrayHasKey('business_details_id', $params);
        $this->assertNotSame('', $params['requistition_id']);
        $this->assertNotSame('', $params['business_details_id']);

        $decodedReqId = base64_decode($params['requistition_id']);
        $decodedBdId  = base64_decode($params['business_details_id']);

        $this->assertEquals($fixture['requisitionId'], (int) $decodedReqId, 'Route requisition_id param must decode to the real requisition id.');
        $this->assertEquals($fixture['bdId'], (int) $decodedBdId, 'Route business_details_id param must decode to businesses_details.id (via $data->id), not the null production-joined field.');
    }

    public function test_button_navigates_correctly_for_project_with_production_row_regression(): void
    {
        $tag = 'T2026059Targeted-WithProd-' . uniqid();
        $fixture = $this->makeSentToPurchaseProject($tag, withProduction: true);
        $this->assertNotNull($fixture['productionId'], 'Sanity: this fixture must have a real production row.');

        $href = $this->renderAndExtractButtonHref($tag);
        $route = $this->matchHrefAgainstRealRouter($href);

        $this->assertEquals(
            \App\Http\Controllers\Organizations\Purchase\PurchaseOrderController::class . '@index',
            $route->getActionName(),
            'Regression: button href must still resolve correctly when a production row DOES exist.'
        );

        $params = $route->parameters();
        $decodedReqId = base64_decode($params['requistition_id']);
        $decodedBdId  = base64_decode($params['business_details_id']);

        $this->assertEquals($fixture['requisitionId'], (int) $decodedReqId);
        $this->assertEquals($fixture['bdId'], (int) $decodedBdId, 'With-production-row case must resolve to the same businesses_details.id-based value as the no-production-row case (fix is not conditional on production existing).');
    }

    public function test_dashboard_badge_decrements_after_viewing_no_production_row_project(): void
    {
        $tag = 'T2026059Targeted-Badge-' . uniqid();
        $fixture = $this->makeSentToPurchaseProject($tag, withProduction: false);

        // Confirm the DB precondition: purchase_is_view = 0 before viewing.
        $before = NotificationStatus::find($fixture['notificationId']);
        $this->assertEquals(0, (int) $before->purchase_is_view);

        // Trace through the REAL dashboard badge-count code path (not just the DB column) —
        // bind a Purchase-role session and call the real controller method.
        session()->put('role_id', config('constants.ROLE_ID.PURCHASE'));
        session()->put('user_id', 1);

        $dashboardController = new DashboardController(app(DashboardService::class));

        $badgeBefore = $this->extractReceivedRequisitionBadgeCount($dashboardController);
        $this->assertGreaterThanOrEqual(1, $badgeBefore, 'Badge count before viewing must be at least 1 (my own fixture row must be counted).');

        // "View" the listing — the real controller call that iteration 6 fixed to flip
        // purchase_is_view using the reliable $row->id-based $bdIds pluck.
        request()->merge(['search' => $tag, 'page' => 1]);
        $view = $this->purchaseController->getAllListMaterialReceivedForPurchase();
        $this->assertStringContainsString('Accept and Send For Purchase', $view->render());

        // Confirm DB flip.
        $after = NotificationStatus::find($fixture['notificationId']);
        $this->assertEquals(1, (int) $after->purchase_is_view, 'purchase_is_view must flip to 1 in the DB after viewing this no-production-row project.');

        // Confirm the dashboard badge itself (traced through the real controller, not the
        // raw column) reflects exactly this one-row decrement.
        $badgeAfter = $this->extractReceivedRequisitionBadgeCount($dashboardController);
        $this->assertEquals($badgeBefore - 1, $badgeAfter, 'Dashboard "Received Requisition Request" badge count must decrement by exactly 1 after viewing this fixture project (isolated by using a fresh, unique fixture — no other row should be affected).');
    }

    /**
     * Calls the real DashboardController::getNotification() and extracts the
     * 'Received Requistion Request' notification's admin_count from the real JSON response.
     */
    private function extractReceivedRequisitionBadgeCount(DashboardController $controller): int
    {
        $response = $controller->getNotification(new Request());
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('notifications', $data);

        $entry = collect($data['notifications'])->first(fn($n) => ($n['message'] ?? null) === 'Received Requistion Request');
        $this->assertNotNull($entry, "Dashboard notifications payload must contain a 'Received Requistion Request' entry for the Purchase role.");

        return (int) $entry['admin_count'];
    }
}
