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

/* Model to save all data at once */
class Bulk extends Model
{
	public function save () {
		$data = Input::getInstance()->post()->item();

		$response = new Response($data);
		$response->toJSON();

	}
}