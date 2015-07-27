<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 7/24/15
 * Time: 6:54 PM
 *
 *
 * Create an API response used across application
 */

namespace System\Helpers;


class Response
{

	/**
	 * @var array
	 */
	private $_response = array();

	/**
	 * @param $data
	 * @param int $status
	 * @param bool|false $error
	 * @param string $description
	 */
	public function __construct($data, $status = 200, $error = false, $description = 'Valid response')
	{
		$this->_response['data'] = $data;
		$this->_response['status'] = $status;
		$this->_response['error'] = $error;
		$this->_response['description'] = $description;
	}

	/* Regular data *


	/**
	 *
	 * Set response status for current message.
	 * @param integer $status
	 * return null
	 */
	public function setStatus( $status ) {
		$this->_response['status'] = $status;
	}

	/**
	 *
	 * Set response description. This can be used to display some custom messages or for some other matters.
	 * @param $description
	 * return null
	 */
	public function setDescription( $description ) {
		$this->_response['description'] = $description;
	}

	/**
	 * Add single data to response array
	 * @param mixed $data
	 * return null
	 */
	public function addSingleData( $data ) {
		$this->_response['data'][] = $data;
	}

	/**
	 * Add data collection to response array. Merge or overwrite with existings data
	 * @param array $data
	 * return null
	 */
	public function addCollectionData( array $data, $overwrite = false ) {

		/* if $this->_response not array, then overwrite it with new content */
		if (!isset($this->_response['data']) || !is_array($this->_response['data'])) {
			$overwrite = true;
		}

		if (!$overwrite) {
			$this->_response['data'] = array_merge( $this->_response['data'], $data);
		} else {
			$this->_response['data'] = $data;
		}
	}

	/**
	 * set error true or false
	 */
	public function setError($error) {
		$this->_response['error'] = $error;
	}


	/**
	 * Print response or return value;
	 * @param bool|true $die
	 * @return string
	 */
	public function toJSON($die = true)
	{
		if ($die) {
			header('Content-Type: application/json; charset=utf-8');
			$return = json_encode($this->_response, JSON_UNESCAPED_UNICODE);
			if (!$return) {
				die(json_last_error_msg());
			}
			die($return);
		}
		return json_encode($this->_response);
	}

}

