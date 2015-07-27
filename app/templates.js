/* Templates*/

/* Form controller templates */

FormController.prototype.optionsTemplate = function (data, methodID) {
	var id = 0, url = '', name = '', weight_from = '', weight_to = '', notes = '';

	if (typeof data !== 'undefined') {
		id = (data.id != null) ? data.id : 0;
		url = (data.url != null) ? data.url : '';
		name = (data.name != null) ? data.name : '';
		weight_from = (data.weight_from != null) ? data.weight_from : '';
		weight_to = (data.weight_to != null) ? data.weight_to : '';
		notes = (data.notes != null) ? data.notes : '';
	}

	var str = '<tr class="fv-options-holder" data-name="options" data-method-id="' + methodID + '" id="option_' + methodID + '">' +
			'<td colspan="3">' +
				'<table>' +
					'<tbody>' +
						'<tr>' +
							'<input type="hidden" name="id" value="' + id + '" />' +
							'<td width="40%" class="first">' +
								'<i class="fa fa-link"></i> Sledovacy URL dopravy' +
							'</td>' +
							'<td colspan="2">' +
								'<input type="text" name="url" validate="url" empty value="' + url + '" />' +
							'</td>' +
						'</tr>' +
						'<tr>' +
							'<td width="40%" class="first">' +
								'<i class="fa fa-truck"></i> Jméno dopravce' +
							'</td>' +
							'<td colspan="2">' +
								'<input type="text" name="name" value="' + name + '"/>' +
							'</td>' +
						'</tr>' +
						'<tr>' +
							'<td width="40%" class="first">' +
								'<i class="fa fa-download"></i> Vàhovy rozsah' +
							'</td>' +
							'<td colspan="2">' +
								'od <input type="number" step="any" value="' + weight_from + '" name="weight_from" validate="compare,numeric" empty compare-to="weight_to" compare-type="<="/> ' +
								'do <input type="number" step="any" value="' + weight_to + '" name="weight_to" validate="compare,numeric" empty compare-to="weight_from" compare-type=">="/> Kg ' +
							'</td>' +
						'</tr>' +
						'<tr>' +
							'<td width="40%" class="first">' +
								'<i class="fa fa-pencil-square-o"></i>' +
								'Jméno dopravce' +
							'</td>' +
							'<td colspan="2">' +
								'<textarea name="notes">' + name + '</textarea>' +
							'</td>' +
						'</tr>' +
					'</tbody>' +
				'</table>' +
			'</td>' +
		'</tr>'


	return str;
}


/**
 * Create a single view line for delivery method
 * @param item
 * @returns {string}
 */
FormController.prototype.singleMethodTemplate = function (item) {
	var price = '', placeholder = 'Nenabizime', range = 'fv-show-range'
	if (!item.fixed_price === null) {
		price = item.fixed_price;
		placeholder = '';
	}

	if (item.status != DELIVERY_METHOD_RANGE) {
		range = '';
	}

	var str = '<tr class="fv-method-data fv-hover-actions jsMethod ' + range + '" data-method-id="' + item.id + '">' +
			'<td>' +
				item.name +
			'</td>' +
			'<td class="">' +
				'<div class="fv-regular">' +
					'<input name="fixed_price" type="number" step="any" value="' + price + '" placeholder="' + placeholder + '"/> Kč ' +
					'<a href="#" class="fv-hidden jsUseRange">Nastavity Roszah</a>' +
				'</div>' +
				'<div class="fv-range">' +
					'<a href="#" class="jsLoadRanges">Zaobrazit Nastavenya Rozsah</a>'+
				'</div>' +
			'</td>' +
			'<td class="text-right">' +
				'<a href="#" class="btn btn-default fv-color-blue-gradient jsOpenSettings fv-hidden"><i class="fa fa-gear"></i> Daisi Nastavenia</a>' +
			'</td>' +
		'</tr>';

	return str;
};



/*Range controller templates */
RangeController.prototype.generateRangeTemplate = function (methodID, rows) {
	var rows = rows || this.rangeTemplate();

	var str = '<tr class="fv-range-holder" id="range_' + methodID + '" data-name="ranges" data-method-id="' + methodID + '">' +
			'<td colspan="3">' +
				'<table>' +
					'<tbody>' + rows +
					'</tbody>' +
				'</table>' +
			'</td>' +
		'</tr>';

	return str;
};

RangeController.prototype.rangeTemplate = function(data) {
	var from = '', to = '', price = '', id = 0;

	if (typeof data !== 'undefined') {
		id = (data.id != null) ? data.id : 0;
		from = (data.range_from != null) ? data.range_from : '';
		to = (data.range_to != null) ? data.range_to : '';
		price = (data.price != null) ? data.price : '';
	}


	var str = '<tr">' +
			'<td width="40%" class="first">' +
				'<input type="hidden" name="id" value="' + id + '" />' +
				'<span class="fv-range-left">' +
					'<i class="fa fa-bar-chart"></i> ' +
					'Pro zboži od <input type="number" step="any" class="range" value="' + from + '" name="range_from" validate="compare,number" compare-to="range_to" compare-type="<="/> ' +
					'do <input type="number" step="any" value="' + to + '" class="range" name="range_to" validate="compare,number" compare-to="range_from" compare-type=">=" /> Kč ' +
				'</span>' +
				'<span class="fv-range-right">' +
					'doprava za' +
				'</span>' +
			'</td>' +
			'<td width="40%">' +
				'<input type="number" step="any" name="price" value="' + price +'" /> Kč' +
			'</td>' +
			'<td class="text-right" width="20%">' +
				'<a href="#" class="jsAddNewRange">Pridat Dalši</a>' +
				'<a href="#" class="jsRemoveRangeRow">Odstranit</a>' +
			'</td>' +
		'</tr>';

	return str;
};