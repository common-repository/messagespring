jQuery(document).ready(function($){
	var targetButton = $('.twy_messagespring_subscribe_button');
	var targetLink = $('.twy_messagespring_subscribe_link');
	var msApiKeyCheckBool = true;
	if($('#twy_messagespring_settings_widget_type').val() == 'link'){
		$('.twy_messagespring_settings_on_link').show();
		$('.twy_messagespring_settings_on_button').hide();
	}
	else{
		$('.twy_messagespring_settings_on_button').show();
		$('.twy_messagespring_settings_on_link').hide();
	}
	$('#twy_messagespring_settings_widget_type').on('change',function(){
		if($('#twy_messagespring_settings_widget_type').val() == 'link'){
			$('.twy_messagespring_settings_on_link').show();
			$('.twy_messagespring_settings_on_button').hide();
		}
		else{
			$('.twy_messagespring_settings_on_button').show();
			$('.twy_messagespring_settings_on_link').hide();
		}
	});
	
	$('.twy_messagespring_subscribe_button,.twy_messagespring_subscribe_link').on('click',function(e){
		e.preventDefault();
	});
	$('#twy_messagespring_settings_link_text').on('keyup',function(){
		var linkText = $(this).val();
		linkText = (linkText.length > 0) ? linkText : 'Follow us on MessageSpring';
		targetLink.html(linkText);
	});
	$('#twy_messagespring_settings_button_text').on('keyup',function(){
		var buttonText = $(this).val();
		buttonText = (buttonText.length > 0) ? buttonText : 'Follow us on MessageSpring';
		targetButton.html(buttonText);
	});
	$('#twy_messagespring_settings_text_color').wpColorPicker({
		change: function(event, ui){
			var colorCode = ui.color.toString();
			targetLink.css('color',colorCode);
		},
		clear: function(){
			targetLink.css('color','#0097A7');
		}
	});
	$('#twy_messagespring_settings_button_text_color').wpColorPicker({
		change: function(event, ui){
			var colorCode = ui.color.toString();
			targetButton.css('color',colorCode);
		},
		clear: function(){
			targetButton.css('color','#ffffff');
		}
	});
	$('#twy_messagespring_settings_button_color').wpColorPicker({
		change: function(event, ui){
			var colorCode = ui.color.toString();
			targetButton.css('background-color',colorCode);
		},
		clear: function(){
			targetButton.css('background-color','#0097A7');
		}
	});
	
	$('#twy_messagespring_settings_api').on('keyup change',function(){
		if(!msApiKeyCheckBool && $(this).length > 0){
			msApiKeyCheckBool = true;
			$('#twy_messagespring_settings_field_test_api').show();
			$('#twy_messagespring_settings_field_test_api_result').html("");
		}
	});
	
	$('#twy_messagespring_settings_field_test_api').on('click',function(e){
		e.preventDefault();
		var apiKey = $('#twy_messagespring_settings_api').val();
		if(apiKey && apiKey.length > 0){
			var data = {
				'action': 'twy_messagespring_verify_api_key',
				'mskey': apiKey
			};
			$(this).hide();
			$('#twy_messagespring_settings_field_test_api_result').html("Hang tight while we check your token!");
			$.post(ajaxurl,data,function(response){
				if(response.trim() == 'success'){
					$('#twy_messagespring_settings_field_test_api_result').css('color','green');
					$('#twy_messagespring_settings_field_test_api_result').html("YOUR API TOKEN IS VALID!");
					msApiKeyCheckBool = true;
				}
				else{
					$('#twy_messagespring_settings_field_test_api_result').css('color','red');
					$('#twy_messagespring_settings_field_test_api_result').html("THIS API TOKEN IS INVALID!");
					msApiKeyCheckBool = false;
				}
			});
		}
	});
});