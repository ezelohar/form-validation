/**
 * Used to validate fields
 * To enable autovalidation, give Input fields next attributes
 * 	validate="separated comma values for validate types", if validate isn't set we wont be do any validation in input
 * 		possible values numeric,compare,url
 * 	empty (if this is set, input can be empty)
 * 	compare-to (with witch input to be compared from same row)
 * 	compare-type (how to compare)
 */

var ValidateClass = function() {

};

/**
 * Clean all input fields from error
 */
ValidateClass.prototype.clean = function () {
	$('.error').each(function (i, item) {
		$(item).removeClass('error');
	});
};

/**
 * Check if there are input elements with class error
 * @returns {boolean}
 */
ValidateClass.prototype.isValid = function () {
	return $('input.error, textarea.error').length === 0;
};

/**
 * Validate input fields. Validators are defined by giving input field attribute validate with validator names
 * @param input
 * @param inputs
 * @returns {boolean}
 */
ValidateClass.prototype.validateInput = function(input, inputs) {
	var self = this, valid = true;
	/* Check if input field has need to be validated */
	if ($(input).attr('validate') != undefined) {
		var validators = $(input).attr('validate').split(',');
		var result = false, vLenth = validators.length;
		while(vLenth--) {
			/* check validator for every type of validation for given input field */
			if (typeof this[validators[vLenth]] == 'function') {
				this[validators[vLenth]]($(input), inputs);
			}
		}
	}

	return valid;
};

/**
 * Check if input field has numeric value
 * @param input (input field element)
 * @returns {boolean}
 */
ValidateClass.prototype.numeric = function (input) {
	var canBeEmpty = (input.attr('empty') != undefined) ? true : false;
	var value = input.val();

	if ($.trim(value).length == 0) {
		return true;
	}

	if (!$.isNumeric($.trim(value))) {
		Notifier.prepare('Required field must be a numeric', NOTIFIER_ERROR, input);
		input.addClass('error');
		return false;
	}

	return true;
};

/**
 * Compare two fields
 * @param input (input field element)
 * @param inputs (array)
 * @returns {boolean}
 */
ValidateClass.prototype.compare = function(input, inputs) {

	var valid = true,
		compareType = input.attr('compare-type'),
		compareTo = this.findInputByName(inputs, input.attr('compare-to')),
		canBeEmpty = (input.attr('empty') != undefined) ? true : false;

	var canCompareToBeEmpty = (compareTo.attr('empty') != undefined) ? true : false;

	var isEmptyFirst = ($.trim(input.val()).length > 0) ? false : true;
	var isEmptySecond = ($.trim(compareTo.val()).length > 0) ? false : true;


	if ( (canBeEmpty && canCompareToBeEmpty) && (isEmptyFirst || isEmptySecond)) {
		return true;
	}

	switch (compareType) {
		case '<=':
			if (parseFloat(input.val()) <= parseFloat(compareTo.val())) {
				valid = true;
			} else {
				valid = false;
			}
			break;
		case '>=':
			if (parseFloat(input.val()) >= parseFloat(compareTo.val())) {
				valid = true;
			} else {
				valid = false;
			}
			break;
	}

	if (!valid) {
		Notifier.prepare('Input field must be ' + compareType + ' from input field it is being compared to' , NOTIFIER_ERROR, input);
		input.addClass('error');
		return valid;
	}

	return valid;
};

/**
 * Check if input field is URL
 * @param input (input field object)
 * @returns {boolean}
 */
ValidateClass.prototype.url = function (input) {
	var value = $.trim(input.val()), canBeEmpty = (input.attr('empty') != undefined) ? true : false;

	if (canBeEmpty && value.length == 0) {
		return true;
	}
	var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
		'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
		'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
		'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
		'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
		'(\\#[-a-z\\d_]*)?$','i'); // fragment locator

	if (!pattern.test(value)) {
		Notifier.prepare('Input field require valid URL to be inserted' , NOTIFIER_ERROR, input);
		input.addClass('error');
		return false;
	}

	return true;
}


/**
 * Find input field from list of inputs by it's name
 * @param inputs
 * @param name
 * @returns {*}
 */
ValidateClass.prototype.findInputByName = function(inputs, name) {
	var item;
	$.each(inputs, function (i, input) {
		input = $(input);
		if (input.attr('name') === name) {
			item = input;
			return false;
		}
	});

	return item;
}