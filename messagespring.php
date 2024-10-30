<?php
/*
	Plugin Name: MessageSpring Omnichannel
	Plugin URI: https://www.messagespring.com
	Description: This plugin connects your website with your MessageSpring account. MessageSpring is an omnichannel communication platform that connects you with your audience along topic lines in multiple channels and any language. 
	Version: 1.0.9
	Author: MessageSpring Development Team
*/

add_action('wp_enqueue_scripts','twy_messagespring_load_js');
function twy_messagespring_load_js(){
	$twy_messagespring_settings_api = get_option('twy_messagespring_settings_api','');
	if($twy_messagespring_settings_api){
		wp_enqueue_script('messageSpringSdk','https://developers.messagespring.com/subscription/static/sdk.js',array(),'1.0.0',true);
	}
}

function twy_ms_generate_widget(){
	$twy_messagespring_output = "";
	$twy_messagespring_settings_widget_type = get_option('twy_messagespring_settings_widget_type','link');
	if($twy_messagespring_settings_widget_type == "link"){
		$twy_messagespring_settings_link_text = get_option('twy_messagespring_settings_link_text','Follow us on MessageSpring');
		$twy_messagespring_settings_text_color = get_option('twy_messagespring_settings_text_color','#0097A7');
		?>
			<div class="twy_messagespring_subscribe_block_frontend"><a href="#" class="twy_messagespring_subscribe-btn twy_messagespring_subscribe_link" style="color:<?php echo esc_attr($twy_messagespring_settings_text_color); ?>;"><?php echo esc_html($twy_messagespring_settings_link_text); ?></a></div>
		<?php
	}
	else{
		$twy_messagespring_settings_button_text = get_option('twy_messagespring_settings_button_text','Follow us on MessageSpring');
		$twy_messagespring_settings_button_text_color = get_option('twy_messagespring_settings_button_text_color','#ffffff');
		$twy_messagespring_settings_button_color = get_option('twy_messagespring_settings_button_color','#0097A7');
		?>
			<div class="twy_messagespring_subscribe_block_frontend"><button class="twy_messagespring_subscribe-btn twy_messagespring_subscribe_button" style="color:<?php echo esc_attr($twy_messagespring_settings_button_text_color); ?>;background-color:<?php echo esc_attr($twy_messagespring_settings_button_color); ?>;"><?php echo esc_html($twy_messagespring_settings_button_text); ?></button></div>
		<?php
	}
}

add_shortcode('ms_display_widget','twy_messagespring_generate_shortcode');
function twy_messagespring_generate_shortcode(){
	ob_start();
	twy_ms_generate_widget();
	return ob_get_clean();
}

add_action('wp_footer','twy_messagespring_show_frontend');
function twy_messagespring_show_frontend(){
	$twy_messagespring_settings_api = get_option('twy_messagespring_settings_api','');
	if($twy_messagespring_settings_api){
		$twy_messagespring_settings_widget_show_footer = get_option('twy_messagespring_settings_widget_show_footer','');
		if($twy_messagespring_settings_widget_show_footer){
			twy_ms_generate_widget();
		}
	?>
		<style>
		.twy_messagespring_subscribe_block_frontend{
			margin-right: 20px;
		}
		.twy_messagespring_subscribe_button{
			padding:10px 20px;
			border:0px;
			cursor:pointer;
			border-radius:5px;
		}
		</style>
		<script type="text/javascript">
		  window.msAsyncInit = function () {
			MS.init({
			  apiKey            : "<?php echo esc_attr($twy_messagespring_settings_api); ?>",
			  openWidgetSelector: ".twy_messagespring_subscribe-btn"
			});
		  };
		</script>
	<?php
	}
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__),'twy_messagespring_add_plugin_links');
function twy_messagespring_add_plugin_links($links){
	$links[] = '<a href="'.admin_url('admin.php?page=MessageSpring').'">'. __('Settings').'</a>';
	return $links;
}

add_action('admin_enqueue_scripts','twy_messagespring_include_admin_scripts');
function twy_messagespring_include_admin_scripts($hook){
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('twy_messagespring_admin_handle',plugins_url('admin.js', __FILE__ ),array('wp-color-picker'),false,true);
	wp_enqueue_script('messageSpringSdk','https://developers.messagespring.com/subscription/static/sdk.js',array(),'1.0.0',true);
}

add_action('admin_menu','twy_messagespring_add_menu_page');
function twy_messagespring_add_menu_page(){
	add_menu_page('MessageSpring','MessageSpring','manage_options','MessageSpring','twy_messagespring_show_menu_page',plugins_url('images/icon.png', __FILE__ ));
}

function twy_messagespring_show_menu_page(){
	?>
	<style>
		.twy_messagespring_settings_field{
			width:350px;
		}
		.twy_messagespring_settings_field_api{
			width:550px;
		}
		.twy_messagespring_settings_table td{
			padding:10px;
		}
		.twy_messagespring_settings_button{
			cursor:pointer;
			background-color:#094778;
			padding:10px 20px;
			color:#fff;
			border:0px;
			outline:0px;
			border-radius:5px;
		}
		.twy_messagespring_settings_button:hover{
			opacity:0.8;
			color:#fff;
			text-decoration:none;
		}
		.twy_messagespring_settings_on_button .wp-picker-holder,.twy_messagespring_settings_on_link .wp-picker-holder{
			position:absolute;
		}
		.twy_messagespring_settings_button_output_style,.twy_messagespring_settings_link_output_style{
			background-color:#fff;
			border:2px solid #14335d;
			border-top:0px;
			text-align:center;
			color:#000;
			width:300px;
			max-width:300px;
		}
		.twy_messagespring_settings_button_output_style_heading,.twy_messagespring_settings_link_output_style_heading{
			color:#fff;
			background-color:#14335d;
			padding:5px 10px;
			text-align:left;
		}
		.twy_messagespring_settings_button_output_style_content,.twy_messagespring_settings_link_output_style_content{
			padding: 0px 10px 25px;
		}
		.twy_messagespring_settings_button_output_style_content .twy_messagespring_subscribe_button{
			padding:10px 20px;
			border:0px;
			cursor:pointer;
			border-radius:5px;
		}
		#twy_messagespring_settings_field_test_api_result{
			font-weight:bold;
		}
		.twy_messagespring_settings_image{
			border:0px !important;
		}
		.twy_messagespring_settings_link_output_style_content_para{
			margin:5px;
		}
		#twy_messagespring_settings_field_test_api,.twy_link{
			color:#14335d;
			text-decoration:underline;
		}
		.twy_messagespring_settings_button_block{
			margin:20px;
			text-align:right;
		}
		.twy_messagespring_settings_heading{
			margin:20px;
			text-align:center;
			font-size:24px;
			font-weight:bold;
		}
		.twy_messagespring_settings_how_to_display{
			text-align:center;
		}
		.twy_messagespring_settings_how_to_display_block{
			width:30%;
			float:left;
			margin:10px;
		}
		.twy_bold{
			font-weight:bold;
		}
		.twy_green{
			color:green;
		}
		.twy_messagespring_settings_how_to_display_block_content{
			background-color:#fff;
			border:1px solid #000;
			padding:10px;
		}
		.twy_messagespring_settings_how_to_display_block_content_height{
			height:200px;
		}
		.twy_messagespring_settings_checkbox{
			margin:20px !important;
			border-radius:0px !important;
			width:20px !important;
			height:20px !important;
		}
	</style>
	<div class="wrap">
		<?php
			if(isset($_POST['twy_messagespring_settings']) && check_admin_referer('twy_ms','twy_ms_nonce')){
				$twy_messagespring_settings_api = sanitize_text_field($_POST['twy_messagespring_settings_api']);
				$twy_messagespring_settings_widget_show_footer = sanitize_text_field($_POST['twy_messagespring_settings_widget_show_footer']);
				if(!$twy_messagespring_settings_widget_show_footer){
					$twy_messagespring_settings_widget_show_footer = '0';
				}
				$twy_messagespring_settings_widget_own_button = sanitize_text_field($_POST['twy_messagespring_settings_widget_own_button']);
				if(!$twy_messagespring_settings_widget_own_button){
					$twy_messagespring_settings_widget_own_button = '0';
				}
				$twy_messagespring_settings_widget_shortcode = sanitize_text_field($_POST['twy_messagespring_settings_widget_shortcode']);
				if(!$twy_messagespring_settings_widget_shortcode){
					$twy_messagespring_settings_widget_shortcode = '0';
				}
				$twy_messagespring_settings_widget_type = sanitize_text_field($_POST['twy_messagespring_settings_widget_type']);
				$twy_messagespring_settings_link_text = sanitize_text_field($_POST['twy_messagespring_settings_link_text']);
				$twy_messagespring_settings_text_color = sanitize_text_field($_POST['twy_messagespring_settings_text_color']);
				$twy_messagespring_settings_button_text = sanitize_text_field($_POST['twy_messagespring_settings_button_text']);
				$twy_messagespring_settings_button_text_color = sanitize_text_field($_POST['twy_messagespring_settings_button_text_color']);
				$twy_messagespring_settings_button_color = sanitize_text_field($_POST['twy_messagespring_settings_button_color']);
				if($twy_messagespring_settings_api){
					$requestOutput = twy_messagespring_check_api_key($twy_messagespring_settings_api);
					if(isset($requestOutput['status']) && $requestOutput['status'] == 200){
						$twy_messagespring_settings_link_text = ($twy_messagespring_settings_link_text) ? $twy_messagespring_settings_link_text : 'Follow us on MessageSpring';
						$twy_messagespring_settings_text_color = ($twy_messagespring_settings_text_color) ? $twy_messagespring_settings_text_color : '#0097A7';
						$twy_messagespring_settings_button_text = ($twy_messagespring_settings_button_text) ? $twy_messagespring_settings_button_text : 'Follow us on MessageSpring';
						$twy_messagespring_settings_button_text_color = ($twy_messagespring_settings_button_text_color) ? $twy_messagespring_settings_button_text_color : '#ffffff';
						$twy_messagespring_settings_button_color = ($twy_messagespring_settings_button_color) ? $twy_messagespring_settings_button_color : '#0097A7';
						if(strlen($twy_messagespring_settings_link_text) > 30){
							$twy_messagespring_settings_link_text = substr($twy_messagespring_settings_link_text,0,30);
						}
						if(strlen($twy_messagespring_settings_button_text) > 30){
							$twy_messagespring_settings_button_text = substr($twy_messagespring_settings_button_text,0,30);
						}
						update_option('twy_messagespring_settings_api',$twy_messagespring_settings_api);
						update_option('twy_messagespring_settings_widget_show_footer',$twy_messagespring_settings_widget_show_footer);
						update_option('twy_messagespring_settings_widget_own_button',$twy_messagespring_settings_widget_own_button);
						update_option('twy_messagespring_settings_widget_shortcode',$twy_messagespring_settings_widget_shortcode);
						update_option('twy_messagespring_settings_widget_type',$twy_messagespring_settings_widget_type);
						update_option('twy_messagespring_settings_link_text',$twy_messagespring_settings_link_text);
						update_option('twy_messagespring_settings_text_color',$twy_messagespring_settings_text_color);
						update_option('twy_messagespring_settings_button_text',$twy_messagespring_settings_button_text);
						update_option('twy_messagespring_settings_button_text_color',$twy_messagespring_settings_button_text_color);
						update_option('twy_messagespring_settings_button_color',$twy_messagespring_settings_button_color);
						echo '<div class="notice notice-success is-dismissible"><p>Settings Saved!</p></div>';
					}
					else{
						echo '<div class="notice notice-error is-dismissible"><p>Error : The MessageSpring API Token that you provided was incorrect. Please see the instructions below and try again.</p></div>';
					}
				}
				else{
					echo '<div class="notice notice-error is-dismissible"><p>Error : API Token is Required</p></div>';
				}
			}
			$twy_messagespring_settings_api = get_option('twy_messagespring_settings_api','');
			$twy_messagespring_settings_widget_show_footer = get_option('twy_messagespring_settings_widget_show_footer','1');
			$twy_messagespring_settings_widget_own_button = get_option('twy_messagespring_settings_widget_own_button','0');
			$twy_messagespring_settings_widget_shortcode = get_option('twy_messagespring_settings_widget_shortcode','0');
			$twy_messagespring_settings_widget_type = get_option('twy_messagespring_settings_widget_type','link');
			$twy_messagespring_settings_link_text = get_option('twy_messagespring_settings_link_text','Follow us on MessageSpring');
			$twy_messagespring_settings_text_color = get_option('twy_messagespring_settings_text_color','#0097A7');
			$twy_messagespring_settings_button_text = get_option('twy_messagespring_settings_button_text','Follow us on MessageSpring');
			$twy_messagespring_settings_button_text_color = get_option('twy_messagespring_settings_button_text_color','#ffffff');
			$twy_messagespring_settings_button_color = get_option('twy_messagespring_settings_button_color','#0097A7');
			if($twy_messagespring_settings_api){
				?>
			<script type="text/javascript">
			  window.msAsyncInit = function () {
				MS.init({
				  apiKey            : "<?php echo esc_attr($twy_messagespring_settings_api); ?>",
				  openWidgetSelector: ".twy_messagespring_subscribe-btn"
				});
			  };
			</script>
			<?php
			}
		?>
		<img src="<?php echo esc_url(plugins_url('images/logo.png', __FILE__ )); ?>" class="twy_messagespring_settings_image"><BR><BR>
		<form action="<?php echo esc_url(str_replace('%7E', '~', $_SERVER['REQUEST_URI'])); ?>" method="post">
			<input type="hidden" name="twy_messagespring_settings" value="1">
			<?php
				wp_nonce_field('twy_ms','twy_ms_nonce');
			?>
			<?php
				if($twy_messagespring_settings_api){
					?>
					<p>Your MessageSpring plugin is configured correctly.</p>
					<?php
				}
				else{
			?>
					<p>With this simple plugin for MessageSpring, you can add a button or a link to your website and allow your customers or followers to subscribe to what you have to say. If you don’t have a MessageSpring account, <a href="https://dashboard.messagespring.com/register" target="_blank"><strong>Sign Up</strong></a> now.</p>
					<p>If you have an existing MessageSpring account, log in with this browser and click <a href="https://dashboard.messagespring.com/integrations/subscriber-widget" target="_blank"><strong>here</strong></a> to get your API Token.</p>
			<?php
				}
			?>
			<table class="twy_messagespring_settings_table">
				<tr>
					<td>The API Token from your MessageSpring account:</td>
					<td><input type="password" name="twy_messagespring_settings_api" id="twy_messagespring_settings_api" class="twy_messagespring_settings_field_api" value="<?php echo esc_html($twy_messagespring_settings_api); ?>"></td>
					<td><a href="#" id="twy_messagespring_settings_field_test_api">Test API Token</a><span id="twy_messagespring_settings_field_test_api_result"></span></td>
				</tr>
				<tr>
					<td>Do you want to the MessageSpring widget to be a button or a link?</td>
					<td>
						<select name="twy_messagespring_settings_widget_type" id="twy_messagespring_settings_widget_type">
							<option value="link"<?php echo (esc_html($twy_messagespring_settings_widget_type) == "link") ? ' selected' : ''; ?>>Link</option>
							<option value="button"<?php echo (esc_html($twy_messagespring_settings_widget_type) == "button") ? ' selected' : ''; ?>>Button</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr class="twy_messagespring_settings_on_link">
					<td>What should the link text be?</td>
					<td><input type="text" name="twy_messagespring_settings_link_text" id="twy_messagespring_settings_link_text" class="twy_messagespring_settings_field" value="<?php echo esc_html($twy_messagespring_settings_link_text); ?>" maxlength="30"></td>
					<td rowspan="2">
						<div class="twy_messagespring_settings_link_output_style">
							<div class="twy_messagespring_settings_link_output_style_heading">Link Style</div>
							<div class="twy_messagespring_settings_link_output_style_content">
								<p class="twy_messagespring_settings_link_output_style_content_para">Based on your design selections, your link will appear like below.</p>
								<p class="twy_messagespring_settings_link_output_style_content_para">Click the link to view the subscriber widget.</p>
								<div class="twy_messagespring_settings_link_output_style_link">
									<a href="#" class="twy_messagespring_subscribe-btn twy_messagespring_subscribe_link" style="color:<?php echo esc_attr($twy_messagespring_settings_text_color); ?>;"><?php echo esc_html($twy_messagespring_settings_link_text); ?></a>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr class="twy_messagespring_settings_on_link">
					<td>What color should the text be?</td>
					<td><input type="text" name="twy_messagespring_settings_text_color" id="twy_messagespring_settings_text_color" class="twyMessageSpringColor" value="<?php echo esc_html($twy_messagespring_settings_text_color); ?>"></td>
				</tr>
				<tr class="twy_messagespring_settings_on_button">
					<td>What should the button text be?</td>
					<td><input type="text" name="twy_messagespring_settings_button_text" id="twy_messagespring_settings_button_text" class="twy_messagespring_settings_field twy_messagespring_settings_field_button" value="<?php echo esc_html($twy_messagespring_settings_button_text); ?>" maxlength="30"></td>
					<td rowspan="3">
						<div class="twy_messagespring_settings_button_output_style">
							<div class="twy_messagespring_settings_button_output_style_heading">Button Style</div>
							<div class="twy_messagespring_settings_button_output_style_content">
								<p class="twy_messagespring_settings_link_output_style_content_para">Based on your design selections, your button will appear like below.</p>
								<p class="twy_messagespring_settings_link_output_style_content_para">Click the button to view the subscriber widget.</p>
								<div class="twy_messagespring_settings_button_output_style_button">
									<button class="twy_messagespring_subscribe-btn twy_messagespring_subscribe_button" style="color:<?php echo esc_attr($twy_messagespring_settings_button_text_color); ?>;background-color:<?php echo esc_attr($twy_messagespring_settings_button_color); ?>;"><?php echo esc_html($twy_messagespring_settings_button_text); ?></button>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr class="twy_messagespring_settings_on_button">
					<td>What color should the text be?</td>
					<td><input type="text" name="twy_messagespring_settings_button_text_color" id="twy_messagespring_settings_button_text_color" class="twyMessageSpringColor twy_messagespring_settings_field_button" value="<?php echo esc_html($twy_messagespring_settings_button_text_color); ?>"></td>
				</tr>
				<tr class="twy_messagespring_settings_on_button">
					<td>What color should the button be?</td>
					<td><input type="text" name="twy_messagespring_settings_button_color" id="twy_messagespring_settings_button_color" class="twyMessageSpringColor twy_messagespring_settings_field_button" value="<?php echo esc_html($twy_messagespring_settings_button_color); ?>"></td>
				</tr>
			</table>
			<div class="twy_messagespring_settings_heading">How do you want to add the MessageSpring button to your site?</div>
			<div class="twy_messagespring_settings_how_to_display">
				<div class="twy_messagespring_settings_how_to_display_block">
					<p class="twy_bold twy_green">Most Popular</p><br>
					<div class="twy_messagespring_settings_how_to_display_block_content">
						<div class="twy_messagespring_settings_how_to_display_block_content_height">
							Please follow these <a class="twy_link" href="https://www.wpbeginner.com/wp-tutorials/how-to-add-a-shortcode-in-wordpress/" target="_blank">instructions</a> below see how to add this shortcode to your site's pages.<br><br>
							<p><code>[ms_display_widget]</code></p>
						</div>
						<strong>Use Shortcode.</strong>
					</div>
					<input type="checkbox" class="twy_messagespring_settings_checkbox" name="twy_messagespring_settings_widget_shortcode" value="1"<?php echo (esc_html($twy_messagespring_settings_widget_shortcode)) ? ' checked' : ''; ?>>
				</div>
				<div class="twy_messagespring_settings_how_to_display_block">
					<p class="twy_bold">Bring your own Button</p><br>
					<div class="twy_messagespring_settings_how_to_display_block_content">
						<div class="twy_messagespring_settings_how_to_display_block_content_height">
							Example<br><br>
							<p><code>&lt;a link="#" class="messagespring_subscribe-btn"&gt;Try Me&lt;/a&gt;</code></p><br>
							<p><code>messagespring_subscribe-btn</code></p>
						</div>
						<strong>Your own class or widget.</strong>
					</div>
					<input type="checkbox" class="twy_messagespring_settings_checkbox" name="twy_messagespring_settings_widget_own_button" value="1"<?php echo (esc_html($twy_messagespring_settings_widget_own_button)) ? ' checked' : ''; ?>>
				</div>
				<div class="twy_messagespring_settings_how_to_display_block">
					<p class="twy_bold">Easiest</p><br>
					<div class="twy_messagespring_settings_how_to_display_block_content">
						<div class="twy_messagespring_settings_how_to_display_block_content_height">
							<img src="<?php echo esc_url(plugins_url('images/in-the-footer.png', __FILE__ )); ?>" class="twy_messagespring_settings_image" style="display:block;margin:auto;">
						</div>
						<strong>In the Footer.</strong>
					</div>
					<input type="checkbox" class="twy_messagespring_settings_checkbox" name="twy_messagespring_settings_widget_show_footer" value="1"<?php echo (esc_html($twy_messagespring_settings_widget_show_footer)) ? ' checked' : ''; ?>>
				</div>
			</div>
			<div class="twy_messagespring_settings_button_block">
				<button class="twy_messagespring_settings_button">Save Settings</button>
			</div>
		</form>
	</div>
	<?php
}

add_action('wp_ajax_twy_messagespring_verify_api_key','twy_messagespring_verify_api_key');
function twy_messagespring_verify_api_key(){
	$twy_messagespring_settings_api = sanitize_text_field($_POST['mskey']);
	$requestOutput = twy_messagespring_check_api_key($twy_messagespring_settings_api);
	if(isset($requestOutput['status']) && $requestOutput['status'] == 200){
		echo "success";
	}
	else{
		echo "error";
	}
	wp_die();
}

function twy_messagespring_check_api_key($twy_messagespring_settings_api = ''){
	$requestOutput = array();
	if($twy_messagespring_settings_api){
		$requestOutput = json_decode(wp_remote_retrieve_body(wp_remote_get('https://open-api.ifyoucan.com/v1/public/verify',array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer '.$twy_messagespring_settings_api
			)
		))),true);
	}
	return $requestOutput;
}

register_deactivation_hook( __FILE__, 'twy_messagespring_deactivated');
function twy_messagespring_deactivated(){
	delete_option('twy_messagespring_settings_api');
	delete_option('twy_messagespring_settings_widget_show_footer');
	delete_option('twy_messagespring_settings_widget_own_button');
	delete_option('twy_messagespring_settings_widget_shortcode');
	delete_option('twy_messagespring_settings_widget_type');
	delete_option('twy_messagespring_settings_link_text');
	delete_option('twy_messagespring_settings_text_color');
	delete_option('twy_messagespring_settings_button_text');
	delete_option('twy_messagespring_settings_button_text_color');
	delete_option('twy_messagespring_settings_button_color');
}
?>
