<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $table = 'businesses';
    protected $primaryKey = 'id';


    // Example relationships
    public function businessDetails()
    {
        return $this->hasMany(BusinessDetails::class, 'business_id');
    }

    public function designModel()
    {
        return $this->hasMany(DesignModel::class, 'business_id');
    }
    public function businessApplicationProcesses()
    {
        return $this->hasMany(BusinessApplicationProcesses::class, 'business_id');
    }

    public function designRevisionForProd()
    {
        return $this->hasMany(DesignRevisionForProd::class, 'business_id');
    }
    public function productionModel()
    {
        return $this->hasMany(ProductionModel::class, 'business_id');
    }
    public function productionDetails()
    {
        return $this->hasMany(ProductionDetails::class, 'business_id');
    }

    public function purchaseOrderModel()
    {
        return $this->hasMany(PurchaseOrderModel::class, 'business_id');
    }
    public function requisition()
    {
        return $this->hasMany(Requisition::class, 'business_id');
    }

    public function customerProductQuantityTracking()
    {
        return $this->hasMany(CustomerProductQuantityTracking::class, 'business_id');
    }

    public function deliveryChalan()
    {
        return $this->hasMany(DeliveryChalan::class, 'business_id');
    }

    public function dispatch()
    {
        return $this->hasMany(Dispatch::class, 'business_id');
    }

    public function logistics()
    {
        return $this->hasMany(Logistics::class, 'business_id');
    }

    public function notificationStatus()
    {
        return $this->hasMany(NotificationStatus::class, 'business_id');
    }

    public function returnableChalan()
    {
        return $this->hasMany(ReturnableChalan::class, 'business_id');
    }
    public function AdminView()
    {
        return $this->hasMany(AdminView::class, 'business_id');
    }
    
}


