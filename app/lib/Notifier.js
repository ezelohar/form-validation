var NotifierManager = function() {
	this.notifications = [];
}


NotifierManager.prototype.display = function(notification, type) {
	this.notification = notification;
	this.type = type;
	var displayClass = 'success';
	if (this.isError()) {
		displayClass = 'error';
	}

	var notification = '';

	alert(this.notification);
}

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
}

Notifier.prototype.flush = function () {

}



NotifierManager.prototype.isError = function() {
	return this.type === NOTIFIER_ERROR;
}

