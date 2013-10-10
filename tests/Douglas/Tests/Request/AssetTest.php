<?php

namespace Douglas\Tests\Request;

class AssetTest extends \PHPUnit_Framework_TestCase
{
	public function testBasicSetup()
	{
		$asset = new \Douglas\Request\Asset(array(
			'asset_url'  => 'some_asset_in_jasper',
			'jasper_url' => 'https://jasperadmin:jasperadmin@localhost:8080/jasperserver/',
			'jsessionid' => 'ABCDE12345',
		));
		$this->assertInstanceOf('\Douglas\Request\Asset', $asset);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testOutputtingAnAsset()
	{
		$request = $this->getMock('\Douglas\Request');
		$request->expects($this->any())->method('getBody')->will($this->returnValue('<html></html>'));
		$request->expects($this->any())->method('send');
		$request->expects($this->any())->method('getHeader');

		$asset = new \Douglas\Request\Asset(array(
			'request' => $request
		));
		
		ob_start();
		$asset->output();
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertEquals('<html></html>', $output);
	}

	public function testRequestInterface()
	{
		$request = $this->getMock('\Douglas\Request');
		$asset = new \Douglas\Request\Asset(array(
			'request' => $request
		));
		$request->expects($this->once())->method('getBody');
		$request->expects($this->once())->method('send');
		$request->expects($this->once())->method('getError');
		$request->expects($this->once())->method('isSuccessful');
		$request->expects($this->once())->method('getBody');
		$request->expects($this->once())->method('getCode');
		$request->expects($this->once())->method('getHeader');
		$request->expects($this->once())->method('getJsessionid');
		$request->expects($this->once())->method('getBody');

		$this->assertNull($asset->send());
		$this->assertNull($asset->getError());
		$this->assertNull($asset->isSuccessful());
		$this->assertNull($asset->getBody());
		$this->assertNull($asset->getCode());
		$this->assertNull($asset->getHeader());
		$this->assertNull($asset->getJsessionid());
	}
}