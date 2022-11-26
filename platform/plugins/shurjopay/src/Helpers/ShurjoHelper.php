<?php

namespace Canopy\ShurjoPay\Helpers;

class ShurjoHelper
{
    /**
     * Generate Random string
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 40): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * get client ip
     * @return mixed|string
     */
    public static function getClientIp(): string
    {
        return $_SERVER['HTTP_CLIENT_IP'] ??
            $_SERVER['HTTP_X_FORWARDED_FOR'] ??
            $_SERVER['HTTP_X_FORWARDED'] ??
            $_SERVER['HTTP_FORWARDED_FOR'] ??
            $_SERVER['HTTP_FORWARDED'] ??
            $_SERVER['REMOTE_ADDR'] ??
            'UNKNOWN';
    }

    /**
     * Custom POST Method
     * @param $postURL
     * @param $postData
     * @param $headers
     * @return mixed
     */
    public static function httpPostMethod($postURL, $postData, $headers = null)
    {
        $url = curl_init($postURL);
        $payload = json_encode($postData);

        curl_setopt($url, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($url, CURLOPT_ENCODING, "");
        curl_setopt($url, CURLOPT_MAXREDIRS, 10);
        curl_setopt($url, CURLOPT_TIMEOUT, 0);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_HTTPHEADER, $headers ?? [
            'Content-Type: application/json'
        ]);
        $resultData = curl_exec($url);
        $resultArray = json_decode($resultData, true);
        $error = curl_error($url);
        if ($error) {
            return $error;
        }
        curl_close($url);
        return $resultArray;
    }

    /**
     * Custom GET Method
     */

    public static function httpGetMethod($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/0 (Windows; U; Windows NT 0; zh-CN; rv:3)");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        $file_contents = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);
        return json_decode($file_contents, true);
    }
}
