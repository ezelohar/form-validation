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

class DeliveryMethod extends Model
{
	/**
	 * connected database object
	 * @var Database
	 */
	protected $_db;

	/**
	 * Model table name
	 * @var string
	 */
	protected $table_name = 'delivery_method';

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
		$results = $this->_db->query("SELECT * FROM " . $this->table_name . " WHERE active = 1");

		return $this->result_array($results);
	}

	public function fetchOne($id)
	{
		return $id;
	}

	public function save()
	{

	}

	public function delete($id)
	{

	}

	public function update($id)
	{

	}
}