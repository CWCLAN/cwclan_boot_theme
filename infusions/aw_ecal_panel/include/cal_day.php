<?php
/**
*
* @package awEventCalendar
* @version $Id: $
* @copyright (c) 2006-2008 Artur Wiebe <wibix@gmx.de>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if(!defined('IN_FUSION')) {
	die;
}


if(isset($_GET['date']) && preg_match(AWEC_DATE_FORMAT, $_GET['date'])) {
	$date = $_GET['date'];
} else {
	$date = sprintf('%04d-%02d-%02d', $ec_year, $ec_month, $ec_mday);
}
list($year, $month, $mday) = explode('-', $date);
$year = intval($year);
$month = intval($month);
$mday = intval($mday);



/****************************************************************************
 * GET
 */
$needle['from']	= $date;
$needle['to']	= $date;
if(awec_get_events($needle, $events, true)===false) {
	ff_redirect('index.php');
}



$res = dbquery("SELECT
	DATE_FORMAT('".$date."', '".$awec_settings['date_fmt']."') AS date");
$row = dbarray($res);
$formated_date = array_shift($row);



/****************************************************************************
 * GUI
 */
/*
opentable($date);
awec_menu();
*/
awec_set_title($formated_date);


if(iAWEC_POST) {
	echo '<a href="edit_event.php?date='.$date.'">'
		.$locale['EC100'].'</a>';
}


if(count($events) && isset($events[$year][$month][$mday])) {
	foreach($events[$year][$month][$mday] as $event) {
		if($event['is_birthday']) {
			ec_render_birthday($event);
		} else {
			$event['ev_title'] = '<a href="view_event.php?id='.$event['event_id'].'">'.$event['ev_title'].'</a>';
			awec_render_event($event);
		}
	}
} else {
	echo '<p>'.$locale['awec_no_events'].'</p>';
}



?>
