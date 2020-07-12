<?php
// CVS by Lonny Luberts
// lonny@pqcomp.com
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com

//multiple project ready
require("global/_top.php");
if ($docname == ""){
$sql = "select document_version, document_desc, document_cat, document_uploader, document_name, document_location from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
$results = mysql_query($sql, $link);
$doc = mysql_fetch_assoc($results);
   print("<h1>".$dcm_name."</h1>Please fill out the form below to submit the information to the system.<br>");
   print("<br><form action='edit.php' enctype='multipart/form-data' method='POST' style='margin-top: 0px; margin-bottom: 0px;'>");
   print("<table width='70%' cellspacing='0' cellpadding='1' border='1'><tr>");
   print("<input type='hidden' name='id' value='".$id."'>");
   print("<td width='30%'><b>Document file name:</b> the actual name of the file (filename.txt).</td>");
   print("<td width='70%'><input type='text' size='40' name='docname' value='".$doc[document_name]."'></td></tr>");
   print("<tr><td width='30%'><b>Document location:</b> the folders that the document is stored in.  Subdirectories seperated by commas (dir1,dir2,dir3,etc....).  Leave Blank for documents in the root folder.</td>");
   print("<td width='70%'><input type='text' size='60' name='doclocation' value='".$doc[document_location]."'></td></tr>");
   print("<tr><td width='30%'><b>Document Version:</b> version of this document (not the project version)</td>");
   print("<td width='70%'><input type='text' size='10' name='docversion' value ='".$doc[document_version]."'></td></tr>");
   print("<tr><td width='30%'><b>Document Phase:</b></td><td width='70%'><select name='docphase'>");
   $sql = "select * from ".XOOPS_DB_PREFIX."_cvs_phases";
   $phases = mysql_query($sql, $link);
   while ($p = mysql_fetch_object($phases)) {
	   if ($p->phase == $doc[document_cat]){
		  print ("<option selected value='".$p->phase."'>".$p->phase."</option>"); 
	   }else{
   	   	  print ("<option value='".$p->phase."'>".$p->phase."</option>");
	   }
   }
   print("</select></td></tr>");
   print("<tr><td width='30%'><b>Document Owner:</b></td><td width='70%'>".$doc[document_uploader]."</td></tr>");
   print("<tr><td width='30%' valign='top'><b>Document Description:</b></td>");
   print ("<td width='70%'><textarea name='docdesc' cols='56' rows='10'>".$doc[document_desc]."</textarea></td></tr>");
   print("</table><br><input type='submit' value='Update Info'></form>");
}else{
	$sql = "update ".XOOPS_DB_PREFIX."_cvs_documents set document_version='$docversion', document_cat='$docphase', document_desc='$docdesc', document_name='$docname', document_location='$doclocation' where document_id='$id'";
	  if (mysql_query($sql, $link)){
		  print ("Record Successfully updated!<br>");
	  }else{
		  print ("Error updating record!<br>");
	  }
	  print ("<i>The edit has been saved to the database, you can return to the main cvs page here: <a href='index.php'>Main CVS Page</a>.</i><br>");
}
require("global/_bottom.php");
?>