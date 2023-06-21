<?php

namespace App\Services\Admin;

use Exception;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;

class ProfileService
{
    protected $USER_IMAGE_PATH;

    public function __construct()
    {
        $this->USER_IMAGE_PATH = Config::get('constant.USER_IMAGE_PATH');
    }

    public function updateProfile($request)
    {
        try {
            $user = User::find(Auth::user()->id);
            if ($request->hasFile('image')) {

                $file_attribue = [
                    'file' => $request->file('image'),
                    'path' => $this->USER_IMAGE_PATH,
                    'old_file' => $user->profile_image,
                ];
                $user->profile_image = Helper::fileUpload($file_attribue);
            }
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $save = $user->save();

            if ($save) {

                return Redirect::back()->with(['message' => 'Profile Updated', 'alert-type' => 'success', 'tab' => 'profile']);
            } else {
                return Redirect::back()->with(['message' => 'Something went wrong. try angain', 'alert-type' => 'success', 'tab' => 'profile'])->withInput($request->all());
            }
        } catch (Exception $e) {

            return Redirect::back()->with(['message' => $e->getMessage(), 'alert-type' => 'success', 'tab' => 'profile'])->withInput($request->all());
        }
    }

    public function updatePassword($request)
    {
        try {
            $user = User::find(Auth::user()->id);

            if (Hash::check($request->old_password, $user->password)) {
                $user->fill([
                    'password' => Hash::make($request->new_password)
                ])->save();
                return Redirect::back()->with(['message' => 'Password Updated successfully', 'alert-type' => 'success', 'tab' => 'password']);
            } else {
                return Redirect::back()->with(['message' => 'Old Password does not match.', 'alert-type' => 'error', 'tab' => 'password'])->withInput($request->all());
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['message' => $e->getMessage(), 'alert-type' => 'error', 'tab' => 'password'])->withInput($request->all());
        }
    }
}
