var ApiManager = function () {

};

ApiManager.prototype.get = function (url, options) {
	var self = this, ajaxObject = {};



	if (typeof options.id === 'number') {
		url = url + '/' + options.id;
	}

	ajaxObject.url = url;
	ajaxObject.method = 'GET';
	ajaxObject.dataType = 'JSON'

	if (typeof options.data === 'object') {
		ajaxObject.data = options.data;
	}

	if (typeof options.callback !== 'function') {
		options.callback = null;
	}


	this.callAjax('GET', ajaxObject, options.callback);
};

ApiManager.prototype.update = function (url, options) {
	var self = this, ajaxObject = {};

	if (!id) {
		Notifier('Missing ID for update action', NOTIFIER_ERROR);
		return false;
	}

	url = url + '/' + id;

	ajaxObject.url = url;
	ajaxObject.method = 'PUT';
	ajaxObject.dataType = 'JSON';

	if (typeof data !== 'undefined') {
		ajaxObject.data = data;
	}


	this.callAjax('PUT', ajaxObject, callback);
};

ApiManager.prototype.delete = function (id, data, callback) {
	var self = this, url = this.url, ajaxObject = {};

	if (!id) {
		Notifier('Missing ID for update action', NOTIFIER_ERROR);
		return false;
	}

	url = url + '/' + id;

	ajaxObject.url = url;
	ajaxObject.method = 'DELETE';
	ajaxObject.dataType = 'JSON';

	if (typeof data !== 'undefined') {
		ajaxObject.data = data;
	}


	this.callAjax('DELETE', ajaxObject, callback);
};

ApiManager.prototype.save = function (id, data, callback) {
	var self = this, url = this.url, ajaxObject = {};

	url = url + '/' + id;

	ajaxObject.url = url;
	ajaxObject.method = 'POST';
	ajaxObject.dataType = 'JSON';

	if (typeof data !== 'undefined') {
		ajaxObject.data = data;
	}


	this.callAjax('POST', ajaxObject, callback);
};

ApiManager.prototype.callAjax = function(type, ajaxObject, callback) {
	$.ajax(ajaxObject).done(function (res) {
		if (res.error) {
			Notifier.display(res.data, NOTIFIER_ERROR, res.status);
		} else {
			if (typeof callback === 'function') {
				callback(res.data);
			}
		}
	});
}
