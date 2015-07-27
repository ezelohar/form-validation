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
use System\Api;

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
	 * @return mixed
	 */
	public function fetchAll()
	{
		$results = $this->_db->query("SELECT * FROM " . self::TABLE_NAME . " WHERE active = 1");

		return $this->result_array($results);
	}

	public function fetchOne($id, $table = self::TABLE_NAME)
	{
		return parent::fetchOne($id, $table);
	}

	public function save($data = array(), $save = 'one')
	{
		# used for inline add
		if (empty($data)) {
			$data = Input::getInstance()->post()->item();
			$save = Input::getInstance()->get()->item('save');
		}


		/* if save all method is on, we will do all, including create/update/delete Just for current task */
		if ($save === 'all') {
			return $this->saveAll($data);
		}

		/* insert new methods */
		$vars = $this->cleanVars(get_object_vars($this));


		foreach ($vars as $name=>$val) {
			if (!isset($data[$name])) {
				$this[$name] = 'NULL';
			} else {
				$this[$name] = $data[$name];
			}
		}

		$statement = $this->_db->prepare("INSERT INTO " . self::TABLE_NAME . " (`id`, `store_id`, `name`, `status`, `fixed_price`, `active`) VALUES (?, ?, ?, ?, ?, ?)");
		$statement->bind_param('iisdi', $this->id, $this->store_id, $this->name, $this->status, $this->fixed_price, $this->active);
		$statement->execute();

		$last_insert_id = $statement->insert_id;

		$statement->close();

		if ($save === 'all') {
			return $last_insert_id;
		}

		return $this->fetchOne($last_insert_id);
	}

	public function delete($id, $table = self::TABLE_NAME)
	{
		return parent::delete($id, $table);
	}

	public function update($id, $data = array())
	{

	}

	/**
	 * Made for sake of task, just to finish it on time, FAST CODE
	 * @param $data
	 */
	private function saveAll($data) {

		$statementUpdateMethod = $this->_db->prepare("UPDATE " . self::TABLE_NAME . " SET `store_id`= ?, `status` = ?, `fixed_price` = ? WHERE id = ?");
		foreach($data as $key=>$val) {

			/* validate results */
			$statement->bind_param('iidi', $this->store_id, $val['status'], $val['fixed_price'], $val['id']);
			$statement->execute();
		}

		$statement->close();
	}
}