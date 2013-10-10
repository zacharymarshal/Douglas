<?php

namespace Douglas\Tests\Request\Report;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorHtml()
    {
        $report = $this->getMock('\Douglas\Request\Report');
        $report->parameters = array('test_param' => 'test_value');
        $error = new \Douglas\Request\Report\Error(
            $report,
            array(
                'code'    => 500,
                'message' => 'Unexpected Error',
                'more'    => 'Some more reasons why this would fail',
            )
        );
        $this->assertNotEmpty($error->getHtml());
    }

    public function testWhenNoError()
    {
        $report = $this->getMock('\Douglas\Request\Report');
        $report->parameters = array('test_param' => 'test_value');
        $error = new \Douglas\Request\Report\Error($report, array());
        $this->assertEmpty($error->getHtml());
    }

    public function testHasDebuggingInformation()
    {
        $report = $this->getMock('\Douglas\Request\Report');
        $report->parameters = array('test_param' => 'test_value');
        $error = new \Douglas\Request\Report\Error(
            $report,
            array(
                'code'    => 500,
                'message' => 'Unexpected Error',
                'more'    => 'Some more reasons why this would fail',
            )
        );
        $this->assertContains('Report Debugging', $error->getHtml());
    }
}
