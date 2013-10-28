<?php

namespace Douglas\Request;

class Maker
{
    public static function send($url, $jsessionid)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        // Force JSON to be returned instead of XML
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        if ($jsessionid) {
            curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID={$jsessionid}");
        }
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        curl_close($ch);
        return array($response, $header_size);
    }
}
