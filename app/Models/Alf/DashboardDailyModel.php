<?php

namespace App\Models\Alf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardDailyModel extends Model
{
    use HasFactory;
    protected $table = 'machine_dashboard_data_date_wise';
    protected $primaryKey = 'id';
}


// CREATE TABLE `machine_dashboard_data_date_wise` (
//     `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
//   `plant_id` VARCHAR(50) DEFAULT NULL,    
//   `dept_id` VARCHAR(50) DEFAULT NULL,    
//   `shift_id` VARCHAR(50) DEFAULT NULL,    
//   `date_from` DATETIME(3) NOT NULL,   
//   `date_to` DATETIME(3) NOT NULL,   
//   `trigger_time_from` DATETIME(3) NOT NULL,   
//   `trigger_time_to` DATETIME(3) NOT NULL,   
//   `machine_name`VARCHAR(255) DEFAULT NULL,    
//   `part_number` VARCHAR(255) DEFAULT NULL,    
//   `actual_stoke` VARCHAR(50) DEFAULT NULL,    
//   `run_time` VARCHAR(50) DEFAULT NULL,        
//   `load_unload_time` VARCHAR(50) DEFAULT NULL,
//   `idle_time` VARCHAR(50) DEFAULT NULL,       
//   `std_spm` VARCHAR(50) DEFAULT NULL,         
//   `run_spm` VARCHAR(50) DEFAULT NULL,         
//   `actual_spm` VARCHAR(50) DEFAULT NULL,      
//   `running_variance` VARCHAR(50) DEFAULT NULL,
//   `avg_variance` VARCHAR(50) DEFAULT NULL,  
//     PRIMARY KEY (`id`)  
//   );
  
     