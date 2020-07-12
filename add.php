<?php

// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com

//multiple project ready
require("global/_top.php");
print("<html><head><title>".$dcm_name."</title></head><body>");
if (isset($docname)) {
   	if ($docname == "") {
      	print("<h1>".$dcm_name."</h1><font color='#FF0000'><b>Error - Required Information Missing</b></font>");
	   	print("<br><br><i>You omitted the name of the document you submitted, please go <a href='javascript:history.go(-1);'>back</a> and enter this information.</i>");
   	} else {
		if ($docversion == "") {
        	print("<h1>".$dcm_name."</h1><font color='#FF0000'><b>Error - Required Information Missing</b></font>");
        	print("<br><br><i>You omitted the version number of the document you submitted, please go <a href='javascript:history.go(-1);'>back</a> and enter this information.</i>");
      	} else {
         	if ($docdesc == "") {
            	print("<h1>".$dcm_name."</h1><font color='#FF0000'><b>Error - Required Information Missing</b></font>");
	         	print("<br><br><i>You omitted the description of the document you submitted, please go <a href='javascript:history.go(-1);'>back</a> and enter this information.</i>");
         	} else {
            	if (!is_uploaded_file($_FILES["docdata"]["tmp_name"])) {
	       			print("<h1>".$dcm_name."</h1><font color='#FF0000'><b>Error - Required Information Missing</b></font>");
	            	print("<br><br><i>You omitted the data file of the document you submitted, please go <a href='javascript:history.go(-1);'>back</a> and enter this information.</i>");
            	} else {
	            	$f = $_FILES['docdata']['name'];
	               	$fp = fopen($_FILES['docdata']['tmp_name'], "rb");
			       	$data = fread($fp, filesize($_FILES['docdata']['tmp_name']));
			       	fclose($fp);
			       	$isimage = check_isimage($f);
			       	if ($isimage){
				       	$data = addslashes($data);
			       	}else{
				       	$data = base64_encode($data);
			       	}
			       	$docup = $usr_obj->name;
			       	$doctime = time();
			       	$docsize = filesize($_FILES['docdata']['tmp_name']);
			       	$sql = "INSERT INTO ".XOOPS_DB_PREFIX."_cvs_documents (document_name, document_version, document_timestamp, document_uploader, document_size, document_cat, document_desc, document_data, document_out, document_out_with, document_file_name, document_location, document_project_id) VALUES ('$docname', '$docversion', '$doctime', '$docup', '$docsize', '$docphase', '$docdesc', '$data', 0, '', '$f', '$doclocation', '$curr_project')";
			       	$add = mysql_query($sql, $link);
		       		if ($add) {
						print("<h1>".$dcm_name."</h1><font color='#008000'><b>Success</b></font><br><br>");
	                  	print("<b>".$docname." has been added to the database!</b><br>");
						print("<i>The file has been added to the database, you can return to the main cvs page here: <a href='index.php'>Main CVS Page</a>.</i>");
	                  	echo "<br><a href='add.php'><font size='4'><b>Upload another file into the system...</b></font></a><br><br>";
               		} else {
                  		print("<h1>".$dcm_name."</h1><font color='#FF0000'><b>Error - Database error</b></font>");
                  		print("<br><br><i>Failed, the document was not added to the database.</i>");
               		}
            	}
         	}
      	}
   	}
} else {
   if ($curr_project > 0){
	   print("<h1>".$dcm_name."</h1>Please fill out the form below to submit the document to the system.<br><br>");
	   print("<form action='add.php' enctype='multipart/form-data' method='POST' style='margin-top: 0px; margin-bottom: 0px;'>");
	   print("<table width='70%' cellspacing='0' cellpadding='1' border='1'>");
	   print("<tr><td width='30%'><b>Document file name:</b> the actual name of the file (filename.txt).</td>");
	   print("<td width='70%'><input type='text' size='40' name='docname'></td></tr>");
	   print("<tr><td width='30%'><b>Document location:</b> the folders that the document is stored in.  Subdirectories seperated by commas (dir1,dir2,dir3,etc....).  Leave Blank for documents in the root folder.</td>");
	   print("<td width='70%'><input type='text' size='60' name='doclocation'></td></tr>");
	   print("<tr><td width='30%'><b>Document Version:</b> version of this document (not the project version)</td><td width='70%'><input type='text' size='10' name='docversion'></td></tr>");
	   print("<tr><td width='30%'><b>Document Phase:</b></td><td width='70%'><select name='docphase'>");
	   $sql = "select * from ".XOOPS_DB_PREFIX."_cvs_phases";
	   $phases = mysql_query($sql, $link);
	   while ($p = mysql_fetch_object($phases)) {
	   	   print ("<option value=\"" . $p->phase . "\">" . $p->phase . "</option>");
	   }
	   print("</select></td></tr>");
	   print("<tr><td width='30%'><b>Document Owner:</b></td><td width='70%'>".$usr_obj->name."</td></tr>");
	   print("<tr><td width='30%' valign='top'><b>Document Description:</b></td><td width='70%'><textarea name='docdesc' cols='56' rows='10'></textarea></td></tr>");
	   print("<tr><td width='30%'><b>File:</b></td><td width='70%'><input type='file' name='docdata' size='50'></td>");
	   print("</tr></table><br>");
	   print("<input type='hidden' size='10' name='curr_project' value='".$curr_project."'>");
	   print("<input type='submit' value='Upload File'></form>");
   }else{
	   print("No project selected!<br>");
	   print("<br><a href='set_curr_project.php'><font size='4'><b>Select a Project</b></font></a><br><br>");
   }
}
print("</body></html>");
require("global/_bottom.php");
?>