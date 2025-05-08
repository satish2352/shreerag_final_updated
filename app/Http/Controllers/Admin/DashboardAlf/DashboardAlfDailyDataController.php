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
use App\Models\Alf\DashboardDailyModel; 



use App\Helpers\CommonHelper;

use DB;
use Session;
use Auth;
use DateTime;
use Carbon\Carbon;
use PDF;

class DashboardAlfDailyDataController extends Controller
{


    public function dailyCroneJob(Request $request,$plant_id = null, $dept_id = null, $pd_id = null)
    {   



          // Satish Code

                    $startDate = new \DateTime('2024-11-01');
                    // $startDate = new \DateTime('2024-05-06');
                    $endDate = new \DateTime('2025-05-08');
                    // $endDate = new \DateTime('2024-05-06');
            
                    while ($startDate <= $endDate) {
                        $todayDate = $startDate->format('Y-m-d');
                        $todayDate = $startDate->format('Y-m-d');
                        // Satish Code End 

                        
        // $todayDate = "2025-05-06";
        // $todayDate = date('Y-m-d');
        $start_time = "$todayDate 07:00:01";
        $end_time = date("Y-m-d", strtotime($todayDate . " +1 day")) . " 06:59:59";

        $shiftMasterNew = ShiftModel::get();
        foreach($shiftMasterNew as $key => $shiftMaster) {
            if ($shiftMaster && $shiftMaster['from_time_new'] && $shiftMaster['to_time_new']) 
            {
                $start_time = $todayDate . ' ' . $shiftMaster['from_time_new'];

                // दोन्ही टाइम्स चे स्ट्रॉ टाईम मध्ये कन्व्हर्ट करा
                $from = strtotime($shiftMaster['from_time_new']);
                $to = strtotime($shiftMaster['to_time_new']);

                if ($to <= $from) {
                    // जर to_time_new'] हे from_time_new'] पेक्षा लहान किंवा समान असेल, म्हणजेच शिफ्ट दुसऱ्या दिवशी संपते
                    $end_time = date("Y-m-d", strtotime($todayDate . " +1 day")) . ' ' . $shiftMaster['to_time_new'];
                } else {
                    // शिफ्ट त्याच दिवशी संपते
                    $end_time = $todayDate . ' ' . $shiftMaster['to_time_new'];
                }
            }
        

            $plant_id = $shiftMaster['plant_id'];
            $machine = MachineModel::where(['plant_id'=>$plant_id,'iot_on_off'=>'ON'])->orderBy('machine_name','ASC')->get();
            $plantCodeData = PlantModel::find($plant_id);
            $plantCode = $plantCodeData ? $plantCodeData->plant_code : ''; // Safe access

            // Satish 
            $colt_ShiftSelected = $shiftMaster['shift_id'];
            foreach($machine as $machineValue) {
                $dataFinal = [];
              
                $machine_name = $machineValue['machine_name'];
                $sql_table_name = $machineValue['sql_table_name'] ?? '';
                $dept_id = $machineValue['dept_id'] ?? '';


                $records = DB::table("$sql_table_name as d")
                    ->join(DB::raw("(SELECT cola_IoT_NumberCode, MAX(TriggerTime) AS FirstTriggerTime 
                                FROM $sql_table_name 
                                WHERE TriggerTime BETWEEN '$start_time' AND '$end_time'
                                AND cola_Actual_Strokes > 0
                                AND cola_IoT_NumberCode > 0
                                AND colt_ShiftSelected = '$colt_ShiftSelected'
                                        GROUP BY cola_IoT_NumberCode,colt_ShiftSelected) as t"), function ($join) {
                            $join->on('d.cola_IoT_NumberCode', '=', 't.cola_IoT_NumberCode')
                            ->on('d.TriggerTime', '=', 't.FirstTriggerTime');
                    })
                    ->select('d.*')->get();
             
                $totalActual_Strokes = 0;
                $runTimeTotal = 0;
                $runTime = 0;
                $runTimeSec = 0;
               
                $ldTime = 0;
                $ldTimeSec = 0;
               
                $IdlTime = 0;
                $IdlTimeSec = 0;
               
                $mscount = 0;
               
                $rowCount = 0;
                $printed = false;
                $printed ='';
                if($records) {
                    $part_number = "";           
                    $actual_stoke = "";          
                    $run_time = "";              
                    $load_unload_time = "";      
                    $idle_time = "";             
                    $std_spm = "";               
                    $run_spm = "";               
                    $actual_spm = "";            
                    $running_variance = "";      
                    $avg_variance = "";

                    foreach($records as $index => $record) {
                        $iotNumber = $record->cola_IoT_NumberCode ?? '';
                        $op_no = $record->cola_OperationNumber ?? '';
                        $partMasterNewData = DB::table('tbl_part_master')
                                    ->where(['plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_number'=>$record->cola_IoT_NumberCode])->first();
                        

                        $multiplePartNumber = DB::table('tbl_part_master')
                            ->where([
                                'plant_id'=> $plant_id,
                                'dept_id' => $dept_id,
                                'iot_number' =>$iotNumber
                            ])
                            ->select('short_part_number as part_number')
                            ->distinct()
                            ->get();
                        $partExists = DB::table('tbl_part_master')
                            ->where(['plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_number'=>$record->cola_IoT_NumberCode])
                            ->exists();

                        if ($partExists) {
                            $rowCount++;
                        }

                             
                        if($partMasterNewData) {


                            $runTimeQuery = DB::table(DB::raw("(SELECT * 
                                    FROM $sql_table_name 
                                    WHERE cola_IoT_NumberCode='$iotNumber' 
                                    AND TriggerTime BETWEEN '$start_time' AND '$end_time') as subquery"))
                                ->sum('cold_RunTime');

                            $cold_Loading_UnloadingTimeQuery = DB::table(DB::raw("(SELECT * 
                                    FROM $sql_table_name 
                                    WHERE cola_IoT_NumberCode='$iotNumber' 
                                    AND TriggerTime BETWEEN '$start_time' AND '$end_time') as subquery"))
                                ->sum('cold_Loading_UnloadingTime');

                                $idleTimeQuery = DB::table(DB::raw("(SELECT * 
                                    FROM $sql_table_name 
                                    WHERE cola_IoT_NumberCode='$iotNumber' 
                                    AND TriggerTime BETWEEN '$start_time' AND '$end_time') as subquery"))
                                ->sum('cold_IdleTime');

                            // Fetch min and max TriggerTime in a single query
                            $availableTimeResult = DB::table($sql_table_name)
                                            ->selectRaw('MIN(TriggerTime) as min_time, MAX(TriggerTime) as max_time')
                                            ->whereBetween('TriggerTime', [$start_time, $end_time])
                                            ->where('cola_IoT_NumberCode',$iotNumber)
                                            ->first();

                            $totalTeaLunchBreak = DB::table($sql_table_name)
                                            ->whereBetween('TriggerTime', [$start_time, $end_time])
                                            ->whereIn('cola_Loss_NumberCode', ['17', '18'])
                                            ->where('cola_IoT_NumberCode',$iotNumber)
                                            ->where('cola_Actual_Strokes', '>', 0)
                                            ->count();

                            $availableTimeFirstValue = $availableTimeResult->min_time ?? null;
                            $availableTimeLastValue = $availableTimeResult->max_time ?? null;

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

                            
                            $availableTime = is_numeric($availableTime) ? floatval($availableTime) : 0;
                            $totalTeaLunchBreak = is_numeric($totalTeaLunchBreak) ? floatval($totalTeaLunchBreak) : 0;

                            $timeDiff = ($availableTime / 60) - ($totalTeaLunchBreak / 60);

                            if ($timeDiff != 0) {
                                $avgActualSpm = ($record->cola_Actual_Strokes / $timeDiff);
                            } else {
                                $avgActualSpm = 0; // Prevent division by zero
                            }

                            $ttt = ($runTimeQuery / 60) + ($cold_Loading_UnloadingTimeQuery / 60);
                            
                            if ($ttt > 0) {
                                $runnSpm = ($record->cola_Actual_Strokes) / $ttt;
                            } else {
                                $runnSpm = 0; // Or handle it in another way
                            }
                            
                            // Running Variance 28-03-2025 (Friday)
                            $stdSPM = $partMasterNewData->spm ?? 0;
                            $runnSpm = round($runnSpm ?? 0, 2);
                            $runningVariance = ($stdSPM != 0) ? (($runnSpm - $stdSPM) / $stdSPM) : 0;
                            $runningVariance = $runningVariance*100;

                            // Avg Variance 28-03-2025 (Friday)
                            $avgSPM = round($avgActualSpm ?? 0, 2);
                            $stdSPM = $partMasterNewData->spm ?? 0;
                            $avgVariance = ($stdSPM != 0) ? (($avgSPM - $stdSPM) / $stdSPM): 0;
                            $avgVariance = $avgVariance*100;

                            $totalActual_Strokes += $record->cola_Actual_Strokes; 
                            if(count($multiplePartNumber)) {
                                foreach($multiplePartNumber as $key=>$multiplePartNumberValue) {
                                    $part_number = $multiplePartNumberValue->part_number ?? ''."/".$op_no ?? '';
                                }
                            }
                            
                            $actual_stoke = $record->cola_Actual_Strokes ?? '';
                                
                            $runTimeQuery1 = round(($runTimeQuery ?? 0) / 60, 2);  
                            $totalSeconds = round($runTimeQuery1 * 60);  
                            $minutes = floor($totalSeconds / 60);  
                            $seconds = $totalSeconds % 60;  
                            $run_time =  sprintf("%d:%02d", $minutes, $seconds);  
                            $runTime += $minutes;
                            $runTimeSec += $seconds;
                            
                            
                            $loadingUnloadingTimeQuery1 = round(($cold_Loading_UnloadingTimeQuery ?? 0) / 60, 2);  
                            $totalSeconds = round($loadingUnloadingTimeQuery1 * 60);  
                            $minutes = floor($totalSeconds / 60);  
                            $seconds = $totalSeconds % 60;  
                            $load_unload_time =  sprintf("%d:%02d", $minutes, $seconds);  
                            $ldTime += $minutes;
                            $ldTimeSec += $seconds;
                            

                            $idleTimeQuery1 = round(($idleTimeQuery ?? 0) / 60, 2);  
                            $totalSeconds = round($idleTimeQuery1 * 60);  
                            $minutes = floor($totalSeconds / 60);  
                            $seconds = $totalSeconds % 60;  
                            $idle_time = sprintf("%d:%02d", $minutes, $seconds); 
                            $IdlTime += $minutes;
                            $IdlTimeSec += $seconds;
                                
                            $std_spm = $partMasterNewData->spm ?? '-';
                            $run_spm = round($runnSpm,2) ?? '0';
                            $actual_spm = round($avgActualSpm,2) ?? '0';
                            $running_variance = round($runningVariance,2) ?? '0' ;
                            $avg_variance = round($avgVariance,2) ?? '0';


                            $totalLossMin11 = 0;
                            $totalLossSec11 = 0;

                        }


                        $exists = DashboardDailyModel::where('plant_id', $plant_id)
                            ->where('dept_id', $dept_id)
                            ->where('shift_id', $colt_ShiftSelected)
                            ->whereDate('date_from', date('Y-m-d', strtotime($start_time)))
                            ->whereDate('date_to', date('Y-m-d', strtotime($end_time)))
                            ->where('trigger_time_from', $start_time)
                            ->where('trigger_time_to', $end_time)
                            ->where('machine_name', $machine_name)
                            ->where('part_number', $part_number)
                            ->exists();


                        // Check if all required fields are empty
                        $allEmpty = 
                        $part_number === '' &&
                        $actual_stoke === '' &&
                        $run_time === '' &&
                        $load_unload_time === '' &&
                        $idle_time === '' &&
                        $std_spm === '' &&
                        $run_spm === '' &&
                        $actual_spm === '' &&
                        $running_variance === '' &&
                        $avg_variance === '';

                        
                        if (!$exists  && !$allEmpty) {
                            $dataFinal[] = [
                                'plant_id'     => $plant_id,
                                'dept_id'      => $dept_id,
                                'shift_id'     => $colt_ShiftSelected,
                                'date_from'    => date('Y-m-d', strtotime($start_time)) ,
                                'date_to'      => date('Y-m-d', strtotime($end_time)) ,
                                'trigger_time_from'   => $start_time,
                                'trigger_time_to'     => $end_time,
                                'machine_name'     => $machine_name,
                                'part_number'      => $part_number,
                                'actual_stoke'     => $actual_stoke,
                                'run_time'         => $run_time,
                                'load_unload_time' => $load_unload_time,
                                'idle_time'        => $idle_time,
                                'std_spm'          => $std_spm,
                                'run_spm'          => $run_spm,
                                'actual_spm'       => $actual_spm,
                                'running_variance' => $running_variance,
                                'avg_variance'     => $avg_variance,
                            
                            ];

                            DashboardDailyModel::insert($dataFinal);
                        }
                       
                       
                    }

                    
                }

                

            }
            // satish code end

            // if ($plant_id === '25' && $dept_id === '78') // MFG - Chassis Assembly Line-c11
            // { 
            //     return view('admin.user.dashboard.spm_analysis.index_dashboard_dept',$dataFinal);
            // }

            // if ($plant_id === '25' && $dept_id === '93') // MFG - Tandem Line-c11
            // { 
            //     return view("admin.user.dashboard.{$plantCode}.93.spm_analysis.index_dashboard_dept",$data);
            // }

            // if ($dept_id != '93' || $dept_id != '78' ) // MFG - Tandem Line-c11
            // { 
            //     return view("admin.user.dashboard.spm_analysis.blank_page.index_dashboard_dept",$data);
            // }

        }
        // dd($dataFinal);

         // Wait 30 seconds
         sleep(2);

         // Move to the next day
         $startDate->modify('+1 day');
     }

    }
  

    
    public function generateMonthOptions_new($startDate, $endDate)
    {
        $currentMonth = date('Y-m'); // Get the current year-month
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        $months = [];

        while ($start <= $end) {
            $value = $start->format('Y-m');
            $label = $start->format('F Y');

            // Only include months up to the current month
            if ($value <= $currentMonth) {
                $months[$value] = $label;
            }

            $start->modify('+1 month');
        }

        return $months;
    }

    
   
}
