var ApiManager = function () {

};

ApiManager.prototype.get = function (url, options) {
	this.callAjax('GET', url, options);
};

ApiManager.prototype.update = function (url, options) {
	this.callAjax('PUT', url, options);
};

ApiManager.prototype.delete = function (id, data, callback) {
	this.callAjax('DELETE', url, options);
};

ApiManager.prototype.save = function (url, options) {
	this.callAjax('POST', url, options);
};


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








