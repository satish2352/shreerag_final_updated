<?php

namespace App\Models\Alf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_machine_master';
    protected $primaryKey = 'id';
}
