<style>
    /* ── Owner Dashboard Design ── */
    .owner-dash { padding: 16px 20px 20px; background: #f4f6fb; }

    /* KPI Cards */
    .kpi-card {
        border-radius: 12px;
        padding: 14px 16px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        margin-bottom: 12px;
    }
    .kpi-card::after {
        content: '';
        position: absolute;
        width: 90px; height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.10);
        top: -20px; right: -20px;
    }
    .kpi-card .kpi-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: rgba(255,255,255,0.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; margin-bottom: 8px;
    }
    .kpi-card .kpi-label { font-size: 11px; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .kpi-card .kpi-value { font-size: 20px; font-weight: 800; line-height: 1.1; }
    .kpi-blue   { background: linear-gradient(135deg, #1a3a6b 0%, #25385F 100%); }
    .kpi-teal   { background: linear-gradient(135deg, #0bb8b2 0%, #0a8f8a 100%); }
    .kpi-navy   { background: linear-gradient(135deg, #2d4e7e 0%, #1e3556 100%); }
    .kpi-green  { background: linear-gradient(135deg, #1da462 0%, #158a50 100%); }

    /* Stat Cards */
    .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 12px 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid transparent;
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 12px;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(0,0,0,0.10); }
    .stat-card.bl-blue  { border-left-color: #25385F; }
    .stat-card.bl-teal  { border-left-color: #0bb8b2; }
    .stat-card.bl-warn  { border-left-color: #f59e0b; }
    .stat-card.bl-green { border-left-color: #1da462; }
    .stat-card .sc-icon {
        width: 40px; height: 40px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .stat-card .sc-label { font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.4px; }
    .stat-card .sc-value { font-size: 30px; font-weight: 800; color: #1a3a6b; line-height: 1.1; margin: 2px 0; }
    .stat-card .sc-sub   { font-size: 13px; color: #9ca3af; }
    .sc-link { font-size: 13px; font-weight: 600; color: #0bb8b2; text-decoration: none; display: flex; align-items: center; gap: 4px; }
    .sc-link:hover { color: #0a8f8a; }

    /* Chart card */
    .chart-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 12px;
        height: 100%;
    }
    .chart-card .chart-title { font-size: 15px; font-weight: 700; color: #1a3a6b; margin-bottom: 14px; }

    /* Profit + Approval cards */
    .info-card {
        background: #fff;
        border-radius: 10px;
        padding: 16px 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid transparent;
        margin-bottom: 12px;
    }
    .info-card.ic-warn   { border-left-color: #f59e0b; background: #fffbf0; }
    .info-card.ic-blue   { border-left-color: #25385F; }
    .info-card.ic-profit { border-left-color: #1da462; background: #f0faf5; }
    .info-card .ic-label { font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 4px; }
    .info-card .ic-value { font-size: 28px; font-weight: 800; color: #1a3a6b; }
    .info-card .ic-sub   { font-size: 13px; color: #9ca3af; margin-top: 4px; }

    /* Section label */
    .section-label { font-size: 12px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; margin-top: 4px; }
    #myDonutChart { max-height: 240px; }

    /* Offcanvas btn */
    .offcanvas-trigger {
        position: fixed; right: 20px; top: 80px; z-index: 50;
        width: 42px; height: 42px; border-radius: 10px;
        background: #25385F; color: #fff; border: none;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; box-shadow: 0 4px 14px rgba(37,56,95,0.4);
        cursor: pointer; transition: background 0.2s;
    }
    .offcanvas-trigger:hover { background: #1a3a6b; }
</style>

{{-- Offcanvas trigger button --}}
<button class="offcanvas-trigger" id="offcanvasTrigger" title="Customer PO List">
    <i class="fas fa-list-alt"></i>
</button>

<div class="owner-dash">

    {{-- ── ROW 1: KPI Cards ── --}}
    <p class="section-label">Financial Overview</p>
    <div class="row">
        <div class="col-xl-3 col-md-6 col-12">
            <div class="kpi-card kpi-blue">
                <div class="kpi-icon"><i class="fas fa-briefcase"></i></div>
                <div class="kpi-label">Business Value</div>
                <div class="kpi-value">&#8377; {{ number_format($owner_kpis['total_business_value'], 2) }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="kpi-card kpi-teal">
                <div class="kpi-icon"><i class="fas fa-file-invoice"></i></div>
                <div class="kpi-label">Total Estimation</div>
                <div class="kpi-value">&#8377; {{ number_format($owner_kpis['total_estimation_all'], 2) }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="kpi-card kpi-navy">
                <div class="kpi-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="kpi-label">Total PO Amount</div>
                <div class="kpi-value">&#8377; {{ number_format($owner_kpis['total_po_amount'], 2) }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="kpi-card kpi-green">
                <div class="kpi-icon"><i class="fas fa-boxes"></i></div>
                <div class="kpi-label">GRN Received</div>
                <div class="kpi-value">&#8377; {{ number_format($owner_kpis['total_grn_accepted_amount'], 2) }}</div>
            </div>
        </div>
    </div>

    {{-- ── ROW 2: Operations + Chart ── --}}
    <p class="section-label">Operations</p>
    <div class="row">

        {{-- Stat tiles --}}
        <div class="col-xl-8">
            <div class="row">
                <div class="col-sm-6 col-12 mb-3">
                    <div class="stat-card bl-blue" style="margin-bottom:0;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="sc-label">Active Projects</div>
                                <div class="sc-value">{{ $return_data['active_businesses'] }}</div>
                                <div class="sc-sub">Currently running</div>
                            </div>
                            <div class="sc-icon" style="background:#e8edf7; color:#25385F;">
                                <i class="fas fa-city"></i>
                            </div>
                        </div>
                        <div class="mt-2 pt-1" style="border-top:1px solid #f0f2f7;">
                            <a href="{{ route('list-business') }}" class="sc-link">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12 mb-3">
                    <div class="stat-card bl-teal" style="margin-bottom:0;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="sc-label">Total Products</div>
                                <div class="sc-value">{{ $return_data['business_details'] }}</div>
                                <div class="sc-sub">Across all projects</div>
                            </div>
                            <div class="sc-icon" style="background:#e0f7f6; color:#0bb8b2;">
                                <i class="fas fa-cube"></i>
                            </div>
                        </div>
                        <div class="mt-2 pt-1" style="border-top:1px solid #f0f2f7;">
                            <a href="{{ route('list-business') }}" class="sc-link">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12 mb-3">
                    <div class="stat-card bl-warn" style="margin-bottom:0;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="sc-label">Products In Progress</div>
                                <div class="sc-value" style="color:#d97706;">{{ $owner_kpis['products_in_progress'] }}</div>
                                <div class="sc-sub">Active workflow items</div>
                            </div>
                            <div class="sc-icon" style="background:#fef3cd; color:#d97706;">
                                <i class="fas fa-spinner"></i>
                            </div>
                        </div>
                        <div class="mt-2 pt-1" style="border-top:1px solid #f0f2f7;">
                            <span class="sc-sub">In manufacturing flow</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12 mb-3">
                    <div class="stat-card bl-green" style="margin-bottom:0;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="sc-label">Products Completed</div>
                                <div class="sc-value" style="color:#1da462;">{{ $owner_kpis['products_completed'] }}</div>
                                <div class="sc-sub">Fully dispatched</div>
                            </div>
                            <div class="sc-icon" style="background:#dcf5e9; color:#1da462;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-2 pt-1" style="border-top:1px solid #f0f2f7;">
                            <span class="sc-sub">Ready for invoicing</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Donut chart --}}
        <div class="col-xl-4 d-flex flex-column">
            <div class="chart-card d-flex flex-column flex-grow-1">
                <div class="chart-title"><i class="fas fa-chart-pie mr-2" style="color:#0bb8b2;"></i>Project Details</div>
                <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                    <canvas id="myDonutChart"></canvas>
                </div>
            </div>
        </div>

    </div>{{-- end Row 2 --}}

    {{-- ── ROW 3: Action + Financials ── --}}
    <p class="section-label">Action & Financials</p>
    <div class="row">

        {{-- Pending Approvals --}}
        <div class="col-xl-4 col-md-4 col-12">
            <div class="info-card {{ $owner_kpis['pending_owner_actions'] > 0 ? 'ic-warn' : 'ic-blue' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="ic-label"><i class="fas fa-clock mr-1"></i>Pending Approvals</div>
                    <span class="badge" style="background:{{ $owner_kpis['pending_owner_actions'] > 0 ? '#f59e0b' : '#25385F' }}; color:#fff; border-radius:6px; font-size:10px;">
                        {{ $owner_kpis['pending_owner_actions'] > 0 ? 'Action Required' : 'All Clear' }}
                    </span>
                </div>
                <div class="ic-value" style="color:{{ $owner_kpis['pending_owner_actions'] > 0 ? '#d97706' : '#1a3a6b' }};">
                    {{ $owner_kpis['pending_owner_actions'] }}
                </div>
                <div class="ic-sub">PO approvals waiting</div>
                <div class="mt-2 pt-1" style="border-top:1px solid rgba(0,0,0,0.06);">
                    <a href="{{ route('list-approved-purchase-orders-owner') }}" class="sc-link">
                        Review Now <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Total PO Orders --}}
        <div class="col-xl-4 col-md-4 col-12">
            <div class="info-card ic-blue">
                <div class="ic-label"><i class="fas fa-file-alt mr-1"></i>Total PO Orders</div>
                <div class="ic-value">{{ $owner_kpis['total_po_orders'] }}</div>
                <div class="ic-sub">Purchase orders raised</div>
                <div class="mt-2 pt-1" style="border-top:1px solid rgba(0,0,0,0.06);">
                    <a href="{{ route('list-business') }}" class="sc-link">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Realized Profit --}}
        <div class="col-xl-4 col-md-4 col-12">
            <div class="info-card ic-profit">
                <div class="ic-label"><i class="fas fa-chart-line mr-1"></i>Realized Profit</div>
                <div class="ic-value" style="color:#1da462;">
                    &#8377; {{ number_format($business_total_amount['profit'], 2) }}
                </div>
                <div class="ic-sub">From fully dispatched products</div>
                <div class="mt-2 pt-1" style="border-top:1px solid rgba(0,0,0,0.06);">
                    <span class="sc-sub" style="color:#1da462; font-weight:600;">Net earnings after costs</span>
                </div>
            </div>
        </div>

    </div>{{-- end Row 3 --}}

</div>{{-- end owner-dash --}}

{{-- Chart Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('myDonutChart').getContext('2d');
        const counts = @json($return_data);
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Business Completed', 'Business In Process', 'Product Completed', 'Product In Process'],
                datasets: [{
                    data: [
                        counts.business_completed ?? 0,
                        counts.business_inprocess ?? 0,
                        counts.product_completed ?? 0,
                        counts.product_inprocess ?? 0
                    ],
                    backgroundColor: ['#1a3a6b', '#0bb8b2', '#1da462', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
                    title: { display: false }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>


{{-- Customer PO List — custom slide-in panel (Bootstrap 4 compatible) --}}
<div id="offcanvasBackdrop" class="erp-offcanvas-backdrop"></div>

<div id="offcanvasRight" class="erp-offcanvas">
    <div class="erp-offcanvas-header">
        <h5 class="erp-offcanvas-title"><i class="fas fa-list-alt mr-2"></i> Customer PO List</h5>
        <button type="button" class="erp-offcanvas-close" id="offcanvasClose" aria-label="Close">&times;</button>
    </div>
    <div class="erp-offcanvas-body">
        <div id="po-accordion">
            @foreach ($offcanvas['offcanvas'] as $po_number => $grouped_data)
                <div class="erp-acc-card">
                    <div class="erp-acc-header" id="erp-heading{{ $loop->index }}">
                        <button class="erp-acc-btn {{ $loop->first ? '' : 'collapsed' }}"
                                data-toggle="collapse"
                                data-target="#erp-collapse{{ $loop->index }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="erp-collapse{{ $loop->index }}">
                            <span>{{ $po_number }} — {{ $grouped_data->first()->title }}</span>
                            <i class="fas fa-chevron-down erp-acc-chevron"></i>
                        </button>
                    </div>
                    <div id="erp-collapse{{ $loop->index }}"
                         class="collapse {{ $loop->first ? 'show' : '' }}"
                         aria-labelledby="erp-heading{{ $loop->index }}"
                         data-parent="#po-accordion">
                        <ul class="list-unstyled erp-acc-body">
                            @foreach ($grouped_data as $data)
                                <li class="erp-po-item">
                                    <span class="erp-po-dot"></span>
                                    <div>
                                        <strong>{{ $data->product_name }}</strong>
                                        <div class="erp-po-status">
                                            @switch(true)
                                                @case($data->quantity_tracking_status == 3005 && $data->dispatch_status_id == 1154)
                                                    <span class="badge badge-success">Dispatch Completed</span>
                                                @break
                                                @case($data->quantity_tracking_status == 3005)
                                                    <span class="badge badge-info">Dispatch Dept — Qty {{ $data->completed_quantity }}</span>
                                                @break
                                                @case($data->quantity_tracking_status == 3004)
                                                    <span class="badge badge-primary">Finance → Dispatch — Qty {{ $data->completed_quantity }}</span>
                                                @break
                                                @case($data->quantity_tracking_status == 3003)
                                                    <span class="badge badge-primary">Finance ← Logistics — Qty {{ $data->completed_quantity }}</span>
                                                @break
                                                @case($data->quantity_tracking_status == 3002)
                                                    <span class="badge badge-warning">Logistics — Qty {{ $data->completed_quantity }}</span>
                                                @break
                                                @case($data->quantity_tracking_status == 3001)
                                                    <span class="badge badge-secondary">Production Complete → Logistics</span>
                                                @break
                                                @default
                                                    <span class="badge badge-light">In Progress</span>
                                            @endswitch
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
/* ── Custom Offcanvas ── */
.erp-offcanvas-backdrop {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.35); z-index: 10000;
    transition: opacity 0.25s;
}
.erp-offcanvas-backdrop.show { display: block; }

.erp-offcanvas {
    position: fixed; top: 0; right: -420px; width: 400px; max-width: 92vw;
    height: 100vh; background: #fff; z-index: 10001;
    display: flex; flex-direction: column;
    box-shadow: -6px 0 28px rgba(26,58,107,0.18);
    transition: right 0.28s cubic-bezier(.4,0,.2,1);
    border-left: 3px solid #1a3a6b;
}
.erp-offcanvas.show { right: 0; }

.erp-offcanvas-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; background: #1a3a6b; color: #fff;
    flex-shrink: 0;
}
.erp-offcanvas-title { margin: 0; font-size: 15px; font-weight: 600; color: #fff; }
.erp-offcanvas-close {
    background: rgba(255,255,255,0.15); border: none; color: #fff;
    width: 32px; height: 32px; border-radius: 6px; font-size: 20px; line-height: 1;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: background 0.15s;
}
.erp-offcanvas-close:hover { background: rgba(255,255,255,0.3); }

.erp-offcanvas-body {
    flex: 1; overflow-y: auto; padding: 12px 14px;
    background: #f8fafd;
}

/* ── Accordion inside panel ── */
.erp-acc-card {
    background: #fff; border-radius: 8px; margin-bottom: 8px;
    border: 1px solid #e2eaf5; overflow: hidden;
}
.erp-acc-btn {
    width: 100%; background: transparent; border: none; text-align: left;
    padding: 11px 14px; font-size: 13.5px; font-weight: 600; color: #1a3a6b;
    display: flex; align-items: center; justify-content: space-between;
    cursor: pointer; transition: background 0.15s;
}
.erp-acc-btn:hover { background: #f0f4fb; }
.erp-acc-chevron { font-size: 11px; transition: transform 0.2s; }
.erp-acc-btn[aria-expanded="true"] .erp-acc-chevron { transform: rotate(180deg); }

.erp-acc-body { margin: 0; padding: 8px 14px 14px; }
.erp-po-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 8px 0; border-bottom: 1px solid #eef2f8; font-size: 13px;
}
.erp-po-item:last-child { border-bottom: none; }
.erp-po-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #1a3a6b;
    flex-shrink: 0; margin-top: 5px;
}
.erp-po-status { margin-top: 4px; }
.erp-po-status .badge { font-size: 11px; padding: 3px 7px; }
</style>

<script>
(function() {
    var trigger   = document.getElementById('offcanvasTrigger');
    var panel     = document.getElementById('offcanvasRight');
    var backdrop  = document.getElementById('offcanvasBackdrop');
    var closeBtn  = document.getElementById('offcanvasClose');

    function open()  { panel.classList.add('show'); backdrop.classList.add('show'); }
    function close() { panel.classList.remove('show'); backdrop.classList.remove('show'); }

    if (trigger)  trigger.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);

    /* Bootstrap 4 accordion chevron sync */
    document.querySelectorAll('.erp-acc-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', expanded ? 'false' : 'true');
        });
    });
})();
</script>
