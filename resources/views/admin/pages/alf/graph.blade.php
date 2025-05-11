@extends('admin.layouts.master')

@section('title')
    @lang('admin.TITLE_DASHBOARD_TEXT')
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Date Filter Form -->
    <div class="card h-70 shadow-lg border-0 rounded-3">
        <div class="card-body text-center p-3">
            @php $pd_id = $plant_id . '_' . $dept_id; @endphp
            <form method="post" action="{{ route('/dashboardsumagograph') }}">
                @csrf
                <div class="row">
                    <input type="hidden" name="plant_id" id="plant_id" value="{{ $plant_id ?? '' }}">
                    <input type="hidden" name="dept_id" id="dept_id" value="{{ $dept_id ?? '' }}">

                    <div class="col-md-2">
                        <input type="date" name="fromDate" id="fromDate" value="{{ $todayDate }}" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="toDate" id="toDate" value="{{ $toDate }}" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <select name="shift_id" id="shift_id" class="form-control">
                            <option value="">Full Shift</option>
                            @foreach($shiftsMaster as $shiftsMasterValue)
                                <option value="{{ $shiftsMasterValue->shift_id }}" {{ $shiftsMasterValue->shift_id == $shift_id ? 'selected' : '' }} title="{{ $shiftsMasterValue->shift_id }} : {{ $shiftsMasterValue->from_time }} to {{ $shiftsMasterValue->to_time }}">{{ $shiftsMasterValue->shift_id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="sql_table_name_from_user" id="sql_table_name_from_user" class="form-control">
                            <option value="">Select Machine</option>
                            @foreach($machine as $machineData)
                                <option value="{{ $machineData->sql_table_name }}" {{ $machineData->sql_table_name == $sql_table_name_from_user ? 'selected' : '' }}>{{ $machineData->machine_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Progress Bar -->
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

    @php
        $chartTypes = [
            'avg_strokes' => ['label' => 'Actual Strokes', 'color' => 'rgba(75, 192, 192, 1)'],
            'avg_run_time' => ['label' => 'Run Time', 'color' => 'rgba(153, 102, 255, 1)'],
            'avg_idle_time' => ['label' => 'Idle Time', 'color' => 'rgba(255, 159, 64, 1)'],
        ];

        $chartTypesBreak = [
            'avg_break' => ['label' => 'Losses Because of Break', 'color' => 'rgba(75, 192, 192, 1)'],
        ];

        $chartTypesWithoutBreak = [
            'avg_without_break' => ['label' => 'Losses Without Break', 'color' => 'rgba(255, 159, 64, 1)'],
        ];
    @endphp

    <div class="my-5">
        {{-- <h3 class="text-center">Machine Data</h3> --}}
        <div class="charts-wrapper">
            @foreach ($chartTypes as $key => $chart)
               

                <div class="chart-box" style="width: 100%;">
                    <h3 class="text-center">{{$chart['label']}} Graph</h3>
                    <canvas id="chart_{{ $key }}" class="chart-canvas"></canvas>
                </div>
            @endforeach
        </div>
    </div>

    <div class="my-5">
        {{-- <h3 class="text-center">Break Losses</h3> --}}
        <div class="charts-wrapper">
            @foreach ($chartTypesBreak as $key => $chart)
                <div class="chart-box" style="height: 400px;">
                    <h3 class="text-center">{{$chart['label']}} Graph</h3>
                    <canvas id="chart_break_{{ $key }}" class="chart-canvas"></canvas>
                </div>
            @endforeach
        </div>
    </div>

    <div class="my-5">
        {{-- <h3 class="text-center">Without Break Losses</h3> --}}
        <div class="charts-wrapper">
            @foreach ($chartTypesWithoutBreak as $key => $chart)
                <div class="chart-box" style="height: 400px;">
                    <h3 class="text-center">{{$chart['label']}} Graph</h3>
                    <canvas id="chart_without_break_{{ $key }}" class="chart-canvas"></canvas>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labelsMain = @json($dailyDataSet['labels'] ?? []);
            const datasetsMain = {
                @foreach ($chartTypes as $key => $meta)
                    "{{ $key }}": {
                        label: "{{ $meta['label'] }}",
                        color: "{{ $meta['color'] }}",
                        data: @json($dailyDataSet[$key] ?? []),
                    },
                @endforeach
            };

            for (const [key, meta] of Object.entries(datasetsMain)) {
                const ctx = document.getElementById(`chart_${key}`)?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labelsMain,
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
                                x: { title: { display: true, text: 'Time' } },
                                y: { beginAtZero: true }
                            },
                            plugins: {
                                legend: { display: true, position: 'top' }
                            }
                        }
                    });
                }
            }

            const labelsBreak = @json($dailyDataSetBreak['labels'] ?? []);
            const datasetsBreak = {
                @foreach ($chartTypesBreak as $key => $meta)
                    "{{ $key }}": {
                        label: "{{ $meta['label'] }}",
                        borderColor: "{{ $meta['color'] }}",
                        data: @json($dailyDataSetBreak[$key] ?? []),
                    },
                @endforeach
            };

            for (const [key, meta] of Object.entries(datasetsBreak)) {
                const ctx = document.getElementById(`chart_break_${key}`)?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labelsBreak,
                            datasets: [{
                                label: meta.label,
                                data: meta.data,
                                borderColor: meta.borderColor,
                                backgroundColor: meta.borderColor,
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: { title: { display: true, text: 'Date' } },
                                y: { beginAtZero: true }
                            },
                            plugins: {
                                legend: { display: true, position: 'top' }
                            }
                        }
                    });
                }
            }

            const labelsWithoutBreak = @json($dailyDataSetWithoutBreak['labels'] ?? []);
            const datasetsWithoutBreak = {
                @foreach ($chartTypesWithoutBreak as $key => $meta)
                    "{{ $key }}": {
                        label: "{{ $meta['label'] }}",
                        borderColor: "{{ $meta['color'] }}",
                        data: @json($dailyDataSetWithoutBreak[$key] ?? []),
                    },
                @endforeach
            };

            for (const [key, meta] of Object.entries(datasetsWithoutBreak)) {
                const ctx = document.getElementById(`chart_without_break_${key}`)?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labelsWithoutBreak,
                            datasets: [{
                                label: meta.label,
                                data: meta.data,
                                borderColor: meta.borderColor,
                                backgroundColor: meta.borderColor,
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: { title: { display: true, text: 'Date' } },
                                y: { beginAtZero: true }
                            },
                            plugins: {
                                legend: { display: true, position: 'top' }
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
