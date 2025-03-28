<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminView extends Model
{
    use HasFactory;
    protected $table = 'admin_view';
    protected $primaryKey = 'id';

    public function AdminView()
{
    return $this->belongsTo(Business::class, 'business_id');
}
}
