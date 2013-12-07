<?php

/* * *************************************************************************
 *   awEventCalendar                                                       *
 *                                                                         *
 *   Copyright (C) 2006-2012 Artur Wiebe                                   *
 *   wibix@gmx.de                                                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 * ************************************************************************* */
if (!defined('IN_FUSION')) {
    die;
}
require_once(INFUSIONS . 'aw_ecal_panel/include/core.php');
/* * **************************************************************************
 * FUNCS
 */
if (!function_exists('awec_post_process_events')) {

    function awec_post_process_events_sort_today($a, $b) {
        if ($a == $b) {
            return 0;
        }
        if (!$a['start_time'] || !$b['start_time']) {
            return -1;
        }

        $a_start = str_replace(':', '', $a['start_time']);
        $b_start = str_replace(':', '', $b['start_time']);
        if ($a_start == $b_start) {
            return 0;
        }
        return ($a_start > $b_start ? 1 : -1);
    }

    function awec_post_process_events(&$events, &$out, $compress_daylies = false) {
        global $ec_today, $ec_tomorrow, $locale, $awec_settings;

        $count = 0;

        $out = array(
            'today' => array(),
            'tomorrow' => array(),
            'others' => array(),
        );

        $current = 'others';
        $path_event = INFUSIONS . 'aw_ecal_panel/view_event.php?id=';
        $path_birthday = INFUSIONS . 'aw_ecal_panel/birthday.php?id=';
        $show_details = ($awec_settings['show_today_in_panel'] ? true : false);

        // dayly recurring events are only shown once. Array contains event_id.
        $ignore_daylies = array();

        foreach ($events as $year => $y_data) {
            ksort($y_data, SORT_NUMERIC);

            foreach ($y_data as $month => $m_data) {
                ksort($m_data, SORT_NUMERIC);

                $today_month = ($ec_today['mon'] == $month && $ec_today['year'] == $year);
                $tomorrow_month = ($ec_tomorrow['mon'] == $month && $ec_tomorrow['year'] == $year);

                foreach ($m_data as $mday => $d_data) {
                    if ($today_month && $ec_today['mday'] == $mday) {
                        $current = 'today';
                    } else if ($tomorrow_month && $ec_tomorrow['mday'] == $mday) {
                        $current = 'tomorrow';
                    } else {
                        $current = 'others';
                    }

                    if ($current == 'today') {
                        usort($d_data, 'awec_post_process_events_sort_today');
                    }

                    foreach ($d_data as $ev) {
                        $item = '';
                        $event_compressed = false;

                        if ($compress_daylies && $ev['ev_repeat'] == AWEC_REP_DAY) {
                            if (in_array($ev['event_id'], $ignore_daylies)) {
                                continue;
                            }
                            $ignore_daylies[] = $ev['event_id'];

                            $event_compressed = true;
                            $item .= awec_format_daily_date($ev['ev_start']);
                            if ($ev['ev_end'] != '0000-00-00') {
                                $item .= ' - ' . awec_format_daily_date($ev['ev_end']);
                            }
                            $item .= ' ';
                        }

                        if ($ev['is_birthday']) {
                            $path = $path_birthday . $ev['user_id'];
                        } else {
                            $path = $path_event . $ev['event_id'];
                        }
                        if ($show_details && $current == 'today') {
                            $body = explode(stripinput(AWEC_BREAK), $ev['ev_body']);
                            $body[0] = parseubb($body[0]);
                        }
                        if ($ev['is_birthday']) {
                        $ev['ev_title'] = preg_replace("/Geburtstag von/", "<span class='icon-gift'></span>", $ev['ev_title']);    
                        } else {
                        $ev['ev_title'] = "<span class='icon-calendar2'></span>&nbsp;". $ev['ev_title'];
                        }
                        $link = '<a href="' . $path . '" class="cwtooltip" title="' . $body[0] . '">' . $ev['ev_title'] . '</a>';

                        // today/tomorrow
                        if ($current != 'others') {
                            if ($ev['start_time']) {
                                $item .= $ev['start_time'];
                                if ($ev['end_time']) {
                                    $item .= '-' . $ev['end_time'];
                                }
                                $item .= '&nbsp;';
                            }
                            // others
                        } else {
                            if (!$event_compressed) {
                                $item .= awec_format_fucking_date($year, $month, $mday, $ev['start_time'], $ev['end_time'], $link) . '&nbsp;';
                                $link = '';
                            }
                        }

                        $item .= $link;
                        /*
                        if ($ev['is_birthday']) {
                            $item .= ' <img src="' . INFUSIONS . 'aw_ecal_panel/icons/birthday.gif" alt="' . $locale['EC712'] . '" title="' . $locale['EC712'] . '" />';
                        }*/

                        if ($show_details && $current == 'today') {
                            $body = explode(stripinput(AWEC_BREAK), $ev['ev_body']);
                            $body[0] = parseubb($body[0]);
                            if (count($body) > 1) {
                                $body[0] = ' <a href="' . $path . '"><span class="icon-plus"></span></a>';
                                $item .= '<span class="small">' . $body[0] . '</span>';
                            }
                        }

                        $out[$current][] = $item;
                        ++$count;
                    }
                }
            }
        }

        return $count;
    }

}
/*
 * show next x days
 */
if ($awec_settings['next_days_in_panel']) {
    $from_time = $awec_now;
    $to_time = $from_time + ($awec_settings['next_days_in_panel'] - 1) * 86400;

    $events = array();
    $needle = array(
        'from' => date('Y-m-d', $from_time),
        'to' => date('Y-m-d', $to_time),
    );
    awec_get_events($needle, $events, false);


    $out = array();
    $count = awec_post_process_events($events, $out, true);


    if ($count) {
        openside("<span class='icon-calendar iconpaddr'></span>" . $locale['EC001']);

        $path = INFUSIONS . 'aw_ecal_panel';

        if (iMEMBER && false) {
            echo '
<ul>';
            if (iAWEC_POST) {
                echo '
	<li><a href="' . $path . '/edit_event.php">' . $locale['EC200'] . '</a></li>';
            }
            echo '
	<li><a href="' . $path . '/my_events.php">' . $locale['EC204'] . '</a></li>
	<li><a href="' . $path . '/my_logins.php">' . $locale['EC206'] . '</a></li>';
            if (awec_admin_access()) {
                echo '
	<li><a href="' . $path . '/admin.php">' . $locale['EC700'] . '</a></li>';
            }
            echo '
</ul>';
        }


        if (iAWEC_ADMIN && ff_db_count("(*)", AWEC_DB_EVENTS, "(ev_status='" . AWEC_PENDING . "')")) {
            echo '
<div style="text-align:center;">
<p>
	<strong><a href="' . $path . '/new_events.php">' . $locale['EC203'] . '</a></strong>
</p>
</div>';
        }

        $more = 0;
        foreach ($out as $type => $content) {
            if (!count($content)) {
                continue;
            }
            if ($type != 'others' || $more) {
                echo '
<strong>' . $locale['EC209'][$type] . ':</strong>';
            }
            echo '
<ul class="awec_panel_ul">
	<li>' . implode("</li>\n\t<li>", $content) . '</li>
</ul>';
            ++$more;
        }


        closeside();
    }
}
?>
