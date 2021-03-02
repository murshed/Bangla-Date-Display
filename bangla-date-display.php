<?php
/*
Plugin Name: Bangla Date Display
Plugin URI: http://i-onlinemedia.net/
Description: Displays Bangla, Gregorian and Hijri date in bangla language via widgets and shortcodes! Options for displaying post/page's time, date, comment count, archive calendar etc in Bangla language.
Author: M.A. IMRAN
Version: 9.1
Author URI: http://facebook.com/imran2w
*/

/*
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or ( at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA. Online: http://www.gnu.org/licenses/gpl.txt;
*/

// Bismillah...

defined( 'ABSPATH' )or die( 'Stop! You can not do this!' );

$bddp_options = get_option( "bddp_options" );
if ( !is_array( $bddp_options ) ) {
	$bddp_options = array(
		'cal_wgt' => '0',
		'trans_dt' => '0',
		'trans_cmnt' => '0',
		'trans_num' => '0',
		'trans_cal' => '0' 
	);
}

require 'translator.php';
require 'class.banglaDate.php';
require 'ajax-archive-calendar.php';

if (!class_exists('uCal')) {
	include_once('uCal.php');
}

add_shortcode('bangla_time', 'render_bangla_clock');
function render_bangla_clock() {

	$bddp_options = get_option( "bddp_options" );

	$offset = isset($bddp_options['en_tz']) ? $bddp_options['en_tz']*60*60 : 21600;
	$hour = gmdate("G", time()+$offset);
	
	if ( $hour >= 5 && $hour <= 5 ) {
		$event = "ভোর ";
	} else if ( $hour >= 6 && $hour <= 11 ) {
		$event = "সকাল ";
	} else if ( $hour >= 12 && $hour <= 14 ) {
		$event = "দুপুর ";
	} else if ( $hour >= 15 && $hour <= 17 ) {
		$event = "বিকাল ";
	} else if ( $hour >= 18 && $hour <= 19 ) {
		$event = "সন্ধ্যা ";
	} else {
		$event = "রাত ";
	}

	ob_start(); // begin output buffering

	echo $event.en_to_bn(gmdate("g:i", time()+$offset));

	$output = ob_get_contents(); // end output buffering
	ob_end_clean(); // grab the buffer contents and empty the buffer
	return $output;
}

add_shortcode('bangla_day', 'render_bangla_day');
function render_bangla_day() {
	ob_start(); // begin output buffering
	
	$bddp_options = get_option( "bddp_options" );
	
	$offset = isset($bddp_options['en_tz']) ? $bddp_options['en_tz']*60*60 : 21600;

	return en_to_bn(gmdate("l", time()+$offset));
	
	$output = ob_get_contents(); // end output buffering
	ob_end_clean(); // grab the buffer contents and empty the buffer
	return $output;
}

add_shortcode('bangla_date', 'render_bangla_date');
function render_bangla_date() {
	
	$day_number = array( "১" => "১লা", "২" => "২রা", "৩" => "৩রা", "৪" => "৪ঠা", "৫" => "৫ই", "৬" => "৬ই", "৭" => "৭ই", "৮" => "৮ই", "৯" => "৯ই", "১০" => "১০ই", "১১" => "১১ই", "১২" => "১২ই", "১৩" => "১৩ই", "১৪" => "১৪ই", "১৫" => "১৫ই", "১৬" => "১৬ই", "১৭" => "১৭ই", "১৮" => "১৮ই", "১৯" => "১৯শে", "২০" => "২০শে", "২১" => "২১শে", "২২" => "২২শে", "২৩" => "২৩শে", "২৪" => "২৪শে", "২৫" => "২৫শে", "২৬" => "২৬শে", "২৭" => "২৭শে", "২৮" => "২৮শে", "২৯" => "২৯শে", "৩০" => "৩০শে", "৩১" => "৩১শে" );

	$bddp_options = get_option("bddp_options");
	if (!is_array($bddp_options)) {
		$bddp_options = array(
			'separator' => ', ',
			'last_word' => '1',
			'ord_suffix' => '1'
		);
	}
	
	$dt_change = isset($bddp_options['dt_change']) ? $bddp_options['dt_change'] : 0;
	$separator = isset($bddp_options['separator']) ? $bddp_options['separator'] : ', ';
	$last_word = isset($bddp_options['last_word']) ? " বঙ্গাব্দ" : "";
	
	$offset = isset($bddp_options['bangla_tz']) ? $bddp_options['bangla_tz']*60*60 : 21600;
	$timestamp = time()+$offset;
	$banglaDate = new BanglaDate($timestamp, $dt_change);
	$the_date = $banglaDate->get_date();

	$day = isset($bddp_options['ord_suffix']) ? $day_number[$the_date[0]] : $the_date[0];
	$month_year = $the_date[1].$separator.$the_date[2];

	ob_start(); // begin output buffering

	echo $day.' '.$month_year.$last_word;

	$output = ob_get_contents(); // end output buffering
	ob_end_clean(); // grab the buffer contents and empty the buffer
	return $output;
}

add_shortcode('bangla_season', 'bddp_bn_season');
function bddp_bn_season() {
	ob_start(); // begin output buffering
	
	$bddp_options = get_option( "bddp_options" );

	$offset = isset($bddp_options['bangla_tz']) ? $bddp_options['bangla_tz']*60*60 : 21600;
	$banglaDate = new BanglaDate(time()+$offset, 0);
	$month = $banglaDate->get_date()[1];

	if ( $month == "বৈশাখ" || $month == "জ্যৈষ্ঠ" ) {
		return "গ্রীষ্মকাল";
	} else if ( $month == "আষাঢ়" || $month == "শ্রাবণ" ) {
		return "বর্ষাকাল";
	}
	else if ( $month == "ভাদ্র" || $month == "আশ্বিন" ) {
		return "শরৎকাল";
	}
	else if ( $month == "কার্তিক" || $month == "অগ্রহায়ণ" ) {
		return "হেমন্তকাল";
	}
	else if ( $month == "পৌষ" || $month == "মাঘ" ) {
		return "শীতকাল";
	}
	else {
		return "বসন্তকাল";
	}
	
	$output = ob_get_contents(); // end output buffering
	ob_end_clean(); // grab the buffer contents and empty the buffer
	return $output;
}

add_shortcode('english_date', 'render_gregorian_date');
function render_gregorian_date() {

	$bddp_options = get_option("bddp_options");
	$bddp_options = get_option("bddp_options");
	if (!is_array($bddp_options)) {
		$bddp_options = array(
			'separator' => ', ',
			'last_word' => '1',
			'ord_suffix' => '1'
		);
	}

	$separator = isset($bddp_options['separator']) ? $bddp_options['separator'] : ', ';
	$last_word = isset($bddp_options['last_word']) ? " খ্রিস্টাব্দ" : "";

	$day_number = array( "1" => "১লা", "2" => "২রা", "3" => "৩রা", "4" => "৪ঠা", "5" => "৫ই", "6" => "৬ই", "7" => "৭ই", "8" => "৮ই", "9" => "৯ই", "10" => "১০ই", "11" => "১১ই", "12" => "১২ই", "13" => "১৩ই", "14" => "১৪ই", "15" => "১৫ই", "16" => "১৬ই", "17" => "১৭ই", "18" => "১৮ই", "19" => "১৯শে", "20" => "২০শে", "21" => "২১শে", "22" => "২২শে", "23" => "২৩শে", "24" => "২৪শে", "25" => "২৫শে", "26" => "২৬শে", "27" => "২৭শে", "28" => "২৮শে", "29" => "২৯শে", "30" => "৩০শে", "31" => "৩১শে" );

	ob_start(); // begin output buffering

	$offset = isset($bddp_options['en_tz']) ? $bddp_options['en_tz']*60*60 : 21600;
	$date = explode(' ', gmdate( "j F Y", time()+$offset ));
	
	$day = isset($bddp_options['ord_suffix']) ? $day_number[$date[0]] : en_to_bn($date[0]);
	$month_year = en_to_bn($date[1].$separator.$date[2]);
	
	echo $day.' '.$month_year.$last_word;

	$output = ob_get_contents(); // end output buffering
	ob_end_clean(); // grab the buffer contents and empty the buffer
	return $output;
}

add_shortcode('hijri_date', 'render_hijri_date');
function render_hijri_date() {

	$bddp_options = get_option( "bddp_options" );
	if ( !is_array( $bddp_options ) ) {
		$bddp_options = array(
			'hijri_tz' => '6',
			'hijri_adjust' => '0',
			'separator' => ', ',
			'last_word' => '1',
			'ord_suffix' => '1' );
	}
	
	if(!array_key_exists('hijri_tz', $bddp_options)) {
		$bddp_options['hijri_tz'] = 6;
	}

	$last_word = isset($bddp_options['last_word']) ? " হিজরি" : "";

	$d = new uCal;

	$day_number = array( "1" => "১লা", "2" => "২রা", "3" => "৩রা", "4" => "৪ঠা", "5" => "৫ই", "6" => "৬ই", "7" => "৭ই", "8" => "৮ই", "9" => "৯ই", "10" => "১০ই", "11" => "১১ই", "12" => "১২ই", "13" => "১৩ই", "14" => "১৪ই", "15" => "১৫ই", "16" => "১৬ই", "17" => "১৭ই", "18" => "১৮ই", "19" => "১৯শে", "20" => "২০শে", "21" => "২১শে", "22" => "২২শে", "23" => "২৩শে", "24" => "২৪শে", "25" => "২৫শে", "26" => "২৬শে", "27" => "২৭শে", "28" => "২৮শে", "29" => "২৯শে", "30" => "৩০শে", "31" => "৩১শে" );

	$month_name = array( "Muh" => "মহর্‌রম", "Saf" => "সফর", "Rb1" => "রবিউল আউয়াল", "Rb2" => "রবিউস সানি", "Jm1" => "জমাদিউল আউয়াল", "Jm2" => "জমাদিউস সানি", "Raj" => "রজব", "Shb" => "শাবান", "Rmd" => "রমজান", "Shw" => "শাওয়াল", "DhQ" => "জিলকদ", "DhH" => "জিলহজ" );

	$separator = isset($bddp_options['separator']) ? $bddp_options['separator'] : ', ';
	$offset =  ($bddp_options['hijri_adjust']*60*60) + ($bddp_options['hijri_tz']*60*60);
	$timestamp = strtotime(gmdate('Y-m-d', time()+$offset));
	$date = explode(' ', $d->date( "j M Y", $timestamp));
	
	$day = isset($bddp_options['ord_suffix']) ? $day_number[$date[0]] : en_to_bn($date[0]);
	$month_year = $month_name[$date[1]].$separator.en_to_bn($date[2]);
	
	ob_start(); // begin output buffering
	
	echo $day.' '.$month_year.$last_word;

	$output = ob_get_contents(); // end output buffering
	ob_end_clean(); // grab the buffer contents and empty the buffer
	return $output;
}


//================== Widget 01 ========================

function widget_bangla_date_display( $args ) {
	extract( $args );

	$bddp_wgt1 = get_option( "bddp_wgt1" );
	if ( !is_array( $bddp_wgt1 ) ) {
		$bddp_wgt1 = array(
			'title' => 'আজকের দিন-তারিখ',
			'day' => '1',
			'time' => '1',
			'en_date' => '1',
			'hijri_date' => '1',
			'bn_date' => '1',
			'season' => '1' );
	}

	echo $before_widget;
	echo $before_title . $bddp_wgt1[ 'title' ] . $after_title;
	echo "<ul>";
	if ( $bddp_wgt1[ 'day' ] == "1" || $bddp_wgt1[ 'time' ] == "1" ) {
		echo "<li>";
	}
	if ( $bddp_wgt1[ 'day' ] == "1" ) {
		echo do_shortcode( '[bangla_day]' );
	}
	if ( $bddp_wgt1[ 'time' ] == "1" ) {
		echo " (";
		echo do_shortcode( '[bangla_time]' );
		echo ")";
	}
	if ( $bddp_wgt1[ 'day' ] == "1" || $bddp_wgt1[ 'show_time' ] == "1" ) {
		echo "</li>";
	}
	if ( $bddp_wgt1[ 'en_date' ] == "1" ) {
		echo "<li>";
		echo do_shortcode( '[english_date]' );
		echo "</li>";
	}
	if ( $bddp_wgt1[ 'hijri_date' ] == "1" ) {
		echo "<li>";
		echo do_shortcode( '[hijri_date]' );
		echo "</li>";
	}
	if ( $bddp_wgt1[ 'bn_date' ] == "1" || $bddp_wgt1[ 'show_season' ] == "1" ) {
		echo "<li>";
	}
	if ( $bddp_wgt1[ 'bn_date' ] == "1" ) {
		echo do_shortcode( '[bangla_date]' );
	}
	if ( $bddp_wgt1[ 'season' ] == "1" ) {
		echo " (";
		echo do_shortcode( '[bangla_season]' );
		echo ")";
	}
	?>
	<?php
	if ( $bddp_wgt1[ 'bn_date' ] == "1" || $bddp_wgt1[ 'season' ] == "1" ) {
		echo "</li>";
	}
	echo "</ul>";
	echo $after_widget;
}

function bddp_wgt1_control() {
	$bddp_wgt1 = get_option("bddp_wgt1");
	if (!is_array($bddp_wgt1)) {
		$bddp_wgt1 = array(
			'title' => 'আজকের দিন-তারিখ',
			'day' => '1',
			'time' => '1',
			'en_date' => '1',
			'hijri_date' => '1',
			'bn_date' => '1',
			'season' => '1'
		);
	}

	if (isset($_POST['widget_control_submit'])) {
		$bddp_wgt1['title'] = htmlspecialchars($_POST['title']);
		$bddp_wgt1['day'] = $_POST['day'];
		$bddp_wgt1['time'] = $_POST['time'];
		$bddp_wgt1['en_date'] = $_POST['en_date'];
		$bddp_wgt1['hijri_date'] = $_POST['hijri_date'];
		$bddp_wgt1['bn_date'] = $_POST['bn_date'];
		$bddp_wgt1['season'] = $_POST['season'];
		update_option("bddp_wgt1", $bddp_wgt1);
	}
	?>

	<p>
		<table width="100%">
			<tr>
				<td> <label for="title">Title: </label>
				</td>
				<td><input type="text" id="title" name="title" value="<?php echo $bddp_wgt1['title'];?>"/> </td>
			</tr>

			<tr>
				<td>Show:</td>
				<td><input type="checkbox" id="day" name="day" value="1" <?php if($bddp_wgt1[ 'day']==1) echo( 'checked="checked"'); ?>/><label for="day">Day</label>
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="checkbox" id="time" name="time" value="1" <?php if($bddp_wgt1[ 'time']==1) echo( 'checked="checked"'); ?>/><label for="time">Time</label>
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="checkbox" id="en_date" name="en_date" value="1" <?php if($bddp_wgt1[ 'en_date']==1) echo( 'checked="checked"'); ?>/><label for="en_date">Gregorian Date</label>
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="checkbox" id="hijri_date" name="hijri_date" value="1" <?php if($bddp_wgt1[ 'hijri_date']==1) echo( 'checked="checked"'); ?>/><label for="hijri_date">Hijri Date</label>
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="checkbox" id="bn_date" name="bn_date" value="1" <?php if($bddp_wgt1[ 'bn_date']==1) echo( 'checked="checked"'); ?>/><label for="bn_date">Bangla Date</label>
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="checkbox" id="season" name="season" value="1" <?php if($bddp_wgt1[ 'season']==1) echo( 'checked="checked"'); ?>/><label for="season">Season Name</label>
				</td>
			</tr>
		</table>

		<input type="hidden" id="widget_control_submit" name="widget_control_submit" value="1"/>
	</p>
	<p><span style="color: gray;">Go to: Settings > <a href="<?php admin_url(); ?>options-general.php?page=bangla-date-display">Bangla Date Display</a> to change plugin settings.</span>
	</p>
	<?php
}

wp_register_sidebar_widget( 'bangla_date_display', 'Bangla Date Display', 'widget_bangla_date_display', array( 'description' => __( 'Displays Bangla, Gregorian & Hijri date, time, day and season name.' ) ) );
wp_register_widget_control( 'bangla_date_display', 'Bangla Date Display', 'bddp_wgt1_control' );

// ========== Action Links =================
function bddp_action_links( $links ) {
	$links[] = '<a href="' . get_admin_url( null, 'options-general.php?page=bangla-date-display' ) . '">Settings</a>';
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bddp_action_links' );


// ============ Setings ========================
if (is_admin()) {
	include 'bddp_admin.php';
}

?>