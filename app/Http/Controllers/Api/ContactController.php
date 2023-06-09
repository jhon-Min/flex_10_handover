<?php

namespace App\Http\Controllers\Api;

use Mail;
use Validator;
use Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class ContactController extends BaseController
{
    /**
     * @group Contact
     * Contact Form
     * This API send contact enquiry to the admin.
     * @bodyParam first_name String required First Name Example:John
     * @bodyParam last_name String required Last Name Example:Deo
     * @bodyParam email String required Email Address  Example:john.deo@test.com
     * @bodyParam message String required Message  Example: Test Enquiry
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $validator = Validator::make($request->all(),[
            "first_name" => "required|min:2",
            "last_name" => "required|min:2",
            "email" => "required|email|min:1",
            "mobile" => "required|min:10|max:15|regex:/^[0-9+ ]*$/",
            "message" => "required|min:5"
        ]);

        try { 

            if($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);    
            }
 
            $mail_attributes = [
                'mail_template' => "emails.contact",
                'mail_to_email' => config('app.administrator_email'),
                'mail_to_name' => config('app.mail_from_name'),
                'mail_subject' => "FlexibleDrive : New Enquiry Received!",
                'mail_body' => [
                    'contact' => $request,
                ]
            ];
            Helper::sendEmail($mail_attributes);
            return $this->sendResponse([],"Mail send!");

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
