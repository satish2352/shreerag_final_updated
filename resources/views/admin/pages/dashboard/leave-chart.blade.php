  <style>
    .status-card {
      border-radius: 10px;
      padding: 20px;
      color: #333;
      background-color: #fff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .status-icon {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      font-weight: bold;
      color: white;
    }
    .available { background-color: #d8dffb; color: #5a5cd3; }
    .previous { background-color: #e6e8fa; color: #5555d4; }
    .pending { background-color: #fdeedc; color: #ff9800; }
    .rejected { background-color: #fbe4e6; color: #e53935; }
    .status-text h6 {
      margin: 0;
      font-weight: bold;
      font-size: 14px;
    }
    .status-text small {
      font-size: 12px;
      color: #6c757d;
    }
  </style>
  <div class="container py-4">
     <hr>
    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Leave Status</span>
  <div class="row g-3">
     <div class="col-md-3 col-sm-6">
      <div class="status-card">
        <div class="status-icon available">{{ $employee_counts['total_leaves'] }}</div>
        <div class="status-text">
          <h6>Total Leaves</h6>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="status-card">
        <div class="status-icon available">{{ $employee_counts['available_leaves'] }}</div>
        <div class="status-text">
          <h6>Current Year Leaves</h6>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="status-card">
        <div class="status-icon previous">{{ $employee_counts['previous_unused_leaves'] }}</div>
        <div class="status-text">
          <h6>Previous unused leaves</h6>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="status-card">
        <div class="status-icon pending">{{ $employee_counts['pending_leaves'] }}</div>
        <div class="status-text">
          <h6>Pending Leave Requests</h6>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- <div class="col-lg-12 col-md-12 mb-4">
   
    <div id="leaveChartsContainer" class="row d-flex justify-content-start"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const leaveData = @json($employee_counts['user_leaves_status'] ?? []);

    console.log("Leave Data:", leaveData);

    const container = document.getElementById("leaveChartsContainer");

    if (!leaveData || leaveData.length === 0) {
        container.innerHTML = "<p>No leave data available.</p>";
        return;
    }

    leaveData.forEach((leave, index) => {
        const leaveType = (leave.leave_type_name || "LeaveType").replace(/\s+/g, '');
        const chartId = `${leaveType}Chart${index}`; // unique ID

        // Create card container
        const chartCard = document.createElement("div");
        chartCard.classList.add("col-lg-4", "col-md-4", "col-sm-6", "mb-4");
        chartCard.innerHTML = `
            <div class="card shadow border-0 p-2">
                <canvas id="${chartId}" width="300" height="300"></canvas>
            </div>
        `;
        container.appendChild(chartCard);

        const ctx = document.getElementById(chartId).getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Total Leaves', 'Taken Leaves', 'Remaining Leaves'],
                datasets: [{
                    data: [
                        Number(leave.leave_count) || 0,
                        Number(leave.total_leaves_taken) || 0,
                        Number(leave.remaining_leaves) || 0
                    ],
                    backgroundColor: ['#2D4E59', '#33B78C', '#34BAB8'],
                    borderWidth: 2
                }]
            },
            options: {
                cutout: '80%',
                plugins: {
                    legend: { position: 'right' },
                    title: {
                        display: true,
                        text: leave.leave_type_name || "Leave Status",
                        font: { size: 16 }
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
});
</script>


 --}}
<div class="col-lg-12 col-md-12 mb-4">
    <div id="leaveChartsContainer" class="row d-flex justify-content-start"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> <!-- Added plugin -->

<script>
document.addEventListener("DOMContentLoaded", function () {
    const leaveData = @json($employee_counts['user_leaves_status'] ?? []);

    console.log("Leave Data:", leaveData);

    const container = document.getElementById("leaveChartsContainer");

    if (!leaveData || leaveData.length === 0) {
        container.innerHTML = "<p>No leave data available.</p>";
        return;
    }

    leaveData.forEach((leave, index) => {
        const leaveType = (leave.leave_type_name || "LeaveType").replace(/\s+/g, '');
        const chartId = `${leaveType}Chart${index}`;

        // Create card container
        const chartCard = document.createElement("div");
        chartCard.classList.add("col-lg-4", "col-md-4", "col-sm-6", "mb-4");
        chartCard.innerHTML = `
            <div class="card shadow border-0 p-2">
                <canvas id="${chartId}" width="300" height="300"></canvas>
            </div>
        `;
        container.appendChild(chartCard);

        const ctx = document.getElementById(chartId).getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Total Leaves', 'Taken Leaves', 'Remaining Leaves'],
        datasets: [{
            data: [
                Number(leave.total_available_leaves) || 0,   // Current + Carry Forward
                Number(leave.total_leaves_taken) || 0,
                Number(leave.remaining_leaves) || 0
            ],
            backgroundColor: ['#2D4E59', '#33B78C', '#34BAB8'],
            borderWidth: 2
        }]
    },
    options: {
        cutout: '80%',
        plugins: {
            legend: { position: 'right' },
            title: {
                display: true,
                text: leave.leave_type_name || "Leave Status",
                font: { size: 16 }
            },
            datalabels: {
                color: '#fff',
                font: { weight: 'bold', size: 14 },
                formatter: (value) => value
            }
        },
        responsive: true,
        maintainAspectRatio: false
    },
    plugins: [ChartDataLabels]
});

        // new Chart(ctx, {
        //     type: 'doughnut',
        //     data: {
        //         labels: ['Total Leaves', 'Taken Leaves', 'Remaining Leaves'],
        //         datasets: [{
        //             data: [
        //                 Number(leave.leave_count) || 0,
        //                 Number(leave.total_leaves_taken) || 0,
        //                 Number(leave.remaining_leaves) || 0
        //             ],
        //             backgroundColor: ['#2D4E59', '#33B78C', '#34BAB8'],
        //             borderWidth: 2
        //         }]
        //     },
        //     options: {
        //         cutout: '80%',
        //         plugins: {
        //             legend: { position: 'right' },
        //             title: {
        //                 display: true,
        //                 text: leave.leave_type_name || "Leave Status",
        //                 font: { size: 16 }
        //             },
        //             datalabels: {
        //                 color: '#fff',
        //                 font: { weight: 'bold', size: 14 },
        //                 formatter: (value) => value // show the number
        //             }
        //         },
        //         responsive: true,
        //         maintainAspectRatio: false
        //     },
        //     plugins: [ChartDataLabels] // Enable plugin
        // });
    });
});
</script>

<style>
#leaveChartsContainer canvas {
    max-width: 300px;
    height: 235px;
    padding: 30px;
}
</style>
