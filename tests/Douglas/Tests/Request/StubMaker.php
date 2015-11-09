<?php

namespace Douglas\Tests\Request;

class StubMaker extends \Douglas\Request\Maker
{
    public static function send($url, $jsessionid, $backend = null)
    {
        $response = "HTTP/1.1 200 OK
Server: Apache-Coyote/1.1
Pragma: No-cache
Cache-Control: no-cache
Expires: Wed, 31 Dec 1969 16:00:00 PST
P3P: CP=\"ALL\"
Set-Cookie: JSESSIONID=13F69B2661E0E723559293E719947D04; Path=/jasperserver-pro; BACKEND=56eda9221030a9e7aba7ff117ba969e4; Secure
Set-Cookie: userLocale=en_US;Expires=Fri, 18-Oct-2013 19:42:12 GMT;HttpOnly
Content-Type: text/html
Content-Length: 3210
Date: Thu, 17 Oct 2013 19:42:13 GMT

<html></html>";
        $header_size = 424;
        return array($response, $header_size);
    }
}
