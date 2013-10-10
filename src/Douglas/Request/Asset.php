<?php

namespace Douglas\Request;

class Asset
{
	protected $jsessionid;
	protected $jasper_url;
	protected $asset_url;

	protected $request;

	public function __construct($options = array())
	{
		$request = $jasper_url = $jsessionid = $asset_url = null;
		extract($options, EXTR_IF_EXISTS);
		$this->jsessionid = $jsessionid;
		$this->jasper_url = $jasper_url;
		$this->asset_url = $asset_url;
		if ( ! $request) {
			$request = new \Douglas\Request(array(
				'url'        => $this->asset_url,
				'jasper_url' => $this->jasper_url,
				'jsessionid' => $this->jsessionid,
			));
		}
		$this->request = $request;
	}

	public function output()
	{
		$this->send();
		header("Content-Type: {$this->getHeader('content-type')}");
		header("Content-Length: {$this->getHeader('content-length')}");
		echo $this->getBody();
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
}