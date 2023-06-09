<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use SoapClient;
use SoapFault;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class LoginController extends BaseController
{

    /**
     * @group Authentication
     * Sign in
     * user login
     * @bodyParam email String required  user registerd email address Example: john.does@test.com
     * @bodyParam password String required  user password Example:johnDeo@123
     */
    public function login(Request $request)
    {

        try {

            $validator = Validator::make(request(['email', 'password']), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }

            $credentials = $request->only('email', 'password');
            $credentials['admin_approval_status'] = 2;

            try {
                $user = User::where('email', $request->email)->whereHas('roles', function ($query) {
                    $query->where('role_id', '3');
                })->with('roles')->first();

                if (isset($user->id) && !empty($user->id)) {
                    if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                        return $this->sendError('Invalid email address or password', [], 401);
                    }
                    if ($user->is_active == 1) {
                        $data = Auth::user();
                        $data['token'] = $token;
                        return $this->sendResponse($data, "Login Success");
                    } else {
                        return $this->sendError('Account is disabled', [], 401);
                    }
                } else {
                    return $this->sendError('Invalid email address or password', [], 401);
                }
            } catch (JWTException $e) {
                return $this->sendError('Unauthorized', [], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /** 
     * @group Authentication
     * Sign out 
     * 
     */
    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        try {
            JWTAuth::parseToken()->invalidate($token);
            return $this->sendResponse([], 'logged out');
        } catch (TokenExpiredException $exception) {
            return $this->sendError('token expired', [], 401);
        } catch (TokenInvalidException $exception) {
            return $this->sendError('token invalid', [], 401);
        } catch (JWTException $exception) {
            return $this->sendError('token missing', [], 401);
        }
    }


    /** 
     * @group Authentication
     * Sign up
     * user registration
     * @bodyParam first_name String required First Name Example: john
     * @bodyParam last_name String required Last Name Example: doe
     * @bodyParam company_name String required Company  Example: test company
     * @bodyParam address_line1 String required Address Line1 Example: test adress1
     * @bodyParam address_line2 String required Address Line2 Example: test adress2
     * @bodyParam state String required ACT, NSW, NT, QLD, SA, TAS, VIC, WA Example: ACT
     * @bodyParam zip String required Pincode (Zipcode)  Example: 123456
     * @bodyParam mobile number required phone number  Example: 1234567890
     * @bodyParam email String required Email address Example: john.does@test.com
     * @bodyParam password String required password String required password having atlist 1 uppercase, 1 lowercase , 1 number, 1 symbol and minimum 8 character long.  Example:johnDeo@123
     * @bodyParam confirm_password String required Confirm Password Example:johnDeo@123
     */
    public function signup(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                // 'account_code' => 'required|min:3',
                'company_name' => 'required|min:2',
                'address_line1' => 'required|min:2',
                'address_line2' => 'required|min:2',
                'state' => 'required',
                'zip' => 'required|regex:/\b\d{4}\b/',
                'mobile' => 'required|min:10|max:15|regex:/^[0-9+ ]*$/',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|max:20|strong_password',
                'confirm_password' => 'required|same:password'
            ], ['zip.required' => 'Postal Code is Required.', 'zip.regex' => 'Postal Code format is invalid.']);

            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }

            $user_save = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'account_code' => $request->account_code,
                'company_name' => $request->company_name,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'state' => $request->state,
                'address_line2' => $request->address_line2,
                'zip' => $request->zip,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
            ];

            $user = User::create($user_save);

            if ($user->id > 0) {
                UserRole::create(['user_id' => $user->id, 'role_id' => 3]);
                $url = Config::get('app.url') . '/users';
                // mail to admin
                $mail_attributes = [
                    'mail_to_email' => config('app.administrator_email'),
                    'mail_to_name' => config('app.mail_from_name'),
                    'mail_subject' => "Flexibledrive : Account Approve Request",
                    'mail_body' => [
                        'description' => 'New Account created. Click following link to Approve or Decline.',
                        'action_title' => 'Approve Account',
                        'action_url' => $url
                    ],

                ];

                Helper::sendEmail($mail_attributes);
                // mail to user
                $mail_attributes = [
                    'mail_template' => "emails.user_account_created",
                    'mail_to_email' => $user->email,
                    'mail_to_name' => $user->name,
                    'mail_subject' => "Flexible Drive : Account Update!",
                ];
                Helper::sendEmail($mail_attributes);
                return $this->sendResponse([], "Signup Success.");
            } else {
                return $this->sendError("Signup Failed", [], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    public function soapTest_OLD()
    {
        try {
            $wsdlUrl = 'https://api.flexibledrive.com.au:29843/adxwsvc/services/CAdxWebServiceXmlCC?wsdl';
            $client = new SoapClient($wsdlUrl);
            var_dump($client->__getFunctions());
            exit;
            $xmlInput = '<PARAM>
                            <GRP ID="GRP1" DIM="1">
                                <FLD NAME="YBPCNUM" TYPE="Char" >BEN05</FLD>
                            </GRP>
                        </PARAM>';
            $CContext = [];
            $CContext["codeLang"] = "ENG";
            $CContext["codeUser"] = "ADMIN";
            $CContext["password"] = "";
            $CContext["poolAlias"] = "WSFDLIVE";
            $CContext["requestConfig"] = "adxwss.trace.on=on&adxwss.trace.size=16384 &adonix.trace.on=on&adonix.trace.level=3&adonix.trace.size=8";

            $result = $client->run($CContext, "YBPCINFO", $xmlInput);
            echo $result->resultXml;
            //echo($result);
            //echo($result ->messages[1]->message);
            //echo($result ->status);
            exit;
        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
    }


    function YBPCINFO($account_code)
    {
        try {

            $wsdlUrl = 'https://api.flexibledrive.com.au:29843/adxwsvc/services/CAdxWebServiceXmlCC?wsdl';

            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );

            $client = new SoapClient($wsdlUrl, $options);

            $xmlInput = '<PARAM>
            <GRP ID="GRP1" DIM="1">
                <FLD NAME="YBPCNUM" TYPE="Char">' . $account_code . '</FLD>
            </GRP>
            </PARAM>'; //BEN05

            $CContext = [];
            $CContext["codeLang"] = "ENG";
            $CContext["codeUser"] = "ADMIN";
            $CContext["password"] = "";
            $CContext["poolAlias"] = "WSFDLIVE";
            $CContext["requestConfig"] = "adxwss.trace.on=on&adxwss.trace.size=16384 &adonix.trace.on=on&adonix.trace.level=3&adonix.trace.size=8";

            $result = $client->run($CContext, "YBPCINFO", $xmlInput);
            echo $result->resultXml;
        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
    }

    function YBPALST($account_code)
    {
        try {

            $wsdlUrl = 'https://api.flexibledrive.com.au:29843/adxwsvc/services/CAdxWebServiceXmlCC?wsdl';

            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );

            $client = new SoapClient($wsdlUrl, $options);

            $xmlInput = '<PARAM>
            <GRP ID="GRP1" DIM="1">
                <FLD NAME="YBPANUM" TYPE="Char">' . $account_code . '</FLD>
            </GRP>
            </PARAM>'; //BEN05

            $CContext = [];
            $CContext["codeLang"] = "ENG";
            $CContext["codeUser"] = "ADMIN";
            $CContext["password"] = "";
            $CContext["poolAlias"] = "WSFDLIVE";
            $CContext["requestConfig"] = "adxwss.trace.on=on&adxwss.trace.size=16384 &adonix.trace.on=on&adonix.trace.level=3&adonix.trace.size=8";

            $result = $client->run($CContext, "YBPALST", $xmlInput);
            echo $result->resultXml;
        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
    }

    function YPRILST($account_code)
    {
        try {

            $wsdlUrl = 'https://api.flexibledrive.com.au:29843/adxwsvc/services/CAdxWebServiceXmlCC?wsdl';

            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );

            $client = new SoapClient($wsdlUrl, $options);

            $xmlInput = '<PARAM>
            <GRP ID="GRP1" DIM="1">
                <FLD NAME="YBPCNUM" TYPE="Char">' . $account_code . '</FLD>
            </GRP>
            </PARAM>'; //BEN05

            $CContext = [];
            $CContext["codeLang"] = "ENG";
            $CContext["codeUser"] = "ADMIN";
            $CContext["password"] = "";
            $CContext["poolAlias"] = "WSFDLIVE";
            $CContext["requestConfig"] = "adxwss.trace.on=on&adxwss.trace.size=16384 &adonix.trace.on=on&adonix.trace.level=3&adonix.trace.size=8";

            $result = $client->run($CContext, "YPRILST", $xmlInput);
            echo $result->resultXml;
        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
    }

    function YWSITMQTY($account_code)
    {
        try {

            $wsdlUrl = 'https://api.flexibledrive.com.au:29843/adxwsvc/services/CAdxWebServiceXmlCC?wsdl';

            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );

            $client = new SoapClient($wsdlUrl, $options);

            $xmlInput = '<PARAM>
            <GRP ID="GRP1" DIM="1">
                <FLD NAME="ITMREF" TYPE="Char">' . $account_code . '</FLD>
            </GRP>
            </PARAM>'; //2100162B

            $CContext = [];
            $CContext["codeLang"] = "ENG";
            $CContext["codeUser"] = "ADMIN";
            $CContext["password"] = "";
            $CContext["poolAlias"] = "WSFDLIVE";
            $CContext["requestConfig"] = "adxwss.trace.on=on&adxwss.trace.size=16384 &adonix.trace.on=on&adonix.trace.level=3&adonix.trace.size=8";

            $result = $client->run($CContext, "YWSITMQTY", $xmlInput);
            echo $result->resultXml;
        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
    }

    function YBPCSEARCH($account_code)
    {
        try {

            $wsdlUrl = 'https://api.flexibledrive.com.au:29843/adxwsvc/services/CAdxWebServiceXmlCC?wsdl';

            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );

            $client = new SoapClient($wsdlUrl, $options);

            $xmlInput = '<PARAM>
            <GRP ID="GRP1" DIM="1">
                <FLD NAME="YBPCSEARCH" TYPE="Char">' . $account_code . '</FLD>
            </GRP>
            </PARAM>'; //BEN05

            $CContext = [];
            $CContext["codeLang"] = "ENG";
            $CContext["codeUser"] = "ADMIN";
            $CContext["password"] = "";
            $CContext["poolAlias"] = "WSFDLIVE";
            $CContext["requestConfig"] = "adxwss.trace.on=on&adxwss.trace.size=16384 &adonix.trace.on=on&adonix.trace.level=3&adonix.trace.size=8";

            $result = $client->run($CContext, "YBPCSEARCH", $xmlInput);
            echo $result->resultXml;
        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
    }

    public function userIsActive(Request $request)
    {

        try {

            $validator = Validator::make(request(['id']), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }

            try {
                $user = User::where('id', $request->id)->whereHas('roles', function ($query) {
                    $query->where('role_id', '3');
                })->with('roles')->first();

                if (isset($user->id) && !empty($user->id)) {
                    $data = $user->is_active;
                    return $this->sendResponse($data, "User Data");
                } else {
                    return $this->sendError("User doesn't exist.", [], 401);
                }
            } catch (JWTException $e) {
                return $this->sendError('Unauthorized', [], 401);
            }
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
