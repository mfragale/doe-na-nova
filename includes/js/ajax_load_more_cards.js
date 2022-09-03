jQuery(document).ready(function ($) {





	/**
	  *	AJAX doenanova_load_more_cards
	  */

	//Just checking if everything is working thus far
	console.log("HELLO doenanova_load_more_cards");

	//checking if we're getting our PHP variables
	console.log(phpVars);


	/**
	*	Click event
	*/
	$('#loadmore_cards-btn').click(function (e) {

		//Confirm the button has been clicked
		console.log("Load more button clicked");

		//Create our variables to be sent
		dataSend = {};
		dataSend.action = phpVars['wp-action-doenanova-load-more-cards'];
		dataSend.last_card = $('#last_card').val();

		$('.loadmore_cards-message').hide();

		//Store button's text so that in case we need to display the text again, after loading, we still have it
		var originalButtonText = $('#loadmore_cards-btn').html();

		//Add loading state to the button
		$('#loadmore_cards-btn').attr('disabled', 'disabled').addClass('loading').html('<i class="fas fa-circle-notch fa-spin"></i>');


		$.ajax({
			type: "post",
			url: phpVars['checkout-url'],
			data: dataSend,
			dataType: 'json',
		})
			.done(function (data, status) {

				//Show what has been the result
				console.log("$.ajax.done");
				console.log("data: ", data);
				console.log("status: ", status);

				$('#loadmore_cards-btn').removeAttr('disabled').removeClass('loading').html(originalButtonText);

				// //If we receive back $return_data["success"] = true;
				// if (data.success) {

				// 	//Loop through each array element and perform the following
				// 	$.each(data, function (index, value) {

				// 		//Loop through all elements except these ones, because they are "success" from $return_data["success"] = true;.
				// 		if (index === 'success' || index === 'has_more_cards') {
				// 			return;
				// 		}

				// 		//Change the value of the hidden field #last_card to the new last card ID		
				// 		$('#last_card').attr('value', value.card_id);

				// 		//Append the newly created elements to this div
				// 		$("#cartoes").append(function () {

				// 			//Create our variables based on the values we got from the response
				// 			var card_id = value.card_id;
				// 			var card_last4 = value.card_last4;
				// 			var card_funding = value.card_funding;
				// 			var card_brand = value.card_brand;
				// 			var card_name = value.card_name;
				// 			var card_exp_month = value.card_exp_month;
				// 			var card_exp_year = value.card_exp_year;

				// 			// (Date in JS sucks...)
				// 			var timestamp = value.card_date;
				// 			var date = new Date(timestamp * 1000);

				// 			var month = new Array();
				// 			month[0] = phpVars.jan;
				// 			month[1] = phpVars.feb;
				// 			month[2] = phpVars.mar;
				// 			month[3] = phpVars.apr;
				// 			month[4] = phpVars.may;
				// 			month[5] = phpVars.jun;
				// 			month[6] = phpVars.jul;
				// 			month[7] = phpVars.aug;
				// 			month[8] = phpVars.sep;
				// 			month[9] = phpVars.oct;
				// 			month[10] = phpVars.nov;
				// 			month[11] = phpVars.dec;
				// 			var n = month[date.getMonth()];
				// 			var card_month = ("0" + (date.getMonth() + 1)).slice(-2);

				// 			var card_year = date.getFullYear().toString().substr(-2);
				// 			var card_day = ("0" + (date.getDate())).slice(-2);
				// 			var card_month_number = ("0" + (date.getMonth() + 1)).slice(-2);

				// 			var weekDay = new Array();
				// 			weekDay[0] = phpVars.sun;
				// 			weekDay[1] = phpVars.mon;
				// 			weekDay[2] = phpVars.tue;
				// 			weekDay[3] = phpVars.wed;
				// 			weekDay[4] = phpVars.thu;
				// 			weekDay[5] = phpVars.fri;
				// 			weekDay[6] = phpVars.sat;
				// 			var w = weekDay[date.getDay()];
				// 			var card_week_day = w;
				// 			// (Date in JS sucks...)

				// 			// (Date in JS sucks...)
				// 			var timestamp2 = value.card_nextBilling;
				// 			var date2 = new Date(timestamp2 * 1000);
				// 			var month2 = new Array();
				// 			month2[0] = phpVars.jan;
				// 			month2[1] = phpVars.feb;
				// 			month2[2] = phpVars.mar;
				// 			month2[3] = phpVars.apr;
				// 			month2[4] = phpVars.may;
				// 			month2[5] = phpVars.jun;
				// 			month2[6] = phpVars.jul;
				// 			month2[7] = phpVars.aug;
				// 			month2[8] = phpVars.sep;
				// 			month2[9] = phpVars.oct;
				// 			month2[10] = phpVars.nov;
				// 			month2[11] = phpVars.dec;
				// 			var n2 = month2[date2.getMonth()];
				// 			var card_nextBilling_month = ("0" + (date2.getMonth() + 1)).slice(-2);
				// 			var card_nextBilling_year = date2.getFullYear().toString().substr(-2);;
				// 			var card_nextBilling_day = ("0" + (date2.getDate())).slice(-2);
				// 			var card_nextBilling_month_number = ("0" + (date2.getMonth() + 1)).slice(-2);

				// 			var weekDay2 = new Array();
				// 			weekDay2[0] = phpVars.sun;
				// 			weekDay2[1] = phpVars.mon;
				// 			weekDay2[2] = phpVars.tue;
				// 			weekDay2[3] = phpVars.wed;
				// 			weekDay2[4] = phpVars.thu;
				// 			weekDay2[5] = phpVars.fri;
				// 			weekDay2[6] = phpVars.sat;
				// 			var w2 = weekDay2[date2.getDay()];
				// 			var card_nextBilling_week_day = w2;
				// 			// (Date in JS sucks...)

				// 			// if (card_status == 'active') {
				// 			// 	var badge = 'is-success';
				// 			// 	var icon = 'check-circle';
				// 			// 	var status = 'Succeeded';
				// 			// } else if (card_status == 'incomplete') {
				// 			// 	var badge = 'is-warning';
				// 			// 	var icon = 'exclamation-circle';
				// 			// 	var status = 'Pending';
				// 			// } else if (card_status == 'canceled') {
				// 			// 	var badge = 'is-danger';
				// 			// 	var icon = 'times-circle';
				// 			// 	var status = 'Failed';
				// 			// }


				// 			//Output what we want			  
				// 			return `
				// 			<div class="accordion-item" id="card-` + card_id + `">
				// 				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#card-` + card_id + `-dropdown" aria-expanded="true" aria-controls="card-` + card_id + `-dropdown">
				// 					<div class="col">
				// 						<i class="fab fa-cc-` + card_brand + `"></i> ` + card_last4 + `
				// 					</div>
				// 					<div class="col text-end me-3">
				// 						` + card_funding + `
				// 					</div>
				// 				</button>

				// 				<div id="card-` + card_id + `-dropdown" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">





				// 					<div class="accordion-body">

				// 						<!-- Button trigger modal -->
				// 						<button type="button" class="btn btn-xs btn-danger float-end" data-bs-toggle="modal" data-bs-target="#` + card_id + `-modal" title="Deletar este cartão">
				// 							<i class="fas fa-trash-alt"></i>
				// 						</button>

				// 						<!-- Button trigger modal -->
				// 						<button type="button" class="btn btn-xs btn-secondary float-end mx-2" data-bs-toggle="modal" data-bs-target="#` + card_id + `-modal-activate" title="Usar este cartão">
				// 							Usar este cartão
				// 						</button>

				// 						<table class="table">
				// 							<tbody>
				// 								<tr>
				// 									<th scope="row">Nome no cartão</th>
				// 									<td>` + card_name + `</td>
				// 								</tr>
				// 								<tr>
				// 									<th scope="row">Expira em</th>
				// 									<td>` + card_exp_month + `/` + card_exp_year + `</td>
				// 								</tr>
				// 							</tbody>
				// 						</table>


				// 						<!-- Modal -->
				// 						<div class="modal fade" id="` + card_id + `-modal" tabindex="-1" aria-labelledby="` + card_id + `-modalLabel" aria-hidden="true">
				// 							<div class="modal-dialog">
				// 								<div class="modal-content">
				// 									<div class="modal-header">
				// 										<h5 class="modal-title" id="` + card_id + `-modalLabel">Deletar cartão</h5>
				// 										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				// 									</div>
				// 									<div class="modal-body">
				// 										Tem certeza que deseja deletar o cartão <i class="fab fa-cc-` + card_brand + `"></i> final ` + card_last4 + `?
				// 									</div>
				// 									<div class="modal-footer">
				// 										<form id="delete_card" action="` + phpVars.action_url + `admin-post.php" method="POST">
				// 											<input type='hidden' name='action' value='stripe_delete_card' />
				// 											<input type="hidden" name='card_id' value='` + card_id + `' />
				// 											<input type="hidden" name="current_url" id="current_url" value="` + phpVars.current_url + `">
				// 											<button type="button" class="btn btn-danger load-on-click">Sim, deletar por favor</button>
				// 										</form>
				// 									</div>
				// 								</div>
				// 							</div>
				// 						</div>


				// 						<!-- Modal ACTIVATE CARD -->
				// 						<div class="modal fade" id="` + card_id + `-modal-activate" tabindex="-1" aria-labelledby="` + card_id + `-modal-activateLabel" aria-hidden="true">
				// 							<div class="modal-dialog">
				// 								<div class="modal-content">
				// 									<div class="modal-header">
				// 										<h5 class="modal-title" id="` + card_id + `-modal-activateLabel">Ativar cartão</h5>
				// 										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				// 									</div>
				// 									<div class="modal-body">
				// 										Tem certeza que deseja usar o cartão <i class="fab fa-cc-` + card_brand + `"></i> final ` + card_last4 + ` para futuras doações?
				// 									</div>
				// 									<div class="modal-footer">
				// 										<form id="activate_card" action="` + phpVars.action_url + `admin-post.php" method="POST">
				// 											<input type='hidden' name='action' value='stripe_activate_card' />
				// 											<input type="hidden" name='card_id' value='` + card_id + `' />
				// 											<input type="hidden" name="current_url" id="current_url" value="` + phpVars.current_url + `">
				// 											<button type="button" class="btn btn-danger load-on-click">Sim, use este cartão por favor</button>
				// 										</form>
				// 									</div>
				// 								</div>
				// 							</div>
				// 						</div>




				// 					</div>





				// 				</div>
				// 			</div>
				// 			`;






				// 		}); //$( "#ajax_response" ).append

				// 		$('.new_cards').hide();
				// 		$('.new_cards').fadeIn(800).removeClass('new_cards');

				// 	}); //$.each	

				// 	if (!data.has_more_cards) {
				// 		$('#loadmore_cards-btn').hide();
				// 	}


				// } else {
				// 	//Show this fail message if something went wrong with the AJAX response
				// 	$('#loadmore_cards-fail-message').fadeIn(800);
				// }
			})
			.fail(function (data, status, error) {

				//Show what has been the result
				console.log("$.ajax.fail");
				console.log("data: ", data);
				console.log("status: ", status);
				console.log("error: ", error);

				//Show this fail message if something went wrong with the AJAX sending
				$('#loadmore_cards-btn').removeAttr('disabled').removeClass('loading').html(originalButtonText);
				$('#loadmore_cards-fail-message').fadeIn(800);
			});

		e.preventDefault();
	});
	/**
	  *	AJAX doenanova_load_more_cards
	  */









});
