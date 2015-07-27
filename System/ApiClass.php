<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 7/26/15
 * Time: 1:43 PM
 */

namespace System;


use System\Core\Router;
class Api {

	/**
	 * Run API system
	 */
	public static function run() {
		$routes = new Router();
		if ($routes->routeExists()) {
			try {
				# create reflection class and initialize original trough it
				$oReflectionClass = new \ReflectionClass($routes->buildModel());

				$modelClass = $oReflectionClass->newInstanceArgs();

				$action = $routes->getAction();
				$id = $routes->getID();

				# Trigger model action and return response
				$response = new \System\Helpers\Response($modelClass->$action($id));
				$response->toJSON();
			} catch (Exception $e) {
				//something went wrong
				$response = new \System\Helpers\Response($e->getMessage(), 200, true);
				$response->toJSON();
			}
		} else {
			$response = new \System\Helpers\Response('Request uri doesn\'t exists', 404, true);
			$response->toJSON();
		}
	}

	public static function readAnnotations($class) {
		$r = new ReflectionClass($class);
		$doc = $r->getDocComment();
		preg_match_all('#@(.*?)\n#s', $doc, $annotations);
		return $annotations[1];
	}
}