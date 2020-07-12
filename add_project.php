<?php
// CVS by Lonny Luberts
// lonny@pqcomp.com
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com
require("global/_top.php");
if ($project_name == "" or $project_short_desc == "" or $project_desc == "" or $project_dist_name == ""){
	if ($project_name <> "" or  $project_short_desc <> "" or $project_desc <> "" or $project_dist_name <> ""){
		if ($project_name == "") print("Missing Project name!<br>");
		if ($project_short_desc == "") print("Missing Project short description!<br>");
		if ($project_desc == "") print("Missing Project description!<br>");
		if ($project_dist_name == "") print("Missing Project distro name!<br>");
	}
	print("<form method='POST' name='add_project.php'>");
	print("<p>Project Name: <input type='text' name='project_name' size='40' value='".$project_name."'></p>");
	print("<p>Project Short Description: <input type='text' name='project_short_desc' size='60' value='".$project_short_desc."'></p>");
	print("<p>Project Description: <textarea rows='10' name='project_desc' cols='60' value='".$project_desc."'></textarea></p>");
	print("<p>Distro File Name (no extension): <input type='text' name='project_dist_name' size='20' value='".$project_dist_name."'></p>");
	print("<p><input type='submit' value='Submit' name='B1'><input type='reset' value='Reset' name='B2'></p></form>");
}else{
	$sql = "INSERT INTO ".XOOPS_DB_PREFIX."_cvs_projects (project_name, project_short_desc, project_desc, project_dist_name) VALUES ('$project_name', '$project_short_desc', '$project_desc', '$project_dist_name')";
	if (mysql_query($sql, $link)){
		print("Project Successfully created!<br>");
		$sql = "SELECT project_id FROM ".XOOPS_DB_PREFIX."_cvs_projects WHERE project_name = '$project_name' AND project_short_desc = '$project_short_desc' AND project_dist_name = '$project_dist_name'";
		$result = mysql_query($sql, $link);
		$proj = mysql_fetch_assoc($result);
		$project = $proj[project_id];
		$sql = "update ".XOOPS_DB_PREFIX."_cvs_temp set curr_project='".$project."' where user_id='$uname'";
		if (mysql_query($sql, $link)){
			print("Project Successfully selected!<br>");
			print("<b>Switched Current Project to: ".$project_name.".</b><br>");
		}else{
			$sql = "INSERT INTO ".XOOPS_DB_PREFIX."_cvs_temp (curr_project, user_id) VALUES ('$project', '$uname')";
			if (mysql_query($sql, $link)){
				print("Project Successfully selected!<br>");
				print("<b>Switched Current Project to: ".$project_name.".</b><br>");
			}else{
				print("Error selecting project!<br>");
			}
	}
	}else{
		print("Error creating project!<br>");
	}
	print("<br><a href='add_project.php'><font size='4'><b>Create Another New Project...</b></font></a><br><br>");
	print("<br><a href='index.php'><font size='4'><b>Main CVS Page...</b></font></a><br><br>");
}
require("global/_bottom.php");
?>