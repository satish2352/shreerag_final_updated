<?php

namespace App\Http\Services\Organizations\Business;

use App\Http\Repository\Organizations\Business\AllListRepositor;
use Exception;

class AllListServices
{
    protected $repo;
    protected $service;

    public function __construct()
    {

        $this->repo = new AllListRepositor();
    }

    public function getAllListForwardedToDesign()
    {
        try {
            $data_output = $this->repo->getAllListForwardedToDesign();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListCorrectionToDesignFromProduction()
    {
        try {
            $data_output = $this->repo->getAllListCorrectionToDesignFromProduction();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function materialAskByProdToStore()
    {
        try {
            $data_output = $this->repo->materialAskByProdToStore();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllStoreDeptSentForPurchaseMaterials()
    {
        try {
            $data_output =  $this->repo->getAllStoreDeptSentForPurchaseMaterials();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function getAllListPurchaseOrder()
    {
        try {
            $data_output = $this->repo->getAllListPurchaseOrder();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function getAllListApprovedPurchaseOrderOwnerlogin()
    {
        try {
            $data_output = $this->repo->getAllListApprovedPurchaseOrderOwnerlogin();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListRejectedPurchaseOrderOwnerlogin()
    {
        try {
            $data_output = $this->repo->getAllListRejectedPurchaseOrderOwnerlogin();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function listPOReceivedForApprovaTowardsOwner()
    {
        try {
            $data_output = $this->repo->listPOReceivedForApprovaTowardsOwner();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listPOPaymentReleaseByVendor()
    {
        try {
            $data_output = $this->repo->listPOPaymentReleaseByVendor();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function loadDesignSubmittedForEstimation()
    { //checked
        try {
            $data_output = $this->repo->loadDesignSubmittedForEstimation();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function loadDesignSubmittedForEstimationBusinessWise($business_details_id)
    { //checked
        try {
            $data_output = $this->repo->loadDesignSubmittedForEstimationBusinessWise($business_details_id);

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAcceptEstimationBOM()
    {
        try {
            $data_output = $this->repo->getAcceptEstimationBOM();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAcceptEstimationBOMBusinessWise($id)
    {
        try {
            $data_output = $this->repo->getAcceptEstimationBOMBusinessWise($id);

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getRejectEstimationBOM()
    { //checked
        try {
            $data_output = $this->repo->getRejectEstimationBOM();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getRejectEstimationBOMBusinessWise($id)
    {
        try {
            $data_output = $this->repo->getRejectEstimationBOMBusinessWise($id);

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getRevisedEstimationBOM()
    { //checked
        try {
            $data_output = $this->repo->getRevisedEstimationBOM();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getRevisedEstimationBOMBusinessWise($id)
    { //checked
        try {
            $data_output = $this->repo->getRevisedEstimationBOMBusinessWise($id);

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function loadDesignSubmittedForProduction()
    {
        try {
            $data_output = $this->repo->loadDesignSubmittedForProduction();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function loadDesignSubmittedForProductionBusinessWise($business_id)
    { //checked
        try {
            $data_output = $this->repo->loadDesignSubmittedForProductionBusinessWise($business_id);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseOrderBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderBusinessWise($purchase_order_id);

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseOrderRejectedBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderRejectedBusinessWise($purchase_order_id);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSubmitedPurchaeOrderByVendorOwnerside()
    {
        try {
            $data_output = $this->repo->getAllListSubmitedPurchaeOrderByVendorOwnerside();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerReceivedGatePass()
    {
        try {
            $data_output = $this->repo->getOwnerReceivedGatePass();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerGRN()
    {
        try {
            $data = $this->repo->getOwnerGRN();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentFromQualityToStoreGeneratedGRN()
    {
        try {
            $data = $this->repo->getAllListMaterialSentFromQualityToStoreGeneratedGRN();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise($id)
    {
        try {
            $data = $this->repo->getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise($id);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListMaterialRecievedToProduction()
    {
        try {
            $data = $this->repo->getOwnerAllListMaterialRecievedToProduction();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getOwnerAllCompletedProduction()
    {
        try {
            $data_output = $this->repo->getOwnerAllCompletedProduction();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerFinalAllCompletedProductionLogistics()
    {
        try {
            $data_output = $this->repo->getOwnerFinalAllCompletedProductionLogistics();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListBusinessReceivedFromLogistics()
    {
        try {
            $data_output = $this->repo->getOwnerAllListBusinessReceivedFromLogistics();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListBusinessFianaceSendToDispatch()
    {
        try {
            $data_output = $this->repo->getOwnerAllListBusinessFianaceSendToDispatch();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listProductDispatchCompletedFromDispatch()
    {
        try {
            $data_output = $this->repo->listProductDispatchCompletedFromDispatch();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listLoginHistory()
    {
        $data_users = $this->repo->listLoginHistory();
        return $data_users;
    }
    //    public function showLoginHistory($id) {
    //     return $this->repo->showLoginHistory($id);
    // }
    // public function showLoginHistory($id)
    // {
    //     $user = $this->repo->showLoginHistory($id);

    //     if ($user) {
    //         $latitude = $user->latitude;
    //         $longitude = $user->longitude;

    //         $apiKey = config('services.google.maps_api_key');

    //         // Call Google Geocoding API
    //         $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
    //             'latlng' => "$latitude,$longitude",
    //             'key'    => $apiKey,
    //         ]);

    //         $responseData = $response->json();

    //         // Get formatted address
    //         if (!empty($responseData['results'][0]['formatted_address'])) {
    //             $address = $responseData['results'][0]['formatted_address'];
    //         } else {
    //             $address = "Address not found";
    //         }

    //         // ✅ Save back to login_history table (update last login of this user)
    //         DB::table('login_history')
    //             ->where('user_id', $id)
    //             ->orderByDesc('id')
    //             ->limit(1) // update only the last login row
    //             ->update(['location_address' => $address]);

    //         // Also attach for showing in blade
    //         $user->location_address = $address;
    //     }

    //     return $user;
    // }

    // public function showLoginHistory($id)
    // {
    //     // Fetch login history record
    //     $user = $this->repo->showLoginHistory($id);

    //     if ($user) {
    //         $latitude = $user->latitude;
    //         $longitude = $user->longitude;

    //         $apiKey = config('services.locationiq.api_key');

    //         // Call LocationIQ Reverse Geocoding API
    //         $response = Http::get("https://us1.locationiq.com/v1/reverse.php", [
    //             'key'    => $apiKey,
    //             'lat'    => $latitude,
    //             'lon'    => $longitude,
    //             'format' => 'json'
    //         ]);

    //         $responseData = $response->json();

    //         // Get formatted address
    //         $address = $responseData['display_name'] ?? "Address not found";

    //         // ✅ Update the same login_history record
    //         DB::table('login_history')
    //             ->where('id', $id) // update by login_history record id
    //             ->update(['location_address' => $address]);

    //         // Attach address for returning
    //         $user->location_address = $address;
    //     }

    //     return $user;
    // }
    // public function showLoginHistory($id)
    // {
    //     // Fetch login history record
    //     $user = $this->repo->showLoginHistory($id);

    //     if ($user) {
    //         $latitude = $user->latitude;
    //         $longitude = $user->longitude;

    //         $apiKey = config('services.locationiq.api_key');

    //         // Call LocationIQ Reverse Geocoding API
    //         $response = Http::get("https://us1.locationiq.com/v1/reverse.php", [
    //             'key'    => $apiKey,
    //             'lat'    => $latitude,
    //             'lon'    => $longitude,
    //             'format' => 'json'
    //         ]);

    //         $responseData = $response->json();

    //         // ✅ Try to get detailed address instead of only display_name
    //         if (isset($responseData['address'])) {
    //             $addressParts = $responseData['address'];

    //             $address = implode(', ', array_filter([
    //                 $addressParts['house_number'] ?? null,
    //                 $addressParts['road'] ?? null,
    //                 $addressParts['neighbourhood'] ?? null,
    //                 $addressParts['suburb'] ?? null,
    //                 $addressParts['city'] ?? $addressParts['town'] ?? $addressParts['village'] ?? null,
    //                 $addressParts['state_district'] ?? null,
    //                 $addressParts['state'] ?? null,
    //                 $addressParts['postcode'] ?? null,
    //                 $addressParts['country'] ?? null,
    //             ]));
    //         } else {
    //             $address = $responseData['display_name'] ?? "Address not found";
    //         }

    //         // ✅ Update the same login_history record
    //         DB::table('login_history')
    //             ->where('id', $id)
    //             ->update(['location_address' => $address]);

    //         // Attach address for returning
    //         $user->location_address = $address;
    //     }

    //     return $user;
    // }

}
