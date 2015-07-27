<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 7/26/15
 * Time: 1:00 PM
 */

namespace System\Helpers;

class Input
{
	/**
	 * Static instance for singleton class
	 * @var Input
	 */
	private static $instance;

	/**
	 * Contains info about method we are looking data for
	 * @var string
	 */
	private $_method;


	/**
	 * If item is null return all data from variable. If $defValue is set, return it if input param doesn't have any value
	 * @param null $item
	 * @param null $defValue
	 * @return array|null
	 */
	public function item($item = null, $defValue = null)
	{
		switch ($this->_method) {
			case 'GET':
				$params = (isset($_GET)) ? $_GET : array();
				break;
			case 'POST':
				$params = (isset($_POST)) ? $_POST : array();
				break;
			case 'PUT':
				$_SERVER['REQUEST_METHOD']==="PUT" ? parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_PUT): $_PUT=array();
				$params = $_PUT;
				break;
			case 'DELETE':
				$_SERVER['REQUEST_METHOD']==="DELETE" ? parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_DELETE): $_DELETE = array();
				$params = $_DELETE;
				break;
		}

		if ($item === null) {
			return $params;
		} else {
			return (isset($params[$item])) ? $params[$item] : (($defValue !== null) ? $defValue : null);
		}
	}

	/**
	 * Set method to get
	 * @return Input
	 */
	public function get() {
		$this->_method = 'GET';
		return $this;
	}

	/**
	 * Set Method to post
	 * @return Input
	 */
	public function post() {
		$this->_method = 'POST';
		return $this;
	}

	/**
	 * Set Method to Put
	 * @return Input
	 */
	public function put() {
		$this->_method = 'put';
		return $this;
	}

	/**
	 * Set Method to Delete
	 * @return Input
	 */
	public function delete() {
		$this->_method = 'delete';
		return $this;
	}

	/**
	 * @return Input
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}