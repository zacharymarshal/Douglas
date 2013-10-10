<?php

namespace Douglas\Tests;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    protected static $successful_request;
    protected static $failed_request;

    public static function setUpBeforeClass()
    {
        self::$successful_request = new \Douglas\Request(
            array(
                'jasper_url' => getJasperUrl(),
                'url'        => '/rest_v2/reports/organizations/dna_demo3/Reports/Test/Bad_Parameter.html',
            )
        );
        self::$failed_request = new \Douglas\Request(
            array(
                'jasper_url' => getJasperUrl(),
                'url'        => '/rest_v2/reports/organizations/dna_demo3/Reports/'
                    . 'Test/Bad_Parameter.html?academic_year=',
            )
        );
    }

    public function testSendDoesntMakeMultipleRequests()
    {
        $return = self::$successful_request->send();
        $second_return = self::$successful_request->send();
        $this->assertFalse($second_return);
    }

    public function testIsSuccessful()
    {
        self::$successful_request->send();
        self::$failed_request->send();

        $this->assertTrue(self::$successful_request->isSuccessful());
        $this->assertFalse(self::$failed_request->isSuccessful());
    }

    public function testGetError()
    {
        self::$successful_request->send();
        self::$failed_request->send();

        $this->assertFalse(self::$successful_request->getError());
        $this->assertEquals(
            array(
                'code'    => 'unexpected.error',
                'message' => 'Unexpected error',
                'more'    => 'java.lang.NumberFormatException',
            ),
            self::$failed_request->getError()
        );
    }

    public function testGetCode()
    {
        self::$successful_request->send();
        self::$failed_request->send();

        $this->assertEquals(200, self::$successful_request->getCode());
        $this->assertEquals(500, self::$failed_request->getCode());
    }

    public function testGetHeader()
    {
        self::$successful_request->send();

        $this->assertCount(9, self::$successful_request->getHeader());
        $this->assertEquals('text/html', self::$successful_request->getHeader('content-type'));
    }

    public function testGetJsessionid()
    {
        self::$successful_request->send();

        $this->assertNotEmpty(self::$successful_request->getJsessionid());
    }

    public function testSendingJsessionId()
    {
        $first_request = new \Douglas\Request(
            array(
                'jasper_url' => getJasperUrl(),
                'url'        => '/rest_v2/reports/organizations/dna_demo3/Reports/Test/Bad_Parameter.html',
            )
        );
        $first_request->send();

        $request = new \Douglas\Request(
            array(
                'jasper_url' => getJasperUrl(),
                'jsessionid' => $first_request->getJsessionid(),
                'url'        => '/rest_v2/reports/organizations/dna_demo3/Reports/Test/Bad_Parameter.html',
            )
        );
        $request->send();
        $this->assertTrue($request->isSuccessful());
        $this->assertFalse($request->getJsessionid(), 'Jasper doesnt send back a jsession id if one is passed.');
    }
}
