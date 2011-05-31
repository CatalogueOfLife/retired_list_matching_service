function enableOrDisableFields()
{
	var value_file, value_text;

	value_file = $("input[name=input_type]:checked").val() == "file" ? "inline" : "none";
	value_text = $("input[name=input_type]:checked").val() == "file" ? "none" : "inline";

	$("#file_upload").css("display", value_file);
	$("#text_search").css("display", value_text);

	if (value_file == "inline")
	{
		$("#file_upload").removeAttr("disabled");
		$("#text_search").attr("disabled", "disabled");
	}
	else
	{
		$("#file_upload").attr("disabled", "disabled");
		$("#text_search").removeAttr("disabled");
	}
}