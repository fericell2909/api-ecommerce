<?php

namespace App\Modules\Api;

class ApiResponse
{
	private $_success = true;
	private $_data = null;
	private $_error = null;
	private $_errorCode = null;
	private $_status = 200;

	public function __construct($data = null)
	{
		$this->_data = $data ?: [];
	}

	public function data($key, $value = null)
	{
		if (is_array($key)) {
			$this->_data = array_merge($this->_data, $key);
		} else {
			$this->_data[$key] = $value;
		}
		return $this;
	}

	public function error($error, $errorCode = null)
	{
		$this->_error = $error;
		$this->_errorCode = $errorCode;
		$this->_success = false;
		if ($this->_status == 200) $this->_status = 400;
		return $this;
	}

	public function errorCode($errorCode)
	{
		$this->_errorCode = $errorCode;
		$this->_success = false;
		if ($this->_status == 200) $this->_status = 400;
		return $this;
	}

	public function status($status)
	{
		$this->_status = $status;
		return $this;
	}

	public function json()
	{
		$obj = [
			'success' => $this->_success,
			'status' => $this->_status,
			'data' => $this->_data,
		];
		if ($this->_error) {
			$obj['error'] = $this->_error;
		}
		if ($this->_errorCode) {
			$obj['errorCode'] = $this->_errorCode;
		}
		return response()->json($obj, $this->_status);
	}
}
