<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 7/26/15
 * Time: 1:00 PM
 */

namespace System\Helpers;

class Validator
{
	/** @var instanceof Validator */
	private static $instance;
	/**
	 * Validator requires name, config and data from which it will validate given item
	 * @param $name
	 * @param $validatorConfig
	 * @param $data
	 * @return bool|mixed
	 */
	public function validate($name, $validatorConfig, $data) {
		$validator = (isset($validatorConfig[$name])) ? $validatorConfig[$name] : false;

		if ($validator === false) {
			return $validator;
		}

		if (!isset($data[$name]) && isset($validator['default'])) {
			return ($validator['default'] === 'null') ? null : $validator['default'];
		}

		if (!isset($data[$name]) && !isset($validator['default'])) {
			$response = new Response("Field {$name} is mandatory in data: ". serialize($data), 200, true);
			$response->toJSON();
		}

		foreach ($validator['validators'] as $val) {
			$isValid = self::$val($data[$name]);

			if (!$isValid) {
				$response = new Response($name . ' is ' . $data[$name] . ' and must be a '. $val, 200, true);
				$response->toJSON();
			}
		}

		return $data[$name];
	}

	/**
	 * Checks if value is int
	 * @param $value
	 * @return bool
	 */
	protected function int($value) {
		return is_numeric($value);
	}

	/**
	 * Check if value is float (int is also accepted)
	 * @param $value
	 * @return bool
	 */
	protected function float($value) {
		return is_float($value) ? true : (self::int($value) ? true : false);
	}

	/**
	 * Check if value is string
	 * @param $value
	 * @return bool
	 */
	protected function string($value) {
		return is_string($value);
	}


	/*
	 * Get singleton instance of Validator object;
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


}