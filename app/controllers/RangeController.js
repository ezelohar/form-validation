var RangeController = function ($methodHolder, methodID) {
	this.bindEvents();
};

RangeController.prototype.bindEvents = function() {
	var self = this;
	$('.jsTable').on('click', '.jsAddNewRange', function(e) {
		e.preventDefault();
		var tbody = $(this).parent().parent().parent();
		tbody.append(self.rangeTemplate());
	});

	$('.jsTable').on('click', '.jsRemoveRangeRow', function(e) {
		e.preventDefault();

		var c = confirm('Are you sure? If you do this, you wont be able to revert your action!!!')

		if (c) {

			var methodRangeTableBoy = $(this).parent().parent().parent();

			/* delete current row */
			$(this).parent().parent().remove();

			/* no ranges found anymore */
			if (!methodRangeTableBoy.find('tr').length) {
				/* reset main method row. If items are deleted they will be also deactivated from database when we save*/
				var topElementForRange = methodRangeTableBoy.parents('.fv-range-holder');
				if (topElementForRange.prev().hasClass('fv-show-range')) {
					topElementForRange.prev().toggleClass('fv-show-range').toggleClass('fv-display-actions');
				} else if (topElementForRange.prev().prev().hasClass('fv-show-range')) {
					topElementForRange.prev().prev().toggleClass('fv-show-range').toggleClass('fv-display-actions');
				}

				topElementForRange.remove();
			}
		}
	});
};

RangeController.prototype.getRanges = function(element, methodID) {
	var self = this;
	API.get(URL_DELIVERY_METHOD_RANGES, {
		data: {
			delivery_method_id: methodID
		},
		callback: function (data) {
			var rows = '';
			$.each(data, function(i, item) {
				rows += self.rangeTemplate(item);
			});
			element.after(self.generateRangeTemplate(methodID, rows))
		}
	});
};


