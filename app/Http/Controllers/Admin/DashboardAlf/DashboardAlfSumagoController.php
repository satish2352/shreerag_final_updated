<?php
namespace App\Http\Controllers\Admin\DashboardAlf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alf\LoginuserModel;
use App\Models\Alf\MachineModel;
use App\Models\Alf\DepartmentModel;
use App\Models\Alf\PlantModel;
use App\Models\Alf\ProjectModel;
use App\Models\Alf\ShiftModel; 

use App\Helpers\CommonHelper;

use DB;
use Session;
use Auth;
use DateTime;
use Carbon\Carbon;
use PDF;

class DashboardAlfSumagoController extends Controller
{

    public function spmAnalysis(Request $request,$plant_id = null, $dept_id = null, $pd_id = null)
    {  

        try {

            $plant_id = '25';
            $dept_id = '78';
            $colt_ShiftSelected = '8 HOUR SHIFT';

            // Filters
            // dd($request);
            // $dateInput = '2025-01-01';
            $dateInput = $request->dateInput ?? '';
            $dateInputTo = $request->dateInput_todate ?? '';
            $colt_ShiftSelected = $request->colt_ShiftSelected ?? '';

            $toDate = '';

            if(!empty($dateInputTo))
            {
                $toDate = $dateInputTo;
            }
            else
            {
                $toDate = date('Y-m-d');
            }

            //Filter Working 
            $todayDate = '';
            if(!empty($dateInput))
            {
                $todayDate = $dateInput;
            }
            else
            {
                $todayDate = date('Y-m-d');
            }

            // Default time range (07:00:01 to next day 06:59:59)
            $start_time = "$todayDate 07:00:01";
            $end_time = date("Y-m-d", strtotime($toDate . " +1 day")) . " 06:59:59";
            // Shift filter dynamically
            if (!empty($colt_ShiftSelected)) 
            {
                $shiftMaster = ShiftModel::where('shift_id', $colt_ShiftSelected)->first();

                if ($shiftMaster && $shiftMaster->from_time_new && $shiftMaster->to_time_new) 
                {
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
            //Filter Working

        // Filters


            $getPlantId = $plant_id ?? '';
            $getDeptId = $dept_id ?? '';
            // $plant_id = Auth::user()->plant; // plant id fetch login employee. //Base Department For Auditor
            // $dept_id = Auth::user()->dept; // dept id fetch login employee.
            // $user_id = Auth::user()->id; // user id fetch login employee.
            // $userDetail = LoginuserModel::where(['id'=>$user_id])->first();
            // $fromYear  =  date('Y',strtotime($userDetail->date_filter_startdate));
            // $toYear  =  date('Y',strtotime($userDetail->date_filter_enddate));
            // $fromDate  =  date('Y-m-d',strtotime($userDetail->date_filter_startdate));
            // $toDate1  =  date('Y-m-d',strtotime($userDetail->date_filter_enddate));
            // $first_day_this_month =  Auth::user()->date_filter_startdate;
            // $last_day_this_month  = Auth::user()->date_filter_enddate;
            // $yearStartMonth = date('m-Y',strtotime($first_day_this_month));
            // $yearEndMonth = date('m-Y',strtotime($last_day_this_month));

            $startdate = '';
            $enddate = '';

            $shiftsMaster = ShiftModel::where('plant_id',$getPlantId)->get();

            // $selected_year='';
            // if(!empty($request->selected_year))
            // {
            //     $selected_year = $request->selected_year-1;
            //     $selected_year_addoneyear = $selected_year+1;
            //     $startdate = $selected_year.'-04-01';
            //     $enddate = $selected_year_addoneyear.'-03-31';
            // }
            // else
            // {
            //     $startdate = $first_day_this_month;
            //     $enddate = $last_day_this_month; 
            // }
            // $months = $this->generateMonthOptions_new($startdate, $enddate);
            $machine = MachineModel::where(['plant_id'=>$getPlantId,'dept_id'=>$getDeptId,'iot_on_off'=>'ON'])->orderBy('machine_name','ASC')->get();

            $div_mainline = [];

            $deptData = DepartmentModel::where(['id'=>$getDeptId])->first();
            $plant = PlantModel::all();
            $plantCodeData = PlantModel::find($getPlantId);
            $plantCode = $plantCodeData ? $plantCodeData->plant_code : ''; // Safe access

            
            $data = [
                'machine'=>$machine,
                'div_mainline'=>$div_mainline,
                'deptData'=>$deptData,
                'plant'=>$plant,
                'getPlantId'=>$getPlantId,
                'getDeptId'=>$getDeptId,
                'todayDate'=>$todayDate,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'shiftsMaster'=>$shiftsMaster,
                'colt_ShiftSelected'=>$colt_ShiftSelected,
                'toDate'=>$toDate,
            ];



            $sql_table_name_from_user = $request->sql_table_name_from_user ?? '';
            if(!empty($sql_table_name_from_user))
            {
                $sql_table_name_from_user = MachineModel::where(['plant_id'=>$getPlantId,'dept_id'=>$getDeptId,'iot_on_off'=>'ON'])->value('sql_table_name');
            }
            else
            {
                $sql_table_name_from_user = $request->sql_table_name_from_user;
            }
            // dd($sql_table_name_from_user);
            $dataFinal = [];
            $arrayDataToPrint = [];
            $machineDataToFetch = MachineModel::where(['sql_table_name'=>$sql_table_name_from_user, 'plant_id'=>$getPlantId,'dept_id'=>$getDeptId,'iot_on_off'=>'ON'])->orderBy('machine_name','ASC')->get();
            if (!empty($machine)) {
                // Preload part master for all machines once
                $allParts = DB::table('tbl_part_master')
                    ->where('plant_id', $getPlantId)
                    ->where('dept_id', $getDeptId)
                    ->select('iot_number', 'spm', 'short_part_number')
                    ->get()
                    ->groupBy('iot_number');

                foreach ($machineDataToFetch as $machineValue) {
                    $sql_table_name = $machineValue->sql_table_name ?? '';
                    // if (empty($sql_table_name)) continue;

                    // // Preload all records for this machine in one go
                    // $records = DB::table("$sql_table_name")
                    //     ->select('cola_IoT_NumberCode', DB::raw('MIN(TriggerTime) as FirstTriggerTime'), DB::raw('SUM(cola_Actual_Strokes) as total_strokes'))
                    //     ->whereBetween('TriggerTime', [$start_time, $end_time])
                    //     ->where('cola_Actual_Strokes', '>', 0)
                    //     ->where('cola_IoT_NumberCode', '>', 0)
                    //     ->when(!empty($colt_ShiftSelected), function ($query) use ($colt_ShiftSelected) {
                    //         $query->where('colt_ShiftSelected', $colt_ShiftSelected);
                    //     })
                    //     ->groupBy('cola_IoT_NumberCode')
                    //     ->get();
                    //     dd($records);


                    
                    
                    if(!empty($colt_ShiftSelected))  {
                        $records = DB::table("$sql_table_name as d")
                                    ->join(DB::raw("(
                                        SELECT cola_IoT_NumberCode, MAX(TriggerTime) AS FirstTriggerTime
                                        FROM $sql_table_name
                                        WHERE TriggerTime BETWEEN '$start_time' AND '$end_time'
                                            AND cola_Actual_Strokes > 0
                                            AND cola_IoT_NumberCode > 0
                                            AND colt_ShiftSelected = '$colt_ShiftSelected'
                                        GROUP BY cola_IoT_NumberCode
                                    ) as t"), function ($join) {
                                        $join->on('d.cola_IoT_NumberCode', '=', 't.cola_IoT_NumberCode')
                                                ->on('d.TriggerTime', '=', 't.FirstTriggerTime');
                                    })
                                    ->select( 'd.cola_IoT_NumberCode', 'd.TriggerTime', 'd.cola_Actual_Strokes') // pick specific fields
                                    ->get();


                    }  else  {

                        $records = DB::table("$sql_table_name as d")
                                    ->join(DB::raw("(
                                        SELECT cola_IoT_NumberCode, MAX(TriggerTime) AS FirstTriggerTime
                                        FROM $sql_table_name
                                        WHERE TriggerTime BETWEEN '$start_time' AND '$end_time'
                                        AND cola_Actual_Strokes > 0
                                        AND cola_IoT_NumberCode > 0
                                        GROUP BY cola_IoT_NumberCode
                                    ) as t"), function ($join) {
                                        $join->on('d.cola_IoT_NumberCode', '=', 't.cola_IoT_NumberCode')
                                            ->on('d.TriggerTime', '=', 't.FirstTriggerTime');
                                    })
                                    ->select( 'd.cola_IoT_NumberCode', 'd.TriggerTime', 'd.cola_Actual_Strokes') // pick specific fields
                                    ->get();
                      
                    }
                    
                    if ($records->isEmpty()) continue;

                    $iotNumbers = $records->pluck('cola_IoT_NumberCode')->toArray();

                    // Preload total run, load/unload, idle time, min/max time
                    
                    $timingData = DB::table($sql_table_name)
                        ->whereIn('cola_IoT_NumberCode', $iotNumbers)
                        ->whereBetween('TriggerTime', [$start_time, $end_time])
                        ->select(
                            'cola_IoT_NumberCode',
                            DB::raw('SUM(cold_RunTime) as total_run_time'),
                            DB::raw('SUM(cold_Loading_UnloadingTime) as total_loading_unloading_time'),
                            DB::raw('SUM(cold_IdleTime) as total_idle_time'),
                            DB::raw('MIN(TriggerTime) as min_time'),
                            DB::raw('MAX(TriggerTime) as max_time')
                        )
                        ->groupBy('cola_IoT_NumberCode')
                        ->get()
                        ->keyBy('cola_IoT_NumberCode');

                    // Preload tea/lunch break counts
                
                    
                    $teaLunchBreaks = DB::table($sql_table_name)
                        ->whereIn('cola_IoT_NumberCode', $iotNumbers)
                        ->whereBetween('TriggerTime', [$start_time, $end_time])
                        ->whereIn('cola_Loss_NumberCode', ['17', '18'])
                        ->where('cola_Actual_Strokes', '>', 0)
                        ->select('cola_IoT_NumberCode', DB::raw('COUNT(TriggerTime) as break_count'))
                        ->groupBy('cola_IoT_NumberCode')
                        ->pluck('break_count', 'cola_IoT_NumberCode')
                        ->toArray();
                    
                    $firstRecord = true;
                    
                    foreach ($records as $record) {
                        $actualStrokes = 0;
                        $iotNumber = $record->cola_IoT_NumberCode;
                        if (empty($iotNumber)) continue;


                        // Satish Data
                        $partMasterNewData = DB::table('tbl_part_master')
                        ->where(['plant_id'=>$getPlantId,'dept_id'=>$getDeptId,'iot_number'=>$iotNumber])->select('spm')->first();
                        $stdSPM = $partMasterNewData->spm ?? 0;
                        
                        // DB::connection()->enableQueryLog();
                        $results = DB::table($sql_table_name)
                                    ->where('cola_IoT_NumberCode', $iotNumber)
                                    ->whereBetween('TriggerTime', [$start_time, $end_time])
                                    ->selectRaw('
                                        SUM(cold_RunTime) as total_run_time,
                                        SUM(cold_Loading_UnloadingTime) as total_loading_unloading_time,
                                        SUM(cold_IdleTime) as total_idle_time,
                                        MIN(TriggerTime) as min_time,
                                        MAX(TriggerTime) as max_time,
                                        SUM(CASE WHEN cola_Loss_NumberCode IN (17, 18) AND cola_Actual_Strokes > 0 THEN 1 ELSE 0 END) as total_tea_lunch_break
                                    ')
                                   
                                    ->first();
                                    
                        // dd(DB::getQueryLog());
                        $runTimeQuery = $results->total_run_time ?? 0;
                        $cold_Loading_UnloadingTimeQuery = $results->total_loading_unloading_time ?? 0;
                        $idleTimeQuery = $results->total_idle_time ?? 0;


                        // // Convert values to minutes
                        $ttt = ($runTimeQuery / 60) + ($cold_Loading_UnloadingTimeQuery / 60);
                                        
                        // Check if $ttt is zero before division
                        if ($ttt > 0) {
                            $runnSpm = ($record->cola_Actual_Strokes) / $ttt;
                        } else {
                            $runnSpm = 0; // Or handle it in another way
                        }


                        $availableTimeFirstValue = $results->min_time ?? null;
                        $availableTimeLastValue = $results->max_time ?? null;
                    
                        $availableTimestart = DateTime::createFromFormat('Y-m-d H:i:s.u', $availableTimeFirstValue) ?: 
                                            DateTime::createFromFormat('Y-m-d H:i:s', $availableTimeFirstValue);
                    
                        $availableTimeend = DateTime::createFromFormat('Y-m-d H:i:s.u', $availableTimeLastValue) ?: 
                                            DateTime::createFromFormat('Y-m-d H:i:s', $availableTimeLastValue);
                    
                        if ($availableTimestart && $availableTimeend) {
                            // Calculate the difference in seconds
                            $availableTime = $availableTimeend->getTimestamp() - $availableTimestart->getTimestamp();
                        } else {
                            $availableTime = '';
                        }

                        $totalTeaLunchBreak = $results->total_tea_lunch_break;
                        $availableTime = is_numeric($availableTime) ? floatval($availableTime) : 0;
                        $totalTeaLunchBreak = is_numeric($totalTeaLunchBreak) ? floatval($totalTeaLunchBreak) : 0;
                    
                        $timeDiff = ($availableTime / 60) - ($totalTeaLunchBreak / 60);
                    
                        if ($timeDiff != 0) {
                            $avgActualSpm = ($record->cola_Actual_Strokes / $timeDiff);
                        } else {
                            $avgActualSpm = 0; // Prevent division by zero
                        }


                        // Avg Variance 28-03-2025 (Friday)
                        $avgSPM = round($avgActualSpm ?? 0, 2);
                        
                        $avgVariance = ($stdSPM != 0) ? (($avgSPM - $stdSPM) / $stdSPM): 0;
                        $avgVariance = $avgVariance*100;

                        // Satish Data

                        $timing = $timingData[$iotNumber] ?? null;
                        if (!$timing) continue;

                        

                        $breakCount = $teaLunchBreaks[$iotNumber] ?? 0;

                        // Calculate available minutes
                        $minTime = strtotime($timing->min_time);
                        $maxTime = strtotime($timing->max_time);
                        $availableTimeSec = $maxTime - $minTime;
                        $availableMinutes = ($availableTimeSec / 60) - $breakCount;
                        // dd($record->total_strokes);
                        $actualStrokes += $record->cola_Actual_Strokes ?? 0;

                        // SPM Calculation
                        $partInfo = $allParts[$iotNumber] ?? collect();
                        $partNumbers = $partInfo->pluck('short_part_number')->toArray();

                        $partNumbers = array_filter($partNumbers, function($value) {
                            return $value !== null;
                        });
                        $partNumbers = array_unique($partNumbers);
                        $partNumbers = implode("/",$partNumbers);

                        // $runningVariance = ($stdSPM != 0) ? (($runnSpm - $stdSPM) / $stdSPM) : 0;
                        // $runningVariance = $runningVariance*100;

                        $runnSpm = round($runnSpm ?? 0, 2);
                        $runningVariance = ($stdSPM != 0) ? (($runnSpm - $stdSPM) / $stdSPM) : 0;
                        $runningVariance = $runningVariance*100;

                        // $avgActualSpm = ($availableMinutes > 0) ? ($actualStrokes / $availableMinutes) : 0;
                        $totalRunningTimeMinutes = ($timing->total_run_time + $timing->total_loading_unloading_time) / 60;
                        $runSpm = ($totalRunningTimeMinutes > 0) ? ($actualStrokes / $totalRunningTimeMinutes) : 0;

                        
                        $avgSPM = round($avgActualSpm ?? 0, 2);
                        $avgVariance = ($stdSPM != 0) ? (($avgSPM - $stdSPM) / $stdSPM): 0;
                        $avgVariance = $avgVariance*100;

                        // $dataLossDetails = $this->getLossDetails($colt_ShiftSelected, $getPlantId, $getDeptId, $sql_table_name, $iotNumbers, $start_time, $end_time);
                        // dd($dataLossDetails);
                        $dataFinal[] = [
                            'machine_name'     => $firstRecord ? $machineValue->machine_name : '',
                            'row_span_machine' => $firstRecord ? $records->count() : '',
                            'iotNumbers'        => $iotNumbers,  
                            'part_number'      => $partNumbers,
                            'actual_stoke'     => $actualStrokes,
                            'run_time'         => $this->formatMinutesSeconds(($timing->total_run_time) / 60),
                            'load_unload_time' => $this->formatMinutesSeconds(($timing->total_loading_unloading_time) / 60),
                            'idle_time'        => $this->formatMinutesSeconds(($timing->total_idle_time) / 60),
                            'std_spm'          => $stdSPM,
                            'run_spm'          => round($runnSpm, 2),
                            'actual_spm'       => round($avgActualSpm, 2) ?? '0',
                            'running_variance' => round($runningVariance, 2),
                            'avg_variance'     => round($avgVariance,2) ?? '0',
                            // 'total_loss'     => [$totalLossMin11.":".$totalLossSec11],
                            // 'total_loss'     => $dataLossDetails['total_loss_time'],
                            'total_loss'     => "",
                            // 'modal_details'  => $dataLossDetails,

                        ];

                        $firstRecord = false;
                    }
                }
            }

        

            // dd($data);
            // Satish asddfasf
                

            // 'machine'=>$machine,
            // 'div_mainline'=>$div_mainline,
            // 'deptData'=>$deptData,
            // 'plant'=>$plant,
            // 'getPlantId'=>$getPlantId,
            // 'getDeptId'=>$getDeptId,
            // 'todayDate'=>$todayDate,
            // 'start_time'=>$start_time,
            // 'end_time'=>$end_time,
            // 'shiftsMaster'=>$shiftsMaster,
            // 'colt_ShiftSelected'=>$colt_ShiftSelected,
            // 'toDate'=>$toDate,
            if ($getPlantId === '25' && $getDeptId === '78') // MFG - Chassis Assembly Line-c11
            { 
                return view('admin.pages.alf.index_dashboard_dept_sumago',compact('sql_table_name_from_user','machine','dataFinal','todayDate','getPlantId','getDeptId','toDate','shiftsMaster','colt_ShiftSelected'));
            }

            if ($getPlantId === '25' && $getDeptId === '93') // MFG - Tandem Line-c11
            { 
                return view("admin.user.dashboard.{$plantCode}.93.spm_analysis.index_dashboard_dept",$data);
            }

            if ($getDeptId != '93' || $getDeptId != '78' ) // MFG - Tandem Line-c11
            { 
                return view("admin.user.dashboard.spm_analysis.blank_page.index_dashboard_dept",$data);
            }

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    //14-03-2025(Friday) SPM Analysis


    function formatMinutesSeconds($minutesFloat) {
        $totalSeconds = round($minutesFloat * 60);
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;
        return sprintf("%d:%02d", $minutes, $seconds);
    }


    // function getLossDetails($colt_ShiftSelected, $getPlantId, $getDeptId, $sql_table_name, $cola_IoT_NumberCode, $start_time, $end_time) {


    //     if(!empty($colt_ShiftSelected)) 
    //     {
    //         $div_mainline11 = DB::table($sql_table_name)
    //         ->whereBetween('TriggerTime', [$start_time, $end_time])
    //         ->where('colt_ShiftSelected',$colt_ShiftSelected)
    //         ->where('cola_IoT_NumberCode',$cola_IoT_NumberCode)
    //         ->select('cola_Loss_NumberCode', DB::raw('COUNT(*) as total_rows')) // adjust as needed
    //         ->where('cola_Actual_Strokes', '>', 0)
    //         ->where('cola_IoT_NumberCode', '>', 0)
    //         ->whereNotIn('cola_Loss_NumberCode', ['1', '0'])
    //         ->groupBy('cola_Loss_NumberCode', 'colt_ShiftSelected')
    //         ->get();
    //     }
    //     else
    //     {
    //         $div_mainline11 = DB::table($sql_table_name)
            
    //         ->whereBetween('TriggerTime', [$start_time, $end_time])
    //         ->where('cola_Actual_Strokes', '>', 0)
    //         ->where('cola_IoT_NumberCode', '>', 0)
    //         ->where('cola_IoT_NumberCode',$cola_IoT_NumberCode)
    //         ->whereNotIn('cola_Loss_NumberCode', ['1', '0'])
    //         ->select('cola_Loss_NumberCode', DB::raw('COUNT(*) as total_rows')) // adjust as needed
    //         ->groupBy('cola_Loss_NumberCode', 'colt_ShiftSelected')
    //         ->get();
    //     }

    //     $totalLossMin = 0;
    //     $totalLossSec = 0;
    //     $loss_details = [];
    //     foreach($div_mainline11 as $lossvalue) {
    //         $outputData= [];

    //         $lossName = DB::table('tbl_loss_master')
    //                 ->where('plant_id',$getPlantId)
    //                 ->where('dept_id',$getDeptId)
    //                 ->where('loss_code', $lossvalue->cola_Loss_NumberCode)
    //                 ->value('loss_name');


    //         $lossTime1 = DB::table(DB::raw("(SELECT * 
    //                 FROM $sql_table_name 
    //                 WHERE TriggerTime BETWEEN '$start_time' AND '$end_time') as subquery"))
    //         ->whereIn('cola_Loss_NumberCode', [$lossvalue->cola_Loss_NumberCode])
    //         ->whereNotIn('cola_Loss_NumberCode', [1])
    //         ->count();
    //         if ($lossTime1 > 0) {
    //             $lossTime1 = $lossTime1 / 60;
    //         } else {
    //             $lossTime1 = 0; // or handle it appropriately
    //         }
    //         $totalSeconds = round($lossTime1 * 60);  
    //         $minutes = floor($totalSeconds / 60);  
    //         $seconds = $totalSeconds % 60;  
    //         $lossTimeDetails =  sprintf("%d:%02d", $minutes, $seconds);  
    //         $totalLossMin += $minutes;
    //         $totalLossSec += $seconds;

    //         $outputData =[
    //             "lossName" => $lossName,
    //             "lossTimeDetails" => $lossTimeDetails,
    //         ];

    //         array_push($loss_details,$outputData);
                
    //     }
        

    //     $finalOutput =[
    //         "total_loss_time" => $totalLossMin.":".$totalLossSec,
    //         "loss_details"=> $loss_details
    //     ];
        
    //     return $finalOutput;
    // }

    


}
