jQuery(document).ready(function ($) {





	/**
	  *	AJAX doenanova_load_more_recurring_donations
	  */

	//Just checking if everything is working thus far
	//console.log("HELLO doenanova_load_more_recurring_donations");

	//checking if we're getting our PHP variables
	//console.log(phpVars);


	/**
	*	Click event
	*/
	$('#loadmore_recurring_donations-btn').click(function (e) {

		//Confirm the button has been clicked
		//console.log("Load more button clicked");

		//Create our variables to be sent
		dataSend = {};
		dataSend.action = phpVars['wp-action-doenanova-load-more-doacoes-recorrentes'];
		dataSend.last_subscription = $('#last_subscription').val();

		$('.loadmore_recurring_donations-message').hide();

		//Store button's text so that in case we need to display the text again, after loading, we still have it
		var originalButtonText = $('#loadmore_recurring_donations-btn').html();

		//Add loading state to the button
		$('#loadmore_recurring_donations-btn').attr('disabled', 'disabled').addClass('loading').html('<i class="fa-solid fa-circle-notch fa-spin"></i>');


		$.ajax({
			type: "post",
			url: phpVars['checkout-url'],
			data: dataSend,
			dataType: 'json',
		})
			.done(function (data, status) {

				//Show what has been the result
				//console.log("$.ajax.done");
				//console.log("data: ", data);
				//console.log("status: ", status);

				$('#loadmore_recurring_donations-btn').removeAttr('disabled').removeClass('loading').html(originalButtonText);

				//If we receive back $return_data["success"] = true;
				if (data.success) {

					//Loop through each array element and perform the following
					$.each(data, function (index, value) {

						//Loop through all elements except these ones, because they are "success" from $return_data["success"] = true;.
						if (index === 'success' || index === 'has_more_subscriptions') {
							return;
						}

						//Change the value of the hidden field #last_subscription to the new last subscription ID		
						$('#last_subscription').attr('value', value.subscription_id);

						//Append the newly created elements to this div
						$("#recorrentes").append(function () {

							//Create our variables based on the values we got from the response
							var subscription_id = value.subscription_id;
							var subscription_status = value.subscription_status;
							var subscription_purpose = value.subscription_purpose;
							var subscription_interval = value.subscription_interval;
							var customer_cardBrand = value.customer_cardBrand;
							var customer_cardLast4 = value.customer_cardLast4;
							var subscription_planAmount = value.subscription_planAmount;

							// (Date in JS sucks...)
							var timestamp = value.subscription_date;
							var date = new Date(timestamp * 1000);

							var month = new Array();
							month[0] = phpVars.jan;
							month[1] = phpVars.feb;
							month[2] = phpVars.mar;
							month[3] = phpVars.apr;
							month[4] = phpVars.may;
							month[5] = phpVars.jun;
							month[6] = phpVars.jul;
							month[7] = phpVars.aug;
							month[8] = phpVars.sep;
							month[9] = phpVars.oct;
							month[10] = phpVars.nov;
							month[11] = phpVars.dec;
							var n = month[date.getMonth()];
							var subscription_month = ("0" + (date.getMonth() + 1)).slice(-2);

							var subscription_year = date.getFullYear().toString().substr(-2);
							var subscription_day = ("0" + (date.getDate())).slice(-2);
							var subscription_month_number = ("0" + (date.getMonth() + 1)).slice(-2);

							var weekDay = new Array();
							weekDay[0] = phpVars.sun;
							weekDay[1] = phpVars.mon;
							weekDay[2] = phpVars.tue;
							weekDay[3] = phpVars.wed;
							weekDay[4] = phpVars.thu;
							weekDay[5] = phpVars.fri;
							weekDay[6] = phpVars.sat;
							var w = weekDay[date.getDay()];
							var subscription_week_day = w;
							// (Date in JS sucks...)

							// (Date in JS sucks...)
							var timestamp2 = value.subscription_nextBilling;
							var date2 = new Date(timestamp2 * 1000);
							var month2 = new Array();
							month2[0] = phpVars.jan;
							month2[1] = phpVars.feb;
							month2[2] = phpVars.mar;
							month2[3] = phpVars.apr;
							month2[4] = phpVars.may;
							month2[5] = phpVars.jun;
							month2[6] = phpVars.jul;
							month2[7] = phpVars.aug;
							month2[8] = phpVars.sep;
							month2[9] = phpVars.oct;
							month2[10] = phpVars.nov;
							month2[11] = phpVars.dec;
							var n2 = month2[date2.getMonth()];
							var subscription_nextBilling_month = ("0" + (date2.getMonth() + 1)).slice(-2);
							var subscription_nextBilling_year = date2.getFullYear().toString().substr(-2);;
							var subscription_nextBilling_day = ("0" + (date2.getDate())).slice(-2);
							var subscription_nextBilling_month_number = ("0" + (date2.getMonth() + 1)).slice(-2);

							var weekDay2 = new Array();
							weekDay2[0] = phpVars.sun;
							weekDay2[1] = phpVars.mon;
							weekDay2[2] = phpVars.tue;
							weekDay2[3] = phpVars.wed;
							weekDay2[4] = phpVars.thu;
							weekDay2[5] = phpVars.fri;
							weekDay2[6] = phpVars.sat;
							var w2 = weekDay2[date2.getDay()];
							var subscription_nextBilling_week_day = w2;
							// (Date in JS sucks...)

							if (subscription_status == 'active') {
								var badge = 'is-success';
								var icon = 'circle-check';
								var status = 'Succeeded';
							} else if (subscription_status == 'incomplete') {
								var badge = 'is-warning';
								var icon = 'circle-exclamation';
								var status = 'Pending';
							} else if (subscription_status == 'canceled') {
								var badge = 'is-danger';
								var icon = 'circle-xmark';
								var status = 'Failed';
							}


							if (subscription_interval == 'week') {
								var subscription_interval_localised = phpVars.week;
							} else if (subscription_interval == 'month') {
								var subscription_interval_localised = phpVars.month;
							} else if (subscription_interval == 'year') {
								var subscription_interval_localised = phpVars.year;
							} else {
								var subscription_interval_localised = phpVars.one_time;
							}


							//Output what we want			  
							return `
							<div class="accordion-item ` + subscription_status + `" id="subs-` + subscription_id + `">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subs-` + subscription_id + `-dropdown" aria-expanded="true" aria-controls="subs-` + subscription_id + `-dropdown">
									<div class="col">
										<div>` + subscription_purpose + `</div>
										<div><small>` + subscription_interval_localised + `</small></div>
									</div>
									<div class="col text-end me-3">
										` + phpVars.doe_na_nova_currency_symbol_js + `` + subscription_planAmount + `,00
									</div>
								</button>

								<div id="subs-` + subscription_id + `-dropdown" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<form action="` + phpVars.action_url + `" method="POST">
											<input type='hidden' name='action' value='stripe_cancel_subscription' />
											<input type="hidden" name='subscription_id' value='` + subscription_id + `' />
											<input type="hidden" name="current_url" id="current_url" value="` + phpVars.current_url + `">
											<button class="btn btn-xs btn-danger float-end load-on-click cancel_subscription_btn" title="Delete this recurring donation"><i class="fa-solid fa-trash"></i></button>
										</form>

										<table class="table">
											<tbody>
												<tr>
													<th scope="row">Criada em</th>
													<td>` + subscription_week_day + `, ` + subscription_day + `/` + subscription_month_number + `/` + subscription_year + `</td>
												</tr>
												<tr>
													<th scope="row">Frequência</th>
													<td>` + subscription_interval_localised + `</td>
												</tr>
												<tr>
													<th scope="row">Próximo pagamento</th>
													<td>` + subscription_nextBilling_week_day + `, ` + subscription_nextBilling_day + `/` + subscription_nextBilling_month_number + `/` + subscription_nextBilling_year + `</td>
												</tr>
												<tr>
													<th scope="row">Valor</th>
													<td>` + phpVars.doe_na_nova_currency_symbol_js + `` + subscription_planAmount + `,00</td>
												</tr>
												<tr>
													<th scope="row">Método de pagamento</th>
													<td><i class="fa-brands fa-cc-` + customer_cardBrand + `"></i> ` + customer_cardLast4 + `</td>
												</tr>
												<tr>
													<th scope="row">Propósito</th>
													<td>` + subscription_purpose + `</td>
												</tr>
												<tr>
													<th scope="row">Status</th>
													<td><span class="` + badge + ` tag"><i class="fa-solid fa-xs fa-` + icon + `"></i> Ativo</span></td>
												</tr>
											</tbody>
										</table>

									</div>
								</div>
							</div>
							`;






						}); //$( "#ajax_response" ).append

						$('.new_subscriptions').hide();
						$('.new_subscriptions').fadeIn(800).removeClass('new_subscriptions');

					}); //$.each	

					if (!data.has_more_subscriptions) {
						$('#loadmore_recurring_donations-btn').hide();
					}


				} else {
					//Show this fail message if something went wrong with the AJAX response
					$('#loadmore_recurring_donations-fail-message').fadeIn(800);
				}
			})
			.fail(function (data, status, error) {

				//Show what has been the result
				//console.log("$.ajax.fail");
				//console.log("data: ", data);
				//console.log("status: ", status);
				//console.log("error: ", error);

				//Show this fail message if something went wrong with the AJAX sending
				$('#loadmore_recurring_donations-btn').removeAttr('disabled').removeClass('loading').html(originalButtonText);
				$('#loadmore_recurring_donations-fail-message').fadeIn(800);
			});

		e.preventDefault();
	});
	/**
	  *	AJAX doenanova_load_more_recurring_donations
	  */









});
