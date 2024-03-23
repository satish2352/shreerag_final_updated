<?php
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use App\Models\ {
    User
};
use Illuminate\Support\Facades\Storage;



function getPermissionForCRUDPresentOrNot($url,$data_for_session) {
    $data =[];
    if(session('role_id') =='1') {
        array_push($data,'per_add');
        array_push($data,'per_update');
        array_push($data,'per_delete');
    } else {
        foreach ($data_for_session as $value_new) {
        
            if($value_new['url'] == $url) {
                info($value_new);
                foreach ($value_new as $key => $value) {
                    info($value);
                    if($value == 1) {
                        array_push($data,$key);
                    }
                }
                return $data;
            }
        }
    }
    return $data;
}

function uploadImage($request, $image_name, $path, $name) {
    // Check if the directory exists, create it if not
    if (!file_exists(storage_path($path))) {
        mkdir(storage_path($path), 0777, true);
    }

    if ($request->hasFile($image_name)) {
        // Save the file locally
        $request->file($image_name)->move(storage_path($path), $name);
    }
}

function removeImage($path) {
    // Delete the file locally
    if (file_exists(storage_path($path))) {
        unlink(storage_path($path));
    }
}

function file_exists_view($path) {
    // Check if the file exists locally
    return file_exists(storage_path($path));
}

