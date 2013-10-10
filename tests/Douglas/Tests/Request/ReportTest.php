<?php

namespace Douglas\Tests\Request;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    public function testPdfIsValidFormat()
    {
        $valid_format = \Douglas\Request\Report::getFormat('PDF');
        $this->assertEquals('pdf', $valid_format);
    }

    public function testXlsIsValidFormat()
    {
        $valid_format = \Douglas\Request\Report::getFormat('XLS');
        $this->assertEquals('xls', $valid_format);
    }

    public function testHtmlIsValidFormat()
    {
        $valid_format = \Douglas\Request\Report::getFormat('HTML');
        $this->assertEquals('html', $valid_format);
    }

    public function testInvalidFormats()
    {
        try {
            $valid_format = \Douglas\Request\Report::getFormat('PHP');
        } catch (\Exception $expected) {
            return;
        }

        $this->fail('An expected exception has not been thrown.');
    }

    public function testGetPasswordlessJasperUrlIsPasswordless()
    {
        $report = new \Douglas\Request\Report(
            array(
                'jasper_url' => 'https://jasperadmin:jasperadmin@localhost:8080/jasperserver/'
            )
        );
        $this->assertEquals('https://localhost:8080/jasperserver/', $report->getPasswordlessJasperUrl());
    }

    public function testGetUrl()
    {
        $report = new \Douglas\Request\Report(
            array(
                'report_url' => '/organizations/demo/Reports/TestReport',
                'format'     => \Douglas\Request\Report::FORMAT_HTML,
                'parameters' => array(
                    'test_param' => 'test_value'
                )
            )
        );
        $this->assertEquals(
            '/rest_v2/reports/organizations/demo/Reports/TestReport.html?test_param=test_value',
            $report->getUrl()
        );
    }

    public function testGetUrlWorksWithWeirdFormats()
    {
        $report = new \Douglas\Request\Report(
            array(
                'report_url' => '/organizations/demo/Reports/TestReport',
                'format'     => 'HtMl',
                'parameters' => array(
                    'test_param' => 'test_value'
                )
            )
        );
        $this->assertEquals(
            '/rest_v2/reports/organizations/demo/Reports/TestReport.html?test_param=test_value',
            $report->getUrl()
        );
    }

    public function testPrettyUrlIsPretty()
    {
        $report = new \Douglas\Request\Report(
            array(
                'report_url' => '/organizations/demo/Reports/TestReport',
            )
        );
        $this->assertEquals('test_report', $report->getPrettyUrl());

        $report = new \Douglas\Request\Report(
            array(
                'report_url' => '/organizations/demo/Reports/Test Report',
            )
        );
        $this->assertEquals('test_report', $report->getPrettyUrl());
    }

    public function testGetHtmlWhenFormatIsntHtml()
    {
        $report = new \Douglas\Request\Report(
            array(
                'format' => \Douglas\Request\Report::FORMAT_PDF,
            )
        );
        $this->assertEquals(false, $report->getHtml('/'));
    }

    public function testGetHtmlReplacesAssets()
    {
        $request = $this->getMock('\Douglas\Request', array('getBody'));
        $request->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('jasperserver/test_image_url'));

        $report = new \Douglas\Request\Report(
            array(
                'format'  => \Douglas\Request\Report::FORMAT_HTML,
                'request' => $request
            )
        );
        $html = $report->getHtml('images/asset.php&url=', 'jasperserver/');
        $this->assertEquals('images/asset.php&url=test_image_url', $html);
    }

    public function testRequestInterface()
    {
        $request = $this->getMock('\Douglas\Request');
        $report = new \Douglas\Request\Report(
            array(
                'request' => $request
            )
        );
        $request->expects($this->once())->method('getBody');
        $request->expects($this->once())->method('send');
        $request->expects($this->once())->method('getError');
        $request->expects($this->once())->method('isSuccessful');
        $request->expects($this->once())->method('getBody');
        $request->expects($this->once())->method('getCode');
        $request->expects($this->once())->method('getHeader');
        $request->expects($this->once())->method('getJsessionid');
        $request->expects($this->once())->method('getBody');

        $this->assertNull($report->send());
        $this->assertNull($report->getError());
        $this->assertNull($report->isSuccessful());
        $this->assertNull($report->getBody());
        $this->assertNull($report->getCode());
        $this->assertNull($report->getHeader());
        $this->assertNull($report->getJsessionid());
    }
}
