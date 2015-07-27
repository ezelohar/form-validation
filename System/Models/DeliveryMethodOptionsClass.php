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

class DeliveryMethodOptions extends Model
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
	protected $table_name = 'delivery_method_options';

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
	 * Fetch all options for delivery method
	 * @return mixed
	 */
	public function fetchAll()
	{
		$delivery_method_id = Input::getInstance()->get()->item('delivery_method_id');

		$query = "SELECT * FROM " . $this->table_name;

		if ($delivery_method_id !== null) {
			$query .= ' WHERE delivery_method_id = ?';
		}

		$preparedObj = $this->_db->prepare($query);
		if ($delivery_method_id !== null) {
			$preparedObj->bind_param('i', $delivery_method_id);
		}

		$preparedObj->execute();

		$results = $preparedObj->get_result();


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