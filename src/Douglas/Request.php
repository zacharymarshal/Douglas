<?php

namespace Douglas;

class Request
{
	protected $url;
	protected $jasper_url;
	protected $jsessionid;
	
	protected $headers = array();
	protected $code;
	protected $body;

	public function __construct($options = array())
	{
		$url = $jasper_url = $jsessionid = null;
		extract($options, EXTR_IF_EXISTS);
		$this->url = $url;
		$this->jasper_url = $jasper_url;
		$this->jsessionid = $jsessionid;
	}

	public function send()
	{
		// Request has already been sent
		if (isset($this->body)) {
			return FALSE;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->getUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		
		// Force JSON to be returned instead of XML
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

		if ($this->jsessionid) {
			curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID={$this->jsessionid}");
		}
		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);

		$this->headers = $this->parseHeader($header);
		$this->code = $this->parseCode($header);
		$this->jsessionid = $this->parseJsessionid($header);
		$this->body = substr($response, $header_size);

		curl_close($ch);

		return TRUE;
	}

	public function getError()
	{
		if ($this->isSuccessful()) {
			return false;
		}
		$jasper_error = json_decode($this->getBody(), true);
		return array(
			'code'    => $jasper_error['errorCode'],
			'message' => $jasper_error['message'],
			'more'    => implode(', ', $jasper_error['parameters']),
		);
	}

	public function isSuccessful()
	{
		return ($this->code == 200);
	}

	public function getBody()
	{
		return $this->body;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getHeader($header = null)
	{
		if ( ! $header) {
			return $this->headers;
		}
		return (isset($this->headers[$header]) ? $this->headers[$header] : false);
	}

	public function getJsessionid()
	{
		return $this->jsessionid;
	}

	protected function getUrl()
	{
		$url = ltrim($this->url, '/');
		$jasper = rtrim($this->jasper_url, '/');
		return "{$jasper}/{$url}";
	}

	protected function parseCode($header)
	{
		$parts = explode(' ', substr($header, 0, strpos($header, "\r\n")));
		return intval($parts[1]);
	}

	protected function parseHeader($header)
	{
		$lines = preg_split("/(\r|\n)+/", $header, -1, PREG_SPLIT_NO_EMPTY);
		array_shift($lines); // HTTP HEADER
		$headers = array();
		foreach ($lines as $line) {
			list($name, $value) = explode(':', $line, 2);
			$name = strtolower(trim($name));
			$value = trim($value);
			if (array_key_exists($name, $headers)) {
				$headers[$name] .= ",{$value}";
			} else {
				$headers[$name] = $value;
			}
		}
		return $headers;
	}

	protected function parseJsessionid($header)
	{
		preg_match("/JSESSIONID=(\S+);/", $header, $cookie);
		return (isset($cookie[1]) ? $cookie[1] : false);
	}
}