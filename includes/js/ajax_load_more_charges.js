jQuery(document).ready(function ($) {

	var i = 1; // VARIABLE FOR INDEXING
	var ajaxTriggerButton = $('.ajax_trigger'); // THE BUTTON TO TRIGGER AJAX FUNCTION    
	var ajaxResponseDiv = $('.ajax_response'); // THE DIV TO DISPLAY AJAX RESPONSE    
	var originalButtonText = ajaxTriggerButton.html(); // Store button's text so that in case we need to display the text again, after loading, we still have it


	// LOADING HELPERS
	function loading(loadingTarget) {
		$(loadingTarget).attr('disabled', 'disabled').addClass('loading').html('<i class="fas fa-circle-notch fa-spin"></i>');
	}
	function loaded(loadedTarget) {
		$(loadedTarget).removeAttr('disabled').removeClass('loading').html(originalButtonText);
	}


	// MAIN AJAX FUNCTION
	function loadMoreCharges(triggerTarget) {
		loading(triggerTarget);

		//Create our variables to be sent
		dataSend = {};
		dataSend.action = phpVars['wp-action-doenanova-load-more-charges'];
		dataSend.last_charge = $('#last_charge').val();

		$.ajax({
			url: phpVars['checkout-url'],
			data: dataSend,
			type: "POST",
			dataType: "html",
		}).done(function (result) {

			$(result).css('opacity', '0').appendTo(ajaxResponseDiv).each(function (index) {
				$(this).delay(20 * index).animate({ opacity: 1, }, 200);
			});

			loaded(triggerTarget); i++;

		}).fail(function (error) {

			var errorMessage = $('<p class="alert alert-danger">' + error.statusText + '</p>').show().delay(3000).fadeOut();
			$(errorMessage).appendTo(ajaxResponseDiv);

			loaded(triggerTarget);

		});
	}


	// AJAX BUTTON TRIGGER CALL
	ajaxTriggerButton.on('click', function () {
		loadMoreCharges(this);
	});


	// EXECUTE FUNCTION ON READY FOR THE FIRST INFO TO APPEAR
	loadMoreCharges(ajaxTriggerButton);




	/**
	  *	AJAX doenanova_load_more_charges
	  */

	//Just checking if everything is working thus far
	//console.log("HELLO doenanova_load_more_charges");

	//checking if we're getting our PHP variables
	//console.log(phpVars);


	/**
	*	Click event
	*/
	// $('#loadmore_recent_transactions-btn').click(function (e) {

	// 	//Confirm the button has been clicked
	// 	console.log("Load more button clicked");

	// 	//Create our variables to be sent
	// 	dataSend = {};
	// 	dataSend.action = phpVars['wp-action-doenanova-load-more-charges'];
	// 	dataSend.last_charge = $('#last_charge').val();

	// 	$('.loadmore_recent_transactions-message').hide();

	// 	//Store button's text so that in case we need to display the text again, after loading, we still have it
	// 	var originalButtonText = $('#loadmore_recent_transactions-btn').html();

	// 	//Add loading state to the button
	// 	$('#loadmore_recent_transactions-btn').attr('disabled', 'disabled').addClass('loading').html('<i class="fas fa-circle-notch fa-spin"></i>');


	// 	$.ajax({
	// 		type: "post",
	// 		url: phpVars['checkout-url'],
	// 		data: dataSend,
	// 		dataType: 'json',
	// 	})
	// 		.done(function (data, status) {

	// 			//Show what has been the result
	// 			console.log("$.ajax.done");
	// 			console.log("data: ", data);
	// 			console.log("status: ", status);

	// 			$('#loadmore_recent_transactions-btn').removeAttr('disabled').removeClass('loading').html(originalButtonText);

	// 			//If we receive back $return_data["success"] = true;
	// 			if (data.success) {

	// 				//Loop through each array element and perform the following
	// 				$.each(data, function (index, value) {

	// 					//Loop through all elements except these ones, because they are "success" from $return_data["success"] = true;.
	// 					if (index === 'success' || index === 'has_more_charges') {
	// 						return;
	// 					}

	// 					//Change the value of the hidden field #last_charge to the new last charge ID		
	// 					$('#last_charge').attr('value', value.charge_id);

	// 					//Append the newly created elements to this div
	// 					$("#transacoes table tbody").append(function () {

	// 						//Create our variables based on the values we got from the response
	// 						var charge_id = value.charge_id;
	// 						var charge_status = value.charge_status;
	// 						var charge_purpose = value.charge_purpose;
	// 						var charge_frequency = value.charge_frequency;
	// 						var charge_brand = value.charge_brand.toLowerCase();
	// 						var charge_last_4 = value.charge_last_4;
	// 						var charge_amount = value.charge_amount;

	// 						// (Date in JS sucks...)
	// 						var timestamp = value.charge_date;
	// 						var date = new Date(timestamp * 1000);
	// 						var month = new Array();
	// 						month[0] = phpVars.jan;
	// 						month[1] = phpVars.feb;
	// 						month[2] = phpVars.mar;
	// 						month[3] = phpVars.apr;
	// 						month[4] = phpVars.may;
	// 						month[5] = phpVars.jun;
	// 						month[6] = phpVars.jul;
	// 						month[7] = phpVars.aug;
	// 						month[8] = phpVars.sep;
	// 						month[9] = phpVars.oct;
	// 						month[10] = phpVars.nov;
	// 						month[11] = phpVars.dec;
	// 						var n = month[date.getMonth()];
	// 						var charge_month = ("0" + (date.getMonth() + 1)).slice(-2);
	// 						var charge_year = date.getFullYear().toString().substr(-2);
	// 						var charge_day = ("0" + (date.getDate())).slice(-2);
	// 						// (Date in JS sucks...)

	// 						if (charge_status == 'succeeded') {
	// 							var badge = 'text-success';
	// 							var icon = 'check-circle';
	// 							var status = 'Succeeded';
	// 						} else if (charge_status == 'pending') {
	// 							var badge = 'text-warning';
	// 							var icon = 'exclamation-circle';
	// 							var status = 'Pending';
	// 						} else if (charge_status == 'failed') {
	// 							var badge = 'text-danger';
	// 							var icon = 'times-circle';
	// 							var status = 'Failed';
	// 						}

	// 						if (charge_frequency == 'week') {
	// 							var charge_frequency_localised = phpVars.week;
	// 						} else if (charge_frequency == 'month') {
	// 							var charge_frequency_localised = phpVars.month;
	// 						} else if (charge_frequency == 'year') {
	// 							var charge_frequency_localised = phpVars.year;
	// 						} else {
	// 							var charge_frequency_localised = '';
	// 						}


	// 						//Output what we want			  
	// 						return `		
	// 							<tr class="` + status + ` ` + charge_id + `">
	// 								<td>
	// 									<div class="fw-bold">` + charge_purpose + `</div>
	// 									<div><small class="text-muted">` + charge_frequency_localised + `</small></div>
	// 								</td>
	// 								<td><i class="fab fa-cc-` + charge_brand + `"></i> ` + charge_last_4 + `</td>
	// 								<td>` + charge_day + `/` + charge_month + `/` + charge_year + `</td>
	// 								<td class="text-end ` + badge + `"> ` + phpVars["doe_na_nova_currency_symbol_js"] + `` + charge_amount + `,00 </td>
	// 							</tr>
	// 							`;
	// 					}); //$( "#ajax_response" ).append

	// 					$('.new_charges').hide();
	// 					$('.new_charges').fadeIn(800).removeClass('new_charges');

	// 				}); //$.each	

	// 				if (!data.has_more_charges) {
	// 					$('#loadmore_recent_transactions-btn').hide();
	// 				}


	// 			} else {
	// 				//Show this fail message if something went wrong with the AJAX response
	// 				$('#loadmore_recent_transactions-fail-message').fadeIn(800);
	// 			}
	// 		})
	// 		.fail(function (data, status, error) {

	// 			//Show what has been the result
	// 			console.log("$.ajax.fail");
	// 			console.log("data: ", data);
	// 			console.log("status: ", status);
	// 			console.log("error: ", error);

	// 			//Show this fail message if something went wrong with the AJAX sending
	// 			$('#loadmore_recent_transactions-btn').removeAttr('disabled').removeClass('loading').html(originalButtonText);
	// 			$('#loadmore_recent_transactions-fail-message').fadeIn(800);
	// 		});

	// 	e.preventDefault();
	// });
	/**
	*	AJAX doenanova_load_more_charges
	*/







});
