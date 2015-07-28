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

class DeliveryMethodOptions extends Model
{

	/**
	 * Model table name
	 * @var string
	 */
	const TABLE_NAME = 'delivery_method_options';


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
	 * Options URL
	 * @var string
	 */
	protected $url;

	/**
	 * Delivery Method Name
	 * @var string
	 */
	protected $name;

	/**
	 * Options weight from
	 * @var float
	 */
	protected $weight_from;

	/**
	 * Options weight to
	 * @var float
	 */
	protected $weight_to;

	/**
	 * Notes
	 * @var string
	 */
	protected $notes;


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
			'default' => 'null',
			'validators' => array()
		),
		'url' => array(
			'default' => null,
			'validators' => array(
				'url'
			)
		),
		'name' => array(
			'default' => 'null',
			'validators' => array(
				'string'
			)
		),
		'weight_from' => array(
			'default' => 0,
			'validators' => array(
				'float'
			)
		),
		'weight_to' => array(
			'default' => 1,
			'validators' => array(
				'int'
			)
		),
		'notes' => array(
			'default' => 1,
			'validators' => array(
				'int'
			)
		)
	);


	/**
	 * Function used to validate and prepare data which will be insert into database after
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
	 * Fetch one row from table {delivery_method_options}
	 * @param $id
	 * @param string $table
	 * @return array
	 */
	public function fetchOne($id, $table = self::TABLE_NAME)
	{
		return parent::fetchOne($id, $table);
	}

	/**
	 * Save one row to to {delivery_method_options} table
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

		$statement = $this->_db->prepare("INSERT INTO " . self::TABLE_NAME . " (`id`, `delivery_method_id`, `store_id`, `url`, `name`, `weight_from`, `weight_to`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}


		$bind = $statement->bind_param('iiissdd', $this->id, $this->delivery_method_id, $this->store_id, $this->url, $this->name, $this->weight_from, $this->weight_to);

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
	 * No usage
	 * @param $id
	 * @param string $table
	 * @return array
	 */
	public function delete($id, $table = self::TABLE_NAME)
	{
		return array();
		/* option can't be deleted */
		/*return parent::delete($id, $table);*/
	}


	/**
	 * Update one row in table {delivery_method_options}
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


		$statement = $this->_db->prepare("UPDATE " . self::TABLE_NAME . " SET `url` = ? , `name` = ?, `weight_from` = ?, `weight_to` = ?, `notes` = ? WHERE id = ? AND store_id = ?");

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		$bind = $statement->bind_param('ssddsii', $this->url, $this->name, $this->weight_from, $this->weight_to, $this->notes, $this->id, $this->store_id);

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
	 * Save multiple rows in table {delivery_method_options}
	 * @param array $data
	 * @return bool
	 */
	public function saveMany(array $data) {
		# get hardcoded store id
		$this->store_id = $this->getStoreID();

		$statement = $this->_db->prepare("INSERT INTO " . self::TABLE_NAME . " (`id`, `delivery_method_id`, `store_id`, `url`, `name`, `weight_from`, `weight_to`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		foreach ($data as $key=>$range) {
			$this->prepareVars($range);


			$bind = $statement->bind_param('iiissdd', $this->id, $this->delivery_method_id, $this->store_id, $this->url, $this->name, $this->weight_from, $this->weight_to);
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
	 * Update many rows in table {delivery_method_options}
	 * @param array $data
	 * @return bool
	 */
	public function updateMany(array $data) {
		# get hardcoded store id
		$this->store_id = $this->getStoreID();

		$statement = $this->_db->prepare("UPDATE " . self::TABLE_NAME . " SET `url` = ? , `name` = ?, `weight_from` = ?, `weight_to` = ?, `notes` = ? WHERE id = ? AND store_id = ?");

		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		foreach ($data as $key=>$range) {
			$this->prepareVars($data);

			$bind = $statement->bind_param('iiissdd', $this->id, $this->delivery_method_id, $this->store_id, $this->url, $this->name, $this->weight_from, $this->weight_to);

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