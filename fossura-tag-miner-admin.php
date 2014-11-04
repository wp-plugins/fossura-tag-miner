<?php
	add_action('admin_head', 'fossura_css');
	add_action('admin_menu', 'fossura_tag_miner_menu');

	function fossura_tag_miner_settings() {
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
?>

    <div class="wrap">
        <?php screen_icon('options-general'); ?>
        <h2>Fossura Tag Miner settings</h2>
	 		<form method="POST" action="">
				<table class="form-table">
				<input type="hidden" name="fossura_tag_miner_update_settings" value="hunne" />
					<tr valign="top">
						<th><label>Extraction algorithm</label></th>
						<td class="fossura_config_td">
							<?php create_radio_button('fossura_tags_mode', 'classic', '', 'Classic <span class="description">(treat all entities equally)</span>');?>
							<?php create_radio_button('fossura_tags_mode', 'nominal', '', 'Nominal <span class="description">(give precedence to the names of people, places and things)</span>');?>
		        		</td>
		        	</tr>
					<td><p><input type="submit" value="Save settings" class="button-primary"/></p></td>
				</table>
	        </form>
<?php
		if (isset($_POST["fossura_tag_miner_update_settings"])) {
			$mode = $_POST['fossura_tags_mode'];
			update_option( 'fossura_tags_mode', $mode );
?>

		    <div id="fossura_settings_saved" class="updated"><strong>Configuration saved</strong></div>
<?php
		}
	}

	function fossura_tag_miner_menu() {
		add_options_page( 'Fossura Tag Miner Options', ' Fossura Tag Miner', 'manage_options', 'fossura-tag-miner', 'fossura_tag_miner_settings' );
	}


	function fossura_css() {
	    echo
	    '<style type="text/css">
	        .fossura-column { text-align: left; width:450px !important; overflow:hidden
			.debug{font-size: 30px;}
	    </style>';
	}

	function create_radio_button($name, $value, $onClickMethodName, $labelText) {
		if ( !isset( $_POST['fossura_tags_mode'] ) ) {
			update_option('fossura_tags_mode', 'classic');
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
		
		echo '<p>';
		echo '<input onClick="'. $onClickMethodName .'();" type="radio" name="'. $name .'" value="'. $value .'"'. $checked .' /><label>'. '&nbsp;&nbsp;' . $labelText .'</label>';
		echo '</p>';
	}
?>