<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use SoapClient;
use SoapFault;

class SageAPIRepository extends BaseRepository
{
    function getCustomerInformation($account_code, $type)
    {

        try {

            $wsdlUrl = config('constant.WSDL_URL');
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

            $result = $client->run($CContext, $type, $xmlInput);

            $result_xml_object = simplexml_load_string($result->resultXml);
            $result_json = json_encode($result_xml_object);
            $result_array = json_decode($result_json, true);
            $price_data = isset(array_column($result_array, 'LIN')[0]) ? array_column($result_array, 'LIN')[0] : false;

            return $price_data;
        } catch (SoapFault $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function getProductQuantity($sku)
    {
        try {

            $wsdlUrl = config('constant.WSDL_URL');
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
                                <FLD NAME="ITMREF" TYPE="Char">' . $sku . '</FLD>
                            </GRP>
                        </PARAM>';

            $CContext = [];
            $CContext["codeLang"] = "ENG";
            $CContext["codeUser"] = "ADMIN";
            $CContext["password"] = "";
            $CContext["poolAlias"] = "WSFDLIVE";
            $CContext["requestConfig"] = "adxwss.trace.on=on&adxwss.trace.size=16384 &adonix.trace.on=on&adonix.trace.level=3&adonix.trace.size=8";

            $result = $client->run($CContext, "YWSITMQTY", $xmlInput);
            $result_xml_object = simplexml_load_string($result->resultXml);
            $result_json = json_encode($result_xml_object);
            $result_array = json_decode($result_json, true);
            $qty_data = isset(array_column($result_array, 'LIN')[0]) ? array_column($result_array, 'LIN')[0] : false;
            if ($qty_data && array_key_exists("FLD", $qty_data)) {
                $qty_data = array($qty_data);
            }

            return $qty_data;
        } catch (SoapFault $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
