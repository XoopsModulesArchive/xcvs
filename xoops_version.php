<?php
$modversion['name'] = "CVS";
$modversion['version'] = "1.5";
$modversion['description'] = "Current Version System";
$modversion['author'] = "Lonny Luberts";
$modversion['credits'] = "Based On Document Management System V2.0 by Richard James Kendall with zipfile class by Eric Mueller";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/file.jpg";
$modversion['dirname'] = "xcvs";//name of directory

// sql file
$modversion['sqlfile']['mysql'] = "sql/cvs.sql";

// Tables
$modversion['tables'][0] = "cvs_documents";
$modversion['tables'][1] = "cvs_history";
$modversion['tables'][2] = "cvs_phases";
$modversion['tables'][3] = "cvs_prev_versions";

// Admin things
$modversion['hasAdmin'] = 0;

// Menu for submenus in main menu when page loads
$modversion['hasMain'] = 1;//0 to remove from main menu
//this propably isn't right... but it works
$mysql_uname = XOOPS_DB_USER;
$mysql_password = XOOPS_DB_PASS;
$mysql_database = XOOPS_DB_NAME;
$mysql_server = XOOPS_DB_HOST;
$link = mysql_connect($mysql_server, $mysql_uname, $mysql_password);
mysql_select_db($mysql_database, $link);
global $xoopsUser;
$uname = $xoopsUser->getVar('uname'); 
$uid = $xoopsUser->getVar('uid'); 
$results = mysql_query("select * from ".XOOPS_DB_PREFIX."_users where uname='$uname'", $link);
$usr_obj = mysql_fetch_object($results);
$res2 = mysql_query("select groupid from ".XOOPS_DB_PREFIX."_groups_users_link where uid='$uid'", $link);
$iswebmast = mysql_fetch_assoc($res2);
if ($iswebmast[groupid] == 1) $admin_user = true;
$modversion['sub'][1]['name'] = "- Upload new file";
$modversion['sub'][1]['url']  = 'add.php';
$modversion['sub'][2]['name'] = "- Select Project";
$modversion['sub'][2]['url']  = 'set_curr_project.php';
if ($admin_user == true){
$modversion['sub'][3]['name'] = "- Build Distro";
$modversion['sub'][3]['url']  = 'build.php?mode=distro';
$modversion['sub'][4]['name'] = "- Build Snapshot";
$modversion['sub'][4]['url']  = 'build.php?mode=snapshot';
$modversion['sub'][5]['name'] = "- Backup CVS Data";
$modversion['sub'][5]['url']  = 'backup.php';
$modversion['sub'][6]['name'] = "- Manage Projects";
$modversion['sub'][6]['url']  = 'projman.php';

}
$modversion['sub'][7]['name'] = "- Help";
$modversion['sub'][7]['url']  = 'help.php';
?>