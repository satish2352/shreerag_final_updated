<?php

namespace App\Http\Repository\Website;

use App\Models\{
    ContactUs
};


class ContactUsRepository
{

    public function addAll($request)
    {
        try {

            $contact = new ContactUs();
            $contact->full_name = $request['full_name'];
            $contact->email = $request['email'];
            $contact->mobile_number = $request['mobile_number'];
            $contact->subject = $request['subject'];
            $contact->message = $request['message'];
            $contact->save();

            return $contact;
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
}
