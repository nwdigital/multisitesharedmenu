<?php if (!defined('ABSPATH')) { exit(); } // exit if accessed directly ?>

<div class="wrap">

	<h2>Multisite Shared Menu Settings</h2>
	<p>Select the source site that will be used for the selected menu location(s).</p>
	<p>Please Note: Use the same theme on each site to ensure menu location compatibility.</p>

	<form method="post" action="options.php">
		<table class="form-table">
			<tbody>
		<?php
			settings_fields( 'menufromsite-group' );
			do_settings_sections( 'menufromsite-group' );

			// Output dropdown menu of available sites...
			// $blogList = wp_get_sites();
			$blogList = get_sites();

			echo '<tr>
					<th scope="row"><label for="mfs_override_site_id">Source Site:</label></th>';

			echo '<td>
					<select name="mfs_override_site_id" id="mfs_override_site_id">';


			echo '<option value="">-- Select --</option>';
			foreach( $blogList as $blogTemp ) {
				$site_id = get_object_vars($blogTemp)["blog_id"];
				$site_url = get_blog_details($site_id)->siteurl;

				if( $site_id != get_current_blog_id() ) {

					echo '<option value="'.absint($site_id).'"';

					if( esc_attr( get_option('mfs_override_site_id') ) == $site_id ) {
						echo ' selected ';
					}

					echo '>'.wp_strip_all_tags($site_url).'</option>';

				}
			}

			echo '</select>
				</td>
			</tr>';

			// Output available theme menu locations...
			echo '<tr>
			<th scope="row"><label for="mfs_override_menu_location">Menu Location:</span></th>
			<td>';


			$locations = get_registered_nav_menus();
			$locationKeys = array_keys( $locations );
			$menuLocation = get_option('mfs_override_menu_location');

			if( !is_array( $menuLocation ) ) {
				$menuLocation = array( $menuLocation );	// backwards-compatibility from previous version
			}

			if( count($locations) ) {

				$option_count = 1;

				foreach ($locationKeys as $curLocation ) {
					if ( in_array ( $curLocation, $menuLocation ) ) {
						$checked = true;
					}
					else {
						$checked = false;
					}

					echo '<input type="checkbox" id="mfs_override_menu_location['.absint($option_count).']" name="mfs_override_menu_location['.absint($option_count).']" value="'. wp_strip_all_tags($curLocation) .'"' . ($checked == true ? ' checked="checked" ' : '' ) . '><label for="mfs_override_menu_location['.absint($option_count).']">' . wp_strip_all_tags($curLocation) . '</label><br/>';
					$option_count++;
				}
			}
			else {
				// No menu locations
				echo '<div class="error"><em>Error: No navigation menus have been registered for this theme. Please view <a href="http://codex.wordpress.org/Function_Reference/register_nav_menu">WordPress\' documentation</a> to learn how to register navigation menus.</em></div>';
			}

			echo '</td></tr>
			<tr>
				<td>';

			submit_button();
			echo '</td></tr>';
			 ?>
			</tbody>
		</table>
	</form>

</div>
