@extends('admin.layouts.master')

@section('title')
    @lang('admin.TITLE_DASHBOARD_TEXT')
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Date Filter Form -->
    <form method="GET" class="mb-4" action="{{ route('/dashboardsumagograph') }}">
        <div style="display: flex; gap: 20px; align-items: center;">
            <div>
                <label>From Date:</label>
                <input type="date" name="fromDate" value="{{ request('fromDate') ?? date('Y-m-d') }}" class="form-control">
            </div>
            <div>
                <label>To Date:</label>
                <input type="date" name="toDate" value="{{ request('toDate') ?? date('Y-m-d') }}" class="form-control">
            </div>
            <div>
                <button type="submit" class="btn btn-primary mt-4">Submit</button>
            </div>
        </div>
    </form>

    @php
        $chartTypes = [
            'avg_strokes' => ['label' => 'Actual Strokes', 'color' => 'rgba(75, 192, 192, 1)'],
            'avg_run_time' => ['label' => 'Run Time', 'color' => 'rgba(153, 102, 255, 1)'],
            'avg_idle_time' => ['label' => 'Idle Time', 'color' => 'rgba(255, 159, 64, 1)'],
        ];
    @endphp

    <div class="my-5">
        <h3 class="text-center">Machine Data</h3>
        <div class="charts-wrapper">
            @foreach ($chartTypes as $key => $chart)
                <div class="chart-box">
                    <canvas id="chart_{{ $key }}" class="chart-canvas"></canvas>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        const labels = @json($dailyDataSet['labels']);
        const datasets = {
            @foreach ($chartTypes as $key => $meta)
                "{{ $key }}": {
                    label: "{{ $meta['label'] }}",
                    color: "{{ $meta['color'] }}",
                    data: @json($dailyDataSet[$key]),
                },
            @endforeach
        };

        window.addEventListener('DOMContentLoaded', () => {
            for (const [key, meta] of Object.entries(datasets)) {
                const ctx = document.getElementById(`chart_${key}`)?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: meta.label,
                                data: meta.data,
                                borderColor: meta.color,
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Time'
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>

    <style>
        .charts-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 30px auto 100px auto;
            max-width: 1200px;
        }

        .chart-box {
            flex: 1 1 557px;
            max-width: 100%;
            min-width: 250px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
            padding: 60px 15px;
            margin: 15px;
        }

        .chart-canvas {
            width: 100% !important;
            height: 300px !important;
        }
    </style>
@endsection
