/**
 * When calling request, options param is used to provide api call with additional data
 * Options:
 * 	id - ID if we are doing single data fetch (e.g. methods/1)
 * 	data - 	data we want to send to back-end using some of http methods
 * 	callback - function to be called after request is finished
 * 	save - used to trigger multiple saves (in this case only for main bulk save on route /methods)
 */


/*
 API URL's
 */
var URL_DELIVERY_METHOD = 'methods';
var URL_DELIVERY_METHOD_OPTIONS = 'options';
var URL_DELIVERY_METHOD_RANGES = 'ranges';
var URL_BULK_SAVE = 'bulk';


var ApiManager = function () {

};

/**
 * Get request for API
 * @param url
 * @param options
 */
ApiManager.prototype.get = function (url, options) {
	this.callAjax('GET', url, options);
};

/**
 * Update request
 * @param url
 * @param options
 */
ApiManager.prototype.update = function (url, options) {
	this.callAjax('PUT', url, options);
};

/**
 * Delete request
 * @param url
 * @param options
 */
ApiManager.prototype.delete = function (url, options) {
	this.callAjax('DELETE', url, options);
};

/**
 * Save request
 * @param url
 * @param options
 */
ApiManager.prototype.save = function (url, options) {
	this.callAjax('POST', url, options);
};


/**
 * Request maker
 * @param type
 * @param url
 * @param options
 */
ApiManager.prototype.callAjax = function(type, url, options) {
	var self = this, ajaxObject = {};



	if (typeof options.id === 'number') {
		url = url + '/' + options.id;
	}

	if (typeof options.save === 'string') {
		url = url + '?save=all'
	}

	ajaxObject.url = url;
	ajaxObject.method = type;
	ajaxObject.dataType = 'JSON'

	if (typeof options.data === 'object') {
		ajaxObject.data = (type === 'POST') ? {data: JSON.stringify(options.data)} : options.data;
	}

	$.ajax(ajaxObject).done(function (res) {
		if (res.error) {
			Notifier.display(res.data, NOTIFIER_ERROR, res.status);
		} else {
			if (typeof options.callback === 'function') {
				options.callback(res.data);
			}
		}
	});
}








