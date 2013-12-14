<?php

/* * *************************************************************************
 *   awEventCalendar                                                       *
 *                                                                         *
 *   Copyright (C) 2006-2010 Artur Wiebe                                   *
 *   wibix@gmx.de                                                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
  +--------------------------------------------------------+
  | Modded for full responsive PHP-Fusion Theme
  | Repo : https://github.com/globeFrEak/CWCLAN-PHPF-Theme
  | Modders : globeFrEak, nevo & xero - www.cwclan.de
  +-------------------------------------------------------- */
if (!defined('IN_FUSION')) {
    die;
}


/* * **************************************************************************
 * GET
 */
// create cal-navigation
$pyear = $ec_year;
$nyear = $ec_year;
$pmonth = $ec_month - 1;
$nmonth = $ec_month + 1;
if ($pmonth < 1) {
    $pmonth = 12;
    $pyear = $ec_year - 1;
} elseif ($nmonth > 12) {
    $nmonth = 1;
    $nyear = $ec_year + 1;
}

$sel_months = '';
for ($i = 1; $i <= 12; ++$i) {
    $sel_months .= '
	<option value="' . $i . '"'
            . ($i == $ec_month ? ' selected="selected"' : ''
            ) . '>' . $locale['EC900'][$i] . '</option>';
}



/* * **************************************************************************
 * GUI
 */


//flyingduck added &amp; instead of &
echo '
<table border="0" width="100%">
<colgroup>
	<col width="20%" />
	<col width="60%" />
	<col width="20%" />
</colgroup>
<tbody>
<tr>
	<td align="left">
		<a href="' . $cal_href . '&amp;y=' . $pyear . '&amp;m=' . $pmonth . '">&lt;&lt; ' . $locale['EC900'][$pmonth] . '</a>
	</td>
	<td align="center">
		<form method="get" action="' . FUSION_SELF . '">
		<input type="hidden" name="cal" value="' . $cal . '" />
		<input type="hidden" name="view" value="' . $view . '" />
		<input type="hidden" name="cat" value="' . $cat . '" />
		<select class="textbox" name="m">' . $sel_months . '</select>
		<input type="text" size="4" maxlength="4" class="textbox" name="y" value="' . $ec_year . '" />
		<input type="submit" value="' . $locale['awec_show'] . '" class="button" />
		</form>
	</td>
	<td align="right">
		<a href="' . $cal_href . '&amp;y=' . $nyear . '&amp;m=' . $nmonth . '">' . $locale['EC900'][$nmonth] . ' &gt;&gt;</a>
	</td>
</tr>
</tbody>
</table>

<hr />';



$month = ($ec_month < 10 ? '0' : '') . $ec_month;


$needle['from'] = $ec_year . '-' . $month . '-01';
$needle['to'] = $ec_year . '-' . $month . '-' . $awec_last_day;
awec_get_events($needle, $events, ($view == 'clist' ? true : false));

if ($view == 'clist') {
//
} else {
    $content = array();
    for ($i = 1; $i <= 31; ++$i) {
        $date = $ec_year . '-' . $month . '-' . ($i < 10 ? '0' : '') . $i;
        if ($view == 'list') {
            $content[$i] = '&nbsp;';
        } else {
            $content[$i] = array(
                'style' => 'empty',
                'data' => '<a href="' . FUSION_SELF . '?cal=day&amp;date=' . $date . '"><span>' . $i . '.</span></a>',
            );
            if (iAWEC_POST) {
                $content[$i]['data'] .= '<a href="edit_event.php?date=' . $date . '" title="' . $locale['EC200'] . '"><span>+</span></a>';
            }
        }
    }
}

if (count($events) && $view != 'clist') {
    foreach ($events[$ec_year][$ec_month] as $day => $more) {
        $birthday_style = '';
        $btext = '';
        foreach ($more as $event) {
            $title = $event['ev_title'];
            if ($event['is_birthday']) {
                $btext .= '<li><a href="birthday.php?id=' . $event['user_id'] . '" class="cwtooltip" data-toggle="tooltip" title="' . $event['ev_title'] . '"><span class="icon-gift mid"></span></a></li>';
                $birthday_style = ' birthday';
            } else {
                $btext .= '<li><a href="view_event.php?id=' . $event['event_id'] . '" class="cwtooltip" data-toggle="tooltip" title="' . $event['ev_title'] . '"><span class="icon-calendar2 mid"></span></a></li>';
            }
        }
        /* FIXME.begin */
        if ($view != 'clist' && $view != 'list') {
            $btext = str_replace("%", "%%", $btext);
        }
        /* FIXME.end */
        if (!empty($btext)) {
            $btext = '<ul>' . $btext . '</ul>';
        }

        if ($view == 'list') {
            $content[$day] = $btext;
        } else {
            $content[$day]['style'] = 'content' . $birthday_style;
            $content[$day]['data'] .= $btext;
        }
    }
}

if ($ec_is_this_month && $view != 'clist' && $view != 'list') {
    $content[$ec_today['mday']]['style'] = 'current';
}


switch ($view) {
    case 'clist':
        require_once('view_clist.php');
        break;
    case 'list':
        $this_month = mktime(0, 0, 0, $ec_month, 1, $ec_year);
        $daysinmonth = date('t', $this_month);
        $first_dow = date('w', $this_month);
        require_once('view_list.php');
        break;
    default:
        awec_render_cal($ec_month, $ec_year, '', $content, 80, true, $awec_settings['sun_first_dow'] == 'yes', $awec_settings['show_week']);
        break;
}
?>
