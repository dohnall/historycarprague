$(document).ready(function() {

	$("#datepicker").datepicker({
		minDate: "+{if $program->get('value', 'order_before')}{$program->get('value', 'order_before')}{else}1{/if}D",
		//maxDate: "+2M",
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		defaultDate: '{if $order_date}{$order_date}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}',
	    onSelect: function(dateText, inst) {
	        var date = $(this).val();
	        $.post(WEBID+'scripts/ajax.php?action=selectTime', { date: date, program: $('#program').val() }, function(html) {
	            $('#selectTime').html(html);
	            $('#show-form').addClass('displaynone');
	            $('#form_order_time_booked').addClass('displaynone');
	            if($('input[name="time"]:enabled').size() == 0) {
					$('#form_order_time_booked').removeClass('displaynone');
				}
	        });
	    },
	    option: $.datepicker.regional['{$LANG_CODE}']
	});

});
