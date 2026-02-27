<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    Products,
    Testimonial,
    ProductServices,
    Team,
    ContactUs,
    VisionMission,
    DirectorDesk
};

class CMSDashboardRepository
{
    public function getCounts()
    {
        $product_count = Products::where('is_active', 1)->where('is_deleted', 0)->count();
        $testimonial_count = Testimonial::where('is_active', 1)->where('is_deleted', 0)->count();
        $product_services_count = ProductServices::where('is_active', 1)->where('is_deleted', 0)->count();
        $team_count = Team::where('is_active', 1)->count();
        $contact_us_count = ContactUs::where('is_active', 1)->count();
        $vision_mission_count = VisionMission::where('is_active', 1)->count();
        $director_desk_count = DirectorDesk::where('is_active', 1)->count();

        return [
            'cms_counts' => [
                'product_count' => $product_count,
                'testimonial_count' => $testimonial_count,
                'product_services_count' => $product_services_count,
                'vision_mission_count' => $vision_mission_count,
                'director_desk_count' => $director_desk_count,
                'team_count' => $team_count,
                'contact_us_count' => $contact_us_count,

            ]



        ];
    }
}
