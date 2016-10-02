<?php

function detect_mobile() {
    if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|playbook|sagem|sharp|sie-|silk|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
        return true;
    else
        return false;
}

define("THEME_BULLET", "<span class='bullet'>&middot;</span>");

if (!defined("IN_FUSION")) {
    die("Access Denied");
}
require_once INCLUDES . "theme_functions_include.php";

function get_head_tags() {
    if (detect_mobile() === true) {
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<script>if (navigator.userAgent.match(/IEMobile\/10\.0/)) {var msViewportStyle = document.createElement(\"style\");
                    msViewportStyle.appendChild(document.createTextNode(\"@-ms-viewport{width:auto!important}\")
                );document.getElementsByTagName(\"head\")[0].appendChild(msViewportStyle);}</script>";
    } else {
        echo "<meta name='viewport' content='width=951, initial-scale=1.0'>";
    }
    echo "<link rel='icon' href='" . THEME . "img/favicon.png' type='image/png'>";
    echo "<link rel='apple-touch-icon' href='" . THEME . "img/icon-200.png' />";
    echo "<link rel='image_src' href='" . THEME . "img/icon-200.png'>";
    //echo "<link rel='stylesheet' href='" . THEME . "css/bootstrap.min.css'>";    
    //echo "<link rel='stylesheet' href='" . THEME . "css/responsive.min.css'>";
    //echo "<link rel='stylesheet' href='" . THEME . "css/icomoon.min.css'>";
    //echo "<link rel='stylesheet' href='" . THEME . "css/phpf-fu.min.css'>";    
    echo "<link href='https://fonts.googleapis.com/css?family=Oswald:400,300|Roboto:400,500|Roboto+Condensed:400,300,700|Roboto+Slab:400,300,700' rel='stylesheet' type='text/css'>";
}

function render_page($license = false) {
    global $aidlink, $locale, $settings, $main_style;

    // Topbar Content
    echo '<div class="topbar navbar">
            <div class="title">CWClan <span class="subtitle">Clan & Community</span></div>
          </div>'; // Topbar End
    // Content Begin
    echo '<div class="wrapper clearfix">          
          <div class="breadcrumb">';
    include INFUSIONS . "cw_login/login.php";
    echo"</div><a href='" . BASEDIR . "index.html'><div class='hero'>";
    echo"<img src='" . THEME . "img/icon-200.png' id='cw_logo' class='cwtooltip' alt='Das Logo!' title='Das Logo!'>";
    echo"</div></a>";
    // Navbar Begin
    echo'<nav class="navbar navbar-inverse" role="navigation">        
            <div class="navbar-header">
                <button type="button" class="navbar-toggle icon-menu2 mid" data-toggle="collapse" data-target="#navbar-navi-collapse">                       
                </button>                
            </div>        
            <div class="collapse navbar-collapse" id="navbar-navi-collapse">';
    include INFUSIONS . "css_dropdown_menu/menu_boot.php";
    echo '</div><!-- /.navbar-collapse -->
        </nav>';
    // Main / Content Begin
    echo '<div class="main clearfix">
                <div class="content">';
    echo U_CENTER . CONTENT . L_CENTER;
    echo'</div>';
    // Sidebar		
    echo '<div class="sidebar">';
    if (LEFT) {
        echo LEFT;
    }
    if (RIGHT) {
        echo RIGHT;
    }
    echo '</div></div>';
    // Upper Footer
    echo '<div class="row upperfooter">
            <div class="col-md-4">';
    include INFUSIONS . "cw_login/site_info.php";
    echo'</div>
            <div class="col-md-4 clearfix">
                <h4>Social</h4>
                <ul class="vertical">
                    <li>
                        <a href="https://plus.google.com/share?url=www.cwclan.de&hl=de" class="cwtooltip" title="Teile uns bei Google+!"
                        onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;">
                        <span class="icon-google-plus3"></span>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.facebook.com/share.php?u=http://www.cwclan.de" target="_blank" class="cwtooltip" title="Teile uns bei Facebook!">
                        <span class="icon-facebook2"></span>
                        </a>
                    </li>
                    <li>
                        <a class="twitter-share-button cwtooltip"
                            href="https://twitter.com/share?url=https%3A%2F%2Fwww.cwclan.de&via=cwclande&related=cwclande&hashtags=cwclan&text=CWCLAN%20-%20Community,%20Zwitscher%20über%20uns!"
                            target="_blank" 
                            title="Zwitscher über uns!" >
                        <span class="icon-twitter2"></span>
                        </a>
                    </li>
                    <li>
                        <a href="http://steamcommunity.com/groups/CW-CLAN" target="_blank" class="cwtooltip" title="Tritt unserer Steam Community bei!">
                        <span class="icon-steam2"></span>
                        </a>
                    </li>
                    <li>
                        <a href="' . BASEDIR . 'infusions/comy_rss_panel/rss.php?type=1" target="_blank" class="cwtooltip" title="Nachrichten Feed zum Abonnieren!">
                        <span class="icon-feed3"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4>Info</h4>
                <div>
                PHP-Fusion Version:<b> ' . $settings['version'] . '</b><br>' . showrendertime() . '
                </div></div>
          </div>';

    // Footer
    echo'<footer class="clearfix"><span style="float:left"><span class="icon-html5 large icons-vmid"></span><span class="icon-css3 large icons-vmid"></span>(c) 2013 <span class="c_orange">cwclan</span> - clan & community</span>
            <span style="float:right">' . showcopyright() . '</span></footer></div>';

    // Scripts and co.
    add_to_footer('<!-- Scripts -->
        <script src="' . THEME . 'js/vendor/bootstrap.min.js"></script>
        <script src="' . THEME . 'js/vendor/modernizr-2.6.2.min.js"></script>
        <script src="' . THEME . 'js/plugins.js"></script>
        <script src="' . THEME . 'js/main.js"></script>');
}

/* New in v7.02 - render comments */

function render_comments($c_data, $c_info) {
    global $locale, $settings;
    opentable($locale['c100']);
    if (!empty($c_data)) {
        echo "<div class='comments floatfix'>\n";
        $c_makepagenav = '';
        if ($c_info['c_makepagenav'] !== FALSE) {
            echo $c_makepagenav = "<div style='text-align:center;margin-bottom:5px;'>" . $c_info['c_makepagenav'] . "</div>\n";
        }
        foreach ($c_data as $data) {
            $comm_count = "<a href='" . FUSION_REQUEST . "#c" . $data['comment_id'] . "' id='c" . $data['comment_id'] . "'>#" . $data['i'] . "</a>\n";
            echo "<div class='comment clearfix'>";
            echo "<div class='commentboxname clearfix'>";
            /* UserAvatar */
            if ($settings['comments_avatar'] == 1) {
                echo "<a href='" . BASEDIR . "profile.php?lookup=" . $data['comment_author_id'] . "' class='comments_user_avatar'>" . $data['user_avatar'] . "</a>\n";
            } else {
                echo "<a href='" . BASEDIR . "profile.php?lookup=" . $data['comment_author_id'] . "' class='comments_user_avatar'><img src='" . IMAGES . "avatars/noavatar100.png' alt='" . $locale['567'] . "' /></a>\n";
            }
            echo "</div>\n";
            echo "<div class='commentbody'>";

            /* Date & Count */
            echo "<div class='commentboxdate clearfix'><span class='icon-clock'></span>" . str_replace(',', '', $data['comment_datestamp']) . "<span style='float:right' class='comment_actions'>" . $comm_count . "</span>\n</div>";

            /* Content */
            echo "<div class='commentbox clearfix'>
            <div class='commentheader'>" . $data['comment_name'] . " schrieb:</div>
            <div>" . $data['comment_message'] . "</div></div>\n";

            if ($data['edit_dell'] !== false) {
                echo "<span class='comment_actions'>" . $data['edit_dell'] . "</span>\n";
            }
            echo "</div></div>\n";
        }
        echo $c_makepagenav;
        if ($c_info['admin_link'] !== FALSE) {
            echo "<div style='float:right' class='comment_admin'>" . $c_info['admin_link'] . "</div>\n";
        }
        echo "</div>\n";
    } else {
        echo $locale['c101'];
    }
    closetable();
}

function newsposter2($info, $sep = "", $class = "") {
    global $locale;
    $res = "";
    $link_class = $class ? " class='$class' " : "";
    $res = "<span " . $link_class . ">" . profile_link($info['user_id'], $info['user_name'], $info['user_status']) . "</span>&nbsp;";
    $res .= showdate("newsdate", $info['news_date']);
    $res .= $info['news_ext'] == "y" || $info['news_allow_comments'] ? $sep : "";
    return "<!--news_poster-->" . $res;
}

function newsopts2($info, $sep, $class = "") {
    global $locale, $settings;
    $res = "";
    $link_class = $class ? " class='$class' " : "";
    if ($info['news_allow_comments'] && $settings['comments_enabled'] == "1") {
        //$res = "<a href='news.php?readmore=" . $info['news_id'] . "#comments'" . $link_class . ">" . $info['news_comments'] . "&nbsp;<span class='icon-bubbles'></span></a> " . $sep . " ";
        //"^/news-([0-9]+)-(.*)\.html(.*)$" => "/news.php?readmore=$1$3",
        $res = "<a href='" . BASEDIR . "news-" . $info['news_id'] . "-" . seostring($info['news_subject']) . ".html#comments'" . $link_class . ">" . $info['news_comments'] . "&nbsp;<span class='icon-bubbles'></span></a> " . $sep . " ";
    }
    if ($info['news_ext'] == "y" || ($info['news_allow_comments'] && $settings['comments_enabled'] == "1")) {
        $res .= $info['news_reads'] . $locale['global_074'];
    }
    return "<!--news_opts-->" . $res;
}

function render_news($subject, $news, $info) {

    global $locale;

    //$linked_subject = '<h3><a href="news.php?readmore=' . $info['news_id'] . '" id="news_' . $info['news_id'] . '">' . $info['news_subject'] . '</a></h3>';
    //"^/news-([0-9]+)-(.*)\.html(.*)$" => "/news.php?readmore=$1$3",
    $linked_subject = '<h3><a href="' . BASEDIR . 'news-' . $info['news_id'] . '-' . seostring($info['news_subject']) . '.html" id="news_' . $info['news_id'] . '">' . $info['news_subject'] . '</a></h3>';

    echo "<article>        
	" . (!empty($subject) ? "$linked_subject" : "$subject") . "\n";
    echo '<div class="article_submenu clearfix">
                            <span class="author">
                                ' . newsposter2($info, '') . itemoptions('N', $info['news_id']) . '
                            </span>
                            <span class="comments">
                                ' . newsopts2($info, ' &middot; ') . '
                            </span>
                        </div>
                        <div class="article clearfix">
						' . $news . '
                        </div>';
    //echo (!isset($_GET['readmore']) && $info['news_ext'] == 'y' ? "<div class='pull-right'><a href='news.php?readmore=" . $info['news_id'] . "' class='cwtooltip' title='weiterlesen: " . $info['news_subject'] . "'>more <span class='icon-newspaper mid'></span></a></div>" : "");
    //"^/news-([0-9]+)-(.*)\.html(.*)$" => "/news.php?readmore=$1$3",  
    echo (!isset($_GET['readmore']) && $info['news_ext'] == 'y' ? "<div class='pull-right'><a href='" . BASEDIR . "news-" . $info['news_id'] . "-".  seostring($info['news_subject']).".html' class='cwtooltip' title='weiterlesen: " . $info['news_subject'] . "'>more <span class='icon-newspaper mid'></span></a></div>" : "");
    echo'</article>';
}

function render_article($subject, $article, $info) {
    global $locale;

    echo "<article>
	" . (!empty($title) ? "<h3>$title</h3>" : "") . "\n";
    echo '<div class="article_submenu clearfix">
                            <div class="article_submenu_left">
                                ' . $info['cat_image'] . '
                            </div>
                            <div class="article_submenu_right">
						      <h3>' . $subject . '</h3>
                                <span class="author">
                                ' . articleposter($info, '') . itemoptions('A', $info['news_id']) . '
                                </span>
                            </div>
                        </div>
                        <div class="article">
						' . $article . '
                        </div>
                        <span class="comments">
                        <a href="#">' . articleopts($info, ' &middot; ') . '</a>
                        </span>
                    </article>';
}

function opentable($title) {

    echo "<article>
	" . (!empty($title) ? "<h3>$title</h3>" : "");
}

function closetable() {

    echo "</article>\n";
}

function openside($title, $collapse = false, $state = "on") {
    global $panel_collapse;
    $panel_collapse = $collapse;
    echo "<div class='box'>\n";
    echo "<h3>" . $title . "</h3>\n";
    if ($collapse == true) {
        $boxname = str_replace(" ", "", $title);
        echo "<span>" . panelbutton($state, $boxname) . "</span>\n";
    }
    echo "<div class='sidebar_div'>";
    if ($collapse == true) {
        echo panelstate($state, $boxname);
    }
}

function closeside() {
    global $panel_collapse;
    if ($panel_collapse == true) {
        echo "</div>\n";
    }
    echo "</div>\n</div>\n";
}

?>
