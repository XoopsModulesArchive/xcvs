<?
// CVS by Lonny Luberts
// lonny@pqcomp.com
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com

require("global/_top.php");
if ($op == "cleanup"){
	if (unlink("temp/cvsbackup.sql")){
	      	print ("Deleted cvsbackup.sql.<br>");
      	}else{
	      	print ("Could not delete cvsbackup.sql.<br>");
      	}
  	echo "<br><a href='index.php'><font size='4'><b>Continue...</b></font></a><br><br>";
}else{
	exec("mysqldump -h ".$mysql_server." -u ".$mysql_uname." -p".$mysql_password." ".$mysql_database." ".XOOPS_DB_PREFIX."_cvs_history ".XOOPS_DB_PREFIX."_cvs_documents ".XOOPS_DB_PREFIX."_cvs_phases ".XOOPS_DB_PREFIX."_cvs_prev_versions ".XOOPS_DB_PREFIX."_cvs_projects ".XOOPS_DB_PREFIX."_cvs_temp 2>&1", $output);
	$output = implode($output, "\n");
	$fd = fopen ("temp/cvsbackup.sql", "wb");
	$out = fwrite ($fd, $output);
	fclose ($fd);
	echo "<a href='temp/cvsbackup.sql'>Click here to download the new backup file.</a><br>";
	echo "<br><a href='backup.php?op=cleanup'><font size='4'><b>Continue - after your download finishes, it is important to click here to clean up your temp directory!</b></font></a><br><br>";
	print ("<i>Backup is not yet completed, you can return to the main cvs page here: <a href='index.php'>Main CVS Page</a>.</i><br>");
}
require("global/_bottom.php");
?>