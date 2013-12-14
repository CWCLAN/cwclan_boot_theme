<?php

/**
 *
 * @package awEventCalendar
 * @version $Id: $
 * @copyright (c) 2006-2010 Artur Wiebe <wibix@gmx.de>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
  +--------------------------------------------------------+
  | Modded for full responsive PHP-Fusion Theme
  | Repo : https://github.com/globeFrEak/CWCLAN-PHPF-Theme
  | Modders : globeFrEak, nevo & xero - www.cwclan.de
  +-------------------------------------------------------- */
if (!defined('IN_FUSION')) {
    die;
}



if (isset($_GET['date']) && preg_match(AWEC_DATE_FORMAT, $_GET['date'])) {
    $date = $_GET['date'];
} else {
    $date = $ec_year . '-' . $ec_month . '-' . $ec_mday;
}

// get first day of the week
$from_unix = strtotime($date);
$day_of_week = date('w', $from_unix);
if ($awec_settings['sun_first_dow'] == 'yes') {
    if ($day_of_week != 0) {
        $from_unix -= $day_of_week * 86400;
    }
} else {
    if ($day_of_week != 1) {
        $from_unix -= ($day_of_week ? $day_of_week - 1 : 6) * 86400;
    }
}


$from = date('Y-m-d', $from_unix);
$to = date('Y-m-d', $from_unix + 518400);

$days_in_from = date('t', $from_unix);
list($from_year, $from_month, $from_mday) = explode('-', $from);
list($to_year, $to_month, $to_mday) = explode('-', $to);



/* * **************************************************************************
 * GET
 */
$needle['from'] = $from;
$needle['to'] = $to;
if (awec_get_events($needle, $events, false) === false) {
    ff_redirect('index.php');
}


$res = dbquery("SELECT
	DATE_FORMAT('" . $from . "', '" . $awec_settings['date_fmt'] . "') AS from_date,
	DATE_FORMAT('" . $to . "', '" . $awec_settings['date_fmt'] . "') AS to_date
	");
$row = dbarray($res);
$caption = $row['from_date'] . ' - ' . $row['to_date'];
awec_set_title($caption);



/* * **************************************************************************
 * GUI
 */
echo '
<table border="0" width="100%" cellspacing="0">
<tbody>
<tr>
	<td align="left"><a href="' . $cal_href . '&amp;date=' . date('Y-m-d', $from_unix - 604800) . '">&lt;&lt;</a></td>
	<td align="center">' . $caption . '</td>
	<td align="right"><a href="' . $cal_href . '&amp;date=' . date('Y-m-d', $from_unix + 604800) . '">&gt;&gt;</a></td>
</tr>
</tbody>
</table>

<hr />';

if (!count($events)) {
    echo '<p>' . $locale['awec_no_events'] . '</p>';
    return;
}



$days = array();

$from_mday += 0;
for ($i = 0; $i < 7; ++$i) {
    if ($from_mday > $days_in_from) {
        $from_mday = 1;
        $from_year = $to_year;
        $from_month = $to_month;
    }

    $from_mday = ($from_mday < 10 ? '0' : '') . $from_mday;
    $days[$from_mday] = array(
        'date' => $from_year . '-' . $from_month . '-' . $from_mday,
        'events' => array(),
    );
    ++$from_mday;
}

foreach ($events as $year => $y_data) {
    foreach ($y_data as $month => $m_data) {
        foreach ($m_data as $day => $d_data) {
            $day = ($day < 10 ? '0' : '') . $day;
            foreach ($d_data as $event) {
                $days[$day]['events'][] = '<a href="view_event.php?id=' . $event['event_id'] . '" class="cwtooltip" data-toggle="tooltip" title="' . $event['ev_title'] . '"><span class="icon-calendar2 mid"></span></a>';
            }
        }
    }
}


$w = ($awec_settings['sun_first_dow'] == 'yes' ? 0 : 1);
echo '
<table class="' . $awec_styles['calendar'] . '" cellspacing="0" width="100%" ">
<colgroup>
	<col width="14%" span="7" />
</colgroup>
<thead>
<tr>';
foreach ($days as $mday => $day) {
    echo '
	<th' . (!empty($awec_styles['th-row']) ? ' class="' . $awec_styles['th-row'] . '"' : '') . '><a href="calendar.php?cal=day&amp;date=' . $day['date'] . '">' . $locale['EC901'][$w++] . ' ' . $mday . '</a></th>';
}
echo '
</tr>
</thead>
</tbody>
<tr>';
foreach (array_values($days) as $day) {
    $num_events = count($day['events']);
    echo '
	<td valign="top" class="' . $awec_styles[$num_events ? 'content' : 'empty'] . '">';
    if ($num_events) {
        echo '
		<ul>
			<li>' . implode("</li>\n\t\t\t<li>", $day['events']) . '</li>
		</ul>';
    }
    echo '
	</td>';
}
echo '
</tr>
</tbody>
</table>';
?>
