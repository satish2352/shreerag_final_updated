<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * T-2026-063 — Dashboard UI Redesign: Coverage Self-Audit
 *
 * This test file covers the 7 gap areas not exercised by T1–T13:
 *
 *  Gap 1 — View Details link hrefs point to defined named routes
 *  Gap 2 — Card title text matches spec ("Leave Approved" label fix)
 *  Gap 3 — No stale old-style elements in changed sections
 *  Gap 4 — Pipeline headers use &rarr; / &#8594; entities (not literal -> or →)
 *  Gap 5 — h-100 class present on every new card in all 5 files
 *  Gap 6 — Finance pipeline header is placed BEFORE the Finance cards
 *  Gap 7 — Total Department card in super-dashboard.blade.php is UNCHANGED
 */
class T2026063DashboardCoverageAuditTest extends TestCase
{
    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    private function dashboardFile(): string
    {
        return base_path('resources/views/admin/pages/dashboard/dashboard.blade.php');
    }

    private function hrFile(): string
    {
        return base_path('resources/views/admin/pages/dashboard/hr-dashboard.blade.php');
    }

    private function estimationFile(): string
    {
        return base_path('resources/views/admin/pages/dashboard/estimation-dashboard.blade.php');
    }

    private function higherFile(): string
    {
        return base_path('resources/views/admin/pages/dashboard/higher-dashboard.blade.php');
    }

    private function superFile(): string
    {
        return base_path('resources/views/admin/pages/dashboard/super-dashboard.blade.php');
    }

    private function webRoutesFile(): string
    {
        return base_path('routes/web.php');
    }

    /** Returns the line number (1-based) of the FIRST occurrence of $pattern in $file, or -1 */
    private function firstLine(string $file, string $pattern): int
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $idx => $line) {
            if (strpos($line, $pattern) !== false) {
                return $idx + 1;
            }
        }
        return -1;
    }

    // -----------------------------------------------------------------
    // GAP 1 — All named routes referenced by new card hrefs exist
    // -----------------------------------------------------------------

    public function test_gap1_finance_card_routes_exist_in_web_php(): void
    {
        $webRoutes = file_get_contents($this->webRoutesFile());

        $financeRoutes = [
            'list-sr-and-gr-genrated-business',
            'list-po-sent-for-approval',
            'list-release-approval-payment-by-vendor',
            'recive-logistics-list',
            'list-send-to-dispatch',
        ];

        foreach ($financeRoutes as $routeName) {
            $this->assertStringContainsString(
                "->name('{$routeName}')",
                $webRoutes,
                "Finance card route '{$routeName}' is missing from routes/web.php"
            );
        }
    }

    public function test_gap1_security_card_route_exists_in_web_php(): void
    {
        $webRoutes = file_get_contents($this->webRoutesFile());
        $this->assertStringContainsString(
            "->name('list-gatepass')",
            $webRoutes,
            "Security card route 'list-gatepass' is missing from routes/web.php"
        );
    }

    public function test_gap1_inventory_card_routes_exist_in_web_php(): void
    {
        $webRoutes = file_get_contents($this->webRoutesFile());

        foreach (['list-accepted-design-from-prod', 'list-inventory-material'] as $routeName) {
            $this->assertStringContainsString(
                "->name('{$routeName}')",
                $webRoutes,
                "Inventory card route '{$routeName}' is missing from routes/web.php"
            );
        }
    }

    public function test_gap1_employee_card_routes_exist_in_web_php(): void
    {
        $webRoutes = file_get_contents($this->webRoutesFile());

        $employeeRoutes = [
            'list-final-production-completed-received-from-fianance',
            'list-dispatch',
            'list-dispatch-final-product-close',
            'list-leaves',
        ];

        foreach ($employeeRoutes as $routeName) {
            $this->assertStringContainsString(
                "->name('{$routeName}')",
                $webRoutes,
                "Employee/Dispatch card route '{$routeName}' is missing from routes/web.php"
            );
        }
    }

    public function test_gap1_cms_card_routes_exist_in_web_php(): void
    {
        $webRoutes = file_get_contents($this->webRoutesFile());

        $cmsRoutes = [
            'list-product',
            'list-services',
            'list-testimonial',
            'list-director-desk',
            'list-vision-mission',
            'list-team',
            'list-contactus-form',
        ];

        foreach ($cmsRoutes as $routeName) {
            $this->assertStringContainsString(
                "->name('{$routeName}')",
                $webRoutes,
                "CMS card route '{$routeName}' is missing from routes/web.php"
            );
        }
    }

    public function test_gap1_super_dashboard_employee_route_exists(): void
    {
        $webRoutes = file_get_contents($this->webRoutesFile());
        $this->assertStringContainsString(
            "->name('list-employee')",
            $webRoutes,
            "Super-dashboard Total Employee route 'list-employee' is missing from routes/web.php"
        );
    }

    // -----------------------------------------------------------------
    // GAP 2 — Card title text matches spec
    // -----------------------------------------------------------------

    public function test_gap2_employee_section_leave_approved_label(): void
    {
        $content = file_get_contents($this->dashboardFile());

        // Correct new label
        $this->assertStringContainsString(
            'Leave Approved',
            $content,
            "Employee section must have 'Leave Approved' label"
        );

        // Old (wrong) label must NOT appear
        $this->assertStringNotContainsString(
            'accept Leave Request',
            $content,
            "Old label 'accept Leave Request' must not appear — it should have been renamed to 'Leave Approved'"
        );
    }

    public function test_gap2_finance_pipeline_card_titles(): void
    {
        $content = file_get_contents($this->dashboardFile());

        // These are the exact span text values in the Finance section
        foreach ([
            'Need to check for Payment',
            'PO Submited For Sanction For Payment',
            'PO Payment Release to Vendor By Finance',
        ] as $title) {
            $this->assertStringContainsString(
                $title,
                $content,
                "Finance card title '{$title}' not found in dashboard.blade.php"
            );
        }
    }

    // -----------------------------------------------------------------
    // GAP 3 — No stale old-style elements in changed sections
    // -----------------------------------------------------------------

    public function test_gap3_no_fa4_icon_classes_in_dashboard(): void
    {
        $content = file_get_contents($this->dashboardFile());

        // FA4 style was "fa fa-<name>" — FA6 is "fa-solid fa-<name>" or "fa-regular fa-<name>"
        // Check that no "fa fa-" class strings appear (FA4-style)
        $this->assertDoesNotMatchRegularExpression(
            '/class="[^"]*\bfa fa-/',
            $content,
            "FA4-style 'fa fa-*' icon class found in dashboard.blade.php — should use FA6 'fa-solid fa-*' syntax"
        );
    }

    public function test_gap3_no_fa4_icon_classes_in_hr_dashboard(): void
    {
        $content = file_get_contents($this->hrFile());
        $this->assertDoesNotMatchRegularExpression(
            '/class="[^"]*\bfa fa-/',
            $content,
            "FA4-style 'fa fa-*' icon class found in hr-dashboard.blade.php"
        );
    }

    public function test_gap3_no_fa4_icon_classes_in_estimation_dashboard(): void
    {
        $content = file_get_contents($this->estimationFile());
        $this->assertDoesNotMatchRegularExpression(
            '/class="[^"]*\bfa fa-/',
            $content,
            "FA4-style 'fa fa-*' icon class found in estimation-dashboard.blade.php"
        );
    }

    public function test_gap3_no_old_bg_warning_in_dashboard_changed_sections(): void
    {
        $content = file_get_contents($this->dashboardFile());

        // The Finance/Security/Inventory/Employee/CMS sections (lines 1100+) must not
        // use old Bootstrap bg-warning on icon divs (the modernized pattern uses inline style backgrounds)
        $lines = file($this->dashboardFile(), FILE_IGNORE_NEW_LINES);
        $changedSectionStart = 1100; // Finance/Security sections start around here

        foreach ($lines as $idx => $line) {
            $lineNo = $idx + 1;
            if ($lineNo < $changedSectionStart) {
                continue;
            }
            if (preg_match('/icon-shape bg-warning/', $line)) {
                $this->fail(
                    "Found 'icon-shape bg-warning' on line {$lineNo} in dashboard.blade.php after line {$changedSectionStart} "
                    . "(changed sections should use inline style backgrounds, not old Bootstrap bg-* classes)"
                );
            }
        }

        $this->assertTrue(true, "No stale bg-warning on icon-shape elements in changed sections");
    }

    public function test_gap3_no_old_bg_warning_in_hr_dashboard(): void
    {
        $content = file_get_contents($this->hrFile());

        // hr-dashboard should have NO bg-warning on any icon-shape (all cards are modernized)
        $this->assertDoesNotMatchRegularExpression(
            '/icon-shape bg-warning/',
            $content,
            "Old-style 'icon-shape bg-warning' found in hr-dashboard.blade.php — should use inline background style"
        );
    }

    public function test_gap3_no_old_bg_warning_in_estimation_dashboard(): void
    {
        $content = file_get_contents($this->estimationFile());

        $this->assertDoesNotMatchRegularExpression(
            '/icon-shape bg-warning/',
            $content,
            "Old-style 'icon-shape bg-warning' found in estimation-dashboard.blade.php"
        );
    }

    // -----------------------------------------------------------------
    // GAP 4 — Pipeline headers use &rarr; or &#8594; entities, not literal -> or →
    // -----------------------------------------------------------------

    public function test_gap4_finance_pipeline_uses_html_entity_not_literal_arrow(): void
    {
        $content = file_get_contents($this->dashboardFile());

        // Extract the finance pipeline header line
        $lines = file($this->dashboardFile(), FILE_IGNORE_NEW_LINES);
        $pipelineLine = '';
        foreach ($lines as $line) {
            if (strpos($line, 'FINANCE PIPELINE') !== false) {
                $pipelineLine = $line;
                break;
            }
        }

        $this->assertNotEmpty($pipelineLine, "FINANCE PIPELINE header not found in dashboard.blade.php");

        // Must use &rarr; or &#8594; (not literal -> or unicode →)
        $this->assertTrue(
            strpos($pipelineLine, '&rarr;') !== false || strpos($pipelineLine, '&#8594;') !== false,
            "FINANCE PIPELINE header must use '&rarr;' or '&#8594;' entity, not literal '->' or '→'"
        );

        $this->assertStringNotContainsString(
            '->',
            $pipelineLine,
            "FINANCE PIPELINE header must not contain literal '->' arrow"
        );
    }

    public function test_gap4_estimation_pipeline_uses_html_entity_not_literal_arrow(): void
    {
        $lines = file($this->estimationFile(), FILE_IGNORE_NEW_LINES);
        $pipelineLine = '';
        foreach ($lines as $line) {
            if (strpos($line, 'ESTIMATION PIPELINE') !== false) {
                $pipelineLine = $line;
                break;
            }
        }

        $this->assertNotEmpty($pipelineLine, "ESTIMATION PIPELINE header not found in estimation-dashboard.blade.php");

        $this->assertTrue(
            strpos($pipelineLine, '&rarr;') !== false || strpos($pipelineLine, '&#8594;') !== false,
            "ESTIMATION PIPELINE header must use '&rarr;' or '&#8594;' entity, not literal '->' or '→'"
        );

        $this->assertStringNotContainsString(
            '->',
            $pipelineLine,
            "ESTIMATION PIPELINE header must not contain literal '->' arrow"
        );
    }

    // -----------------------------------------------------------------
    // GAP 5 — h-100 class present on every new card
    // -----------------------------------------------------------------

    public function test_gap5_h100_on_all_modernized_cards_in_hr_dashboard(): void
    {
        $content = file_get_contents($this->hrFile());

        // All 6 HR cards must have h-100; count card divs vs h-100 occurrences
        $cardDivCount = substr_count($content, 'class="card shadow border-0');
        $h100Count = substr_count($content, 'card shadow border-0 h-100');

        $this->assertEquals(
            $cardDivCount,
            $h100Count,
            "All {$cardDivCount} card divs in hr-dashboard.blade.php must include 'h-100' class; found {$h100Count}"
        );
        $this->assertGreaterThanOrEqual(6, $h100Count, "hr-dashboard.blade.php must have at least 6 h-100 cards");
    }

    public function test_gap5_h100_on_all_modernized_cards_in_estimation_dashboard(): void
    {
        $content = file_get_contents($this->estimationFile());

        $cardDivCount = substr_count($content, 'class="card shadow border-0');
        $h100Count = substr_count($content, 'card shadow border-0 h-100');

        $this->assertEquals(
            $cardDivCount,
            $h100Count,
            "All cards in estimation-dashboard.blade.php must include 'h-100'; found {$h100Count} of {$cardDivCount}"
        );
        $this->assertGreaterThanOrEqual(4, $h100Count, "estimation-dashboard.blade.php must have at least 4 h-100 cards");
    }

    public function test_gap5_h100_on_total_employee_card_in_super_dashboard(): void
    {
        $content = file_get_contents($this->superFile());

        // Total Employee card (the only modernized card) must have h-100
        $lines = file($this->superFile(), FILE_IGNORE_NEW_LINES);
        $totalEmployeeLine = -1;
        foreach ($lines as $idx => $line) {
            if (strpos($line, 'Total Employee') !== false) {
                $totalEmployeeLine = $idx + 1;
                break;
            }
        }
        $this->assertGreaterThan(0, $totalEmployeeLine, "'Total Employee' label not found in super-dashboard.blade.php");

        // Look backwards up to 10 lines for the card div with h-100
        $found = false;
        for ($i = max(0, $totalEmployeeLine - 10); $i < $totalEmployeeLine; $i++) {
            if (strpos($lines[$i], 'h-100') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Total Employee card in super-dashboard.blade.php is missing 'h-100' class");
    }

    // -----------------------------------------------------------------
    // GAP 6 — Finance pipeline header comes BEFORE the Finance cards
    // -----------------------------------------------------------------

    public function test_gap6_finance_pipeline_header_is_before_finance_cards(): void
    {
        $lines = file($this->dashboardFile(), FILE_IGNORE_NEW_LINES);

        $pipelineHeaderLine = -1;
        $firstFinanceCardLine = -1;

        foreach ($lines as $idx => $line) {
            if ($pipelineHeaderLine === -1 && strpos($line, 'FINANCE PIPELINE') !== false) {
                $pipelineHeaderLine = $idx + 1;
            }
            // First Finance card has border-left with #e6a817 (Finance yellow color)
            if ($firstFinanceCardLine === -1 && $pipelineHeaderLine > 0
                && strpos($line, 'Need to check for Payment') !== false) {
                $firstFinanceCardLine = $idx + 1;
            }
        }

        $this->assertGreaterThan(0, $pipelineHeaderLine, "'FINANCE PIPELINE' header not found");
        $this->assertGreaterThan(0, $firstFinanceCardLine, "First Finance card ('Need to check for Payment') not found");
        $this->assertLessThan(
            $firstFinanceCardLine,
            $pipelineHeaderLine,
            "Finance pipeline header (line {$pipelineHeaderLine}) must come BEFORE the first Finance card (line {$firstFinanceCardLine})"
        );
    }

    public function test_gap6_estimation_pipeline_header_is_before_estimation_cards(): void
    {
        $lines = file($this->estimationFile(), FILE_IGNORE_NEW_LINES);

        $pipelineHeaderLine = -1;
        $firstCardLine = -1;

        foreach ($lines as $idx => $line) {
            if ($pipelineHeaderLine === -1 && strpos($line, 'ESTIMATION PIPELINE') !== false) {
                $pipelineHeaderLine = $idx + 1;
            }
            if ($firstCardLine === -1 && $pipelineHeaderLine > 0
                && strpos($line, 'card shadow border-0') !== false) {
                $firstCardLine = $idx + 1;
            }
        }

        $this->assertGreaterThan(0, $pipelineHeaderLine, "'ESTIMATION PIPELINE' header not found");
        $this->assertGreaterThan(0, $firstCardLine, "First Estimation card div not found");
        $this->assertLessThan(
            $firstCardLine,
            $pipelineHeaderLine,
            "Estimation pipeline header (line {$pipelineHeaderLine}) must come BEFORE first card (line {$firstCardLine})"
        );
    }

    // -----------------------------------------------------------------
    // GAP 7 — Total Department card in super-dashboard is UNCHANGED
    // -----------------------------------------------------------------

    public function test_gap7_total_department_card_is_unchanged_in_super_dashboard(): void
    {
        $content = file_get_contents($this->superFile());

        // The Total Department card must still exist
        $this->assertStringContainsString(
            'Total',
            $content,
            "'Total Department' label must still exist in super-dashboard.blade.php"
        );

        $this->assertStringContainsString(
            'Department',
            $content,
            "'Total Department' label must still exist in super-dashboard.blade.php"
        );

        $this->assertStringContainsString(
            'department_total_count',
            $content,
            "PHP var '\$department_count[\'department_total_count\']' must still be in super-dashboard.blade.php"
        );
    }

    public function test_gap7_total_department_card_still_has_old_style_bg_warning(): void
    {
        $content = file_get_contents($this->superFile());

        // The Total Department card was intentionally left unchanged; it should still have the
        // old icon-shape bg-warning style (not modernized)
        $this->assertStringContainsString(
            'bg-warning',
            $content,
            "Total Department card in super-dashboard.blade.php must retain old 'bg-warning' style (was intentionally NOT modernized)"
        );
    }

    public function test_gap7_total_department_card_does_not_have_h100(): void
    {
        // Total Department card was not modernized; it must NOT have h-100.
        // In super-dashboard.blade.php, "Total" and "Department" appear on separate lines,
        // so search for the PHP variable that uniquely identifies the Department card.
        $lines = file($this->superFile(), FILE_IGNORE_NEW_LINES);

        $deptLabelLine = -1;
        foreach ($lines as $idx => $line) {
            if (strpos($line, 'department_total_count') !== false) {
                $deptLabelLine = $idx;
                break;
            }
        }

        $this->assertGreaterThanOrEqual(0, $deptLabelLine, "department_total_count var not found in super-dashboard.blade.php");

        // Search backwards up to 15 lines for the card div
        $deptCardDivLine = -1;
        for ($i = $deptLabelLine; $i >= max(0, $deptLabelLine - 15); $i--) {
            if (strpos($lines[$i], 'card shadow border-0') !== false) {
                $deptCardDivLine = $i;
                break;
            }
        }

        $this->assertGreaterThanOrEqual(0, $deptCardDivLine, "Total Department card div not found in super-dashboard.blade.php");

        // That card div line must NOT contain h-100
        $this->assertStringNotContainsString(
            'h-100',
            $lines[$deptCardDivLine],
            "Total Department card div must NOT have 'h-100' (it was intentionally left unchanged without that class)"
        );
    }

    public function test_gap7_total_employee_card_has_h100_but_total_department_does_not(): void
    {
        $lines = file($this->superFile(), FILE_IGNORE_NEW_LINES);

        $employeeCardHas100 = false;
        $departmentCardHas100 = true; // pessimistic default — will be set false if confirmed

        foreach ($lines as $idx => $line) {
            // Employee card: card div with h-100 followed closely by 'Total Employee'
            if (strpos($line, 'card shadow border-0 h-100') !== false) {
                for ($j = $idx + 1; $j < min($idx + 10, count($lines)); $j++) {
                    if (strpos($lines[$j], 'Total Employee') !== false) {
                        $employeeCardHas100 = true;
                        break;
                    }
                }
            }

            // Department card: identified by its unique PHP variable
            // The card div for it should NOT have h-100
            if (strpos($line, 'department_total_count') !== false) {
                // Look backwards for the nearest card div
                for ($k = $idx - 1; $k >= max(0, $idx - 15); $k--) {
                    if (strpos($lines[$k], 'card shadow border-0') !== false) {
                        if (strpos($lines[$k], 'h-100') === false) {
                            $departmentCardHas100 = false; // confirmed — no h-100
                        }
                        break;
                    }
                }
            }
        }

        $this->assertTrue($employeeCardHas100, "Total Employee card in super-dashboard.blade.php must have 'h-100'");
        $this->assertFalse($departmentCardHas100, "Total Department card must NOT have 'h-100' (intentionally unchanged)");
    }
}
