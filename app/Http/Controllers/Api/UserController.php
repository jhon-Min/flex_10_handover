<?php

namespace App\Http\Controllers\Api;

use Auth;
use Hash;
use Config;
use Storage;
use JWTAuth;
use App\User;
use Validator;
use JWTFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;


class UserController extends BaseController
{

    /**
     * Create a new  instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->USER_IMAGE_PATH = Config::get('constant.USER_IMAGE_PATH');
    }
    /**
     * @group Profile 
     * User Profile
     * This API use to get Logged in user profile detail.
     */
    public function index()
    {
        try {
            $user_profile = User::find(Auth::user()->id);

            return $this->sendResponse($user_profile, 'User Profile');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 401);
        }       
    }
    /**
     * @group Profile 
     * User Profile Update
     * This API use to Update Logged in user profile detail.
     * @bodyParam first_name String required First Name Example: john
     * @bodyParam last_name String required Last Name Example: doe
     * @bodyParam company_name String required Company  Example: test company
     * @bodyParam address_line1 String required Address Line1 Example: test adress1
     * @bodyParam address_line2 String required Address Line2 Example: test adress2
     * @bodyParam state String required ACT, NSW, NT, QLD, SA, TAS, VIC, WA Example: ACT
     * @bodyParam zip String required Pincode (Zipcode)  Example: 123456
     * @bodyParam profile_image String required User Profile image base64 incoded   Example: base64 string
     * @response {
     *  "success": true,
     *       "data": {
     *       "id": 11,
     *       "first_name": "Krutik1",
     *       "last_name": "Patel1",
     *       "email": "kkrutikk@gmail.com",
     *       "email_verified_at": null,
     *      "account_code": null,
     *       "company_name": "Test Company",
     *       "address_line1": "New Test Street",
     *       "address_line2": "Test City",
     *       "state": "Test State",
     *       "zip": "1224",
     *       "profile_image": "BbU16LP5Uc.jpg",
     *       "mobile": "1234567890",
     *       "created_at": "2019-09-30 12:21:15",
     *       "updated_at": "2019-10-04 09:27:45",
     *       "image_url": "http://35.164.124.177/fd-backend/public/storage/users/BbU16LP5Uc.jpg"
     *   },
     *   "message": "User Profile"
     * }
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'company_name' => 'required|min:2',
                'address_line1' => 'required|min:2',
                'address_line2' => 'required|min:2',
                'state' => 'required',
                'zip' => 'required|regex:/\b\d{4}\b/',
                'mobile' => 'required|min:10|regex:/^[0-9+ ]*$/',
				'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            ], ['zip.required' => 'Postal Code is Required.', 'zip.regex' => 'Postal Code format is invalid.']);

            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }
           
            $user = User::find(Auth::user()->id);

            if(isset($request->profile_image) && !empty($request->profile_image)) {

                $image = $request->profile_image;
                $image = substr($image, strpos($image, ",") + 1);
                $imageName = str_random(10) . '.jpg';
               
                $path = $this->USER_IMAGE_PATH . $imageName;
                $location = Storage::disk('public')->put($path, base64_decode($image));
                
                if($location) {
                    if($user->profile_image) {
                        $store_path = $this->USER_IMAGE_PATH . $user->profile_image;
                        Storage::disk('public')->delete($store_path);
                    }
                    $user->profile_image = $imageName;
                }
            } 

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->company_name = $request->company_name;
            $user->address_line1 = $request->address_line1;
            $user->address_line2 = $request->address_line2;
            $user->state = $request->state;
            $user->zip = $request->zip;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->save();

            return $this->sendResponse($user, 'Profile Updated successfully.');
            
            
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 401);
        }
    }


    /**
     * @group Profile 
     * User password update
     * This API will update logged in user password. 
     * @bodyParam old_password String required password String required password having atlist 1 uppercase, 1 lowercase , 1 number, 1 symbol and minimum 8 character long.  Example:johnDeo@123
     * @bodyParam old_password String required password String required password having atlist 1 uppercase, 1 lowercase , 1 number, 1 symbol and minimum 8 character long.  Example:johnDeo@123
     * @bodyParam confirm_password String required Confirm Password Example:johnDeo@123
     */
    public function passwordUpdate(Request $request) {
        try {
            
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|max:20',
                'new_password' => 'required|string|max:20|strong_password|different:old_password',
                'confirm_password' => 'required|same:new_password'
            ],['new_password.different' => 'The new password and Current password must be different.']);

            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }

            $user = User::find(Auth::user()->id);
             
            if (Hash::check($request->old_password, $user->password)) {
                $user->fill([
                    'password' => Hash::make($request->new_password)
                ])->save();
                return $this->sendResponse([], 'Password Updated successfully.');

            } else {
                return $this->sendError('Current password does not match.', [], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 401);
        }
    }

   
}
