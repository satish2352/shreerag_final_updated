{{-- <div class="col-lg-6 col-md-6 mb-4">
    <h4>My Leave Status</h4>
    <div class="col-lg-9 col-md-9">
        <canvas id="leaveStatusChart" width="500" height="500"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const leaveData = @json($employee_counts['user_leaves_status'] ?? []);

    if (leaveData.length > 0) {
        const ctx = document.getElementById('leaveStatusChart').getContext('2d');

        const leaveTypes = leaveData.map(item => item.leave_type_name);
        const leaveCount = leaveData.map(item => parseInt(item.leave_count));
        const takenLeaves = leaveData.map(item => parseInt(item.total_leaves_taken) || 0);
        const remainingLeaves = leaveData.map(item => parseInt(item.remaining_leaves) || 0);

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: leaveTypes,
                datasets: [
                    {
                        label: 'Total Leaves',
                        data: leaveCount,
                        backgroundColor: '#2d4e59',
                        hoverOffset: 4
                    },
                    {
                        label: 'Taken Leaves',
                        data: takenLeaves,
                        backgroundColor: '#33b78c',
                        hoverOffset: 4
                    },
                    {
                        label: 'Balanced Leaves',
                        data: remainingLeaves,
                        backgroundColor: '#199cc2',
                        hoverOffset: 4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'My Leave Status Breakdown'
                    }
                }
            }
        });
    } else {
        document.getElementById('leaveStatusChart').parentElement.innerHTML = "<p>No leave data available.</p>";
    }
});
</script> --}}

<div class="col-lg-12 col-md-12 mb-4 ">
    <hr>
    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Leave Status</span>
    <div id="leaveChartsContainer" class="row d-flex justify-content-start "> <!-- Ensure Flexbox for row -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const leaveData = @json($employee_counts['user_leaves_status'] ?? []);

    console.log("Leave Data:", leaveData); // Debugging

    const container = document.getElementById("leaveChartsContainer");

    if (leaveData.length === 0) {
        container.innerHTML = "<p>No leave data available.</p>";
        return;
    }

    leaveData.forEach((leave) => {
        const leaveType = leave.leave_type_name.replace(/\s+/g, ''); // Remove spaces (e.g., 'Casual Leave' -> 'CasualLeave')

        // Create a new canvas for each leave type
        const chartContainer = document.createElement("div");
        chartContainer.classList.add("col-lg-3", "col-md-4", "col-sm-6", "mb-4", "shadow-sm",  "rounded", "card", "shadow" , "m-4"); // Adjusted to fit 4 charts in a row
        chartContainer.innerHTML = `
       
            <canvas id="${leaveType}Chart" width="300" height="300"></canvas>
        `;
        container.appendChild(chartContainer);

        // Render chart
        const ctx = document.getElementById(`${leaveType}Chart`).getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Total Leaves', 'Taken Leaves', 'Remaining Leaves'],
                datasets: [{
                    data: [
                        parseInt(leave.leave_count),
                        parseInt(leave.total_leaves_taken) || 0,
                        parseInt(leave.remaining_leaves) || 0
                    ],
                    backgroundColor: ['#2d4e59', '#33b78c', '#199cc2'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: leave.leave_type_name }
                }
            }
        });
    });
});
</script>

