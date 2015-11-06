<?php

namespace Douglas\Tests\Request;

class StubMakerError extends \Douglas\Request\Maker
{
    public static function send($url, $jsessionid, $backend = null)
    {
        $response = <<<JSON
HTTP/1.1 500 Internal Server Error
Server: Apache-Coyote/1.1
Pragma: No-cache
Cache-Control: no-cache
Expires: Wed, 31 Dec 1969 16:00:00 PST
P3P: CP="ALL"
Set-Cookie: JSESSIONID=DB43F9A66F176FB4D1A9B898AE0D6AD5; Path=/jasperserver-pro; BACKEND=56eda9221030a9e7aba7ff117ba969e4; Secure
Set-Cookie: userLocale=en_US;Expires=Fri, 18-Oct-2013 20:13:36 GMT;HttpOnly
Content-Type: application/json
Transfer-Encoding: chunked
Date: Thu, 17 Oct 2013 20:13:36 GMT
Connection: close

{"message":"Unexpected error","errorCode":"unexpected.error","parameters":["java.lang.NumberFormatException"]}
JSON;
        $header_size = 445;
        return array($response, $header_size);
    }
}
