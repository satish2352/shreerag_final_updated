<?php
namespace App\Http\Controllers\Admin\DashboardAlf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alf\MachineModel;
use App\Models\Alf\ShiftModel; 
use App\Models\Alf\DashboardDailyModel; 
use DB;
use Illuminate\Support\Collection;

class DashboardAlfController extends Controller
{

    public function spmAnalysis(Request $request,$plant_id = null, $dept_id = null, $pd_id = null)
    {  

        try {

            $plant_id = '25';

            $dept_id = '78';

            $dateInput = $request->dateInput ?? '';
            $dateInputTo = $request->dateInput_todate ?? '';
            
            $shift_id = $request->shift_id ? $request->shift_id : 'A-SH';

            $toDate = '';

            if(!empty($dateInputTo))
            {
                $toDate = $dateInputTo;
            }
            else
            {
                $toDate = date('Y-m-d');
            }

            $todayDate = '';
            if(!empty($dateInput))
            {
                $todayDate = $dateInput;
            }
            else
            {
                $todayDate = date('Y-m-d');
            }

            $start_time = "$todayDate 07:00:01";
            $end_time = date("Y-m-d", strtotime($toDate . " +1 day")) . " 06:59:59";
            // Shift filter dynamically
            if (!empty($shift_id)) 
            {
                $shiftMaster = ShiftModel::where('shift_id', $shift_id)->first();

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


            $shiftsMaster = ShiftModel::where('plant_id',$plant_id)->get();
            if($request->sql_table_name_from_user==null)
            {
                $machinData = MachineModel::where(['plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_on_off'=>'ON'])->first();
                $sql_table_name_from_user = $machinData->sql_table_name_from_user;
            }
            else
            {
                $machinData = MachineModel::where(['sql_table_name'=>$request->sql_table_name_from_user, 'plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_on_off'=>'ON'])->orderBy('machine_name','ASC')->first();
                $sql_table_name_from_user = $request->sql_table_name_from_user;
            }
            
            $machine_name = $machinData->machine_name;
            // \DB::enableQueryLog(); // Enable query log

            $results = DashboardDailyModel::where([
                                'plant_id'     => $plant_id,
                                'dept_id'      => $dept_id,
                                'shift_id'     => $shift_id,
                                // 'date_from'    => date('Y-m-d', strtotime($start_time)) ,
                                // 'date_to'      => date('Y-m-d', strtotime($end_time)) ,
                                // 'trigger_time_from'   => $start_time,
                                // 'trigger_time_to'     => $end_time,
                                'machine_name'     => $machine_name,
                            ])
                            ->whereBetween('trigger_time_from', [$start_time, $end_time])
                            ->whereBetween('trigger_time_to', [$start_time, $end_time])
                            ->get();
                            // dd(\DB::getQueryLog()); // Show results of log

                // Assume $results is your collection
            $grouped = $results->groupBy(function ($item) {
                    return implode('|', [
                        $item->plant_id,
                        $item->dept_id,
                        $item->shift_id,
                        // $item->date_from,
                        // $item->date_to,
                        // $item->trigger_time_from,
                        // $item->trigger_time_to,
                        $item->machine_name,
                        $item->part_number
                    ]);
                });

                $averaged = $grouped->map(function ($items, $groupKey) {
                        return [
                            'plant_id' => $items->first()->plant_id,
                            'dept_id' => $items->first()->dept_id,
                            'shift_id' => $items->first()->shift_id,
                            'date_from' => $items->first()->date_from,
                            'date_to' => $items->first()->date_to,
                            'trigger_time_from' => $items->first()->trigger_time_from,
                            'trigger_time_to' => $items->first()->trigger_time_to,
                            'machine_name' => $items->first()->machine_name,
                            'part_number' => $items->first()->part_number,
                    
                            // Convert to numeric before averaging if needed
                            'actual_stoke' => round($items->avg(fn($i) => (float)$i->actual_stoke), 2),
                            'run_time' => round($items->avg(fn($i) => (float)$i->run_time), 2),
                            'load_unload_time' => round($items->avg(fn($i) => (float)$i->load_unload_time), 2),
                            'idle_time' =>round($items->avg(fn($i) => (float)$i->idle_time), 2),
                            'std_spm' => round($items->avg(fn($i) => (float)$i->std_spm), 2),
                            'run_spm' => round($items->avg(fn($i) => (float)$i->run_spm), 2),
                            'actual_spm' => round($items->avg(fn($i) => (float)$i->actual_spm), 2),
                            'running_variance' => round($items->avg(fn($i) => (float)$i->running_variance), 2),
                            'avg_variance' => round($items->avg(fn($i) => (float)$i->avg_variance), 2),

                        ];
                })->values();

                $alldataFinal = collect($averaged)->map(function ($item) {
                    return (object) $item;
                });
                // dd($alldataFinal);
                $dataFinal = $alldataFinal->groupBy('machine_name');
                $machine = MachineModel::where(['plant_id'=>$plant_id,'dept_id'=>$dept_id,'iot_on_off'=>'ON'])->get();
                          
                return view('admin.pages.alf.index_dashboard_dept',compact('sql_table_name_from_user','machine','dataFinal','todayDate','plant_id','dept_id','toDate','shiftsMaster','shift_id'));

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
    
}
