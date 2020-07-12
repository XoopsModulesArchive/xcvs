<?php
// CVS by Lonny Luberts
// lonny@pqcomp.com
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com
require("global/_top.php");
if ($mode == "edit"){
	$sql = "SELECT * from ".XOOPS_DB_PREFIX."_cvs_projects WHERE project_id = '".$project."'";
	$result = mysql_query($sql, $link);
	$projs = mysql_fetch_assoc($result);
	print("<form method='POST' name='edit_project.php'>");
	print("<p>Project Name: <input type='text' name='project_name' size='40' value='".$projs[project_name]."'></p>");
	print("<p>Project Short Description: <input type='text' name='project_short_desc' size='60' value='".$projs[project_short_desc]."'></p>");
	print("<p>Project Description: <textarea rows='10' name='project_desc' cols='60'>".$projs[project_desc]."</textarea></p>");
	print("<p>Distro File Name (no extension): <input type='text' name='project_dist_name' size='20' value='".$projs[project_dist_name]."'></p>");
	print("<input type='hidden' name='mode' value='save'>");
	print("<input type='hidden' name='project' value='".$project."'>");
	print("<p><input type='submit' value='Submit' name='B1'><input type='reset' value='Reset' name='B2'></p></form>");
}elseif ($mode == "save"){
	$sql = "UPDATE ".XOOPS_DB_PREFIX."_cvs_projects SET project_name = '$project_name', project_short_desc = '$project_short_desc', project_desc = '$project_desc', project_dist_name = '$project_dist_name' WHERE project_id = '$project'";
	if (mysql_query($sql, $link)){
		print("Project Successfully updated!<br>");
	}else{
		print("Error updating project!<br>");
	}
	print("<br><a href='edit_project.php'><font size='4'><b>Edit Another Project...</b></font></a><br><br>");
	print("<br><a href='index.php'><font size='4'><b>Main CVS Page...</b></font></a><br><br>");
}else{
	$sql = "SELECT * from ".XOOPS_DB_PREFIX."_cvs_projects";
	$result = mysql_query($sql, $link);
	$projectcount = mysql_num_rows($result);
	if ($projectcount < 1){
		print("There are no projects!<br>");
		if ($admin_user == true){
			print("<br><a href='add_project.php'><font size='4'><b>Create New Project...</b></font></a><br><br>");
		}
	}
	for ($i=0;$i < $projectcount;$i++){
		$projs = mysql_fetch_assoc($result);
		//will need to do multiple page control
		//list projects with descriptions
		//project_name, project_short_desc, project_desc, project_dist_name
		print("<br><a href='edit_project.php?project=".$projs[project_id]."&mode=edit'><font size='4'><b>".$projs[project_name]."</b></font></a><br>");
		print($projs[project_short_desc]."<br>");
		print($projs[project_desc]."<br>");
		print($projs[project_dist_name]."<br><br><br>");
	}
}
require("global/_bottom.php");
?>