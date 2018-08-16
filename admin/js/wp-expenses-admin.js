(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 */
    $('.wp-expenses-datepicker').datepicker();

	var last_css_id_array = $(".clonedInput").last().attr('id').slice('-');
	var row_key = last_css_id_array[last_css_id_array.length - 1];

    $(".add-expense").live('click', function() {
        row_key++;

		var new_row =
		'<tr id="clonedInput-' + row_key + '" class="clonedInput">' +
				'<td><span class="dashicons dashicons-minus remove-expense"></span> <span class="dashicons dashicons-plus add-expense"></span></td>' +
				'<td><select name="wp_expenses_items[' + row_key + '][expense-type]" id="expense-type" class="expense-type">' +
				    '<option>--</option>' +
				    '<option value="mileage">Mileage</option>' +
					'<option value="hotel">Hotel</option>' +
		            '<option value="plane-tickets">Plane Tickets</option>' +
		            '<option value="reciept">Reciept</option>' +
					'<option value="food-ent">Food/Ent</option>' +
		            '<option value="parking">Parking</option>' +
		        '</select></td>' +
				'<td><input type="text" name="wp_expenses_items[' + row_key + '][expense-amount]" value="" class="ltr" /></td>' +
				'<td><span class="mileage-reimbursement-count" style="display:none"><span class="currency-type">$</span> <span class="mileage-reimbursement-total">--</span></span></td>' +
		'</tr>';

        $(this).parent().parent("tr").after(new_row);
        return false;
    });

	function calculate_mileage() {
		var val = $(this).val();
		console.log(val);
		switch (val){
			case 'mileage':
					console.log($(this).parent().parent().find('.mileage-reimbursement-count'));
				$(this).parent().parent().find('.mileage-reimbursement-count').show();
				$(this).parent().parent().find('input[type=text]').addClass('mileage');
				break;
			default:
				$(this).parent().parent().find('.mileage-reimbursement-count').hide();
				$(this).parent().parent().find('input[type=text]').removeClass('mileage');
				break;
		}
	}

	function add_price_total() {
		//console.log($(this).parent().parent().find('.mileage-reimbursement-total'));
		var calc = $(this).val() * .55;
		$(this).parent().parent().find('.mileage-reimbursement-total').text(calc.toFixed(2));
	}

	function remove(){
		var cloneCount = $(".clonedInput").length;
		if(cloneCount > 1) {
			$(this).parent().parent().remove();
		}
	}

	$("body").delegate("input.mileage","keyup", add_price_total);
	$("body").delegate(".expense-type","change", calculate_mileage);
	$("body").delegate(".remove-expense","click", remove);

	$('.expense-type').change(function() {
		calculate_mileage();
	});

    $('.expense').sortable({
        distance: 5,
        opacity: 0.6,
        cursor: 'move'
    });

})( jQuery );
