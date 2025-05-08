$arrayDataToPrint= [];
        if(!empty($machine)) {
            $printedMachines = []; 
            foreach($machine as $machineValue) {

                $eachMachine= [];
            
                $sql_table_name = $machineValue->sql_table_name ?? '';
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
                
                // dd($records);
            
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
            
                // $iotArrayNumberCode=array();
                // for ($i = 0; $i < count($records); $i++) {
                //    array_push($iotArray, $records[$i]->cola_IoT_NumberCode);
                // }
                

                foreach($records as $index => $record) {
                    $eachMachinePartData= [];

            
                    $totalRecords = count($records);
                
                    $iotNumber = $record->cola_IoT_NumberCode ?? '';
                
                    $op_no = $record->cola_OperationNumber ?? '';
                    
                
                    $partMasterNewData = DB::table('tbl_part_master')
                    ->where(['plant_id'=>$getPlantId,'dept_id'=>$getDeptId,'iot_number'=>$iotNumber])->select('spm')->first();
                
                    $multiplePartNumber = DB::table('tbl_part_master')
                        ->where([
                            'plant_id'=> $getPlantId,
                            'dept_id' => $getDeptId,
                            'iot_number' =>$iotNumber
                        ])
                        ->select('short_part_number as part_number')
                        ->distinct()
                        ->get();
                
                        $partExists = $partMasterNewData ? true : false;

                        if ($partExists) {
                            $rowCount++;
                        }
                
                        
                
                        
                
                
                        if($partMasterNewData!="") {
                    

                            $results = DB::table($sql_table_name)
                                        ->selectRaw('
                                            SUM(cold_RunTime) as total_run_time,
                                            SUM(cold_Loading_UnloadingTime) as total_loading_unloading_time,
                                            SUM(cold_IdleTime) as total_idle_time
                                        ')
                                        ->where('cola_IoT_NumberCode', $iotNumber)
                                        ->whereBetween('TriggerTime', [$start_time, $end_time])
                                        ->first();

                            $runTimeQuery = $results->total_run_time ?? 0;
                            $cold_Loading_UnloadingTimeQuery = $results->total_loading_unloading_time ?? 0;
                            $idleTimeQuery = $results->total_idle_time ?? 0;

                            // Build base query once
                            $baseQuery = DB::table($sql_table_name)
                                ->whereBetween('TriggerTime', [$start_time, $end_time])
                                ->where('cola_IoT_NumberCode', $iotNumber);

                            // Get min, max trigger times
                            $availableTimeResult = $baseQuery->clone() // clone so base query stays intact
                                ->selectRaw('MIN(TriggerTime) as min_time, MAX(TriggerTime) as max_time')
                                ->first();

                            // Get total tea/lunch break count
                            $totalTeaLunchBreak = $baseQuery->clone()
                                ->whereIn('cola_Loss_NumberCode', ['17', '18'])
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
                            
                            // Convert values to minutes
                            $ttt = ($runTimeQuery / 60) + ($cold_Loading_UnloadingTimeQuery / 60);
                            
                            // Check if $ttt is zero before division
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
                        
                            
                            if(!in_array($machineValue->machine_name, $printedMachines)) {
                                $eachMachinePartData['row_span_machine'] = count($records);  //Satish Print
                                $eachMachinePartData['machine_name'] = $machineValue->machine_name; //Satish Print
                                $printedMachines[] = $machineValue->machine_name; //Satish Print
                            }
                            
                            $totalActual_Strokes += $record->cola_Actual_Strokes;
                            if(!empty($multiplePartNumber)) {
                                $partNumber = [];
                                foreach($multiplePartNumber as $multiplePartNumberValue) {
                                    array_push($partNumber, $multiplePartNumberValue->part_number ?? '' ."/".$op_no ?? ''); //Satish Print
                                }
                            }
                            $eachMachinePartData['part_number'] = $partNumber; //Satish Print
                            $eachMachinePartData['actual_stoke'] = $record->cola_Actual_Strokes ?? ''; //Satish Print
                                
                                    $runTimeQuery1 = round(($runTimeQuery ?? 0) / 60, 2);  
                                    $totalSeconds = round($runTimeQuery1 * 60);  
                                    $minutes = floor($totalSeconds / 60);  
                                    $seconds = $totalSeconds % 60;  
                                    $eachMachinePartData['run_time'] =  sprintf("%d:%02d", $minutes, $seconds);   //Satish Print
                                    $runTime += $minutes;
                                    $runTimeSec += $seconds;
                                    
                                    //Another TD
                                    $loadingUnloadingTimeQuery1 = round(($cold_Loading_UnloadingTimeQuery ?? 0) / 60, 2);  
                                    $totalSeconds = round($loadingUnloadingTimeQuery1 * 60);  
                                    $minutes = floor($totalSeconds / 60);  
                                    $seconds = $totalSeconds % 60;  
                                    $eachMachinePartData['load_unload_time'] =   sprintf("%d:%02d", $minutes, $seconds);     //Satish Print
                                    $ldTime += $minutes;
                                    $ldTimeSec += $seconds;
                                
                            //Another TD
                                    $idleTimeQuery1 = round(($idleTimeQuery ?? 0) / 60, 2);  
                                    $totalSeconds = round($idleTimeQuery1 * 60);  
                                    $minutes = floor($totalSeconds / 60);  
                                    $seconds = $totalSeconds % 60;  
                                    $eachMachinePartData['idle_time'] =   sprintf("%d:%02d", $minutes, $seconds);   //Satish Print
                                    $IdlTime += $minutes;
                                    $IdlTimeSec += $seconds;
                                    
                            //Another TD
                            $eachMachinePartData['std_spm'] =  $partMasterNewData->spm ?? '-'; //Satish Print
                            $eachMachinePartData['run_spm'] =  round($runnSpm,2) ?? '0';  //Satish Print
                            $eachMachinePartData['actual_spm'] =  round($avgActualSpm,2) ?? '0'; //Satish Print
                            $eachMachinePartData['run_var'] =  round($runningVariance,2) ?? '0'; //Satish Print
                            $eachMachinePartData['avg_var'] =  round($avgVariance,2) ?? '0'; //Satish Print
                            

                                if(!empty($colt_ShiftSelected)) 
                                {
                                    $div_mainline11 = DB::table($sql_table_name)
                                    ->select('cola_Loss_NumberCode', DB::raw('COUNT(TriggerTime) as total_rows')) // adjust as needed
                                    ->whereBetween('TriggerTime', [$start_time, $end_time])
                                    ->where('cola_Actual_Strokes', '>', 0)
                                    
                                    ->where('colt_ShiftSelected',$colt_ShiftSelected)
                                    ->where('cola_IoT_NumberCode',$record->cola_IoT_NumberCode)
                                    ->where('cola_IoT_NumberCode', '>', 0)
                                    ->whereNotIn('cola_Loss_NumberCode', ['1', '0'])
                                    ->groupBy('cola_Loss_NumberCode', 'colt_ShiftSelected')
                                    ->get();


                                    
                                }
                                else
                                {
                                    $div_mainline11 = DB::table($sql_table_name)
                                    ->select('cola_Loss_NumberCode', DB::raw('COUNT(TriggerTime) as total_rows')) // adjust as needed
                                    ->whereBetween('TriggerTime', [$start_time, $end_time])
                                    ->where('cola_Actual_Strokes', '>', 0)
                                    ->where('cola_IoT_NumberCode',$record->cola_IoT_NumberCode)
                                    ->where('cola_IoT_NumberCode', '>', 0)
                                    ->whereNotIn('cola_Loss_NumberCode', ['1', '0'])
                                    ->groupBy('cola_Loss_NumberCode', 'colt_ShiftSelected')
                                    ->get();
                                }
                                

                                $totalLossMin11 = 0;
                                $totalLossSec11 = 0;
                            

                                
                                    foreach($div_mainline11 as $lossvalue) {
                            
                                $lossName = DB::table('tbl_loss_master')
                                        ->where('plant_id',$getPlantId)
                                        ->where('dept_id',$getDeptId)
                                        ->where('loss_code', $lossvalue->cola_Loss_NumberCode)
                                        ->value('loss_name');
                                
                                $lossTime1 = DB::table(DB::raw("(SELECT TriggerTime, cola_Loss_NumberCode  
                                        FROM $sql_table_name 
                                        WHERE TriggerTime BETWEEN '$start_time' AND '$end_time') as subquery"))
                                ->whereIn('cola_Loss_NumberCode', [$lossvalue->cola_Loss_NumberCode])
                                ->whereNotIn('cola_Loss_NumberCode', [1])
                                ->count();
                                
                                
                                
                                if ($lossTime1 > 0) {
                                    $lossTime1 = $lossTime1 / 60;
                                } else {
                                    $lossTime1 = 0; // or handle it appropriately
                                }
                                
                                
                                
                                $totalSeconds = round($lossTime1 * 60);  
                                $minutes = floor($totalSeconds / 60);  
                                $seconds = $totalSeconds % 60;  
                                $lossTimeDetails =  sprintf("%d:%02d", $minutes, $seconds);  

                                $totalLossMin11 += $minutes;
                                $totalLossSec11 += $seconds;
                                    
                                    }
        
                            //Another TD
                            $eachMachinePartData['total_loss'] =  $totalLossMin11.":".$totalLossSec11;//Satish Print
                            //Another TD
                            # Button
                            //
                        }

                        array_push($eachMachine ,$eachMachinePartData);
                }

              array_push($arrayDataToPrint ,$eachMachine);
            }
            
        }
