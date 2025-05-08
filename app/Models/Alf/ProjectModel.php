<?php

namespace App\Models\Alf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_project_master';
    protected $primaryKey = 'id';
}
