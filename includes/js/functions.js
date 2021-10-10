jQuery(document).ready(function ($) {

	//Ajusts #amount input size according to the text inside it
	function ajustInputWidth() {
		var num_caracteres_valor = $("#form-doar #amount").val().length;

		$('.amount_width_container').animate({
			width: "40" * num_caracteres_valor,
		}, 100, function () {
			// Animation complete.
		});
	}

	//When there's a change on #amount, call function ajustInputWidth().
	$("#form-doar #amount").on('keyup change', function () {
		ajustInputWidth();
	});


	//Allow only numbers on #amount
	$("#amount").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			// Allow: Ctrl+A, Command+A
			(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
			// Allow: home, end, left, right, down, up
			(e.keyCode >= 35 && e.keyCode <= 40)) {
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});




	//Fade in and zoom out for #doenanova-wrap
	function doenanova_wrap_fadein() {
		$('#doenanova-wrap').css({ "transform": "scale(1)", "opacity": "100" });
	}
	setTimeout(doenanova_wrap_fadein, 200);



	//ADD A LOADING STATE TO BUTTON ON CLICK
	$('#doenanova-wrap').on('click', '.load-on-click', function (e) {
		e.preventDefault();
		$(this).attr('disabled', 'disabled').addClass('loading').html('<i class="fas fa-circle-notch fa-spin"></i>');

		var form = $(this).parent();

		if (form.is("form")) {
			form.submit();
		} else {
			document.location.href = $(this).attr('href');
		}
	});



	//TRIGGER DROPDOWN
	$("#doacoes-recorrentes").on('click', '.dropdown-menu-btn', function () {
		var target = $(this).data('target');
		$('#' + target).toggleClass("is-active");

		$(this).children().toggleClass("fa-rotate-90");
	});




	//SHOW REPORT
	$('#doenanova-wrap').on('click', '.doenanova-report', function (e) {
		e.preventDefault();
		$('#doenanova-report').fadeIn();
	});
	$('#doenanova-wrap').on('click', '.doenanova-report-close', function (e) {
		e.preventDefault();
		$('#doenanova-report').fadeOut();
	});


	//SHOW HOW TO USE
	$('#doenanova-wrap').on('click', '.doenanova-how-to-use', function (e) {
		e.preventDefault();
		$('#doenanova-how-to-use').fadeIn();
	});
	$('#doenanova-wrap').on('click', '.doenanova-how-to-use-close', function (e) {
		e.preventDefault();
		$('#doenanova-how-to-use').fadeOut();
	});





});
