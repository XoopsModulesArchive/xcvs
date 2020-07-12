<?php

// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com

set_time_limit(0);
require("global/_cleantop.php");
switch ($type) {
   case "view":
   	  addHistory($id, "DL_VIEW");
   	  $sql = "select * from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
      $file = mysql_query($sql, $link);
      $row = mysql_fetch_object($file);
   	  header("Content-disposition: attachment; filename=" . $row->document_file_name);
   	  $data = $row->document_data;
	  $data = base64_decode($data);
	  print ($data);
      break;
   case "checkout":
   	  addHistory($id, "DL_CHECKOUT");
   	  $sql = "update ".XOOPS_DB_PREFIX."_cvs_documents set document_out='1', document_out_with='$uname' where document_id=$id";
      $ok = mysql_query($sql, $link);
      if ($ok) {
         $sql = "select * from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
      	 $file = mysql_query($sql, $link);
      	 $row = mysql_fetch_object($file);
   	  	 header("Content-disposition: attachment; filename=" . $row->document_file_name);
   	  	 $data = $row->document_data;
	     $data = base64_decode($data);
   	  	 print ($data);
      }
   	  break;
   case "release":
   	  addHistory($id, "UL_RELEASE");
   	  $sql = "select document_out_with from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
      $results = mysql_query($sql, $link);
      $person = mysql_fetch_object($results);
      if ($person->document_out_with == $uname) {
   	     $sql = "update ".XOOPS_DB_PREFIX."_cvs_documents set document_out='0', document_out_with='' where document_id=$id";
         $ok = mysql_query($sql, $link);
         if ($ok) {
      	    header("Location: index.php");
         }
      } else {
      	 header("Location: index.php?message=Lock Release Failed - You do not currently hold the lock on this file");
      }
   	  break;
   case "checkin":
   	  if (!isset($newdocdesc)) {
   	     $sql = "select document_desc,document_out_with from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
         $results = mysql_query($sql, $link);
         $person = mysql_fetch_object($results);
         if ($person->document_out_with == $uname) {
         	 ?>
         	 <html>
         	 <head>
         	    <title><?php print($dcm_name); ?></title>
         	 </head>
         	 <body>
         	 <h1><?php print($dcm_name); ?></h1>
         	 Please fill out the form below to check this document back in.
         	 <br><br>
         	 <form action="op.php" enctype="multipart/form-data" method="POST" style="margin-top: 0px; margin-bottom: 0px;">
         	 <input type="hidden" name="type" value="checkin">
         	 <input type="hidden" name="id" value="<?php print($id); ?>">
         	 <table width="50%" cellspacing="0" cellpadding="1" border="1">
                <tr>
                   <td width="30%"><b>File:</b></td>
                   <td width="70%"><input type="file" name="newdocdata" size="50"></td>
                </tr>
                <tr>
                   <td width="30%"><b>Version Change:</b></td>
                   <td width="70%">
                      <select name="docverchange">
                         <option value="minor">Increment Minor Version Number (1.x)</option>
                         <option value="major">Increment Major Version Nunmber (x.0)</option>
                      </select>
                   </td>
                </tr>
                <tr>
                <?php
                   print("<td width='30%' valign='top'><b>Document Description:</b></td>");
                   print("<td width='70%'><textarea name='newdocdesc' cols='56' rows='10'>".$person->document_desc."</textarea></td>");
                ?>
                </tr>
             </table>
             <br>
             <input type="submit" value="Check File In">
             </form>
         	 </body>
         	 </html>
         	 <?php
         } else {
            header("Location: index.php?message=You cannot check in a document you do not hold the lock for");
         }
      } else {
      	 if ($newdocdesc == "") {
      	 	?>
            <h1><?php print($dcm_name); ?></h1>
            <font color="#FF0000"><b>Error - Required Information Missing</b></font>
            <br><br>
            <i>You omitted the description of the document you submitted, please go <a href="javascript:history.go(-1);">back</a> and enter this information.</i>
            <?php  
      	 } else {
      	 	 if (!is_uploaded_file($_FILES["newdocdata"]["tmp_name"])) {
      	 	 	?>
      	 	    <h1><?php print($dcm_name); ?></h1>
      	 	    <font color="#FF0000"><b>Error - Required Information Missing</b></font>
                <br><br>
                <i>You omitted the data file of the document you submitted, please go <a href="javascript:history.go(-1);">back</a> and enter this information.</i>
      	 	    <?php
      	 	 } else {
      	 	 	addHistory($id, "UL_CHECKIN");
      	 	 	$sql = "select document_version, document_desc, document_data, document_size, document_file_name from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
      	 	 	$results = mysql_query($sql, $link);
      	 	 	$doc = mysql_fetch_object($results);
      	 	 	$sql = "insert into ".XOOPS_DB_PREFIX."_cvs_prev_versions (document_id, document_version, document_desc, document_data, document_size, document_file_name) values ($id, '$doc->document_version', '$doc->document_desc', '$doc->document_data', '$doc->document_size', '$doc->document_file_name')";
      	 	 	$ok = mysql_query($sql, $link);
      	 	 	$f = $_FILES['newdocdata']['name'];
	       	    //move_uploaded_file($_FILES['newdocdata']['tmp_name'], "/data1/seg/uploaded_work/" . $_FILES['newdocdata']['name']);
                $fp = fopen($_FILES['newdocdata']['tmp_name'], "rb");
	            $data = fread($fp, filesize($_FILES['newdocdata']['tmp_name']));
	            fclose($fp);
	            $isimage = check_isimage($f);
	  			if (!$isimage) $data = base64_encode($data);
	            if ($docverchange == "minor") {
				   $newversion = $doc->document_version + 0.1;
				} else {
				   $verbits = explode(".", $doc->document_version);
				   $newversion = $verbits[0];
				   $newversion++;
				}
				$doctime = time();
				$docsize = filesize($_FILES['newdocdata']['tmp_name']);
				$sql = "update ".XOOPS_DB_PREFIX."_cvs_documents set document_version='$newversion', document_timestamp='$doctime', document_size='$docsize', document_desc='$newdocdesc', document_data='$data', document_out=0, document_out_with='', document_file_name='$f' where document_id=$id";
				$ok2 = mysql_query($sql, $link);
				if ($ok && $ok2) {
				   $sql = "select document_name, document_version, document_uploader, document_cat from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
				   $docs2 = mysql_query($sql, $link);
                                   $doc2 = mysql_fetch_object($docs2);
	               				   ?>
                   <h1><?php print($dcm_name); ?></h1>
                   <font color="#008000"><b>Success</b></font>
                   <br><br>
                   <i>The document has been checked back in, you can close this window.</i>
                   <?php
				} else {
				   ?>
		  	       <h1><?php print($dcm_name); ?></h1>
	               <font color="#FF0000"><b>Error - Database error</b></font>
	               <br><br>
	               <i>Failed, the document was not checked back in.</i>
                  <?php
				}
      	 	 }
      	 }
      }
   	  break;
   case "hist":
   	  $sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
      $results = mysql_query($sql, $link);
      $doc = mysql_fetch_object($results);
   	  ?>
   	  <html>
   	  <head>
   	     <title>Document History</title>
   	  </head>
   	  <body>
   	  <h1><?php print($dcm_name); ?></h1>
   	  Here is the document history for: <b><?php print($doc->document_name); ?></b>
   	  <br><br>
   	  <?php
   	  $sql = "select * from ".XOOPS_DB_PREFIX."_cvs_history where document_id=$id order by hist_timestamp desc";
   	  $results = mysql_query($sql, $link);
   	  while ($hist = mysql_fetch_object($results)) {
   	  	 $d = date("j/m/Y \@ H:i:s", $hist->hist_timestamp);
   	  	 print("<b>" . $d . ":</b><br>");
   	  	 switch($hist->hist_action) {
   	  	 	case "DL_VIEW":
   	  	 	   print ("Downloaded for viewing by <i>" . getFullName($hist->hist_person) . "</i><br><br>");
   	  	 	   break;
   	  	 	case "DL_CHECKOUT":
   	  	 	   print ("Locked for editing by <i>" . getFullName($hist->hist_person) . "</i><br><br>");
   	  	 	   break;
   	  	 	case "UL_RELEASE":
   	  	 	   print ("Locked released (no check in) by <i>" . getFullName($hist->hist_person) . "</i><br><br>");
   	  	 	   break;
   	  	 	case "UL_CHECKIN":
   	  	 	   print ("Locked released (check in) by <i>" . getFullName($hist->hist_person) . "</i><br><br>");
   	  	 	   break;
   	  	 }
   	  }
   	  ?>
   	  </body>
   	  </html>
   	  <?php
   	  break;
   case "delete":
   	  if ($admin_user == true) {
   	  	 $sql = "delete from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id";
   	  	 mysql_query($sql, $link);
   	  	 $sql = "delete from ".XOOPS_DB_PREFIX."_cvs_prev_versions where document_id=$id";
   	  	 mysql_query($sql, $link);
   	  	 $sql = "delete from ".XOOPS_DB_PREFIX."_cvs_history where document_id=$id";
   	  	 mysql_query($sql, $link);
   	  	 header("Location: index.php");
      } else {
      	 header("Location: index.php?message=Deletion Failed - You are not permitted to delete files.");
      }
   	  break;
   case "getprevversion":
   	  $sql = "select * from ".XOOPS_DB_PREFIX."_cvs_prev_versions where document_id=$id and document_version='$ver'";
      $file = mysql_query($sql, $link);
      $row = mysql_fetch_object($file);
   	  header("Content-disposition: attachment; filename=" . $row->document_file_name);
   	  $data = $row->document_data;
   	  $isimage = check_isimage($row->document_file_name);
	  if (!$isimage) $data = base64_decode($data);
   	  print ($data);
   	  break;
   case "backup":
   	  break;
}
require("global/_cleanbottom.php");
?>