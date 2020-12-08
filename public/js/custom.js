jQuery(document).ready(function () {
	jQuery(".report th input").change(function () {
		var getColumnClass = jQuery(this).attr("id");
		var getSearchText = jQuery(this).val();
		jQuery(".report th input").val("");
		jQuery(this).val(getSearchText);
		jQuery(
			".report .table td." +
				getColumnClass +
				":contains('" +
				getSearchText +
				"')"
		)
			.parent()
			.show();

		jQuery(
			".report .table td." +
				getColumnClass +
				":not(:contains('" +
				getSearchText +
				"'))"
		)
			.parent()
			.hide();
	});
});
