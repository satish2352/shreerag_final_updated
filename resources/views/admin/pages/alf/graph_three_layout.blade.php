@extends('admin.layouts.master')

@section('title')
    @lang('admin.TITLE_DASHBOARD_TEXT')
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @php
        $timeframes = [
            'daily' => $dailyDataSet,
            'hourly' => $hourlyDataSet,
            'weekly' => $weeklyDataSet,
            'monthly' => $monthlyDataSet,
        ];

        $chartTypes = [
            'avg_strokes' => ['label' => 'Actual Strokes', 'color' => 'rgba(75, 192, 192, 1)'],
            'avg_run_time' => ['label' => 'Run Time', 'color' => 'rgba(153, 102, 255, 1)'],
            'avg_idle_time' => ['label' => 'Idle Time', 'color' => 'rgba(255, 159, 64, 1)'],
        ];
    @endphp

    @foreach ($timeframes as $timeframe => $dataSet)
        <div class="my-5">
            <h3 class="text-center">{{ ucfirst($timeframe) }} Data</h3>
            <div class="charts-wrapper">
                @foreach ($chartTypes as $key => $chart)
                    <div class="chart-box">
                        <canvas id="{{ $timeframe }}_{{ $key }}" class="chart-canvas"></canvas>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <script>
        const chartConfigs = {
            @foreach ($timeframes as $timeframe => $dataSet)
                "{{ $timeframe }}": {
                    labels: @json($dataSet['labels']),
                    data: {
                        @foreach ($chartTypes as $key => $chart)
                            "{{ $key }}": @json($dataSet[$key]),
                        @endforeach
                    }
                },
            @endforeach
        };

        const chartMeta = {
            @foreach ($chartTypes as $key => $chart)
                "{{ $key }}": {
                    label: "{{ $chart['label'] }}",
                    color: "{{ $chart['color'] }}"
                },
            @endforeach
        };

        window.addEventListener('DOMContentLoaded', () => {
            for (const [timeframe, config] of Object.entries(chartConfigs)) {
                for (const [key, data] of Object.entries(config.data)) {
                    const canvasId = `${timeframe}_${key}`;
                    const ctx = document.getElementById(canvasId)?.getContext('2d');
                    if (ctx && chartMeta[key]) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: config.labels,
                                datasets: [{
                                    label: chartMeta[key].label,
                                    data: data,
                                    borderColor: chartMeta[key].color,
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
                                            text: timeframe.charAt(0).toUpperCase() + timeframe.slice(1)
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
