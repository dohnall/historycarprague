$(document).ready(function() {

	if($("#datepicker .ui-state-active").size() == 1) {
		$("#datepicker .ui-state-active").click();
	}

	$('.changeTotal').change(function() {
		getTotalPrice();
	});

		
	$(document).on('click', 'input[name="time"]:enabled', function() {
		var THIS = $(this);
		
		$('#show-form').removeClass('displaynone');
		
		$('html, body').animate({
	      scrollTop: $("#show-form").offset().top
	    }, 800);
		
		var cars = parseInt(THIS.attr('data-cars'));
		$('input[name="cars"]').attr('max', cars);
		if(parseInt($('input[name="people"]').attr('data-min')) > 0) {
			$('input[name="people"]').attr('min', $('input[name="people"]').attr('data-min'));
		} else {
			$('input[name="people"]').attr('min', 1);
		}
		if(parseInt($('input[name="people"]').attr('data-max')) > 0) {
			$('input[name="people"]').attr('max', $('input[name="people"]').attr('data-max'));
		} else {
			$('input[name="people"]').attr('max', cars * 6);
		}
	});

	function getTotalPrice() {
		var total = 0;
		var total2 = 0;
		var price = parseInt($('#price').val());
		var price_type = parseInt($('#price_type').val());
		var people = parseInt($('input[name="people"]').val());
		var cars = parseInt($('input[name="cars"]').val());

		//cena za vuz
		if(price_type == 1) {
			total = price * cars;
		//cena za osobu
		} else if(price_type == 2) {
			total = price * people;
		//cena za vuz za hodinu
		} else if(price_type == 3) {
			var hours = parseInt($('input[name="hours"]').val());
			total = price * cars * hours;
		}

		$.each($('.accessory'), function(k, v) {
			var THIS = $(v);
			total2 = total2 + THIS.val() * THIS.attr('data-price');		
		});
		total2 = parseInt(total2);

		$('#sumtotal').text(total + total2);
		var sale = parseInt($('#sale').attr('data-item-sale'));
		var totalsale = Math.round(total - (total * sale / 100));

		if(sale > 0) {
			$('#total').text(total);
		} else {
			$('#total').text(total + total2);
		}
		$('#totalsale').text(totalsale);
		$('#total2').text(total2);
		$('#sale').text(Math.round(totalsale + total2));
	}

});
