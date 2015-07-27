<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 7/26/15
 * Time: 11:58 AM
 */
namespace System\Core;


use System\Helpers\Response;

class Router
{

	/**
	 * Set mapping from route to class working with that route
	 * @var array
	 */
	private $_routesMapper = array(
		'methods' 	=> 'DeliveryMethod',
		'options' 	=> 'DeliveryMethodOptions',
		'ranges' 	=> 'DeliveryMethodRanges',
		'bulk'		=> 'Bulk'
	);

	/**
	 * Main route params
	 * @var array
	 */
	private $_params = array();

	/**
	 * @var string
	 */
	private $_model;

	/**
	 * @var string
	 */
	private $_method;

	/**
	 * @var string
	 */
	private $_action;

	/**
	 * @var int
	 */
	private $_id = 0;

	/**
	 * Fetch and process url
	 */
	public function __construct()
	{
		$this->processRoute();
		$this->setHTTPMethod();
	}


	/**
	 * Fetch url and clean it
	 */
	private function processRoute()
	{

		# clean query string from URL
		$params = explode('?', $_SERVER['REQUEST_URI']);
		$params = $params[0];

		# extract route
		$params = explode('/', $params);


		# remove first member of array because it is empty
		array_shift($params);

		$this->_params = $params;

		if (!isset($this->_params[0])) {
			$response = new \System\Helpers\Response('Model not set', 200, true);
			$response->toJSON();
		}

		$this->_model = $this->_params[0];

		$this->_method = $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Set HTTP Method
	 */
	private function setHTTPMethod()
	{
		switch ($this->getMethod()) {
			case 'GET':
				$this->setGetMethod();
				break;
			case 'PUT':
				$this->setPutMethod();
				break;
			case 'POST':
				$this->setPostMethod();
				break;
			case 'DELETE':
				$this->setDeleteMethod();
				break;
		}
	}

	/**
	 * Set HTTP Method as GET
	 */
	private function setGetMethod() {
		if (isset($this->_params[1])) {
			$this->_action = 'fetchOne';
			$this->_id = $this->_params[1];
		} else {
			$this->_action = 'fetchAll';
		}
	}

	/**
	 * Set HTTP Method as PUT
	 */
	private function setPutMethod() {
		$this->_action = 'update';
		if (!isset($this->_params[1])) {
			$response = new \System\Helpers\Response('Request is missing ID', 200, true);
			$response->toJSON();
		}
		$this->_id = $this->_params[1];
	}

	/**
	 * Set HTTP Method as POST
	 */
	private function setPostMethod() {
		$this->_action = 'save';
		if (empty($_POST)) {
			$response = new \System\Helpers\Response('Please submit data', 200, true);
			$response->toJSON();
		}
	}


	/**
	 * Set HTTP Method as DELETE
	 */
	private function setDeleteMethod() {
		$this->_action = 'delete';
		if (!isset($this->_params[1])) {
			$response = new \System\Helpers\Response('Request is missing ID', 200, true);
			$response->toJSON();
		}
		$this->_id = $this->_params[1];
	}



	/**
	 * Check to see if route exists
	 * @return bool
	 */
	public function routeExists() {
		return isset($this->_routesMapper[$this->getModel()]);
	}


	/**
	 * Model name
	 * @return string
	 */
	public function getModel()
	{
		# die with error if api model isn't set
		return $this->_model;
	}

	/**
	 * Model Action
	 * @return string
	 */
	public function getAction()
	{
		return $this->_action;
	}

	/**
	 * Model object ID
	 * @return int
	 */
	public function getID()
	{
		return $this->_id;
	}


	/**
	 * Get HTTP Request method
	 * @return string
	 */
	public function getMethod()
	{
		return $this->_method;
	}


	public function buildModel() {
		return 'System\Models\\' . $this->_routesMapper[$this->getModel()];
	}
}