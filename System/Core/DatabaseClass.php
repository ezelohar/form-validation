<?php
namespace System\Core;

use System\Helpers\Response;


/* simple library to work with database */
class Database {

	/** @var instanceof Database */
	private static $instance;

	/** @var mysqli object **/
	private $_connection;

	/**
	 * Connect to database using mysqli
	 * @param $dbServer
	 * @param $dbUser
	 * @param $dbPass
	 * @param $dbName
	 */
	private function __construct($dbServer, $dbUser, $dbPass, $dbName) {
		$this->_connection = new \mysqli($dbServer, $dbUser, $dbPass, $dbName);

		if ($this->_connection->connect_error) {
			$response = new Response('Database connection error', 200, true);
			$response->toJSON();
		}

		$this->_connection->set_charset("utf8");
	}

	/**
	 * Close database connection
	 */
	public function close() {
		$this->_connection->close();
	}

	/**
	 * Return database object to work with
	 * @return object|mysqli
	 */
	public function getDB() {
		return $this->_connection;
	}


	/*
	 * Get singleton instance of Database object;
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
		}

		return self::$instance;
	}
}