jQuery(document).ready(function ($) {


	var form = document.getElementById('form-doar');
	var mycard = $("#card").length;

	if (mycard) {

		//VALIDATE WHEN FORM IS SUBMITED WITH A NEW CARD

		// Create a Stripe client
		var stripe = Stripe(phpVars['stripe-pk']);

		// Create an instance of Elements
		var elements = stripe.elements();

		// Try to match bootstrap 4 styling
		var style = {
			base: {
				color: '#080b33',
				fontFamily: 'BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif',
				fontSize: '14px',
				lineHeight: '26px',
				fontWeight: 300,

				'::placeholder': {
					color: '#b2b8c7',
				}
			}
		};

		// Card
		var card = elements.create('card', {
			hidePostalCode: true,
			'style': style
		});

		card.mount('#card');

		// Submit
		$('#payment-submit').on('click', function (e) {
			e.preventDefault();


			//Store button's text so that in case we need to display the text again, after loading, we still have it
			var originalButtonText = $('#payment-submit').html();

			//Add loading state to the button
			$('#payment-submit').attr('disabled', 'disabled').addClass('loading').html('<i class="fa-solid fa-circle-notch fa-spin"></i>');


			var cardData = {
				'name': $('#name').val()
			};


			//If validation doesn't pass
			if ($(form)[0].checkValidity() === false) {

				$('#payment-submit').removeAttr('disabled').removeClass('loading').html(originalButtonText);

				event.stopPropagation();

			} else {

				//If validation passes OK
				stripe.createToken(card, cardData).then(function (result) {

					//console.log(result);

					//Validate again, this time to check if Stripe has any errors
					if (result.error && result.error.message) {

						$('#payment-submit').removeAttr('disabled').removeClass('loading').html(originalButtonText);

					} else {

						// Insert the token ID into the form so it gets submitted to the server
						var hiddenInput = document.createElement('input');
						hiddenInput.setAttribute('type', 'hidden');
						hiddenInput.setAttribute('name', 'stripeToken');
						hiddenInput.setAttribute('value', result.token.id);
						form.appendChild(hiddenInput);

						// Submit the form
						form.submit();

					}
				});

			}

			$('#form-doar').addClass('was-validated');

		});

	} else {


		//VALIDATE WHEN FORM IS SUBMITED WITH AN EXISTING CARD

		$('#payment-submit').on('click', function (e) {
			e.preventDefault();

			var originalButtonText = $('#payment-submit').html();
			$('#payment-submit').attr('disabled', 'disabled').addClass('loading').html('<i class="fa-solid fa-circle-notch fa-spin"></i>');

			if ($(form)[0].checkValidity() === false) {
				$('#payment-submit').removeAttr('disabled').removeClass('loading').html(originalButtonText);
				event.stopPropagation();

			} else {
				// Submit the form
				form.submit();
			}

			$('#form-doar').addClass('was-validated');
		});



	}











});
