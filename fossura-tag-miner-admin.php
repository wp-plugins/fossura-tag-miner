<?php
	add_action('admin_head', 'fossura_css');
	add_action('admin_menu', 'fossura_tag_miner_menu');

	if( !class_exists( 'WP_Http' ) ) {
    	include_once( ABSPATH . WPINC . '/class-http.php' );
    }

	function fossura_tag_miner_settings() {
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

	if (NULL == get_option( 'textcavate_is_registered' )) {
		update_option( 'textcavate_is_registered', 'false');
	}

	$registered = get_option( 'textcavate_is_registered' );
	
	if ($registered == 'true') {
	?>
	

    <div class="wrap">

        <?php screen_icon('options-general'); ?>
        <?php
			$url = 'http://www.textcavate.com/get/user/license/';
			$body = array( 
				'username' => get_option( 'textcavate_username' )
			);
				
			$request = new WP_Http;
			$result = $request->request( $url , array( 'method' => 'POST', 'body' => $body ) );
			$license = $result['body'];
			$disabled = '';
			$gray_style = '';
			if ( 'free' == $license ) {
				$disabled = 'disabled="disabled"';
				$gray_style = 'style="color: LightGray;"';
			}
       	?>
        <h2><?php _e('Tag Miner settings')?></h2>
        <h3><?php _e('Basic settings')?></h3>
        <div class="fossura_settings_form" style="width: 70%; float: left; overflow: visible;">
	 		<form method="POST" action="">
				<table class="form-table">
				<input type="hidden" name="fossura_tag_miner_update_settings" value="hunne" />
					<tr valign="top">
						<th><label><?php _e('Extraction algorithm')?></label></th>
						<td class="fossura_config_td">
							<?php create_radio_button('fossura_tags_mode', 'classic', __('Classic <span class="description">(treat all entities equally)</span>'));?>
							<?php create_radio_button('fossura_tags_mode', 'nominal', __('Nominal <span class="description">(give precedence to the names of people, places and things)</span>'));?>
		        		</td>
		        	</tr>
		        	<tr>
		        		<th><label><?php _e('Add tags when...')?></label></th>
		        		<td class="fossura_config_td">
		        			<?php create_radio_button('fossura_tags_trigger', 'publish', __('Publishing post <span class="description">(for the adventurous)</span>')); ?>
		        			<?php create_radio_button('fossura_tags_trigger', 'draft', __('Saving draft <span class="description">(for the meticulous)</span>'));?>
		        		</td>
		        	</tr>
	        	</table>
			<table class="form-table">
			<h3 <?php echo $gray_style;?>><?php _e('Advanced settings')?></h3>
		        	<tr>
			        	<th class="fossura_config_td" <?php echo $gray_style;?>><?php _e('Number of tags')?></th>
			        	<td class="fossura_config_td" <?php echo $gray_style;?>>
			        		<?php create_text_field('fossura_tags_number', $disabled);?>
			        		<p <?php echo $gray_style;?> class="description"><?php _e('How many tags should be added to each post?')?></p>
			        	</td>
		        	</tr>
		        	<tr>
			        	<th class="fossura_config_td" <?php echo $gray_style;?>><?php _e('Dates')?></th>
						<td class="fossura_config_td">
						<?php create_check_box( "fossura_tags_dates", "checked", __("Include dates in list of tags"), $disabled );?>
						<p <?php echo $gray_style;?> class="description"><?php _e('Days and months are excluded from the list of tags by default. Check this option to include them.')?></p>
						</td>
		        	</tr>
		        	<th class="fossura_config_td" <?php echo $gray_style;?>><?php _e('Pronouns')?></th>
						<td class="fossura_config_td">
							<p>
							<?php create_check_box( "fossura_tags_pronouns", "checked", __("Include pronouns in list of tags"), $disabled );?>
							<p <?php echo $gray_style;?> class="description"><?php _e('Words referring to names (I, you, they et cetera) are excluded from the list of tags by default. Check this option to include them.')?></p>
						</td>
		        	</tr>
					<td><p><input type="submit" value="<?php _e('Save settings')?>" class="button-primary"/></p></td>
				</table>
	 <?php
		if (isset($_POST["fossura_tag_miner_update_settings"])) {
			$mode = $_POST['fossura_tags_mode'];
			$trigger = $_POST['fossura_tags_trigger'];
			$number = $_POST['fossura_tags_number'];
			$dates = $_POST['fossura_tags_dates'];
			$pronouns = $_POST['fossura_tags_pronouns'];

			update_option( 'fossura_tags_mode', $mode );
			update_option( 'fossura_tags_trigger', $trigger);
			update_option( 'fossura_tags_number', $number);
			update_option( 'fclose(handle)ossura_tags_pronouns', $pronouns );
			update_option( 'fossura_tags_dates', $dates);
?>
		    <div id="fossura_settings_saved" class="updated" style="padding: 10px;"><strong>Configuration saved</strong></div>
<?php
		}
?>
	    </div>
		<?php 
				if ( 'free' == $license ) {
					?>
					<div class="highlight fossura_upgrade_notify" style="width: 25%; float:right; padding: 10px;">
						<p><?php _e('You are currently using a <strong>textCavate Free plan</strong>.')?></p>
						<p><?php _e('To gain access to the features below, please upgrade your account at <a href="http://www.textcavate.com/admin/upgrade/" target="_blank">textcavate.com</a>')?></p>
						<a a href="http://www.textcavate.com/admin/upgrade/" target="_blank" class="button button-primary"><?php _e('Upgrade')?></a>						
					</div>
					<?php
				}
				elseif ( 'business' == $license ) {
					?>
					<div class="highlight fossura_upgrade_notify" style="width: 25%; float:right; padding: 10px;">
					<p><?php _e('You are currently using a <strong>textCavate Business plan</strong>.')?></p>
					<p><?php _e('To change your plan, please navigate to <a href="http://www.textcavate.com/admin/upgrade/" target="_blank">textcavate.com</a>')?></p>
					</div>
					<?php
				}
				else {
					?>
						<div class="highlight fossura_upgrade_notify" style="width: 25%; float:right; padding: 10px;">
						<p><?php _e('You are currently using a <strong>textCavate <?php echo $license ?> plan</strong>.')?></p>
						<p><?php _e('If you require more monthly queries, please upgrade your account at <a href="http://www.textcavate.com/admin/upgrade/" target="_blank">textcavate.com</a>')?></p>
						<a a href="http://www.textcavate.com/admin/upgrade/" target="_blank" class="button button-primary"><?php _e('Upgrade')?></a>						
					</div>
					<?php

				}
			?>

<?php
	}
	
	else {
	?>
	<div class="wrap">
        <h2><?php _e('Tag Miner settings')?></h2>
        <div class="textcavate_registration_container" style="width: 49%; float: left;">
        <h3><?php _e('Activation')?></h3>
		<p><?php _e('To use Tag Miner, you need to register for a textCavate account. It\'s a quick and easy process, and shouldn\'t take more than a minute.')?></p>
		<form target="_blank" method="GET" action="http://www.textcavate.com/register/">
			<input type="hidden" name="plugin_type" value="wordpress" />
			<p><input type="submit" value="<?php _e('Register')?>" class="button-primary"/></p>
		</form>
		
		<p><?php _e('Upon completion of the registration process, you will receive an API key. Enter this API key and your textCavate username below to activate the plugin. That\'s it :)')?></p>
		<form method="POST" action="">
			<input type="hidden" name="textcavate_register_form" value="hunne" />
			<p><input placeholder="<?php _e('textCavate API key')?>" type="text" name="textcavate_register_api_key" value=""/></p>
			<p><input placeholder="<?php _e('textCavate username')?>" type="text" name="textcavate_register_username" value=""/></p>
			<p><input type="submit" value="<?php _e('Activate')?>" class="button-primary"/></p>
		</form>
		</div>
		<?php
			if ( isset ( $_POST["textcavate_register_form"] ) && $_POST["textcavate_register_username"] != "" ) {
				$url = 'http://www.textcavate.com/plugin/confirm/registration/';
				$body = array( 
					'plugin_type' => 'wordpress',
					'username' => $_POST['textcavate_register_username'],
					'api_key' => $_POST['textcavate_register_api_key'],
				);

				$request = new WP_Http;
				$result = $request->request( $url , array( 'method' => 'POST', 'body' => $body ) );
				$valid = strtolower($result['body']);
				if ( "false" == $valid) {
					echo'<p class="warning invalid_credentials"><strong>API key or username is invalid. Please double check your credentials by <a href="http://www.textcavate.com/admin">logging in to your textCavate account</a>.</strong></p>';
				}
				elseif ( "true" == $valid ) {
					update_option( 'textcavate_is_registered' , "true");
					update_option( 'textcavate_username', $_POST['textcavate_register_username']);
					update_option( 'textcavate_api_key', $_POST['textcavate_register_api_key']);
					echo '<script>document.location.reload(true);</script>';

				}
			}
		}
?>
	</div>

	<?php
	}

	function fossura_tag_miner_menu() {
		add_options_page( __('Tag Miner Options'), 'Tag Miner', 'manage_options', 'fossura-tag-miner', 'fossura_tag_miner_settings' );
	}


	function fossura_css() {
	    echo
	    '<style type="text/css">
	        .fossura-column { text-align: left; width:450px !important; overflow:hidden }
	        .invalid_credentials { background-color: #fcc; padding: 5px;}
			.debug{font-size: 30px;}
	    </style>';
	}

	function create_check_box( $name, $value, $labelText, $disabled ) {
		$checked = '';

		if ( NULL == get_option( 'fossura_tags_dates' )  ) {
		 	update_option('fossura_tags_dates', 'false');
		}

		if ( NULL == get_option( 'fossura_tags_pronouns' )  ) {
		 	update_option('fossura_tags_pronouns', 'false');
		}

		if ( 'fossura_tags_dates' == $name ) {
			if ( isset($_POST['fossura_tags_dates'] ) ) {
						if ( $_POST['fossura_tags_dates'] == 'true' ) {
							$checked = 'checked';
						}
						else {
							$checked = '';
						}

			} elseif( !isset( $_POST['fossura_tags_dates'] ) ) {
				$saved_mode = get_option( 'fossura_tags_dates' );
				if ( $saved_mode == 'true' ) {
					$checked = 'checked';
				}
				else {
					$checked = '';
				}
			}
		}

		if ( 'fossura_tags_pronouns' == $name ) {
			if ( isset($_POST['fossura_tags_pronouns'] ) ) {
						if ( $_POST['fossura_tags_pronouns'] == 'true' ) {
							$checked = 'checked';
						}
						else {
							$checked = '';
						}
			} elseif( !isset( $_POST['fossura_tags_pronouns'] ) ) {
				$saved_mode = get_option( 'fossura_tags_pronouns' );
				if ( $saved_mode == 'true' ) {
					$checked = 'checked';
				}
				else {
					$checked = '';
				}
			}
		}

		$css = '';
		if ( 'disabled="disabled"' == $disabled ) {
			$css = 'style="color: LightGray;"';
		}
		echo '<p>';
		echo '<input type="hidden" name="'. $name .'" value="false" />';
		echo '<input ' . $disabled . ' type="checkbox" name="'. $name .'" value="true" '. $checked .' /><label ' . $css. ' for="' . $name . '">'. '&nbsp;&nbsp;' . $labelText .'</label>';
		echo '</p>';
	}

	function create_radio_button($name, $value, $labelText) {
		if ( NULL == get_option( 'fossura_tags_mode' )  ) {
			update_option('fossura_tags_mode', 'classic');
		}
		
		if ( NULL == get_option( 'fossura_tags_trigger' ) ) {			
			update_option('fossura_tags_trigger', 'publish');
		}

		$checked = '';

		if ( 'fossura_tags_mode' == $name ) {
			if ( isset($_POST['fossura_tags_mode'] ) ) {
						if ( $_POST['fossura_tags_mode'] == $value ) {
							$checked = 'checked="checked"';
						}
			} elseif( !isset( $_POST['fossura_tags_mode'] ) ) {
				$saved_mode = get_option( 'fossura_tags_mode' );
				if ( $saved_mode == $value ) {
					$checked = 'checked="checked"';
				}
			}
		}

		if ( 'fossura_tags_trigger' == $name ) {
			if ( isset($_POST['fossura_tags_trigger'] ) ) {
						if ( $_POST['fossura_tags_trigger'] == $value ) {
							$checked = 'checked="checked"';
						}
			} elseif( !isset( $_POST['fossura_tags_trigger'] ) ) {
				$saved_mode = get_option( 'fossura_tags_trigger' );
				if ( $saved_mode == $value ) {
					$checked = 'checked="checked"';
				}
			}
		}
		
		echo '<p>';
		echo '<input type="radio" name="'. $name .'" value="'. $value .'"'. $checked .' /><label>'. '&nbsp;&nbsp;' . $labelText .'</label>';
		echo '</p>';
	}

	function create_text_field($name, $disabled) {

		if (NULL == get_option( 'fossura_tags_number') ) {
			update_option( ' fossura_tags_number' , 5);
		}

		$number = get_option( 'fossura_tags_number' );

		if ( isset($_POST['fossura_tags_number'] ) ) {
			$number = $_POST['fossura_tags_number'];
		}

		echo '<p>';
		echo "<input type=text name=$name value=$number " . $disabled . ">";
		echo '</p>';
	}

?>