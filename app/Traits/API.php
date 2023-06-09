<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;

trait API
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function callPartsDBAPI($api, $params = array())
    {
        if (is_array(Session::get('Auth-Cookie'))) {
            $auth_cookie = Session::get('Auth-Cookie')[1];
        } else {
            $auth_cookie = Session::get('Auth-Cookie');
        }
        $url = config('partsdb.endpoint') . $api;
        $response = Curl::to($url)
            ->withData($params)
            ->withHeader('cookie: ' . $auth_cookie)
            ->get();

        return json_decode($response);
    }
}
