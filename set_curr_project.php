<?php
// CVS by Lonny Luberts
// lonny@pqcomp.com
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com
require("global/_top.php");
if ($project <> ""){
	$sql = "update ".XOOPS_DB_PREFIX."_cvs_temp set curr_project='".$project."' where user_id='$uname'";
	if (mysql_query($sql, $link)){
		print("Project Successfully selected!<br>");
		print("<b>Switched Current Project to: ".$name.".</b><br>");
	}else{
		print("Error selecting project!<br>");
	}
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
		print("<br><a href='set_curr_project.php?project=".$projs[project_id]."&name=".$projs[project_name]."'><font size='4'><b>".$projs[project_name]."</b></font></a><br>");
		print($projs[project_short_desc]."<br>");
		print($projs[project_desc]."<br>");
		print($projs[project_dist_name]."<br>");
		print("There are ".totalProjectFiles($projs[project_id])." files in this project.<br>");
		print("There are ".proj_totalCheckedOut($projs[project_id])." documents checked out from this project.<br><br><br>");
	}
}
print("<br><a href='index.php'><font size='4'><b>Main CVS Page...</b></font></a><br><br>");
require("global/_bottom.php");
?>