@extends('admin.layouts.master')
@section('title')
@lang('admin.TITLE_DASHBOARD_TEXT')
@endsection
@section('content')

<style>
   /* Progress bar container */
 
   th {
   padding: 10px !important;
   }
   .table-responsive {
   max-height: 500px; /* Adjust height as needed */
   overflow-y: auto;
   position: relative;
   }
   .table thead tr {
   position: sticky;
   top: 0;
   background: white; /* Ensures the header is visible */
   z-index: 1000; /* Keeps it above other elements */
   }

   .btn-primary{
      background-color: #696cff;
   }
</style>
<div class="">
<!-- Content -->
<!-- 14-02-2025 -->
<?php 
   $today_date11 = $todayDate;
   $tdate11 = date('Y-m-d');
   $div_mainline11 = [];
?>
<div class="container">
   <?php 
      $triggerTime = date('d/m/Y',strtotime($todayDate));
   ?>
  
   <div class="row">
      <input type="hidden" id="today_date11" value="{{$today_date11}}">
      <div class="col-md-10">
         <h5 class="card-title p-2" style="color: #696cff;">{{ $deptData->dept_name ?? '' }} - Historic SPM Analysis - {{ $triggerTime }} : {{ $start_time ?? '' }} : {{ $end_time ?? '' }} : {{ $shift_id ?? '' }}</h5>
      </div>

   </div>
   <div class="card h-70 shadow-lg border-0 rounded-3">
      <div class="card-body text-center p-3">
         <?php  $pd_id = $plant_id.'_' .$dept_id; ?>
         <form method="post" action="{{ route('/dashboardalf') }}">
            @csrf
            <div class="row">
               <input type="hidden" name="plant_id" id="plant_id" value="{{ $plant_id ?? '' }}">
               <input type="hidden" name="dept_id" id="dept_id" value="{{ $dept_id ?? '' }}">
            
               <div class="col-md-2">
                 <input type="date" name="dateInput" id="dateInput" value="{{ $todayDate }}" class="form-control">
               </div>

               <div class="col-md-2">
                 <input type="date" name="dateInput_todate" id="dateInput_todate" value="{{ $toDate }}" class="form-control">
               </div>

               <div class="col-md-2">
                  <select name="shift_id" id="shift_id" class="form-control">
                     <option value="">Full Shift</option>
                     @foreach($shiftsMaster as $shiftsMasterValue)
                     <option value="{{ $shiftsMasterValue->shift_id }}" {{  $shiftsMasterValue->shift_id == $shift_id ? 'selected' : '' }} title="{{ $shiftsMasterValue->shift_id }} : {{ $shiftsMasterValue->from_time }} to {{ $shiftsMasterValue->to_time }}">{{ $shiftsMasterValue->shift_id }}</option>
                     @endforeach
                  </select>
               </div>


               <div class="col-md-2">
                  <select name="sql_table_name_from_user" id="sql_table_name_from_user" class="form-control">
                     <option value="">Select Machine</option>
                     @foreach($machine as $machineData)
                     <option value="{{ $machineData->sql_table_name }}" {{  $machineData->sql_table_name  == $sql_table_name_from_user ? 'selected' : '' }} >{{ $machineData->machine_name }}</option>
                     @endforeach
                  </select>
               </div>


               <div class="col-md-3">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button> 
                   <?php $pd_id = $plant_id.'_'.$dept_id;  ?>
                  <a href="{{ route('/dashboardalf',['plant_id'=>$plant_id,'dept_id'=>$dept_id,'pd_id'=>$pd_id]) }}" class="btn btn-primary" title="SPM Analysis List" ><i class="fa fa-th" aria-hidden="true"></i></a>
             
                  {{-- <a href="{{ route('user.line_supervisor',['plant_id'=>$plant_id,'dept_id'=>$dept_id,'pd_id'=>$pd_id]) }}" class="btn btn-primary" title="SPM Analysis Graph" ><i class="fa fa-signal" aria-hidden="true"></i></a> --}}
               </div>
               
            </div>
         </form>
      </div>
      <div id="refresh-progress-bar" style="
   position: fixed;
   top: 0;
   left: 0;
   height: 4px;
   width: 0;
   background-color: #696cff;
   z-index: 9999;
   transition: width 0.5s ease;
"></div>
   </div>
     
</div>
<div class="container">
   <section style="margin-top:20px;">
      <div class="card h-70 shadow-lg border-0 rounded-3">
         <div class="card-body text-center p-3">
            <div class="machine_data_table">
               <div class="table-responsive">
                  
                  <table class="table table-bordered">
                     <thead class="thead-dark" style="vertical-align:text-top;">
                        <tr>
                           <th>Machine Name</th>
                           <th>Part Number</th>
                           <th>Actual Strokes</th>
                           <th>Run Time</th>
                           <th>Load/Unload Time</th>
                           <th>IdleTime</th>
                           <th>Std. SPM</th>
                           <th title="Running SPM = (ActualStroke)/(RunTime + LoadingUnloadingTime)">Runn. SPM</th>
                           <th title="Actual SPM = (ActualStroke)/(AvailableTime - TeaBreak - LunchBreak)">Actual SPM</th>
                           <th title="Running Variance = (Running SPM - Std. SPM)/(Std. SPM)*100">Runn. Variance(%)</th>
                           <th title="Avg. Variance = (Actual SPM - Std. SPM)/(Std. SPM)*100">Avg. Variance(%)</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($dataFinal) > 0)
                         @foreach ($dataFinal as $machineName => $entries)
                             @php $rowCount = $entries->count(); @endphp
                             @foreach ($entries as $index => $entry)
                                 <tr>
                                     @if ($index === 0)
                                         <td rowspan="{{ $rowCount }}">{{ $entry->machine_name }}</td>
                                     @endif
                                     <td>{{ $entry->part_number }}</td>
                                     <td>{{ $entry->actual_stoke }}</td>
                                     <td>{{ $entry->run_time }}</td>
                                     <td>{{ $entry->load_unload_time }}</td>
                                     <td>{{ $entry->idle_time }}</td>
                                     <td>{{ $entry->std_spm }}</td>
                                     <td>{{ $entry->run_spm }}</td>
                                     <td>{{ $entry->actual_spm }}</td>
                                     <td>{{ $entry->running_variance }}</td>
                                     <td>{{ $entry->avg_variance }}</td>
                                 </tr>
                             @endforeach
                         @endforeach

                         @else
                         <tr>
                           <td class="align-middle" colspan="11">No data found</td>
                         </tr>
                         @endif
                     </tbody>
                 </table>
                
                 
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
 $(document).ready(function () {
  // Set max date to today
  const today = new Date().toISOString().split('T')[0];
  document.getElementById("dateInput_todate").setAttribute("max", today);
});
</script>

@if($today_date11===$tdate11 && empty($shift_id))
<script>
   $(document).ready(function () {
   let modalOpen = false;
   let refreshTimer = null; // timeout ID retain करतो

   $('.modal').on('show.bs.modal', function () {
      modalOpen = true;
   });

   $('.modal').on('hide.bs.modal', function () {
      modalOpen = false;
   });

   function refreshContent() {
      if (!modalOpen) {
         // Start progress bar
         $("#refresh-progress-bar").css("width", "0").show().animate({ width: "100%" }, 800);

         // Load content
         $(".machine_data_table").load(location.href + " .machine_data_table", function () {
            // End progress bar
            $("#refresh-progress-bar").css("width", "100%");
            setTimeout(function () {
               $("#refresh-progress-bar").fadeOut(5000, function () {
                  $(this).css("width", "0");
               });
            }, 5000);
         });
      }

      // Store timeout ID
      // refreshTimer = setTimeout(refreshContent, 5000);
   }

   // Start auto-refresh cycle
   // refreshContent();

   // Stop function
   window.stopFunction = function (event) {
      clearTimeout(refreshTimer); // timeout बंद करा
      console.log("Refresh stopped.");
   };

   // Optional startFunction – if needed in future
   window.startFunction = function (event) {
      event.preventDefault();
      refreshContent(); // पुन्हा सुरु करा
      console.log("Refresh started.");
   };
});
</script>
@endif

<script>
document.getElementById("dateInput_todate").addEventListener("change", validateDates);
document.getElementById("dateInput").addEventListener("change", validateDates);

function validateDates() {
    const fromDate = new Date(document.getElementById("dateInput").value);
    const toDate = new Date(document.getElementById("dateInput_todate").value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Ignore time

    if (fromDate > toDate) {
        alert("From Date should not be greater than To Date.");
        return false;
    }

    if (fromDate > today || toDate > today) {
        alert("Dates should not be in the future.");
        return false;
    }

    // Optional: allow submission or update UI
}
</script>

<script>
//   document.addEventListener('DOMContentLoaded', function () {
//     const today = new Date().toISOString().split('T')[0];
//     const dateInput = document.getElementById('dateInput');
//     dateInput.max = today;
//     dateInput.value = today; // optional: set current date as default
//   });
</script>




