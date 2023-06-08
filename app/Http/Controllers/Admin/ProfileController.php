<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Hash;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;
use App\User;
use Redirect;
use Validator;
use App\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {

        $this->USER_IMAGE_PATH = Config::get('constant.USER_IMAGE_PATH');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $data['profile'] = Auth::user();
        return view('profile.profile', $data)->with(['tab' => 'profile']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $user = User::find(Auth::user()->id);
            $rules = [
                "first_name" => "required|min:2",
                "last_name" => "required|min:2",
            ];
            if ($request->hasFile('image')) {
                $rules["image"] = "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=200,max_height=200";
            }
            $validator = \Validator::make($request->all(), $rules, ['image.dimensions' => 'Please upload image with required dimensions', 'image.max' => 'The image may not be greater than 2MB']);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput()->with('tab', 'profile');
            }

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
        } catch (\Exception $e) {

            return Redirect::back()->with(['message' => $e->getMessage(), 'alert-type' => 'success', 'tab' => 'profile'])->withInput($request->all());
        }
    }
    public function passwordUpdate(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|max:20',
                'new_password' => 'required|string|max:20|strong_password|different:old_password',
                'confirm_password' => 'required|same:new_password'
            ], ['new_password.different' => 'The new password and Current password must be different.']);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput()->with('tab', 'password');
            }

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
