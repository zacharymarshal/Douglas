<?php

namespace Douglas\Tests;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    protected function getSuccessfulRequest()
    {
        $request = new \Douglas\Request(
            array(
                'jasper_url' => 'https://jasperadmin:jasperadmin@localhost:8080/jasperserver/',
                'url'        => 'some_report.html',
                'maker'      => 'Douglas\Tests\Request\StubMaker'
            )
        );
        return $request;
    }

    protected function getErrorRequest()
    {
        $request = new \Douglas\Request(
            array(
                'jasper_url' => 'https://jasperadmin:jasperadmin@localhost:8080/jasperserver/',
                'url'        => 'some_report.html',
                'maker'      => 'Douglas\Tests\Request\StubMakerError'
            )
        );
        return $request;
    }

    public function testIsSuccessful()
    {
        $request = $this->getSuccessfulRequest()->send();
        $this->assertTrue($request->isSuccessful());
    }

    public function testGetJsessionid()
    {
        $request = $this->getSuccessfulRequest()->send();
        $this->assertEquals('13F69B2661E0E723559293E719947D04', $request->getJsessionid());
    }

    public function testGetCode()
    {
        $request = $this->getSuccessfulRequest()->send();
        $this->assertEquals(200, $request->getCode());
    }

    public function testGetHeader()
    {
        $request = $this->getSuccessfulRequest()->send();
        $this->assertCount(9, $request->getHeader());
        $this->assertEquals('text/html', $request->getHeader('content-type'));
    }

    public function testGetBody()
    {
        $request = $this->getSuccessfulRequest()->send();
        $this->assertEquals('<html></html>', $request->getBody());
    }

    public function testSend()
    {
        $request = $this->getSuccessfulRequest()->send();
        $this->assertInstanceOf('\Douglas\Request', $request);
    }

    public function testGetErrorForSuccessfulRequest()
    {
        $request = $this->getSuccessfulRequest()->send();
        $this->assertFalse($request->getError());
    }

    public function testGetError()
    {
        $request = $this->getErrorRequest()->send();
        $this->assertEquals(
            array(
                'code'    => 'unexpected.error',
                'message' => 'Unexpected error',
                'more'    => 'java.lang.NumberFormatException',
            ),
            $request->getError()
        );
    }
}
