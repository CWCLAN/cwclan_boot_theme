<?php
//Theme Settings
define("THEME_BULLET", "<span class='bullet'>&middot;</span>"); //bullet image
//Theme Settings /

if (!defined("IN_FUSION")) {
    die("Access Denied");
}
require_once INCLUDES . "theme_functions_include.php";

function get_head_tags() {
    echo "<link rel='stylesheet' href='" . THEME . "css/bootstrap.css'>";
    echo "<link rel='stylesheet' href='" . THEME . "css/bootstrap-responsive.css'>";
    echo "<link rel='stylesheet' href='" . THEME . "css/icomoon.css'>";
    echo "<link rel='stylesheet' href='" . THEME . "css/main.css'>";
    echo "<link rel='stylesheet' href='" . THEME . "css/normalize.min.css'>";
    echo "<link rel='stylesheet' href='" . THEME . "css/responsive.css'>";
    echo "<link href='http://fonts.googleapis.com/css?family=Oswald:400,300|Roboto:400,500|Roboto+Condensed:400,300,700|Roboto+Slab:400,300,700' rel='stylesheet' type='text/css'>";
}

function render_page($license = false) {
    global $aidlink, $locale, $settings, $main_style;

    // Topbar Content
    echo '<div class="topbar navbar">
            <div class="title">CWClan <span class="subtitle">Clan & Community</span></div>
            ' . showbanners() . '</div>'; // Topbar End
    // Content Begin
    echo '
        <div class="wrapper clearfix">

            <div class="breadcrumb">Du bist hier: <span class="c_orange">Home</span></div>

            <div class="hero"></div>';

    // Navbar Begin
    echo '
            <div class="navbar navbar-inverse">
                <div class="container">
                    <div class="navbar-inner">
                        <div class="container">
                            <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                            <div class="navi_hint no-pc"><a data-toggle="collapse" data-target=".nav-toggle">Navigation</a></div>
                            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-toggle">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </a>
                            <div class="nav-toggle nav-collapse collapse">
                                <ul class="nav">
                                    <li><a href="'.BASEDIR.'index.php">Startseite</a></li>
                                    <li><a href="'.BASEDIR.'forum/index.php">Forum</a></li>                                    
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            Fotogalerie
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="'.BASEDIR.'photogallery.php">Galerie</a></li>
                                            <li><a href="#">Meist angesehene Bilder</a></li>
                                        </ul>
                                    </li> <!-- Dropdown End -->
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            Server
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">HLStats</a></li>
                                            <li><a href="#">Map Bewertungen</a></li>
                                            <li><a href="#">Reserved Slots</a></li>
                                        </ul>
                                    </li> <!-- Dropdown End -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>'; //Navbar End
    // Main / Content Begin
    echo '
            <div class="main clearfix">
                <div class="content">

                    ' . U_CENTER . CONTENT . L_CENTER . '

                </div>';
// Sidebar		
    echo '
                <div class="sidebar">';
    if (RIGHT) {
        echo RIGHT;
    }
    if (LEFT) {
        echo LEFT;
    }
    echo '			</div>
            </div>';
// Footer
    echo'
            <footer class="clearfix"><span style="float:left;padding-top:7px">(c) 2013 <span class="c_orange">cwclan</span> - clan & community</span>
            <span style="float:right">'.showcopyright().'</span></footer>
        </div>  
        <div class="footernav flleft visible-desktop">
            <div class="links-section flleft">
                <h4>Server</h4>                    
                <ul class="links-s-content">
                    <li>
                        <a href="#" title="" target="_blank">TF2</a>
                    </li>
                    <li>
                        <a href="#" title="" target="_blank">Minecraft</a>
                    </li>
                    <li>
                        <a href="#" title="" target="_blank">Mumble - Voice Server</a>
                    </li>
                </ul>
            </div>
            <div class="links-section flleft">
                <h4>Heißer Stuff</h4>                    
                <ul class="links-s-content">
                    <li>
                        <a href="http://timkopplow.com/dev/cwish/" target="_blank" title="Nevos Responsive Design">Nevos Responsive Design</a>
                    </li>
                    <li>
                        <a href="#" title="">Test</a>
                    </li>
                </ul>
            </div>
            <div class="links-section flleft">
                <h4>Info</h4>
                <div class="links-s-content">
                PHP-Fusion Version:<b> '.$settings['version'].'</b><br>'.showrendertime().'
                </div>
            </div>
        </div>';
    // Scripts and co.
    echo '
		<!-- Scripts -->        
        <script src="' . THEME . 'js/vendor/bootstrap.min.js"></script>
        <script src="' . THEME . 'js/plugins.js"></script>
        <script src="' . THEME . 'js/main.js"></script>       
        </script>
        <script> 
            $(".tp").tooltip({
            placement : "right"
            });
        </script>
        
        <script> 
            $(".tp2").tooltip({
            placement : "right"
            });
        </script>';    
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
            $comm_count = "<a href='" . FUSION_REQUEST . "#c" . $data['comment_id'] . "' id='c" . $data['comment_id'] . "' name='c" . $data['comment_id'] . "'>#" . $data['i'] . "</a>";
            echo "<div class='tbl2 clearfix floatfix'>\n";
            if ($settings['comments_avatar'] == "1") {
                echo "<span class='comment-avatar'>" . $data['user_avatar'] . "</span>\n";
            }
            echo "<span style='float:right' class='comment_actions'>" . $comm_count . "\n</span>\n";
            echo "<span class='comment-name'>" . $data['comment_name'] . "</span>\n<br />\n";
            echo "<span class='small'>" . $data['comment_datestamp'] . "</span>\n";
            if ($data['edit_dell'] !== false) {
                echo "<br />\n<span class='comment_actions'>" . $data['edit_dell'] . "\n</span>\n";
            }
            echo "</div>\n<div class='tbl1 comment_message'>" . $data['comment_message'] . "</div>\n";
        }
        echo $c_makepagenav;
        if ($c_info['admin_link'] !== FALSE) {
            echo "<div style='float:right' class='comment_admin'>" . $c_info['admin_link'] . "</div>\n";
        }
        echo "</div>\n";
    } else {
        echo $locale['c101'] . "\n";
    }
    closetable();
}

function render_news($subject, $news, $info) {

    global $locale;



    echo "\n
	<article>
	" . (!empty($title) ? "<h3>$title</h3>" : "") . "\n";
    echo '
                        <div class="article_submenu clearfix">
                            <div class="article_submenu_left">
                                ' . $info['cat_image'] . '
                            </div>
                            <div class="article_submenu_right">
						      <h3>' . $subject . '</h3>
                                <span class="author">
                                Published by ' . newsposter($info, '') . itemoptions('N', $info['news_id']) . '
                                </span>
                            </div>
                        </div>
                        <p class="article">
						' . $news . '
                        </p>
                        <span class="comments">
                        <a href="#">' . newsopts($info, ' &middot; ') . '</a>
                        </span>
                    </article>';
}

function render_article($subject, $article, $info) {
    global $locale;

    echo "\n
	<article>
	" . (!empty($title) ? "<h3>$title</h3>" : "") . "\n";
    echo '
                        <div class="article_submenu clearfix">
                            <div class="article_submenu_left">
                                ' . $info['cat_image'] . '
                            </div>
                            <div class="article_submenu_right">
						      <h3>' . $subject . '</h3>
                                <span class="author">
                                Published by ' . articleposter($info, '') . itemoptions('N', $info['news_id']) . '
                                </span>
                            </div>
                        </div>
                        <p class="article">
						' . $article . '
                        </p>
                        <span class="comments">
                        <a href="#">' . articleopts($info, ' &middot; ') . '</a>
                        </span>
                    </article>';
}

function opentable($title) {

    echo "\n
	<article>
	" . (!empty($title) ? "<h3>$title</h3>" : "") . "\n";
}

function closetable() {

    echo "
                    </article>\n";
}

$panel_collapse = true;

function openside($title, $collapse = false, $state = "on") {

    global $panel_collapse;
    $panel_collapse = $collapse;

    echo "<div class='box'>";
    echo "<h3>" . $title . "</h3>\n";
    echo "<div class='sidebar_div'>";

    // Collapse?
    if ($collapse == true) {
        $boxname = str_replace(" ", "", $title);
        echo "" . panelbutton($state, $boxname) . "";
    }
    echo '';

    // Collapse?
    if ($collapse == true) {
        echo panelstate($state, $boxname);
    }
}

function closeside() {

    global $panel_collapse;

    if ($panel_collapse == true) {
        echo "";
    }
    echo "</div></div>";
}

?>
