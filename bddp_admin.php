<?php

defined( 'ABSPATH' )or die( 'Stop! You can not do this!' );

function bddp_options_page() {
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Bangla Date Display Settings</h1>
		<a href="#how_to_use" class="page-title-action">How to use?</a>
		<hr class="wp-header-end">

		<?php if(isset($_POST["restore_defaults"]) == "1") { delete_option('bddp_options'); } ?>

		<form method="post" action="options.php">

			<?php

			function rplc_symbol( $symbol ) {
				$symbol = str_replace( '"', '&#34;', $symbol );
				return $symbol;
			}

			settings_fields( 'bddp-settings-group' );

			$bddp_options = get_option( "bddp_options" );
			if ( !is_array( $bddp_options ) ) {
				$bddp_options = array(
					'trans_dt' => '0',
					'bangla_tz' => '6',
					'en_tz' => '6',
					'hijri_tz' => '6',
					'trans_cmnt' => '0',
					'trans_num' => '0',
					'trans_cal' => '0',
					'dt_change' => '0',
					'ord_suffix' => '1',
					'separator' => ', ',
					'last_word' => '1',
					'hijri_adjust' => '-24',
					'cal_wgt' => '0' );
			}
	
			$last_word = isset($bddp_options['last_word']) ? true : false;
			$ord_suffix = isset($bddp_options['ord_suffix']) ? true : false;
	
			$time_zones = array(
				'-12' => '-12',
				'-11' => '-11',
				'-10' => '-10',
				'-9' => '-9',
				'-8' => '-8',
				'-7' => '-7',
				'-6' => '-6',
				'-5' => '-5',
				'-4:30' => '-4.5',
				'-4' => '-4',
				'-3:30' => '-3.5',
				'-3' => '-3',
				'-2' => '-2',
				'-1' => '-1',
				'0' => '0',
				'+1' => '1',
				'+2' => '2',
				'+3' => '3',
				'+3:30' => '3.5',
				'+4' => '4',
				'+4:30' => '4.5',
				'+5' => '5',
				'+5:30' => '5.5',
				'+6' => '6',
				'+6:30' => '6.5',
				'+7' => '7',
				'+8' => '8',
				'+9' => '9',
				'+9:30' => '9.5',
				'+10' => '10',
				'+10:30' => '10.5',
				'+11' => '11',
				'+12' => '12',
				'+13' => '13'
			);

			?>
			
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label>Language settings</label></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Language settings</span></legend>
								<label for="bddp_options[trans_dt]"><input id="bddp_options[trans_dt]" type="checkbox" name="bddp_options[trans_dt]" value="1" <?php if(isset($bddp_options[ 'trans_dt'])==1) echo( 'checked="checked"'); ?>/> Show post/page/comment's date and time in Bangla language</label>
								<br>
								<label for="bddp_options[trans_cmnt]"><input id="bddp_options[trans_cmnt]" type="checkbox" name="bddp_options[trans_cmnt]" value="1" <?php if(isset($bddp_options[ 'trans_cmnt'])==1) echo( 'checked="checked"'); ?>/> Show comment count in Bangla language</label>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>
			
			<h2>Time Zone</h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="greg_tz">Gregorian Date</label></th>
						<td>
							<select id="greg_tz" name="bddp_options[en_tz]">
							<?php
								foreach($time_zones as $key=>$value) {
									if($bddp_options['en_tz'] == $value) {
										echo '<option value="'.$value.'" selected>GMT '.$key.'</option>';
									} else {
										echo '<option value="'.$value.'">GMT '.$key.'</option>';
									}
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="bangla_tz">Bangla Date</label></th>
						<td>
							<select id="bangla_tz" name="bddp_options[bangla_tz]">
							<?php
								foreach($time_zones as $key=>$value) {
									if($bddp_options['bangla_tz'] == $value) {
										echo '<option value="'.$value.'" selected>GMT '.$key.'</option>';
									} else {
										echo '<option value="'.$value.'">GMT '.$key.'</option>';
									}
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hijri_tz">Hijri Date</label></th>
						<td>
							<select id="hijri_tz" name="bddp_options[hijri_tz]">
							<?php
								foreach($time_zones as $key=>$value) {
									if($bddp_options['hijri_tz'] == $value) {
										echo '<option value="'.$value.'" selected>GMT '.$key.'</option>';
									} else {
										echo '<option value="'.$value.'">GMT '.$key.'</option>';
									}
								}
							?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			
			
			<h2>Date and Time adjustment</h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="dt_change">Change Bangla date at</label></th>
						<td>
							<select id="dt_change" name="bddp_options[dt_change]">
									<option value="6" <?php if($bddp_options[ 'dt_change']=="6" ) { echo " selected"; } ?>>06:00 AM (Morning)</option>
									<option value="0" <?php if($bddp_options[ 'dt_change']=="0" ) { echo " selected"; } ?>>12:00 AM (Midnight)</option>
								</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hijri_adjust">Adjust Hijri Date</label></th>
						<td>
							<select id="hijri_adjust" name="bddp_options[hijri_adjust]">
								<?php
									$hijri_adjst_options = array(
										'-3 days' => '-72',
										'-2 days' => '-48',
										'-1 day' => '-24',
										'±0 day' => '0',
										'+1 day' => '+24',
										'+2 days' => '+48',
										'+3 days' => '+72',
									);
									foreach($hijri_adjst_options as $key=>$value) {
										if($bddp_options[ 'hijri_adjust'] == $value) {
											echo '<option value="'.$value.'" selected>'.$key.'</option>';
										} else {
											echo '<option value="'.$value.'">'.$key.'</option>';
										}
									}
								?>
							</select>
							<p class="description">Hijri month can have 29 or 30 days depending on the visibility of the moon. Adjust it manually.</p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<h2>Date Formatting</h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label>Date separator</label></th>
						<td>
							<p><input type="radio" id="sep1" name="bddp_options[separator]" value=", " <?php if($bddp_options[ 'separator']==", " ) { echo " checked"; } ?>> <label for="sep1">Comma</label>
							</p>
							<p><input type="radio" id="sep2" name="bddp_options[separator]" value=" " <?php if($bddp_options[ 'separator']==" " ) { echo " checked"; } ?>> <label for="sep2">Space</label>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label>More options</label></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>More options</span></legend>
								<label for="bddp_options[ord_suffix]"><input type="checkbox" id="bddp_options[ord_suffix]" name="bddp_options[ord_suffix]" value="1" <?php if($ord_suffix==true) echo('checked="checked"'); ?>/> Show ordinal suffix <span style="color:green;">(Eg. ১লা, ২রা)</span></label>
								<br>
								<label for="bddp_options[last_word]"><input type="checkbox" id="bddp_options[last_word]" name="bddp_options[last_word]" value="1" <?php if($last_word==true) echo('checked="checked"'); ?>/> Show last word <span style="color:green;">(Eg. খ্রিস্টাব্দ/বঙ্গাব্দ/হিজরি)</span></label>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button(); ?>
		</form>


		<form method="post" action="options.php">
			<?php settings_fields( 'bddp-settings-group' ); ?>

			<input type="hidden" name="restore_defaults" value="1">
			<input type="submit" value="Restore Default Settings" class="button button-secondary">
		</form>
		<br/>

		<a name="how_to_use"></a>
		<div class="postbox">
			<h3 class="hndle" style="padding: 10px; margin: 0;"><span>How to use?</span></h3>
			<div class="inside">
				<p><strong>Go to: Appearance > <a href="<?php admin_url(); ?>widgets.php">Widgets</a> to use following widgets:</strong>
				</p>
				<ul style="list-style-type: square; margin-left: 10px;">
					<li>Bangla Date Display</li>
					<li>Advanced Archive Calendar</li>
				</ul>

				<hr/>

				<p><strong>OR, Use following shortcodes:</strong>
				</p>
				<table style="border-collapse:collapse;" width="100%">
					<tr>
						<th style="border: 1px solid silver; background-color: #CCC;">Item</th>
						<th style="border: 1px solid silver; background-color: #CCC;">Shortcode</th>
						<th style="border: 1px solid silver; background-color: #CCC;">PHP Code</th>
					</tr>
					<tr>
						<td style="border: 1px solid silver; padding-left: 5px;">Bangla date:</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB"> &#91;bangla_date&#93;</span></span></code>
						</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB">   &#60;&#63;php echo do_shortcode&#40;&#39;&#91;bangla_date&#93;&#39;&#41;; </span><span style="color: #0000BB">&#63;&#62;</span></span>
</code>
						</td>
					</tr>
					<tr>
						<td style="border: 1px solid silver; padding-left: 5px;">Gregorian date:</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB"> &#91;english_date&#93;</span></span></code>
						</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB">   &#60;&#63;php echo do_shortcode&#40;&#39;&#91;english_date&#93;&#39;&#41;; </span><span style="color: #0000BB">&#63;&#62;</span></span>
</code>
						</td>
					</tr>
					<tr>
						<td style="border: 1px solid silver; padding-left: 5px;">Hijri date:</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB"> &#91;hijri_date&#93;</span></span></code>
						</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB">   &#60;&#63;php echo do_shortcode&#40;&#39;&#91;hijri_date&#93;&#39;&#41;; </span><span style="color: #0000BB">&#63;&#62;</span></span>
</code>
						</td>
					</tr>
					<tr>
						<td style="border: 1px solid silver; padding-left: 5px;">Current time:</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB"> &#91;bangla_time&#93;</span></span></code>
						</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB">   &#60;&#63;php echo do_shortcode&#40;&#39;&#91;bangla_time&#93;&#39;&#41;; </span><span style="color: #0000BB">&#63;&#62;</span></span>
</code>
						</td>
					</tr>
					<tr>
						<td style="border: 1px solid silver; padding-left: 5px;">Day name:</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB"> &#91;bangla_day&#93;</span></span></code>
						</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB">   &#60;&#63;php echo do_shortcode&#40;&#39;&#91;bangla_day&#93;&#39;&#41;; </span><span style="color: #0000BB">&#63;&#62;</span></span>
</code>
						</td>
					</tr>
					<tr>
						<td style="border: 1px solid silver; padding-left: 5px;">Season name:</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB"> &#91;bangla_season&#93;</span></span></code>
						</td>
						<td style="border: 1px solid silver; padding-left: 5px;"><code><span style="color: #000000"><span style="color: #0000BB">   &#60;&#63;php echo do_shortcode&#40;&#39;&#91;bangla_season&#93;&#39;&#41;; </span><span style="color: #0000BB">&#63;&#62;</span></span>
</code>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<a name="credits"></a>
		<div class="postbox">
			<h3 class="hndle" style="padding: 10px; margin: 0;"><span>Credits</span></h3>
			<div class="inside">
				<table class="form-table">
					<tr valign="top">
						<td style="width: 80px;">
							<a href="http://facebook.com/imran2w" target="_blank"><img src="http://www.gravatar.com/avatar/<?php echo md5( "imran2w@gmail.com" ); ?>" /></a>
						</td>
						<td>
							<p>Developer: <a href="http://facebook.com/imran2w" target="_blank">M.A. IMRAN</a><br/>Facebook: <a href="http://facebook.com/imran2w" target="_blank">http://facebook.com/imran2w</a><br/> E-Mail: <a href="mailto:imran2w@gmail.com">imran2w@gmail.com</a><br/> Web: <a href="http://i-onlinemedia.net" target="_blank">www.i-onlinemedia.net</a>
							</p>
						</td>
					</tr>
				</table>
			</div>
		</div>

	</div>

<?php
	}


	add_action( 'admin_menu', 'bddp_admin' );

	function bddp_admin() {
		add_options_page( 'Bangla Date Display Settings', 'Bangla Date Display', 'activate_plugins', 'bangla-date-display', 'bddp_options_page' );
	}

	// Register settings --------------------------------
	add_action( 'admin_init', 'register_bddp_settings' );

	function register_bddp_settings() {
		register_setting( 'bddp-settings-group', 'bddp_options' );
	}


?>