<?php

wp_enqueue_style('bootstrap-css', plugins_url('/css/bootstrap.css', __FILE__));
wp_enqueue_style('dataTables-bootstrap-css', plugins_url('/css/dataTables.bootstrap4.min.css', __FILE__), array('bootstrap-css'));
wp_enqueue_script('jquery', plugins_url('/js/jquery-3.5.1.js', __FILE__));
wp_enqueue_script('jquery-dataTables', plugins_url('/js/jquery.dataTables.min.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('dataTables-buttons', plugins_url('/js/dataTables.buttons.min.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('jszip', plugins_url('/js/jszip.min.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('pdfmake', plugins_url('/js/pdfmake.min.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('vfs_fonts', plugins_url('/js/vfs_fonts.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('buttons-html5', plugins_url('/js/buttons.html5.min.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('buttons-print', plugins_url('/js/buttons.print.min.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('dataTables-bootstrap4', plugins_url('/js/dataTables.bootstrap4.min.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('dataTables-select', plugins_url('/js/dataTables.select.min.js', __FILE__), array('jquery'), null, true);
wp_enqueue_script('dataTables-checkboxes', plugins_url('/js/dataTables.checkboxes.min.js', __FILE__), array('jquery'), null, true);

?>

<?php
global $wpdb;
$Cf7_To_Any_Api = new Cf7_To_Any_Api();
$cf_id = (isset($_GET['form_id']) && is_numeric($_GET['form_id']) ? intval($_GET['form_id']) : 0); ?>

<div class="cf_entries" id="cf_entries">
	<form name="form_entries" id="form_entries" class="cf7toanyapi_entries" method="get">
		<label for="form" class="cf7toanyapi_select_form"><?php esc_html_e( 'Choose a form:', 'contact-form-to-any-api' ); ?></label>
		<select name="form" id="form_id" class="form_id cf7toanyapi_forms">
			<option value=""><?php esc_html_e( 'Select Form', 'contact-form-to-any-api' ); ?></option>
			<?php
			$posts = get_posts(
                array(
                    'post_type'     => 'wpcf7_contact_form',
                    'numberposts'   => -1,
                    'suppress_filters' => false
                )
            );
            foreach($posts as $post){
            	$selected = $post->ID === $cf_id ? 'selected="selected"' : '';?>
                <option value="<?php echo esc_attr($post->ID); ?>" <?php echo $selected; ?>>
                    <?php echo esc_html($post->post_title . '(' . $post->ID . ')'); ?>
                </option>
                <?php
            }?>
		</select>
	</form>
	<?php
	if(isset($cf_id) && $cf_id != ''){
		
	    $cf7_entries_sql = $wpdb->prepare(
	        'SELECT * FROM ' . $wpdb->prefix . 'cf7anyapi_entries 
	        WHERE `form_id` = %d 
	        AND data_id IN( 
	            SELECT * FROM ( 
	                SELECT data_id FROM ' . $wpdb->prefix . 'cf7anyapi_entries 
	                WHERE 1 = 1 
	                AND `form_id` = %d 
	                GROUP BY `data_id` 
	                ORDER BY `data_id` DESC
	            ) temp_table
	        ) 
	        ORDER BY `data_id` DESC',
	        $cf_id,
	        $cf_id
	    );
		$result = $wpdb->get_results($cf7_entries_sql);

		if($result){
			$data_sorted = $Cf7_To_Any_Api->cf7toanyapi_sortdata($result);
			$fields = $Cf7_To_Any_Api->cf7toanyapi_get_db_fields($cf_id);
			$display_character = (int) apply_filters('cf7toanyapi_display_character_count',500);
			$arr_field_type_info = $Cf7_To_Any_Api->cf7toanyapi_field_type_info($cf_id);?>				
			<div id="table_data">
				<table class="tbl table table-striped table-bordered cf7toanyapi_table" id="cf7toanyapi_table">
					<thead>
						<tr class="cf7toanyapi_dataid_all">								
							<?php
							echo '<th class="manage-column">checkbox</th>';
							foreach ($fields as $k => $v){
								echo '<th class="manage-column" data-key="'.esc_html($v).'">'.ucfirst(str_replace('_',' ',$Cf7_To_Any_Api->cf7toanyapi_admin_get_field_name($v))).'</th>';
							}?>
						</tr>
					</thead>
					<tbody>
						<?php							
						if(!empty($data_sorted)){
							foreach ($data_sorted as $k => $v )   {					
								echo '<tr data-id="'.$k.'" class="cf7toanyapi_dataid">';
								$k = (int)$k;
								echo '<td data-id="'.$k.'" class="cf7toanyapi_dataid"></td>';
								foreach ($fields as $k2 => $v2) {
									//Get fields related values
									$_value = ((isset($v[$k2])) ? $v[$k2] : '&nbsp;');
									$_value1 = filter_var($_value, FILTER_SANITIZE_URL);

									//Check value is URL or not
									if (!filter_var($_value1, FILTER_VALIDATE_URL) === false) {
										$_value = esc_url($_value);
										//If value is url then setup anchor tag with value
										if(!empty($arr_field_type_info) && array_key_exists($k2,$arr_field_type_info) && $arr_field_type_info[$k2] == 'file'){
											//Add download attributes in tag if field type is attachement?>
											<td data-head="<?php echo $Cf7_To_Any_Api->cf7toanyapi_admin_get_field_name($v2); ?>">
												<a href="<?php echo esc_url($_value); ?>" target="_blank" title="<?php echo esc_url($_value); ?>" download ><?php echo esc_html(basename($_value)); ?>
												</a>
											</td><?php
										}else{ ?>
											<td data-head="<?php echo $Cf7_To_Any_Api->cf7toanyapi_admin_get_field_name($v2); ?>">
												<a href="<?php echo esc_url($_value); ?>" target="_blank" title="<?php echo esc_url($_value); ?>" ><?php echo esc_html(basename($_value)); ?>
												</a>
											</td><?php
										}
									}
									else if($Cf7_To_Any_Api->cf7toanyapi_admin_get_field_name($v2) == 'submitted_from'){
										echo '<td data-head="'.$Cf7_To_Any_Api->cf7toanyapi_admin_get_field_name($v2).'"><a href="'.get_the_permalink($_value).'" target="_blank">'.get_the_title($_value).'</a></td>';
									}
									else{
										$_values = esc_html(html_entity_decode($_value));
										if(strlen($_values) > $display_character){

											echo '<td data-head="'.$Cf7_To_Any_Api->cf7toanyapi_admin_get_field_name($v2).'">'.esc_html(substr($_values, 0, $display_character)).'...</td>';
										}else{
											echo '<td data-head="'.$Cf7_To_Any_Api->cf7toanyapi_admin_get_field_name($v2).'">'.htmlspecialchars_decode($_value).'</td>';
										}
									}
								}
								echo '</tr>';
							}
						} ?>
					</tbody>
				</table>
			</div><?php
		} else{ ?>
			<div id="table_data">
				<h3><?php esc_html_e( 'Please choose a form to continue.', 'contact-form-to-any-api' ); ?></h3>
			</div><?php
		}
	}?>
</div>