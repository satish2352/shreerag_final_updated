<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    Gatepass
};

class SecurityDashboardRepository
{
    public function getCounts()
    {
        $get_pass = Gatepass::where('is_active', 1)->where('is_deleted', 0)->count();
        return [

            'secuirty_dept_counts' => [
                'get_pass' => $get_pass,
            ]
        ];
    }
}
