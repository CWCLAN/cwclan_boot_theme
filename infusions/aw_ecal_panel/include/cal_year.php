<?php
/***************************************************************************
 *   awEventCalendar                                                       *
 *                                                                         *
 *   Copyright (C) 2006-2008 Artur Wiebe                                   *
 *   wibix@gmx.de                                                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 ***************************************************************************/
if(!defined('IN_FUSION')) {
	die;
}


awec_set_title($locale['awec_calendar'].' - '.$ec_year);


echo '
<form action="'.FUSION_SELF.'" method="get">
<input type="hidden" name="cal" value="year" />
<input type="hidden" name="cat" value="'.$cat.'" />
<table width="100%" border="0">
<colgroup>
	<col width="20%" />
	<col width="60%" />
	<col width="20%" />
</colgroup>
<tbody>
<tr>
	<td align="left">
		<a href="'.$cal_href.'&amp;y='.($ec_year-1).'">&lt;&lt; '.($ec_year-1).'</a>
	</td>
	<td align="center">
		<strong>'.$locale['EC450'].':</strong>
		<input type="text" class="textbox" size="5"
			maxlength="4" name="y" value="'.$ec_year.'" />
		<input type="submit" class="button"
			value="'.$locale['awec_show'].'" />
	</td>
	<td align="right">
		<a href="'.$cal_href.'&amp;y='.($ec_year+1).'">'.($ec_year+1).' &gt;&gt;</a>
	</td>
</tr>
</tbody>
</table>
</form>
<hr />';


$quicks = array();
for($i=1; $i<=12; ++$i) {
	$quicks[] = '<a href="'.$cal_href.'&amp;y='.$ec_year.'#mon'.$i.'">'
		.$locale['EC900'][$i].'</a>';
}
echo '
<p>'.implode(' | ', $quicks).'
</p>';


$needle['from']	= $ec_year.'-01-01';
$needle['to']	= $ec_year.'-12-31';
awec_get_events($needle, $events, false);


for($i=1; $i<=12; ++$i) {
	echo '
<table width="100%" cellspacing="0" class="'.$awec_styles['list'].'" id="mon'.$i.'">
<colgroup>
	<col width="24" />
	<col width="16" />
	<col width="*" />
</colgroup>
<thead>
<tr>
	<th colspan="3">'.$locale['EC900'][$i].'</th>
</tr>
</thead>
<tbody>';

	if(!isset($events[$ec_year][$i])) {
		echo '
<tr>
	<td colspan="3"></td>
</tr>
</tbody>
</table>';
		continue;
	}

	$month = ($i<10 ? '0' : '').$i;


	ksort($events[$ec_year][$i], SORT_NUMERIC);
	$count = 0;
	foreach($events[$ec_year][$i] as $mday => $day_data) {
		echo '
<tr class="'.$awec_styles[++$count%2==0 ? 'even' : 'odd'].'">
	<td valign="top" align="right">'
		.'<a href="calendar.php?cal=day&amp;date='.$ec_year.'-'.$month
			.'-'.($mday<10 ? '0' : '').$mday.'">'.$mday.'.</a></td>
	<td></td>
	<td valign="top">';
		if(count($day_data)) {
			echo '<ul>';
			foreach($day_data as $data) {
				if($data['is_birthday']) {
					echo '<li><a href="birthday.php'
						.'?id='.$data['user_id'].'">'
						.$data['ev_title'].'</a>'
						.' <img src="icons/birthday.gif" alt="'.$locale['EC712'].'" />'
						.'</li>';
				} else {
					echo '<li><a href="view_event.php'
						.'?id='.$data['event_id'].'">'
						.$data['ev_title'].'</a></li>';
				}
			}
			echo '</ul>';
		}
		echo '
	</td>
</tr>';
	}

	echo '
</tbody>
</table>
<p></p>';
}


?>
