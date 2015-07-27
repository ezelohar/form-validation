/**
 * Main form controller
 * @constructor
 */
var FormController = function () {
	this.deliveryMethods = [];


	this.bindEvents();
};

/**
 * Run controller
 */
FormController.prototype.run = function () {
	var self = this;
	API.get(URL_DELIVERY_METHOD, {
		callback: function (data) {
			self.buildDisplay(data);
		}
	});
};

/**
 * Build display
 * @param data
 */
FormController.prototype.buildDisplay = function (data) {
	var self = this, form = '';
	$.each(data, function (i, item) {
		form += self.singleMethodTemplate(item);
	});

	$('.jsTable').find('tbody').html(form);
};


FormController.prototype.bindEvents = function () {
	var self = this;
	$('.jsTable').on('click', '.jsUseRange', function (e) {
		e.preventDefault();
		var parent = $(this).parent().parent().parent();

		var subTableElement = self.getSubTableElement(parent, 'fv-range-holder');

		parent.toggleClass('fv-show-range').toggleClass('fv-display-actions');

		if (subTableElement) {
			parent.next().toggle();
		} else {
			parent.after(Range.generateRangeTemplate(parent.attr('data-method-id')));
		}
	});

	$('.jsTable').on('click', '.jsOpenSettings', function (e) {
		e.preventDefault();


		var parent = $(this).parent().parent();
		var subTableElement = self.getSubTableElement(parent, 'fv-options-holder');

		parent.toggleClass('fv-display-actions');

		if ($(this).hasClass('fv-button-active')) {
			subTableElement.hide();
		} else {
			if (subTableElement !== false) {
				subTableElement.show();
			} else {
				API.get(URL_DELIVERY_METHOD_OPTIONS, {
					data: {
						delivery_method_id: parent.attr('data-method-id')
					},
					callback: function (data) {
						data = data[0];
						parent.after(self.optionsTemplate(data, parent.attr('data-method-id')));
					}
				});
			}
		}

		$(this).toggleClass('fv-button-active');
	});

	$('.jsTable').on('click', '.jsLoadRanges', function (e) {
		e.preventDefault();

		var parent = $(this).parent().parent().parent();

		parent.toggleClass('fv-display-actions');

		var subTableElement = self.getSubTableElement(parent, 'fv-range-holder');

		if (!subTableElement) {
			Range.getRanges(parent, parent.attr('data-method-id'));
		} else {
			subTableElement.toggle();
		}
	});
};

FormController.prototype.getSubTableElement = function (element, ident) {
	var elementFirst = element.next(), elementSecond = element.next().next();

	if (elementFirst.hasClass(ident)) {
		return elementFirst;
	} else if (elementSecond.hasClass(ident) && !elementFirst.hasClass('fv-hover-actions')) {
		return elementSecond
	} else {
		return false;
	}
};


/**
 * Save all data as bulk to back-end
 */
FormController.prototype.saveForm = function () {

	/**
	 * Clean errors on input fields
	 */
	Validate.clean();

	/*Fetch method data */
	var self = this, tableRows = $('.jsTable tr.fv-method-data'), methodData = [], rangeData = [], optionsData = [], error = false;

	console.log(tableRows);
	$.each(tableRows, function (i, item) {
		var methodRow = {};
		/* Get ID */
		methodRow.id = $(item).attr('data-method-id');


		/* check for price and status */

		methodRow.fixed_price = $(item).find('input[name="fixed_price"]').val();
		/* status*/

		/* ranges */
		if ($('#range_' + methodRow.id).length !== 0) {
			methodRow.fixed_price = null;
			methodRow.status = DELIVERY_METHOD_RANGE;


			var ranges = self.fetchRangesData(methodRow.id);
			if (!ranges) {
				Notifier.prepare('Please set your ranges for specific method', NOTIFIER_ERROR, $(item));
				error = true;
			}
			methodRow.ranges = ranges;
		} else if ($.trim(methodRow.fixed_price) === "") {
			methodRow.status = DELIVERY_METHOD_UNAVAILABLE;
		} else if ($.trim(methodRow.fixed_price) == 0) {
			methodRow.status = DELIVERY_METHOD_FREE;
		} else if ($.isNumeric($.trim(methodRow.fixed_price))) {
			methodRow.status = DELIVERY_METHOD_PAID;
		} else {
			Notifier.prepare('Delivery method price must be empty or a valid number', NOTIFIER_ERROR);
			$(item).find('input[name="fixed_price"]').addClass('error');
		}

		if ($('#option_' + methodRow.id).length !== 0) {
			methodRow.options = self.fetchMethodOptions(methodRow.id);
		}

		methodData.push(methodRow);

	});


	if (!error && Validate.isValid()) {
		API.save(URL_BULK_SAVE, {

		});
	} else {
		Notifier.flush();
	}
};

FormController.prototype.fetchRangesData = function (id) {
	var trs = $('#range_' + id).find('table tr'), rangesData = [];
	console.log(trs);
	$.each(trs, function(i, item) {
		var tr = $(item);

		var inputs = tr.find('input'), emptyEntry = 0;

		var obj = {

		}

		console.log(inputs);
		$.each(inputs, function(i, input) {
			input = $(input);

			/* validate all data, fill up errors in HTML */
			Validate.validateInput(input, inputs);

			var value = $.trim(input.val());

			if (value == 0 || value == '') {
				emptyEntry++
			}

			obj[input.attr('name')] = input.val();
		});

		if (inputs.length != emptyEntry) {
			rangesData.push(obj);
		}
	});

	return rangesData;
}

FormController.prototype.fetchMethodOptions = function (id) {
	var inputs = $('#option_' + id).find('input, textarea'), optionsData = {}, isValid = true;
	$.each(inputs, function (i, item) {
		item = $(item);
		/* validate all data, fill up errors in HTML */
		Validate.validateInput(item, inputs);

		optionsData[item.attr('name')] = item.val();
	});

	if (!isValid) {
		return false;
	}

	optionsData.delivery_method_id = id;


	return optionsData;
}
