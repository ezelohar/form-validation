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

	protected $store_id = 1;

	public function __construct()
	{
		$this->_db = Database::getInstance()->getDB();
	}


	protected function getStoreID() {
		return $this->store_id;
	}


	/**
	 * Takes array of class variables and return only those who are used as an table columns
	 * @param $vars
	 * @return array
	 */
	protected function cleanVars(array $vars) {
		$returnVars = array();
		foreach($vars as $key=>$val) {
			if ($key[0] !== '_') {
				$returnVars[$key] = $val;
			}
		}

		return $returnVars;
	}

	/**
	 * Fetch one item from Databsase based on it's ID
	 * @param $id
	 * @param $table
	 * @return array
	 */
	public function fetchOne($id, $table)
	{
		$query = "SELECT * FROM " . $table;
		$query .= ' WHERE id = ? AND store_id = ?';

		$preparedObj = $this->_db->prepare($query);
		$preparedObj->bind_param('ii', $id, $this->store_id);
		$preparedObj->execute();

		$results = $preparedObj->get_result();

		return $this->result_array($results);
	}

	/**
	 * Set row's active field to 0. We don't delete rows from database for sake of keeping it consistent
	 * @param $id
	 * @param $table
	 * @return array
	 */
	public function delete($id, $table) {
		$query = "UPDATE " . $table;
		$query .= ' SET active = 0 WHERE id = ? AND store_id = ?';

		$preparedObj = $this->_db->prepare($query);
		$preparedObj->bind_param('ii', $id, $this->store_id);
		$preparedObj->execute();

		$results = $preparedObj->get_result();

		return $this->result_array($results);
	}

	/**
	 * Function used to validate and prepare data which will be insert into database after
	 * @param $data
	 */
	abstract protected function prepareVars(array $data);


	/**
	 * Return array of results
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