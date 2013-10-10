<?php

namespace Douglas\Request;

class Report
{
	const FORMAT_HTML = 'html';
	const FORMAT_PDF = 'pdf';
	const FORMAT_XLS = 'xls';

	public $jasper_url;
	public $report_url;
	public $parameters;
	public $format;

	protected $jsessionid;
	protected $request;

	public function __construct($options = array())
	{
		$request = $jasper_url = $jsessionid = null;
		$report_url = null;
		$format = self::FORMAT_HTML;
		$parameters = array();
		extract($options, EXTR_IF_EXISTS);
		$this->jsessionid = $jsessionid;
		$this->jasper_url = $jasper_url;
		$this->report_url = $report_url;
		$this->parameters = $parameters;
		$this->format = $format;
		
		if ( ! $request) {
			$request = new \Douglas\Request(array(
				'url'        => $this->getUrl(),
				'jasper_url' => $this->jasper_url,
				'jsessionid' => $this->jsessionid,
			));
		}
		$this->request = $request;
	}

	public function getPasswordlessJasperUrl()
	{
		return preg_replace('/\/\/.*@/', '//', $this->jasper_url);
	}

	public function getUrl()
	{
		$url_params = http_build_query($this->parameters);
		$report_url = ltrim($this->report_url, '/');
		$format = self::getFormat($this->format);
		return "/rest_v2/reports/{$report_url}.{$format}?{$url_params}";
	}

	public function getPrettyUrl()
	{
		$pretty = explode('/', $this->report_url);
		$pretty = end($pretty);
		// Remove spaces
		$pretty = trim($pretty);
		// First replace spaces with underscores
		$pretty = str_replace(' ', '_', $pretty);
		// Then change camel case to underscore, i.e., TestCase -> test_case
		$pretty = strtolower($pretty[0]) . substr($pretty, 1);
		$quoted_sep = preg_quote('_', '|');
		$search = "|([A-Z])([^A-Z]+)|e";
		$replace = '"' . $quoted_sep . '" . strtolower("\\1") . "\\2"';
		$pretty = preg_replace($search, $replace, $pretty);
		// Then make everything lowercase
		$pretty = strtolower($pretty);
		// Replace any duplicate underscores
		$pretty = preg_replace('/_+/', '_', $pretty);
		return $pretty;
	}

	public function getHtml($asset_path, $base_url = '/jasperserver')
	{
		if ($this->format !== self::FORMAT_HTML) {
			return false;
		}
		$body = str_replace($base_url, $asset_path, $this->getBody());
		return $body;
	}

	public function send()
	{
		return $this->request->send();
	}

	public function getError()
	{
		return $this->request->getError();
	}

	public function isSuccessful()
	{
		return $this->request->isSuccessful();
	}

	public function getBody()
	{
		return $this->request->getBody();
	}

	public function getCode()
	{
		return $this->request->getCode();
	}

	public function getHeader($header = null)
	{
		return $this->request->getHeader($header);
	}

	public function getJsessionid()
	{
		return $this->request->getJsessionid();
	}

	public static function getFormat($format)
	{
		$format = strtolower($format);
		$allowed_formats = array(
			self::FORMAT_HTML,
			self::FORMAT_XLS,
			self::FORMAT_PDF
		);
		if ( ! in_array($format, $allowed_formats)) {
			throw new \Exception('Invalid format.');
		}
		return $format;
	}
}