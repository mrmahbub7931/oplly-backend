<?php

namespace Canopy\Bkash\Helpers;

class BkashHelper
{
 
    /**
     * Custom POST Method
     * @param $PostURL
     * @param $PostData
     * @return mixed
     */
    public static function tokenPostMethod($PostURL,$PostData)
    {
        // dd($PostData);
        $username = config('bkash.username');
        $password = config('bkash.password');
        $url = curl_init($PostURL);
        $posttoken = json_encode($PostData);
        $header = array(
            "Content-Type:application/json",
            "username:{$username}",               
            "password:{$password}"
        );

        curl_setopt($url,CURLOPT_HTTPHEADER, $header);
        curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url,CURLOPT_POSTFIELDS, $posttoken);
        curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $resultdata = curl_exec($url);
        $ResultArray = json_decode($resultdata, true);
        curl_close($url);

        return $ResultArray;
    }

    /**
     * Custom POST Method
     * @param $PostURL
     * @param $PostData
     * @return mixed
     */
    public static function createPostMethod($PostURL,$PostData)
    {
        $app_key = config('bkash.appKey');
        $url = curl_init($PostURL);
        $requestbodyJson = json_encode($PostData);
        $auth = $_SESSION['btoken'];

        $header = array(
            "Content-Type:application/json",
            "Authorization:{$auth}",
            "x-app-key:{$app_key}"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $requestbodyJson);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        $arrObj = json_decode($resultdata,true);
        curl_close($url);
        return $arrObj;
    }

    /**
     * Custom POST Method
     * @param $PostURL
     * @param $PostData
     * @return mixed
     */
    public static function executePostMethod($PostURL,$PostData)
    {
        session_start();
        $app_key = config('bkash.appKey');
        $url = curl_init($PostURL);
        $posttoken  = json_encode($PostData);
        $auth = $_SESSION['btoken'];

        $header = array(
            "Content-Type:application/json",
            "Authorization:{$auth}",
            "x-app-key:{$app_key}"
        );
        
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $posttoken);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        $arrObj = json_decode($resultdata,true);
        curl_close($url);
        
        return $arrObj;
    }
}