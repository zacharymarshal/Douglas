<?php

namespace Douglas\Request\Report;

class Error
{
    protected $report;
    protected $error;

    public function __construct($report, $error = array())
    {
        $this->report = $report;
        $this->error = $error;
    }

    public function getHtml()
    {
        if ( ! $this->error) {
            return '';
        }

        $message = trim($this->error['message']);
        $code = trim($this->error['code']);
        $more = trim($this->error['more']);
        $debug = new Debug($this->report);

        return <<<HTML
<h2>Error</h2>
<p><pre><strong>Code:</strong> {$code}</pre></p>
<p><pre><strong>Message:</strong> {$message}</pre></p>
<p><pre><strong>More:</strong> {$more}</pre></p>
{$debug->getHtml()}
HTML;
    }
}
