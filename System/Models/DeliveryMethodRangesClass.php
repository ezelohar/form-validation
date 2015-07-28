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
use System\Helpers\Response;
use System\Helpers\Validator;

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
	 * Store ID is hardcoded. This should be returned from logged in user
	 * @var int
	 */
	protected $store_id = 1;


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


	/**
	 * Fields description used to describe their validation rules
	 * @var array
	 */
	protected $_field_properties = array(
		'id' => array(
			'default' => 'null',
			'validators' => array(
				'int'
			)
		),
		'delivery_method_id' => array(
			'mandatory' => true,
			'validators' => array(
				'int',
			)
		),
		'store_id' => array(
			'default' => 1,
			'validators' => array()
		),
		'range_from' => array(
			'default' => 'null',
			'validators' => array(
				'float'
			)
		),
		'range_to' => array(
			'default' => 'null',
			'validators' => array(
				'float'
			)
		),
		'price' => array(
			'default' => 0,
			'validators' => array(
				'float'
			)
		),
		'active' => array(
			'default' => 1,
			'validators' => array(
				'int'
			)
		)
	);


	/**
	 *
	 * @param $data
	 */
	public function prepareVars(array $data)
	{
		$vars = $this->cleanVars(get_object_vars($this));

		foreach ($vars as $name => $val) {

			$item = Validator::getInstance()->validate($name, $this->_field_properties, $data);

			if ($item !== false) {
				$this->{$name} = $item;
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
		# get hardcoded store id
		$this->store_id = $this->getStoreID();

		$query = "SELECT * FROM " . self::TABLE_NAME. " WHERE store_id = ?";

		if ($delivery_method_id !== null) {
			$query .= ' AND delivery_method_id = ?';
		}

		$statement = $this->_db->prepare($query);

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}



		if ($delivery_method_id !== null) {
			$bind = $statement->bind_param('ii', $this->store_id, $delivery_method_id);
		} else {
			$bind = $statement->bind_param('i', $this->store_id);
		}

		if ($bind === false) {
			$response = new Response('Bind has errors: ' . htmlspecialchars($statement->error), 200, true);
			$response->toJSON();
		}

		$execute = $statement->execute();

		if ($execute === false) {
			$response = new Response('Query was unable to execute: ' . htmlspecialchars($statement->error), 200, true);
			$response->toJSON();
		}

		$results = $statement->get_result();


		return $this->result_array($results);
	}

	/**
	 * Update one row in table {delivery_method_range}
	 * @param $id
	 * @param string $table
	 * @return array
	 */
	public function fetchOne($id, $table = self::TABLE_NAME)
	{
		return parent::fetchOne($id, $table);
	}

	/**
	 * Add one row new row to table {delivery_method_range}
	 * @param array $data
	 * @return array
	 */
	public function save($data = array())
	{
		if (empty($data)) {
			$data = Input::getInstance()->post()->item();
		}
		# get hardcoded store id
		$this->store_id = $this->getStoreID();

		$this->prepareVars($data);

		$statement = $this->_db->prepare("INSERT INTO " . self::TABLE_NAME . " (`id`, `delivery_method_id`, `store_id`, `range_from`, `range_to`, `price`, `active`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		# currently hardcoded. I would use annotations to write down information about every table column
		$bind = $statement->bind_param('iidddi', $this->delivery_method_id, $this->store_id, $this->range_from, $this->range_to, $this->price, $this->active);

		if ($bind === false) {
			$response = new Response('Bind has errors: ' . htmlspecialchars($statement->error), 200, true);
			$response->toJSON();
		}

		$execute = $statement->execute();

		if ($execute === false) {
			$response = new Response('Query was unable to execute: ' . htmlspecialchars($statement->error), 200, true);
			$response->toJSON();
		}

		$lastInsertId = $statement->insert_id;

		$statement->close();

		return $this->fetchOne($lastInsertId);
	}

	/**
	 * Set active = 0 for one row in table {delivery_method_range}
	 * @param $id
	 * @param string $table
	 * @return array
	 */
	public function delete($id, $table = self::TABLE_NAME)
	{
		return parent::delete($id, $table);
	}

	/**
	 * Deactivate all ID's in provided array
	 * @param array $data
	 * @return bool
	 */
	public function deleteMany( array $data) {
		# get hardcoded store id
		$this->store_id = $this->getStoreID();


		$in = '(';
		foreach ($data as $range) {
			$in .= intval($range['id']) . ', ';
		}
		$in = substr($in, 0, -2);
		$in .= ')';

		$result = $this->_db->query("UPDATE " . self::TABLE_NAME . " SET active = 0 WHERE store_id = " . $this->store_id . " AND id IN " . $in);

		if (!$result) {
			$response = new Response('Fetch all returned error: '. $this->_db->error, 200, true);
			$response->toJSON();
		}

		return true;
	}

	/**
	 * Update one row in table {delivery_method_range}
	 * @param $id
	 * @param array $data
	 * @return array
	 */
	public function update($id, $data = array())
	{
		if (empty($data)) {
			$data = Input::getInstance()->put()->item();
			$data['id'] = $id;
		}

		# get hardcoded store id
		$this->store_id = $this->getStoreID();



		$this->prepareVars($data);


		$statement = $this->_db->prepare("UPDATE " . self::TABLE_NAME . " SET `range_from` = ? , `range_to` = ?, `price` = ?, `active` = ? WHERE id = ? AND store_id = ?");

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}


		$bind = $statement->bind_param('dddiii', $this->range_from, $this->range_to, $this->price, $this->active, $this->id, $this->store_id);

		if ($bind === false) {
			$response = new Response('Bind has errors: ' . htmlspecialchars($statement->error), 200, true);
			$response->toJSON();
		}

		$execute = $statement->execute();

		if ($execute === false) {
			$response = new Response('Query was unable to execute: ' . htmlspecialchars($statement->error), 200, true);
			$response->toJSON();
		}

		$statement->close();

		return $this->fetchOne($this->id);
	}

	/**
	 * Save more than one row
	 * @param array $data
	 * @return bool
	 */
	public function saveMany(array $data) {
		# get hardcoded store id
		$this->store_id = $this->getStoreID();


		$statement = $this->_db->prepare("INSERT INTO " . self::TABLE_NAME . " (`id`, `delivery_method_id`, `store_id`, `range_from`, `range_to`, `price`, `active`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		foreach ($data as $key=>$range) {
			$this->prepareVars($range);


			$bind = $statement->bind_param('iidddi', $this->delivery_method_id, $this->store_id, $this->range_from, $this->range_to, $this->price, $this->active);

			if ($bind === false) {
				$response = new Response('Bind has errors: ' . htmlspecialchars($statement->error), 200, true);
				$response->toJSON();
			}
			$execute = $statement->execute();

			if ($execute === false) {
				$response = new Response('Query was unable to execute: ' . htmlspecialchars($statement->error), 200, true);
				$response->toJSON();
			}
		}

		$statement->close();


		return true;
	}

	/**
	 * Update more than one row
	 * @param array $data
	 * @return bool
	 */
	public function updateMany(array $data) {
		# get hardcoded store id
		$this->store_id = $this->getStoreID();

		$statement = $this->_db->prepare("UPDATE " . self::TABLE_NAME . " SET `range_from` = ? , `range_to` = ?, `price` = ?, `active` = ? WHERE id = ? AND store_id = ?");

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		foreach ($data as $key=>$range) {
			$this->prepareVars($range);

			$bind = $statement->bind_param('dddiii', $this->range_from, $this->range_to, $this->price, $this->active, $this->id, $this->store_id);

			if ($bind === false) {
				$response = new Response('Bind has errors: ' . htmlspecialchars($statement->error), 200, true);
				$response->toJSON();
			}

			$execute = $statement->execute();

			if ($execute === false) {
				$response = new Response('Query was unable to execute: ' . htmlspecialchars($statement->error), 200, true);
				$response->toJSON();
			}

		}

		$statement->close();

		return true;
	}
}