jQuery(function() {
	jQuery("input[name='Locked']").click(function() {	
		if (jQuery(this).is(':checked')) {
			var selectInput = jQuery("select[name='PaymentMonth']").attr('disabled', 'disabled');
			var lessionsInput = jQuery("input[name='Lessions']").attr('disabled', 'disabled');
			jQuery(".AdvancedDropdown").attr('disabled', 'disabled');
			jQuery('<input class="hidden nolabel backupvalue" type="hidden" name="PaymentMonth" value="' + selectInput.val() + '">').insertAfter(selectInput);
			jQuery('<input class="hidden nolabel backupvalue" type="hidden" name="Lessions" value="' + lessionsInput.val() + '">').insertAfter(lessionsInput);
		}
		else {
			jQuery("select[name='PaymentMonth']").removeAttr('disabled');
			jQuery("input[name='Lessions']").removeAttr('disabled');
			jQuery(".AdvancedDropdown").removeAttr('disabled');
			jQuery("input.backupvalue").remove();
		}
	});
	
	if (jQuery("input[name='Locked']").is(':checked')) {
		var selectInput = jQuery("select[name='PaymentMonth']").attr('disabled', 'disabled');
		var lessionsInput = jQuery("input[name='Lessions']").attr('disabled', 'disabled');
		jQuery(".AdvancedDropdown").attr('disabled', 'disabled');
		jQuery('<input class="hidden nolabel backupvalue" type="hidden" name="PaymentMonth" value="' + selectInput.val() + '">').insertAfter(selectInput);
		jQuery('<input class="hidden nolabel backupvalue" type="hidden" name="Lessions" value="' + lessionsInput.val() + '">').insertAfter(lessionsInput);
	}
	
	// Auto fetch default hourtype and teacher salary
	jQuery('input[name="TeacherID"]').change(function() {
		var teacherID = jQuery(this).val();
		
		jQuery.ajax({
			url: 'admin/coursebooking/getTeacherInfo',
			dataType: 'json',
			data: { 
				'teacherID': teacherID 
			},
			success: function(data) {
				jQuery('input[name="TeacherHourTypeID"]').val(data['hourtype']['value']);
				jQuery('input[name="TeacherHourTypeID"] + input').val(data['hourtype']['code'] + ' ' + data['hourtype']['text']);
				jQuery('input[name="TeacherSalaryClassID"]').val(data['salary']['value']);
				jQuery('input[name="TeacherSalaryClassID"] + input').val(data['salary']['text']);				
			}
		});		
	});
});