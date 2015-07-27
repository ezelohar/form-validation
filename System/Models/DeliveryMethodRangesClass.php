<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 7/26/15
 * Time: 12:37 AM
 */

namespace System\Models;


use System\Core\Model;
use System\Core\Database;
use System\Helpers\Input;

class DeliveryMethodRanges extends Model
{

	/**
	 * Model table name
	 * @var string
	 */
	const TABLE_NAME = 'delivery_method_ranges';


	/**
	 * connected database object
	 * @var Database
	 */
	protected $_db;

	/**
	 * Options id
	 * @var int
	 */
	protected $id;

	/**
	 * Id of delivery method
	 * @var int
	 */
	protected $delivery_method_id;


	/**
	 * Range from
	 * @var float
	 */
	protected $range_from;

	/**
	 * Range to
	 * @var float
	 */
	protected $range_to;

	/**
	 * Price for specific range
	 * @var float
	 */
	protected $price;

	/**
	 * is element active
	 * @var bool
	 */
	protected $active;

	public function prepareVars($data) {
		$vars = $this->cleanVars(get_object_vars($this));

		foreach ($vars as $name=>$val) {
			if (!isset($data[$name])) {
				$this[$name] = null;
			} else {
				$this[$name] = $data[$name];
			}
		}
	}

	/**
	 * Fetch all options for delivery method
	 * @return mixed
	 */
	public function fetchAll()
	{
		$delivery_method_id = Input::getInstance()->get()->item('delivery_method_id');

		$query = "SELECT * FROM " . self::TABLE_NAME;

		if ($delivery_method_id !== null) {
			$query .= ' WHERE delivery_method_id = ?';
		}

		$preparedObj = $this->_db->prepare($query);
		if ($delivery_method_id !== null) {
			$preparedObj->bind_param('i', $delivery_method_id);
		}

		$preparedObj->execute();

		$results = $preparedObj->get_result();

		$preparedObj->close();


		return $this->result_array($results);
	}

	public function fetchOne($id, $table = self::TABLE_NAME)
	{
		return parent::fetchOne($id, $table);
	}

	public function save($data = array())
	{
		if (empty($data)) {
			$data = Input::getInstance()->post()->item();
		}

		$this->prepareVars($data);

		$statement = $this->_db->prepare("INSERT INTO " . self::TABLE_NAME . ' (`id`, `delivery_method_id`, `range_from`, `range_to`, `price`, `active`)');

		# currently hardcoded. I would use annotations to write down information about every table column
		$statement->bind_param('iissdd', $this->id, $this->delivery_method_id, $this->range_from, $this->range_to, $this->price, $this->active);
		$statement->execute();

		$lastInsertId = $statement->insert_id;

		$statement->close();

		return $this->fetchOne($lastInsertId);
	}

	public function delete($id, $table = self::TABLE_NAME)
	{
		return parent::delete($id, $table);
	}

	public function update($id, $data = array())
	{
		if (empty($data)) {
			$data = Input::getInstance()->put()->item();
			$data['id'] = $id;
		}

		$this->prepareVars($data);


		$statement = $this->_db->prepare("UPDATE " . self::TABLE_NAME . " SET `range_from` = ? , `range_to` = ?, `price` = ?, `active` = ? WHERE id = ?");
		$statement->bind_param('iidi', $this->range_from, $this->range_to, $this->price, $this->active, $this->id);
		$statement->execute();


		$statement->close();

		return $this->fetchOne($this->id);
	}
}