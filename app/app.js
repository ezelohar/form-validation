
/*
 API URL's
 */
var URL_DELIVERY_METHOD = 'methods';
var URL_DELIVERY_METHOD_OPTIONS = 'options';
var URL_DELIVERY_METHOD_RANGES = 'ranges';
var URL_BULK_SAVE = 'bulk';

/*
 Delivery method statuses
 */
var DELIVERY_METHOD_UNAVAILABLE = 0;
var DELIVERY_METHOD_FREE = 1;
var DELIVERY_METHOD_PAID = 2;
var DELIVERY_METHOD_RANGE = 3;


/*
 Notifier Type's
 */
var NOTIFIER_ERROR = 0;
var NOTIFIER_SUCCESS = 1;


/** Initialize  files **/
var Notifier = new NotifierManager();
var API = new ApiManager();
var Validate = new ValidateClass();

/** Initialize controllers **/
var Range = new RangeController();
var Form = new FormController();

/** Run app **/
Form.run();




