/*
 Notifier Type's
 */
var NOTIFIER_ERROR = 0;
var NOTIFIER_SUCCESS = 1;

var NotifierManager = function() {
	this.notifications = [];
};


/**
 * Show single notification
 * @param notification
 * @param type
 */
NotifierManager.prototype.display = function(notification, type) {
	var displayClass = 'success';
	if (this.isError(type)) {
		displayClass = 'error';
	}

	$('#myModal').removeClass('error');
	var bodyContent = '', content = [];

	if (this.isError(type)) {
		$('#myModalLabel').html('Ups something went wrong!');
		content = notification
	} else {
		content = notification
		$('#myModalLabel').html('Information')
	}

	$('#myModal').addClass(displayClass);


	$('#myModalBody').html(content);

	$('#myModal').modal('toggle');
};

/**
 * Add notifications to queeue and display them all
 * @param notification
 * @param type
 * @param object
 */
NotifierManager.prototype.prepare = function (notification, type, object) {
	if (typeof type == 'undefined') {
		type = 'success';
	}

	if (typeof object == 'undefined') {
		object = null;
	}

	this.notifications.push({
		notification: notification,
		type: type,
		object: object
	})
};


/**
 * Display queeed notifications
 */
NotifierManager.prototype.flush = function () {
	var str = '', self = this;


	$.each(this.notifications, function (i, item) {
		var cssClass = (self.isError(item.type)) ? 'error' : 'success';
		str += '<span class=' + cssClass + '>' + item.notification + '</span>';
	});


	$('#myModalLabel').html('Information')

	$('#myModalBody').html(str);

	$('#myModal').modal('toggle');
};


/**
 * Check if type is error type
 * @param type
 * @returns {boolean}
 */
NotifierManager.prototype.isError = function(type) {
	return type === NOTIFIER_ERROR;
};

