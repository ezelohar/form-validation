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

	protected $_table_name;
	/**
	 * connected database object
	 * @var Database
	 */
	protected $_db;

	public function __construct()
	{
		$this->_db = Database::getInstance()->getDB();
	}

	protected $_validate = array();


	protected function cleanVars($vars) {
		$returnVars = array();
		foreach($vars as $key=>$val) {
			if ($key[0] !== '_') {
				$returnVars[$key] = $val;
			}
		}

		return $returnVars;
	}


	public function fetchOne($id, $table)
	{
		$query = "SELECT * FROM " . $table;
		$query .= ' WHERE id = ?';

		$preparedObj = $this->_db->prepare($query);
		$preparedObj->bind_param('i', $id);
		$preparedObj->execute();

		$results = $preparedObj->get_result();

		return $this->result_array($results);
	}


	public function delete($id, $table) {
		$query = "UPDATE " . $table;
		$query .= ' SET active = 0 WHERE id = ?';

		$preparedObj = $this->_db->prepare($query);
		$preparedObj->bind_param('i', $id);
		$preparedObj->execute();

		$results = $preparedObj->get_result();

		return $this->result_array($results);
	}

	abstract public function prepareVars($data);


	/**
	 *
	 * @param $result mysqli resource object
	 * @return array
	 */
	public function result_array($result)
	{
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}

}