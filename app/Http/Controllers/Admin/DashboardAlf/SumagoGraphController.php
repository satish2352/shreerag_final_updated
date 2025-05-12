<?php

namespace App\Http\Controllers\Admin\DashboardAlf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alf\ShiftModel; 
use App\Models\Alf\MachineModel;
use App\Models\Alf\DashboardDailyModel;



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
        
        $tableName = 'div_pp08_digital_partitioned';
        // Get fromDate and toDate from request or default to current date
        $manualDate = date('Y-m-d');
        $dateInput = $request->input('fromDate') ? $request->input('fromDate'): $manualDate;
        $dateInputTo = $request->input('toDate') ? $request->input('toDate') :$manualDate;

        // Create full datetime boundaries
        $plant_id = '25';

        $dept_id = '78';
        $shiftsMaster = ShiftModel::where('plant_id',$plant_id)->get();
        $machine = MachineModel::where(['plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_on_off'=>'ON'])->get();

        // $sql_table_name_from_user = $request->sql_table_name_from_user;
        // if($sql_table_name_from_user==null) {
        //     $machinData = MachineModel::where(['plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_on_off'=>'ON'])->first();
        //     $sql_table_name_from_user = $machinData->sql_table_name_from_user;
        // }  else {
        //     $machinData = MachineModel::where(['sql_table_name'=>$request->sql_table_name_from_user, 'plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_on_off'=>'ON'])->orderBy('machine_name','ASC')->first();
        //     $sql_table_name_from_user = $request->sql_table_name_from_user;
        // }
        

        $shift_id = $request->shift_id ? $request->shift_id : 'A-SH';

        $toDate = '';
        $todayDate = '';
        if(!empty($dateInput)) {
            $todayDate = $dateInput;
        } else {
            $todayDate = date('Y-m-d');
        }

        if(!empty($dateInputTo)) {
            $toDate = $dateInputTo;
        } else {
            $toDate = date('Y-m-d');
        }

        $start_time = "$todayDate 07:00:01";
        $end_time = date("Y-m-d", strtotime($toDate . " +1 day")) . " 06:59:59";

        if (!empty($shift_id)) {
            $shiftMaster = ShiftModel::where('shift_id', $shift_id)->first();

            if ($shiftMaster && $shiftMaster->from_time_new && $shiftMaster->to_time_new) {
                $start_time = $todayDate . ' ' . $shiftMaster->from_time_new;

                // दोन्ही टाइम्स चे स्ट्रॉ टाईम मध्ये कन्व्हर्ट करा
                $from = strtotime($shiftMaster->from_time_new);
                $to = strtotime($shiftMaster->to_time_new);

                if ($to <= $from) {
                    // जर to_time_new हे from_time_new पेक्षा लहान किंवा समान असेल, म्हणजेच शिफ्ट दुसऱ्या दिवशी संपते
                    $end_time = date("Y-m-d", strtotime($toDate . " +1 day")) . ' ' . $shiftMaster->to_time_new;
                } else {
                    // शिफ्ट त्याच दिवशी संपते
                    $end_time = $toDate . ' ' . $shiftMaster->to_time_new;
                }
            }
        }


        $sql_table_name_from_user = $request->sql_table_name_from_user;
        if($sql_table_name_from_user==null) {
            $machinData = MachineModel::where(['plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_on_off'=>'ON'])->first();
            $sql_table_name_from_user = $machinData->sql_table_name_from_user;
        } else {
            $machinData = MachineModel::where(['sql_table_name'=>$request->sql_table_name_from_user, 'plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_on_off'=>'ON'])->orderBy('machine_name','ASC')->first();
            $sql_table_name_from_user = $request->sql_table_name_from_user;
        }
        
        $machine_name = $machinData->machine_name;

        if(($todayDate == $toDate) && ($todayDate == date('Y-m-d'))) {
            $data = DB::table($tableName)
                    ->selectRaw('HOUR(TriggerTime) as time_unit, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
                    ->whereBetween('TriggerTime', [$start_time, $end_time])
                    // ->whereNotIn('cola_Loss_NumberCode', [17, 18])
                    ->where('cola_Actual_Strokes', '>', 0)
                    ->where('cola_IoT_NumberCode', '>', 0)
                    ->groupBy(DB::raw('HOUR(TriggerTime)'))
                    ->orderBy(DB::raw('HOUR(TriggerTime)'))
                    ->get();


            $labels = $data->pluck('time_unit')->map(function ($hour) {
                        $start = date('g A', mktime($hour, 0));
                        $end = date('g A', mktime($hour + 1, 0));
                        return "$start to $end";
                    })->toArray();

        } elseif ($todayDate === $toDate) {
            $data = DB::table($tableName)
                    ->selectRaw('HOUR(TriggerTime) as time_unit, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
                    ->whereBetween('TriggerTime', [$start_time, $end_time])
                    // ->whereNotIn('cola_Loss_NumberCode', [17, 18])
                    ->where('cola_Actual_Strokes', '>', 0)
                    ->where('cola_IoT_NumberCode', '>', 0)
                    ->groupBy(DB::raw('HOUR(TriggerTime)'))
                    ->orderBy(DB::raw('HOUR(TriggerTime)'))
                    ->get();

            // $data = DashboardDailyModel::where([
            //             'plant_id'     => $plant_id,
            //             'dept_id'      => $dept_id,
            //             'shift_id'     => $shift_id,
            //             'machine_name'     => $machine_name,
            //         ])
            //         ->selectRaw('HOUR(trigger_time_from) as time_unit, AVG(actual_stoke) as avg_strokes, AVG(run_time) as avg_run_time, AVG(idle_time) as avg_idle_time')
            //         ->whereBetween('trigger_time_from', [$start_time, $end_time])
            //         ->whereBetween('trigger_time_to', [$start_time, $end_time])
            //         ->groupBy(DB::raw('HOUR(trigger_time_from)'))
            //         ->orderBy(DB::raw('HOUR(trigger_time_from)'))
            //         ->get();

            // $labels = $data->pluck('time_unit')->map(function ($hour) {
            //     return date('g A', mktime($hour, 0));
            // })->toArray();

            $labels = $data->pluck('time_unit')->map(function ($hour) {
                        $start = date('g A', mktime($hour, 0));
                        $end = date('g A', mktime($hour + 1, 0));
                        return "$start to $end";
                    })->toArray();
        } else {
            $data = DB::table($tableName)
                ->selectRaw('DATE(TriggerTime) as time_unit, AVG(cola_Actual_Strokes) as avg_strokes, AVG(cold_RunTime) as avg_run_time, AVG(cold_IdleTime) as avg_idle_time')
                ->whereBetween('TriggerTime', [$start_time, $end_time])
                ->groupBy(DB::raw('DATE(TriggerTime)'))
                ->orderBy(DB::raw('DATE(TriggerTime)'))
                ->get();
            
                
            $labels = $data->pluck('time_unit')->toArray();


            //  $data = DashboardDailyModel::where([
            //             'plant_id'     => $plant_id,
            //             'dept_id'      => $dept_id,
            //             'shift_id'     => $shift_id,
            //             'machine_name'     => $machine_name,
            //         ])
            //         ->selectRaw('HOUR(trigger_time_from) as time_unit, AVG(actual_stoke) as avg_strokes, AVG(run_time) as avg_run_time, AVG(idle_time) as avg_idle_time')
            //         ->whereBetween('trigger_time_from', [$start_time, $end_time])
            //         ->whereBetween('trigger_time_to', [$start_time, $end_time])
            //         // ->groupBy(DB::raw('HOUR(trigger_time_from)'))
            //         // ->orderBy(DB::raw('HOUR(trigger_time_from)'))
            //         ->get();

            //         dd($data);
        }

        // Build dataset
        $dailyDataSet = [
            'labels' => $labels,
            'avg_strokes' => $data->pluck('avg_strokes')->toArray(),
            'avg_run_time' => $data->pluck('avg_run_time')->toArray(),
            'avg_idle_time' => $data->pluck('avg_idle_time')->toArray(),
        ];



        if(($todayDate == $toDate) && ($todayDate == date('Y-m-d'))) {
            $dataBreak = DB::table($tableName)
                        ->selectRaw('HOUR(TriggerTime) as time_unit, AVG(cola_Loss_NumberCode) as avg_break_time')
                        ->whereBetween('TriggerTime', [$start_time, $end_time])
                        ->whereIn('cola_Loss_NumberCode', [17, 18])
                        ->where('cola_Actual_Strokes', '>', 0)
                        ->where('cola_IoT_NumberCode', '>', 0)
                        ->groupBy(DB::raw('HOUR(TriggerTime)'))
                        ->orderBy(DB::raw('HOUR(TriggerTime)'))
                        ->get();

            $labelsBreak = $dataBreak->pluck('time_unit')->map(function ($hour) {
                $start = date('g A', mktime($hour, 0));
                $end = date('g A', mktime($hour + 1, 0));
                return "$start to $end";
            })->toArray();

        } elseif ($todayDate === $toDate) {

            $dataBreak = DB::table($tableName)
                        ->selectRaw('HOUR(TriggerTime) as time_unit, AVG(cola_Loss_NumberCode) as avg_break_time')
                        ->whereBetween('TriggerTime', [$start_time, $end_time])
                        ->whereIn('cola_Loss_NumberCode', [17, 18])
                        ->where('cola_Actual_Strokes', '>', 0)
                        ->where('cola_IoT_NumberCode', '>', 0)
                        ->groupBy(DB::raw('HOUR(TriggerTime)'))
                        ->orderBy(DB::raw('HOUR(TriggerTime)'))
                        ->get();

            // $labelsBreak = $dataBreak->pluck('time_unit')->map(function ($hour) {
            //     return date('g A', mktime($hour, 0));
            // })->toArray();

            $labelsBreak = $dataBreak->pluck('time_unit')->map(function ($hour) {
                $start = date('g A', mktime($hour, 0));
                $end = date('g A', mktime($hour + 1, 0));
                return "$start to $end";
            })->toArray();
        } else {
            $dataBreak = DB::table($tableName)
            ->selectRaw('DATE(TriggerTime) as time_unit, AVG(cola_Loss_NumberCode) as avg_break_time')
                ->whereBetween('TriggerTime', [$start_time, $end_time])
                ->whereIn('cola_Loss_NumberCode', [17, 18])
                ->where('cola_Actual_Strokes', '>', 0)
                ->where('cola_IoT_NumberCode', '>', 0)
                ->groupBy(DB::raw('DATE(TriggerTime)'))
                ->orderBy(DB::raw('DATE(TriggerTime)'))
                ->get();

            $labelsBreak = $dataBreak->pluck('time_unit')->toArray();
        }

        $dailyDataSetBreak = [
            'labels' => $labelsBreak,
            'avg_break' => $dataBreak->pluck('avg_break_time')->toArray(),
        ];

        
        

        if(($todayDate == $toDate) && ($todayDate == date('Y-m-d'))) {
            $dataWithoutBreak = DB::table($tableName)
                            ->selectRaw('HOUR(TriggerTime) as time_unit, AVG(cola_Loss_NumberCode) as avg_without_break_time')
                            ->whereBetween('TriggerTime', [$start_time, $end_time])
                            ->whereNotIn('cola_Loss_NumberCode', [17, 18])
                            ->where('cola_Actual_Strokes', '>', 0)
                            ->where('cola_IoT_NumberCode', '>', 0)
                            ->groupBy(DB::raw('HOUR(TriggerTime)'))
                            ->orderBy(DB::raw('HOUR(TriggerTime)'))
                            ->get();



            $labelsWithoutBreak = $dataWithoutBreak->pluck('time_unit')->map(function ($hour) {
                                $start = date('g A', mktime($hour, 0));
                                $end = date('g A', mktime($hour + 1, 0));
                                return "$start to $end";
                            })->toArray();
        
        
        } elseif ($todayDate === $toDate) {

            $dataWithoutBreak = DB::table($tableName)
                        ->selectRaw('HOUR(TriggerTime) as time_unit, AVG(cola_Loss_NumberCode) as avg_without_break_time')
                        ->whereBetween('TriggerTime', [$start_time, $end_time])
                        ->whereNotIn('cola_Loss_NumberCode', [17, 18])
                        ->where('cola_Actual_Strokes', '>', 0)
                        ->where('cola_IoT_NumberCode', '>', 0)
                        ->groupBy(DB::raw('HOUR(TriggerTime)'))
                        ->orderBy(DB::raw('HOUR(TriggerTime)'))
                        ->get();

            // $labelsWithoutBreak = $dataWithoutBreak->pluck('time_unit')->map(function ($hour) {
            //     return date('g A', mktime($hour, 0));
            // })->toArray();

            $labelsWithoutBreak = $dataWithoutBreak->pluck('time_unit')->map(function ($hour) {
                $start = date('g A', mktime($hour, 0));
                $end = date('g A', mktime($hour + 1, 0));
                return "$start to $end";
            })->toArray();

        } else {
            $dataWithoutBreak = DB::table($tableName)
                ->selectRaw('DATE(TriggerTime) as time_unit, AVG(cola_Loss_NumberCode) as avg_without_break_time')
                ->whereBetween('TriggerTime', [$start_time, $end_time])
                ->whereNotIn('cola_Loss_NumberCode', [17, 18])
                ->where('cola_Actual_Strokes', '>', 0)
                ->where('cola_IoT_NumberCode', '>', 0)
                ->groupBy(DB::raw('DATE(TriggerTime)'))
                ->orderBy(DB::raw('DATE(TriggerTime)'))
                ->get();

            $labelsWithoutBreak = $dataWithoutBreak->pluck('time_unit')->toArray();
        }

        $dailyDataSetWithoutBreak = [
            'labels' => $labelsWithoutBreak,
            'avg_without_break' => $dataWithoutBreak->pluck('avg_without_break_time')->toArray(),
        ];

        // dd($dailyDataSetWithoutBreak);
        // return view('admin.pages.alf.graph', compact('dailyDataSet'));
        return view('admin.pages.alf.graph', compact('dailyDataSet', 'dailyDataSetBreak', 'dailyDataSetWithoutBreak',  'shiftsMaster','plant_id','shift_id','dept_id','machine','sql_table_name_from_user','todayDate','toDate'));
    }



}
