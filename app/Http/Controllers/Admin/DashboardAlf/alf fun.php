 public function spmAnalysis(Request $request,$plant_id = null, $dept_id = null, $pd_id = null)
    {   

        $getPlantId = '25';
        $getDeptId = '78';

        $plant_id = '25';
        $dept_id = '78';
        $user_id = '1';


        // Filters
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

        $todayDate =  $todayDate;
        // Default time range (07:00:01 to next day 06:59:59)
        $start_time = "$todayDate 07:00:01";
        $end_time = date("Y-m-d", strtotime($todayDate . " +1 day")) . " 06:59:59";

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
        $userDetail = LoginuserModel::where(['id'=>$user_id])->first();
        $fromYear  =  date('Y',strtotime($userDetail->date_filter_startdate));
        $toYear  =  date('Y',strtotime($userDetail->date_filter_enddate));
        $fromDate  =  date('Y-m-d',strtotime($userDetail->date_filter_startdate));
        $toDate1  =  date('Y-m-d',strtotime($userDetail->date_filter_enddate));
        $first_day_this_month =  Auth::user()->date_filter_startdate;
        $last_day_this_month  = Auth::user()->date_filter_enddate;
        $yearStartMonth = date('m-Y',strtotime($first_day_this_month));
        $yearEndMonth = date('m-Y',strtotime($last_day_this_month));

        $startdate = '';
        $enddate = '';

        $shiftsMaster = ShiftModel::where('plant_id',$getPlantId)->get();

        $selected_year='';
        if(!empty($request->selected_year))
        {
            $selected_year = $request->selected_year-1;
            $selected_year_addoneyear = $selected_year+1;
            $startdate = $selected_year.'-04-01';
            $enddate = $selected_year_addoneyear.'-03-31';
        }
        else
        {
            $startdate = $first_day_this_month;
            $enddate = $last_day_this_month; 
        }
        $months = $this->generateMonthOptions_new($startdate, $enddate);
        $machine = MachineModel::where(['plant_id'=>$getPlantId,'dept_id'=>$getDeptId,'iot_on_off'=>'ON'])->orderBy('machine_name','ASC')->get();

      
        $div_mainline = [];

        $deptData = DepartmentModel::where(['id'=>$getDeptId])->first();
        $plant = PlantModel::all();
        $plantCodeData = PlantModel::find($getPlantId);
        $plantCode = $plantCodeData ? $plantCodeData->plant_code : ''; // Safe access

        // echo $start_time;
        // echo "<br>";

        // echo $end_time;
        // exit;

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

        if ($getPlantId === '25' && $getDeptId === '78') // MFG - Chassis Assembly Line-c11
        { 
            return view('admin.pages.alf.index_dashboard_dept',$data);
        }

        if ($getPlantId === '25' && $getDeptId === '93') // MFG - Tandem Line-c11
        { 
            return view("admin.user.dashboard.{$plantCode}.93.spm_analysis.index_dashboard_dept",$data);
        }

        if ($getDeptId != '93' || $getDeptId != '78' ) // MFG - Tandem Line-c11
        { 
            return view("admin.user.dashboard.spm_analysis.blank_page.index_dashboard_dept",$data);
        }
    }
    //14-03-2025(Friday) SPM Analysis

