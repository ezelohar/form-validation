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

class DeliveryMethod extends Model
{

	/**
	 * Model table name
	 * @var string
	 */
	const TABLE_NAME = 'delivery_method';

	/**
	 * connected database object
	 * @var Database
	 */
	protected $_db;


	/**
	 * Delivery Method ID
	 * @var int
	 */
	protected $id;

	/**
	 * Delivery Method Name
	 * @var string
	 */
	protected $name;


	/**
	 * Store ID is hardcoded. This should be returned from logged in user
	 * @var int
	 */
	protected $store_id = 1;

	/**
	 * Delivery method status
	 * Possible values 0, 1, 2 ,3
	 * @var int
	 */
	protected $status;

	/**
	 * Delivery method fixed price for delivery
	 * @var float
	 */
	protected $fixed_price;


	/**
	 * is delivery method active
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
		'name' => array(
			'mandatory' => true,
			'validators' => array(
				'string',
			)
		),
		'store_id' => array(
			'default' => 1,
			'validators' => array()
		),
		'status' => array(
			'default' => 0,
			'validators' => array(
				'int'
			)
		),
		'fixed_price' => array(
			'default' => 'null',
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
	 * Fetch all active data from table
	 * @return mixed
	 */
	public function fetchAll()
	{
		$results = $this->_db->query("SELECT * FROM " . self::TABLE_NAME . " WHERE active = 1 AND store_id = " . $this->getStoreID());

		if (!$results) {
			$response = new Response('Fetch all returned error: '. $this->_db->error, 200, true);
			$response->toJSON();
		}

		return $this->result_array($results);
	}

	/**
	 * Fetch one item from table
	 * @param $id
	 * @param string $table
	 * @return array
	 */
	public function fetchOne($id, $table = self::TABLE_NAME)
	{
		return parent::fetchOne($id, $table);
	}

	/**
	 * Save one item to table
	 * @param array $data
	 * @param string $save
	 * @return array
	 */
	public function save($data = array(), $save = 'one')
	{
		# used for inline add
		if (empty($data)) {
			$data = Input::getInstance()->post()->item();
			$save = Input::getInstance()->get()->item('save');
		}
		# get hardcoded store id
		$this->store_id = $this->getStoreID();


		/* if save all method is on, we will do all, including create/update/delete Just for current task */
		if ($save === 'all') {
			return $this->saveAll($data);
		}

		/* Prepare validate and clean all vars */
		$this->prepareVars($data);

		$statement = $this->_db->prepare("INSERT INTO " . self::TABLE_NAME . " (`id`, `store_id`, `name`, `status`, `fixed_price`, `active`) VALUES (NULL, ?, ?, ?, ?, ?)");
		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		$bind = $statement->bind_param('isidi', $this->store_id, $this->name, $this->status, $this->fixed_price, $this->active);
		if ($bind === false) {
			$response = new Response('Bind has errors: ' . htmlspecialchars($statement->error), 200, true);
			$response->toJSON();
		}

		$execute = $statement->execute();
		if ($execute === false) {
			$response = new Response('Query was unable to execute: ' . htmlspecialchars($statement->error), 200, true);
			$response->toJSON();
		}

		$last_insert_id = $statement->insert_id;

		$statement->close();

		return $this->fetchOne($last_insert_id);
	}

	/**
	 * Deactivate one item in database
	 * @param $id
	 * @param string $table
	 * @return array
	 */
	public function delete($id, $table = self::TABLE_NAME)
	{
		return parent::delete($id, $table);
	}

	/**
	 * Update one row in table
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


		$statement = $this->_db->prepare("UPDATE " . self::TABLE_NAME . " SET `store_id`= ?, `status` = ?, `fixed_price` = ? WHERE id = ?");
		if ($statement === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		$bind = $statement->bind_param('iidi', $this->store_id, $this->status, $this->fixed_price, $this->id);
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
	 * Used to save/update/delete all data at once
	 * @param $data
	 */
	private function saveAll($data)
	{
		# get hardcoded store id
		$this->store_id = $this->getStoreID();

		$methods = $data['methods'];
		$rangesToDelete = $data['toDelete'];

		$optionsModel = new DeliveryMethodOptions();
		$rangesModel = new DeliveryMethodRanges();

		$statementUpdateMethod = $this->_db->prepare("UPDATE " . self::TABLE_NAME . " SET `store_id`= ?, `status` = ?, `fixed_price` = ? WHERE id = ?");
		if ($statementUpdateMethod === false) {
			$response = new Response('Prepare statement has error '. htmlspecialchars($this->_db->error), 200, true);
			$response->toJSON();
		}

		# used to save ranges after
		$rangesUpdate = array();
		$rangesSave = array();

		# used to save options
		$optionsUpdate = array();
		$optionsSave = array();

		foreach ($methods as $key => $method) {



			if (isset($method['options'])) {
				# if ID is not set, that means we are creating new row
				if (!isset($method['options']['id']) || $method['options']['id'] == 0) {
					$optionsSave[] = $method['options'];
				} else {
					$optionsUpdate[] = $method['options'];
				}
			}

			if (isset($method['ranges'])) {
				$ranges = $method['ranges'];

				# prepare ranges for bulk update/add/delete

				foreach ($ranges as $rKey => $range) {
					if (!isset($range['id']) || $range['id'] == 0) {
						$rangesSave[] = $range;
					} else {
						$rangesUpdate[] = $range;
					}
				}
			}


			# Validate results

			$bind = $statementUpdateMethod->bind_param('iidi', $this->store_id, $method['status'], $method['fixed_price'], $method['id']);
			if ($bind === false) {
				$response = new Response('Bind has errors: ' . htmlspecialchars($statementUpdateMethod->error), 200, true);
				$response->toJSON();
			}

			$execute = $statementUpdateMethod->execute();
			if ($execute === false) {
				$response = new Response('Query was unable to execute: ' . htmlspecialchars($statementUpdateMethod->error), 200, true);
				$response->toJSON();
			}
		}

		$statementUpdateMethod->close();


		# it is cheaper for server to have few more loops trough this small number of data
		# than to have more openings of mysqli preparation statements and closing of them

		# save all new ranges
		if (!empty($rangesSave)) {
			$rangesModel->saveMany($rangesSave);
		}

		# update existing ranges
		if (!empty($rangesUpdate)) {
			$rangesModel->updateMany($rangesUpdate);
		}

		# save new options
		if (!empty($optionsSave)) {
			$optionsModel->saveMany($optionsSave);
		}

		# update existing options
		if (!empty($optionsUpdate)) {
			$optionsModel->updateMany($optionsUpdate);
		}

		# Ranges to delete
		if (!empty($rangesToDelete)) {
			$rangesModel->deleteMany($rangesToDelete);
		}


		$response = new Response(array('execution' => true));
		$response->toJSON();
	}
}