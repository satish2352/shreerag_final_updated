<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisionMission extends Model
{
    use HasFactory;
    protected $table = 'vision_mission';
    protected $primaryKey = 'id';
    protected $fillable = ['vision_description','mission_description', 'vision_image', 'mission_image'];
}
