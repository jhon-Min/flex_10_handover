<?php

namespace App\Http\Controllers\Api;

use Hash;
use Config;
use App\User;
use validator;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

class PasswordResetController extends BaseController
{
    /**
     * @group Forgot Password
     * Reset Password
     * This API will send verification token to registerd email address.
     * @bodyParam email String required Email Address. Example:john.deo@test.com
     * 
     */
    public function resetRequest(Request $request)
    {
        try {
        
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }

            $user = User::where('email', $request->email)->first();

            if($user) {
                $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email],['email' => $user->email,'token' => str_random(60)]);
                if ($user && $passwordReset) {
                    $user->notify(
                        new PasswordResetRequest($passwordReset->token)
                    );
                }
            }
            
            return $this->sendResponse([], "If user is registered, password reset link will be sent to registered email address.");

        } catch (\Exception $e) {
            
            return $this->sendError($e->getMessage(), [], 401);
        }  
    }
    /**
     * @group Forgot Password
     * Verify Token
     * This EMail will verify token from email address.
     *  
     * 
     * 
     */
    public function verifyToken($token)
    {
        try {
            $passwordReset = PasswordReset::where('token', $token)
                ->first();
            if (!$passwordReset) {

                return $this->sendError('This password reset token is invalid', [], 401);
            }
            if (Carbon::parse($passwordReset->updated_at)->addMinutes(Config::get('constant.password_reset_timeout'))->isPast()) {
            $passwordReset->delete();
            return $this->sendError('Token has been expired. Please try again.', [], 401);
            }
            return $this->sendResponse($passwordReset, "Token is Valid.");
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }  
    }
    /**
     * @group Forgot Password
     * Set New Password
     * This API will set new password
     * @bodyParam password String required password having atlist 1 uppercase, 1 lowercase , 1 number, 1 symbol and minimum 8 character long.  Example:johnDeo@123
     * @bodyParam token String required token from email address
     */
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|max:20|strong_password',
                'confirm_password' => 'required|same:password',
                'token' => 'required|string:exists:password_resets'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }

            $passwordReset = PasswordReset::where('token', $request->token)->first();

            if (!$passwordReset) {
                return $this->sendError('This password reset token is invalid', [], 401);
            }
                
            $user = User::where('email', $passwordReset->email)->first();
        
            $user->password = Hash::make($request->password);
            $user->save();
            $passwordReset->delete();
            $user->notify(new PasswordResetSuccess($passwordReset));
            return $this->sendResponse($user, "Password reset successfull.");
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }  
    }
}
