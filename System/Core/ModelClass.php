<?php
/**
 * Abstract class to initialize models
 * User: ezelohar
 * Date: 7/25/15
 * Time: 9:50 PM
 */

namespace System\Core;

abstract class Model
{
	/**
	 * connected database object
	 * @var Database
	 */
	protected $_db;

	public function __construct() {
		$this->_db = Database::getInstance()->getDB();
	}

	protected $_validate = array();


	public function validate()
	{

	}


	/**
	 *
	 * @param $result mysqli resource object
	 * @return array
	 */
	public function result_array($result) {
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}

}