<?php

namespace Douglas\Tests\Request\Report;

class DebugTest extends \PHPUnit_Framework_TestCase
{
    public function testInformation()
    {
        $report = $this->getMock('\Douglas\Request\Report');
        $report->parameters = array('test_param' => 'test_value');
        $debug = new \Douglas\Request\Report\Debug($report);
        $this->assertNotEmpty($debug->getHtml());
    }

    public function testHasParameters()
    {
        $report = $this->getMock('\Douglas\Request\Report');
        $report->parameters = array('test_param' => 'test_value');
        $debug = new \Douglas\Request\Report\Debug($report);
        $debug_html = $debug->getHtml();
        $this->assertContains('test_param', $debug_html);
        $this->assertContains('test_value', $debug_html);
    }
}
