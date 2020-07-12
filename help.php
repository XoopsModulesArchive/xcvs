<?
// CVS by Lonny Luberts
// lonny@pqcomp.com
// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com

// todo:
// add subdirectory support
// add image support

require("global/_top.php");
?>
<dl>
 <dt><b>Uploading a new file:</b></dt>
 <blockquote>
  <dt>You will upload a file into the CVS system to make it available for editing and distribution.</dt>
  <dt><b>The Add Page:</b></dt>
  <blockquote>
   <dt><i>Document File Name:</i> This is the name of the file, with no path information (filename.txt)</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Location:</i>&nbsp; This is the folder location within the project, multiple subfolders are stored in order separated by commas (folder1,folder2,folder3,etc....)</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Version:</i>&nbsp; This is the version of the document itself.&nbsp; This version is and will be different from your distribution version.</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Phase:</i>&nbsp; This is the phase of development that your document is in.&nbsp; Once it hits Implementation it will be included automatically when building a distro.</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Owner:</i>&nbsp; This is an auto value.&nbsp; It is the person who uploaded the file.</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Description:</i>&nbsp; This is a description of the file and what it does.&nbsp; This field is for information purposes only.&nbsp; It is also used to note changes and improvements to the document.</dt>
   <dt>&nbsp;</dt>
   <dt><i>File:</i> This is the location to upload the file from.</dt>
  </blockquote>
 </blockquote>
 <dt><b>Build a Distro:</b></dt>
 <blockquote>
  <dt>Building a distro complies all of the files that are at <u>Implementation</u> stage into a zip file and offers the zip file for download.</dt>
 </blockquote>
 <dt><b>Build a Snapshot:</b></dt>
 <blockquote>
  <dt>Building a snapshot compiles <u>all </u>of the files into a zip file and offers the zip file for download.</dt>
 </blockquote>
 <dt><b>Working with Files:</b></dt>
 <blockquote>
  <dt><i>Download to View:</i>&nbsp; This will allow a person to take a look at a file in it's current state.</dt>
  <dt>&nbsp;</dt>
  <dt><i>Download and Checkout: </i> This option is for someone to check the file out for editing.&nbsp; The file will not be available to anyone else for editing until it is checked back in.&nbsp; </dt>
  <dt>&nbsp;</dt>
  <dt><i>Check In:</i>&nbsp; This is for checking a file back in after having checked the file out and making changes.&nbsp; The file will then be available for others to edit.</dt>
  <dt>&nbsp;</dt>
  <dt><i>View History:</i>&nbsp; This will show you the document history.</dt>
  <dt>&nbsp;</dt>
  <dt><i>Edit Info:</i>&nbsp; This will allow for editing of information pertaining to a file and is available to webmasters only.</dt>
 </blockquote>
</dl>
<blockquote>
 <dl>
  <dt><b>Editing a file:</b></dt>
  <blockquote>
   <dt><i>Document File Name:</i> This is the name of the file, with no path information (filename.txt)</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Location:</i>&nbsp; This is the folder location within the project, multiple subfolders are stored in order separated by commas (folder1,folder2,folder3,etc....)</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Version:</i>&nbsp; This is the version of the document itself.&nbsp; This version is and will be different from your distribution version.</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Phase:</i>&nbsp; This is the phase of development that your document is in.&nbsp; Once it hits Implementation it will be included automatically when building a distro.</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Owner:</i>&nbsp; This is an auto value.&nbsp; It is the person who uploaded the file.</dt>
   <dt>&nbsp;</dt>
   <dt><i>Document Description:</i>&nbsp; This is a description of the file and what it does.&nbsp; This field is for information purposes only.&nbsp; It is also used to note changes and improvements to the document.</dt>
  </blockquote>
 </dl>
</blockquote>
<?php
print("<br><a href='index.php'><font size='4'><b>Return to CVS...</b></font></a><br><br>");
print("<br><br><font size='1'>");
print("<i>Current Version System ".$dcm_version.".  By Lonny Luberts<br></i>");
print("<i>Based on DCM System Version 2.0.  By Richard James Kendall.</i>");
print("</font></body></html>");
require("global/_bottom.php");
?>