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

	public function save()
	{

	}

	public function delete($id, $table = self::TABLE_NAME)
	{
		return parent::delete($id, $table);
	}

	public function update($id)
	{

	}
}