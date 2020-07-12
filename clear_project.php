<?php
// CVS by Lonny Luberts
// lonny@pqcomp.com
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com
require("global/_top.php");
if ($project <> ""){
	//delete all documents attached to this project
	//confirm before remove.
	if ($confirm == "yes"){
		print("This process cannot be undone!");
		print("<br><a href='clear_project.php?confim=positive&project=$project'><font size='4'><b>CONTINUE</b></font></a><br><br>");
		print("<br><a href='clear_project.php'><font size='4'><b>CANCEL</b></font></a><br><br>");
	}elseif ($confirm == "positive"){
		$sql = "DELETE from ".XOOPS_DB_PREFIX."_cvs_documents WHERE document_project_id = '$project'";
		if(mysql_query($sql, $link)){
			print("Successfully cleared project!<br>");
		}else{
			print("Error clearing project!<br>");
		}
	}else{
		print("Are you sure you want to clear ".$name."?");
		print("<br><a href='clear_project.php?confim=yes&project=$project'><font size='4'><b>YES</b></font></a><br><br>");
		print("<br><a href='clear_project.php'><font size='4'><b>NO</b></font></a><br><br>");
	}
	print("<br><a href='index.php'><font size='4'><b>Clear Project not fully functioning! Main CVS Page...</b></font></a><br><br>");
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
		print("<br><a href='edit_project.php?project=".$projs[project_id]."&name=".$projs[project_name]."'><font size='4'><b>".$projs[project_name]."</b></font></a><br>");
		print($projs[project_short_desc]."<br>");
		print($projs[project_desc]."<br>");
		print($projs[project_dist_name]."<br><br><br>");
	}
}
require("global/_bottom.php");
?>