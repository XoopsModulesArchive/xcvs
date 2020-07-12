<?php
include("../../mainfile.php");
include(XOOPS_ROOT_PATH."/header.php");
//$xoopsOption['show_rblock'] = 1;
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com
include ("global/global_funcs.php");
//lets get current project if set
$resultz = mysql_query("select curr_project from ".XOOPS_DB_PREFIX."_cvs_temp where user_id='$uname'", $link);
$getproj = mysql_fetch_assoc($resultz);
$curr_project = $getproj[curr_project];
if ($curr_project == "") $curr_project = 0;
if ($curr_project == 0){
	$sql = "INSERT INTO ".XOOPS_DB_PREFIX."_cvs_temp (curr_project, user_id) VALUES ('$curr_project', '$uname')";
	mysql_query($sql, $link);
}
$resultx = mysql_query("select project_name from ".XOOPS_DB_PREFIX."_cvs_projects where project_id='$curr_project'", $link);
$getproj = mysql_fetch_assoc($resultx);
$curr_project_name = $getproj[project_name];
if ($curr_project_name == "") $curr_project_name = "None";
print("<b>Current Project: ".$curr_project_name."</b><br>");
?>