<?php
// CVS by Lonny Luberts
// lonny@pqcomp.com
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com

// todo:
// finish backup and restore
// add cvs block for most recent updated
	//add admin functions for block
// add cvs block for most acive developers ????

require("global/_top.php");

function getend($str) {
   $strbits = explode(".", $str);
   $fext = $strbits[count($strbits) - 1];
   return $fext;
}
?>
<html>
<head>
   <title><?php print($dcm_name); ?></title>
</head>
<script language="javascript">
function downloadToView(docid) {
   newWindow = window.open("",'','scrollbars=yes, toolbars=no, width=100, height=50');
   newWindow.self.location = "op.php?type=view&id=" + docid;
}

function downloadToEdit(docid) {
   newWindow = window.open("",'','scrollbars=yes, toolbars=no, width=100, height=50');
   newWindow.self.location = "op.php?type=checkout&id=" + docid;
   self.location = "download.php";
}

function releaseLock(docid) {
   if (confirm("You are about to release the lock on this file, this means that any changes you make to you copy of the document cannot be re-integrated into the system.\n\nAre you sure? (Ok = Yes, Cancel = No)")) {
   	   newWindow = window.open("",'','scrollbars=yes, toolbars=no, width=410, height=500');
	   self.location = "op.php?type=release&id=" + docid;
   }
}

function checkBackIn(docid) {
   newWindow = window.open("",'','scrollbars=yes, toolbars=no, width=600, height=500');
   newWindow.self.location = "op.php?type=checkin&id=" + docid;
   self.location = "upload.php";
}

function viewHistory(docid) {
   newWindow = window.open("",'','scrollbars=yes, toolbars=no, width=410, height=500');
   newWindow.self.location = 'op.php?type=hist&id=' + docid;
   self.location = "index.php";
}

function deleteDocument(docid) {
   if (confirm("You are about to delete every version of this document that exists on the system.\n\nAre you sure? (Ok = Yes, Cancel = No)")) {
	   self.location = "op.php?type=delete&id=" + docid;
   }
}

function editDocument(docid) {
	   self.location = "edit.php?id=" + docid;
}
</script>
<body>
<h1><?php print($dcm_name); ?></h1>
<?php
print ("You are: " . $usr_obj->name . "<br>");
if ($curr_project <> 0){
	$sql = "SELECT * from ".XOOPS_DB_PREFIX."_cvs_projects WHERE project_id = '".$curr_project."'";
	$result = mysql_query($sql, $link);
	$proj = mysql_fetch_assoc($result);
	print ("You have ".proj_numberCheckedOut($curr_project)." document(s) checked out from this project.<br>");
	print ("There are a total of ".proj_totalCheckedOut($curr_project)." document(s) checked out from this project.<br>");
	print ("You have ".numberCheckedOut()." document(s) checked out all total.<br>");
	print ("There are a total of ".totalCheckedOut()." document(s) checked out all total.<br>");
	$displimit = $HTTP_GET_VARS[displimit];
	$pagenum = $HTTP_GET_VARS[pagenum];
	if ($displimit == "") $displimit = 0;
	if ($pagenum == "") $pagenum = 1;
	$filecount = totalProjectFiles($curr_project);
	print ("There are ".$filecount." files in this project.<br>");
	print ("<br>");
	if (isset($message)) {
	   print ("<font color=\"#FF0000\"><b>" . $message . "</b></font><br><br>");
	}
	if ($filecount > 30){
	print ("<p align='center'><b>Alphabetical listing</b></p><p align='center'>");
	print ("(<a href='index.php?sort=alpha&letter=a'>A</a> <a href='index.php?sort=alpha&letter=b'>B</a> <a href='index.php?sort=alpha&letter=c'>C</a> <a href='index.php?sort=alpha&letter=d'>D</a> <a href='index.php?sort=alpha&letter=e'>E</a> ");
	print ("<a href='index.php?sort=alpha&letter=f'>F</a> <a href='index.php?sort=alpha&letter=g'>G</a> <a href='index.php?sort=alpha&letter=h'>H</a> <a href='index.php?sort=alpha&letter=i'>I</a> <a href='index.php?sort=alpha&letter=j'>J</a> ");
	print ("<a href='index.php?sort=alpha&letter=k'>K</a> <a href='index.php?sort=alpha&letter=l'>L</a> <a href='index.php?sort=alpha&letter=m'>M</a> <a href='index.php?sort=alpha&letter=n'>N</a> <a href='index.php?sort=alpha&letter=o'>O</a> ");
	print ("<a href='index.php?sort=alpha&letter=p'>P</a> <a href='index.php?sort=alpha&letter=q'>Q</a> <a href='index.php?sort=alpha&letter=r'>R</a> <a href='index.php?sort=alpha&letter=s'>S</a> <a href='index.php?sort=alpha&letter=t'>T</a> ");
	print ("<a href='index.php?sort=alpha&letter=u'>U</a> <a href='index.php?sort=alpha&letter=v'>V</a> <a href='index.php?sort=alpha&letter=w'>W</a> <a href='index.php?sort=alpha&letter=x'>X</a> <a href='index.php?sort=alpha&letter=y'>Y</a> ");
	print ("<a href='index.php?sort=alpha&letter=z'>Z</a>)</p><br>");
	}
	$docs = mysql_query($sql, $link);
	if ($sort != "alpha"){
		echo "<big><b><center>Page ".$pagenum."</center></b></big><br><br>";
		if ($filecount > 15){
		  echo "Pages ( ";
		  $i=0;
		  $j=1;
		  for ($i;$i < $filecount;$i+=15){
		      $hinum = ($i + 15);
		      if ($hinum > $filecount) $hinum = $filecount;
		      echo "<a href='index.php?displimit=".$i."&pagenum=".$j."'> ".$j." </a>";
		      $j += 1;    
		  }
		  echo " )<br>";
		}
		$sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents WHERE document_project_id = '".$curr_project."' ORDER BY document_name ASC LIMIT $displimit,15";
	}else{
		$sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents WHERE document_name LIKE '".$letter."%' AND document_project_id = '".$curr_project."' ORDER BY document_name ASC";
		echo "<center><a href='index.php'><font size='4'><b>Return to normal listing...</b></font></a></center><br>";
	}
	if (mysql_num_rows($docs) == 0) {
	   ?>
	   <table width="100%" cellspacing="0" cellpadding="0" border="1"><tr><td width="100%" align="middle">
	   <i>There are no documents available!</i>
	   </td></tr></table>
	   <?php
	} else {
	   if ($sort != "alpha"){
	   		$sql = "SELECT document_id, document_name, document_version, document_timestamp, document_uploader, document_size, document_cat, document_desc, document_out, document_out_with, document_file_name, document_location FROM ".XOOPS_DB_PREFIX."_cvs_documents WHERE document_project_id = '".$curr_project."' ORDER BY document_name ASC LIMIT $displimit,15";
	   }else{
		  	$sql = "SELECT document_id, document_name, document_version, document_timestamp, document_uploader, document_size, document_cat, document_desc, document_out, document_out_with, document_file_name, document_location FROM ".XOOPS_DB_PREFIX."_cvs_documents WHERE document_name LIKE '".$letter."%' AND document_project_id = '".$curr_project."' ORDER BY document_name ASC"; 
	   }
	   $documents = mysql_query($sql, $link);
	   while ($row = mysql_fetch_object($documents)) {
	   	  $d = date("j/m/Y \@ H:i:s", $row->document_timestamp);
	      ?>
	      <table width="100%" cellpadding="0" cellspacing="0" border="1">
	         <tr>
	            <?php
	            if (checkedOutByUser($row->document_id)) {
	               ?>
	               <td width="100%" colspan="3" bgcolor="#FF8080"><b><?php print($row->document_name . " v" . $row->document_version); ?></b></td>
	               <?php
	            } else {
	               ?>
	               <td width="100%" colspan="3" bgcolor="#EFEFEF"><b><?php print($row->document_name . " v" . $row->document_version); ?></b></td>
	               <?php
	            }
	            ?>
	         </tr>
	         <tr>
	         <?
	            if (getend($row->document_file_name) == "php"){
		            print ("<td width='10%' rowspan='11' colspan='1' align='middle' valign='middle'><img src='images/php.gif'><br><center><font size='5'></font></center></td>");
	            }elseif (getend($row->document_file_name) == "txt"){
		            print ("<td width='10%' rowspan='11' colspan='1' align='middle' valign='middle'><img src='images/notepad.gif'><br><center><font size='5'>".getend($row->document_file_name)."</font></center></td>");
	            }elseif (getend($row->document_file_name) == "htm" or getend($row->document_file_name) == "html"){
		            print ("<td width='10%' rowspan='11' colspan='1' align='middle' valign='middle'><img src='images/html.gif'><br><center><font size='5'>".getend($row->document_file_name)."</font></center></td>");
	            }elseif (getend($row->document_file_name) == "gif" or getend($row->document_file_name) == "jpg" or getend($row->document_file_name) == "jpeg" or getend($row->document_file_name) == "png" or getend($row->document_file_name) == "tif" or getend($row->document_file_name) == "tiff" or getend($row->document_file_name) == "bmp" or getend($row->document_file_name) == "pcx"){
		            print ("<td width='10%' rowspan='11' colspan='1' align='middle' valign='middle'><img src='images/pic.gif'><br><center><font size='5'>".getend($row->document_file_name)."</font></center></td>");
	            }elseif (getend($row->document_file_name) == "zip" or getend($row->document_file_name) == "gz"){
		            print ("<td width='10%' rowspan='11' colspan='1' align='middle' valign='middle'><img src='images/zip.gif'><br><center><font size='5'>".getend($row->document_file_name)."</font></center></td>");
	            }elseif (getend($row->document_file_name) == "sql"){
		            print ("<td width='10%' rowspan='11' colspan='1' align='middle' valign='middle'><img src='images/mysql.gif'><br><center><font size='5'>".getend($row->document_file_name)."</font></center></td>");
	            }else{
	            	print ("<td width='10%' rowspan='11' colspan='1' align='middle' valign='middle'><img src='images/file.gif'><br><center><font size='5'>".getend($row->document_file_name)."</font></center></td>");
	        	}
	         ?>
	            <td width="20%" rowspan="1" colspan="1"><b>File Name:</b></td>
	            <td width="70%" colspan="1" rowspan="1"><?php print($row->document_file_name); ?></td>
	         </tr>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1"><b>File Location:</b></td>
	            <td width="70%" colspan="1" rowspan="1"><?php print($row->document_location); ?></td>
	         </tr>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1"><b>Author:</b></td>
	            <td width="70%" colspan="1" rowspan="1"><?php print($row->document_uploader); ?></td>
	         </tr>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1"><b>File Version:</b></td>
	            <td width="70%" colspan="1" rowspan="1"><?php print($row->document_version); ?></td>
	         </tr>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1"><b>Last Updated:</b></td>
	            <td width="70%" colspan="1" rowspan="1"><?php print($d); ?></td>
	         </tr>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1"><b>Size (bytes):</b></td>
	            <td width="70%" colspan="1" rowspan="1"><?php print($row->document_size); ?></td>
	         </tr>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1"><b>Development Phase:</b></td>
	            <td width="70%" colspan="1" rowspan="1"><?php print($row->document_cat); ?></td>
	         </tr>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1" align="top"><b>Description:</b></td>
	            <td width="70%" colspan="1" rowspan="1"><?php print($row->document_desc); ?></td>
	         </tr>
	         <?php
	         if ($row->document_out == 0) {
	         	 ?>
	         	 <tr>
	                <td width="20%" rowspan="1" colspan="1" align="top"><b>Document Out:</b></td>
	                <td width="70%" colspan="1" rowspan="1"><b><font color="#008000">No</font></b></td>
	             </tr>
	         	 <?php
	         } else {
	         	 ?>
	         	 <tr>
	                <td width="20%" rowspan="1" colspan="1" align="top"><b>Document Out:</b></td>
	                <td width="70%" colspan="1" rowspan="1"><b><font color="#FF0000">Yes</font></b> with <?php print(getFullName($row->document_out_with) . " &lt;<a href=\"mailto:" . getEmailAddr($row->document_out_with) . "\">" . getEmailAddr($row->document_out_with) . "</a>&gt;"); ?></td>
	             </tr>
	         	 <?php
	         }
	         ?>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1" align="top"><b>Previous Versions:</b></td>
	            <td width="70%" colspan="1" rowspan="1">
	            <?php
	            $sql2 = "select document_version from ".XOOPS_DB_PREFIX."_cvs_prev_versions where document_id=$row->document_id";
	            $prev_vers = mysql_query($sql2, $link);
	            if (mysql_num_rows($prev_vers) == 0) {
	            	print ("<i>None</i>");
	            } else {
	               while ($pv = mysql_fetch_object($prev_vers)) {
	         	      print ("[<a href=\"op.php?type=getprevversion&id=" . $row->document_id . "&ver=" . $pv->document_version . "\">" . $pv->document_version . "</a>] ");
	               }
	            }
	            ?>
	            </td>
	         </tr>
	         <tr>
	            <td width="20%" rowspan="1" colspan="1" align="top"><b>Edited by:</b></td>
	            <td width="70%" colspan="1" rowspan="1">
	            <?php
	            $sql3 = "select * from ".XOOPS_DB_PREFIX."_cvs_history where document_id=$row->document_id and hist_action='UL_CHECKIN' group by hist_person";
	            $editors = mysql_query($sql3, $link);
	            if (mysql_num_rows($editors) == 0) {
	            	print ("<i>Nobody</i>");
	            } else {
	               while ($ed = mysql_fetch_object($editors)) {
	         	      print ("[" . getFullName($ed->hist_person) . "] ");
	               }
	            }
	            ?>
	            </td>
	         </tr>
	         <tr>
	            <td width="1000%" colspan="3" align="right">
	            <input type="button" value="Download to View" onClick="javascript:downloadToView('<?php print($row->document_id); ?>');">
	            <?php
	            if ($row->document_out == 0) {
	               ?>
	               <input type="button" value="Download and Check Out" onClick="javascript:downloadToEdit('<?php print($row->document_id); ?>');">
	               <?php
	            } else {
	               ?>
	               <input type="button" value="Check In" onClick="javascript:checkBackIn('<?php print($row->document_id); ?>');">
	               <input type="button" value="Release Lock" onClick="javascript:releaseLock('<?php print($row->document_id); ?>');">
	               <?php
	            }
	            ?>
	            <input type="button" value="View History" onClick="javascript:viewHistory('<?php print($row->document_id); ?>');">
	            <?
	            	if ($admin_user == true){
			            ?>
			            <input type="button" value="Delete this Document" onClick="javascript:deleteDocument('<?php print($row->document_id); ?>');">
			            <input type="button" value="Edit Info" onClick="javascript:editDocument('<?php print($row->document_id); ?>');">
			            <?
	        		}
	            ?>
	            </td>
	         </tr>
	      </table>
	      <?php
	   }
	}
	if ($sort != "alpha"){
		if ($filecount > 15){
		  echo "Pages ( ";
		  $i=0;
		  $j=1;
		  for ($i;$i < $filecount;$i+=15){
		      $hinum = ($i + 15);
		      if ($hinum > $filecount) $hinum = $filecount;
		      echo "<a href='index.php?displimit=".$i."&pagenum=".$j."'> ".$j." </a>";
		      $j += 1;    
		  }
		  echo " )<br><br>";
		}
	}
}else{
	//select project
	//make temp table to store project info and pull curr_project at global funcs
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
		print("<br><a href='set_curr_project.php?project=".$projs[project_id]."'><font size='4'><b>".$projs[project_name]."</b></font></a><br>");
		print($projs[project_short_desc]."<br>");
		print($projs[project_desc]."<br>");
		print($projs[project_dist_name]."<br><br><br>");
	}
}
print("<br><br><font size='1'>");
print("<i>Current Version System ".$dcm_version.".  By Lonny Luberts<br></i>");
print("<i>Based on DCM System Version 2.0.  By Richard James Kendall.</i>");
print("</font></body></html>");
require("global/_bottom.php");
?>