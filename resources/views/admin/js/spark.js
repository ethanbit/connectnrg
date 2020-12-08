jQuery(document).ready(function(){
	jQuery('#order_selectall').click(function(){
		if(jQuery(this).is(':checked')){
			jQuery('#orderlist tbody input[type="checkbox"]').prop('checked', true);
		}else{
			jQuery('#orderlist tbody input[type="checkbox"]').prop('checked', false);
		}
	})

	jQuery('a.delete_holiday').click(function(){
		var getID = jQuery(this).data('id');
		jQuery('#holiday_id').val(getID);
	})

	jQuery('a.holiday_addnew').click(function(){
		jQuery('#editdevice')[0].reset();
		jQuery('#editdevice').attr('action','/admin/addnewholiday');
	})	

	jQuery('a.holiday_edit').click(function(){
		var getID = jQuery(this).data('id');
		var getName = jQuery(this).data('name');
		var getDate = jQuery(this).data('date');
		var getStatus = jQuery(this).data('status');
		jQuery('#edit_holiday_id').val(getID);
		jQuery('#holiday_name').val(getName);
		jQuery('#holiday_date').val(getDate);
		jQuery('#holiday_status option').each(function(){
			var loadStatus = jQuery(this).attr('value');
			if(loadStatus == getStatus){
				jQuery(this).attr('selected', 'selected');
			}
		})
	})
})