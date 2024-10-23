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
if (!function_exists('convertToWords')) {
    function convertToWords($number) {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
            20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty',
            60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ? $words[$point / 10] . " " . $words[$point = $point % 10] : '';
        return $result . "rupees" . ($points ? " and " . $points . " paise" : "") . " only";
    }
}