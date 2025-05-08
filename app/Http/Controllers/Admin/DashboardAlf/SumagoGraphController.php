<?php

namespace App\Http\Controllers\Admin\DashboardAlf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SumagoGraphController extends Controller
{
//     public function showGraph(Request $request)
//     {
//         $tableName = 'div_pp07_digital_partitioned';
//         // $today = now()->toDateString();                     // e.g., 2025-05-04
//         // $startOfWeek = now()->startOfWeek()->toDateString(); // Monday
//         // $endOfWeek = now()->endOfWeek()->toDateString();     // Sunday
//         // $startOfMonth = now()->startOfMonth()->toDateString();
//         // $endOfMonth = now()->endOfMonth()->toDateString();


//         $manualDate = '2025-04-26';
//         // Today
//         $today = date('Y-m-d', strtotime($manualDate));
//         // Start of week (Monday)
//         $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($manualDate)));
//         // End of week (Sunday)
//         $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($manualDate)));
//         // Start of month
//         $startOfMonth = date('Y-m-01', strtotime($manualDate));
//         // End of month
//         $endOfMonth = date('Y-m-t', strtotime($manualDate));

    
//         // Daily (Today, grouped by date)
//         $dailyData = DB::table($tableName)
//                 ->selectRaw('DATE(TriggerTime) as day, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
//                 ->whereDate('TriggerTime', $today)
//                 ->groupBy(DB::raw('DATE(TriggerTime)'))
//                 ->orderBy(DB::raw('DATE(TriggerTime)'))
//                 ->get();

//         // Hourly (Today, grouped by hour)
//         $hourlyData = DB::table($tableName)
//                     ->selectRaw('HOUR(TriggerTime) as hour, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
//                     ->whereDate('TriggerTime', $today)
//                     ->groupBy(DB::raw('HOUR(TriggerTime)'))
//                     ->orderBy(DB::raw('HOUR(TriggerTime)'))
//                     ->get();
    
    
//         // Weekly (Current week, grouped by week number)
//         $weeklyData = DB::table($tableName)
//     ->selectRaw('YEAR(TriggerTime) as year, WEEK(TriggerTime, 1) as week, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
//     ->whereBetween(DB::raw('DATE(TriggerTime)'), [$startOfWeek, $endOfWeek])
//     ->groupBy('year', 'week')
//     ->orderBy('year')
//     ->orderBy('week')
//     ->get();


    
//         // Monthly (Current month, grouped by month)
//         $monthlyData = DB::table($tableName)
//                     ->selectRaw('MONTH(TriggerTime) as month, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
//                     ->whereBetween(DB::raw('DATE(TriggerTime)'), [$startOfMonth, $endOfMonth])
//                     ->groupBy(DB::raw('MONTH(TriggerTime)'))
//                     ->orderBy(DB::raw('MONTH(TriggerTime)'))
//                     ->get();
    
    
//         // Prepare datasets
//         $dailyDataSet = [
//             'labels' => $dailyData->pluck('day')->toArray(),
//             'avg_strokes' => $dailyData->pluck('avg_strokes')->toArray(),
//             'avg_run_time' => $dailyData->pluck('avg_run_time')->toArray(),
//             'avg_idle_time' => $dailyData->pluck('avg_idle_time')->toArray(),
//         ];
    
//         $hourlyDataSet = [
//             'labels' => $hourlyData->pluck('hour')->toArray(),
//             'avg_strokes' => $hourlyData->pluck('avg_strokes')->toArray(),
//             'avg_run_time' => $hourlyData->pluck('avg_run_time')->toArray(),
//             'avg_idle_time' => $hourlyData->pluck('avg_idle_time')->toArray(),
//         ];
    
//         $weeklyDataSet = [
//             // 'labels' => $weeklyData->pluck('week')->toArray(),

//             'labels' => $weeklyData->map(fn($row) => $row->year . '-W' . $row->week)->toArray(),
//             'avg_strokes' => $weeklyData->pluck('avg_strokes')->toArray(),
//             'avg_run_time' => $weeklyData->pluck('avg_run_time')->toArray(),
//             'avg_idle_time' => $weeklyData->pluck('avg_idle_time')->toArray(),
//         ];
    
//         $monthlyDataSet = [
//             'labels' => $monthlyData->pluck('month')->toArray(),
//             'avg_strokes' => $monthlyData->pluck('avg_strokes')->toArray(),
//             'avg_run_time' => $monthlyData->pluck('avg_run_time')->toArray(),
//             'avg_idle_time' => $monthlyData->pluck('avg_idle_time')->toArray(),
//         ];
    

//         \Log::info('Daily count: ' . $dailyData->count());
// \Log::info('Weekly count: ' . $weeklyData->count());
// \Log::info('Monthly count: ' . $monthlyData->count());


//         return view('admin.pages.alf.graph', compact(
//             'dailyDataSet', 'hourlyDataSet', 'weeklyDataSet', 'monthlyDataSet'
//         ));
//     }
    

// public function showGraph(Request $request)
// {
//     $tableName = 'div_pp07_digital_partitioned';

//     $manualDate = '2025-04-26';

//     // Daily: Last 6 days from the manual date (including that day)
//     $startDaily = date('Y-m-d', strtotime('-5 days', strtotime($manualDate)));
//     $endDaily = $manualDate;

//     // Weekly: Last 6 months (approx 26 weeks) from manual date
//     $startWeekly = date('Y-m-d', strtotime('-6 months', strtotime($manualDate)));
//     $endWeekly = $manualDate;

//     // Monthly: Last 12 months from manual date
//     $startMonthly = date('Y-m-d', strtotime('-12 months', strtotime($manualDate)));
//     $endMonthly = $manualDate;

//     // 🔹 Daily Data (last 6 days)
//     $dailyData = DB::table($tableName)
//         ->selectRaw('DATE(TriggerTime) as day, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
//         ->whereBetween(DB::raw('DATE(TriggerTime)'), [$startDaily, $endDaily])
//         ->groupBy(DB::raw('DATE(TriggerTime)'))
//         ->orderBy(DB::raw('DATE(TriggerTime)'))
//         ->get();

//     // 🔹 Weekly Data (last 6 months)
//     $weeklyData = DB::table($tableName)
//         ->selectRaw('YEAR(TriggerTime) as year, WEEK(TriggerTime, 1) as week, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
//         ->whereBetween(DB::raw('DATE(TriggerTime)'), [$startWeekly, $endWeekly])
//         ->groupBy('year', 'week')
//         ->orderBy('year')
//         ->orderBy('week')
//         ->get();

//     // 🔹 Monthly Data (last 12 months)
//     $monthlyData = DB::table($tableName)
//         ->selectRaw('YEAR(TriggerTime) as year, MONTH(TriggerTime) as month, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
//         ->whereBetween(DB::raw('DATE(TriggerTime)'), [$startMonthly, $endMonthly])
//         ->groupBy('year', 'month')
//         ->orderBy('year')
//         ->orderBy('month')
//         ->get();


//         // Hourly: data for the provided date only
// $hourlyData = DB::table($tableName)
// ->selectRaw('HOUR(TriggerTime) as hour, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
// ->whereDate('TriggerTime', $manualDate)
// ->groupBy(DB::raw('HOUR(TriggerTime)'))
// ->orderBy(DB::raw('HOUR(TriggerTime)'))
// ->get();

//     // ✅ Prepare datasets
//     $dailyDataSet = [
//         'labels' => $dailyData->pluck('day')->toArray(),
//         'avg_strokes' => $dailyData->pluck('avg_strokes')->toArray(),
//         'avg_run_time' => $dailyData->pluck('avg_run_time')->toArray(),
//         'avg_idle_time' => $dailyData->pluck('avg_idle_time')->toArray(),
//     ];

//     $weeklyDataSet = [
//         'labels' => $weeklyData->map(fn($row) => $row->year . '-W' . $row->week)->toArray(),
//         'avg_strokes' => $weeklyData->pluck('avg_strokes')->toArray(),
//         'avg_run_time' => $weeklyData->pluck('avg_run_time')->toArray(),
//         'avg_idle_time' => $weeklyData->pluck('avg_idle_time')->toArray(),
//     ];

//     $monthlyDataSet = [
//         'labels' => $monthlyData->map(fn($row) => $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT))->toArray(),
//         'avg_strokes' => $monthlyData->pluck('avg_strokes')->toArray(),
//         'avg_run_time' => $monthlyData->pluck('avg_run_time')->toArray(),
//         'avg_idle_time' => $monthlyData->pluck('avg_idle_time')->toArray(),
//     ];

//     $hourlyDataSet = [
//         'labels' => $hourlyData->pluck('hour')->toArray(),
//         'avg_strokes' => $hourlyData->pluck('avg_strokes')->toArray(),
//         'avg_run_time' => $hourlyData->pluck('avg_run_time')->toArray(),
//         'avg_idle_time' => $hourlyData->pluck('avg_idle_time')->toArray(),
//     ];

//     return view('admin.pages.alf.graph', compact(
//         'dailyDataSet', 'weeklyDataSet', 'monthlyDataSet','hourlyDataSet'
//     ));


    

//     // return view('admin.pages.alf.graphwithtab', compact(
//     //     'dailyDataSet', 'weeklyDataSet', 'monthlyDataSet','hourlyDataSet'
//     // ));
// }


    public function showGraph(Request $request)
    {
        $tableName = 'div_pp07_digital_partitioned';

        // Get fromDate and toDate from request or default to current date
        $manualDate = date('Y-m-d');
        $fromDateInput = $request->input('fromDate') ?? $manualDate;
        $toDateInput = $request->input('toDate') ?? $manualDate;

        // Create full datetime boundaries
        $fromDateTime = $fromDateInput . ' 00:00:00';
        $toDateTime = $toDateInput . ' 23:59:59';

        // Check if same date to decide hourly or daily aggregation
        if ($fromDateInput === $toDateInput) {
            // Group by HOUR for single-day data
            // $data = DB::table($tableName)
            //     ->selectRaw('HOUR(TriggerTime) as time_unit, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
            //     ->whereBetween('TriggerTime', [$fromDateTime, $toDateTime])
            //     ->groupBy(DB::raw('HOUR(TriggerTime)'))
            //     ->orderBy(DB::raw('HOUR(TriggerTime)'))
            //     ->get();


            $data = DB::table($tableName)
                    ->selectRaw('HOUR(TriggerTime) as time_unit, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
                    ->whereBetween('TriggerTime', [$fromDateTime, $toDateTime])
                    ->whereNotIn('cola_Loss_NumberCode', [17, 18])
                    ->where('cola_Actual_Strokes', '>', 0)
                    ->where('cola_IoT_NumberCode', '>', 0)
                    ->groupBy(DB::raw('HOUR(TriggerTime)'))
                    ->orderBy(DB::raw('HOUR(TriggerTime)'))
                    ->get();


            // Format hours into AM/PM labels
            $labels = $data->pluck('time_unit')->map(function ($hour) {
                return date('g A', mktime($hour, 0));
            })->toArray();
        } else {
            // Group by DATE for multi-day range
            $data = DB::table($tableName)
                ->selectRaw('DATE(TriggerTime) as time_unit, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
                ->whereBetween('TriggerTime', [$fromDateTime, $toDateTime])
                ->groupBy(DB::raw('DATE(TriggerTime)'))
                ->orderBy(DB::raw('DATE(TriggerTime)'))
                ->get();

            // Use full date as label
            $labels = $data->pluck('time_unit')->toArray();
        }

        // Build dataset
        $dailyDataSet = [
            'labels' => $labels,
            'avg_strokes' => $data->pluck('avg_strokes')->toArray(),
            'avg_run_time' => $data->pluck('avg_run_time')->toArray(),
            'avg_idle_time' => $data->pluck('avg_idle_time')->toArray(),
        ];

        return view('admin.pages.alf.graph', compact('dailyDataSet'));
    }



}
