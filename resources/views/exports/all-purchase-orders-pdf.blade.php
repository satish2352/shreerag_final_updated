@foreach ($orders as $index => $order)
    @if ($index > 0)
        <div style="page-break-before: always;"></div>
    @endif
    @include('organizations.common-pages.purchase-order-view', [
        'purchase_order_id' => $order['purchase_order_id'],
        'purchaseOrder' => $order['purchaseOrder'],
        'purchaseOrderDetails' => $order['purchaseOrderDetails'],
        'getOrganizationData' => $getOrganizationData,
        'getAllRulesAndRegulations' => $getAllRulesAndRegulations,
        'business_id' => $order['business_id'],
        'is_pdf' => true,
    ])
@endforeach
