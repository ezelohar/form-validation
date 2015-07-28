/*
 Delivery method statuses
 */
var DELIVERY_METHOD_UNAVAILABLE = 0;
var DELIVERY_METHOD_FREE = 1;
var DELIVERY_METHOD_PAID = 2;
var DELIVERY_METHOD_RANGE = 3;





/** Initialize  files **/
var Notifier = new NotifierManager();
var API = new ApiManager();
var Validate = new ValidateClass();

/** Initialize controllers **/
var Range = new RangeController();
var Form = new FormController();

/** Run app **/
Form.run();




