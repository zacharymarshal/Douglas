<?php

namespace Douglas\Request\Report;

class Debug
{
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function getHtml()
    {
        $report_parameters = $this->report->parameters;
        ksort($report_parameters);
        $report_parameter_html = array();
        foreach ($report_parameters as $key => $value) {
            $report_parameter_html[] = "<p><pre><strong>{$key}:</strong> {$value}</pre></p>";
        }
        $report_parameter_html = implode("\n", $report_parameter_html);

        return <<<HTML
<h2>Report Debugging</h2>
<p><pre><strong>Jasper URL:</strong> {$this->report->getPasswordlessJasperUrl()}</pre></p>
<p><pre><strong>Report URL:</strong> {$this->report->report_url}</pre></p>
<p><pre><strong>Pretty URL:</strong> {$this->report->getPrettyUrl()}</pre></p>
<h3>Parameters</h3>
{$report_parameter_html}
<h3>Request</h3>
<p><pre>{$this->report->getUrl()}</pre></p>
HTML;
    }
}
