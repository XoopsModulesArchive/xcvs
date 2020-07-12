<?
// general config
$dcm_name = "Current Version System";
$dcm_url = XOOPS_URL."/modules/cvs/";
$dcm_version = "1.5";

// mysql config
$mysql_uname = XOOPS_DB_USER;
$mysql_password = XOOPS_DB_PASS;
$mysql_database = XOOPS_DB_NAME;
$mysql_server = XOOPS_DB_HOST;

$link = mysql_connect($mysql_server, $mysql_uname, $mysql_password);
mysql_select_db($mysql_database, $link);
global $xoopsUser;
$uname = $xoopsUser->getVar('uname'); 
$uid = $xoopsUser->getVar('uid'); 
$results = mysql_query("select * from ".XOOPS_DB_PREFIX."_users where uname='$uname'", $link);
$usr_obj = mysql_fetch_object($results);
$res2 = mysql_query("select groupid from ".XOOPS_DB_PREFIX."_groups_users_link where uid='$uid'", $link);
$iswebmast = mysql_fetch_assoc($res2);
if ($iswebmast[groupid] == 1) $admin_user = true;

foreach ($_GET as $key => $value) {
	$$key = $value;
}

foreach ($_POST as $key => $value) {
	$$key = $value;
}

function getFullName($usern) {
   global $link, $uname;
   $sql = "select * from ".XOOPS_DB_PREFIX."_users where uname='$usern'";
   $row = mysql_fetch_object(mysql_query($sql, $link));
   return $row->name;
}

function getEmailAddr($usrn) {
   global $link, $uname;
   $sql = "select * from ".XOOPS_DB_PREFIX."_users where uname='$usrn'";
   $row = mysql_fetch_object(mysql_query($sql, $link));
   return $row->email;
}

function addHistory($id, $type) {
   global $link, $uname;
   $htime = time();
   $sql = "insert into ".XOOPS_DB_PREFIX."_cvs_history (document_id, hist_action, hist_person, hist_timestamp) values ($id, '$type', '$uname', '$htime')";
   $ok = mysql_query($sql, $link);
   return $ok;
}

function numberCheckedOut() {
   global $link, $uname;
   $sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents where document_out_with='$uname'";
   $results = mysql_query($sql, $link);
   return mysql_num_rows($results);
}

function proj_numberCheckedOut($proj) {
   global $link, $uname;
   $sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents where document_out_with='$uname' AND document_project_id='$proj'";
   $results = mysql_query($sql, $link);
   return mysql_num_rows($results);
}

function totalCheckedOut() {
   global $link, $uname;
   $sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents where document_out='1'";
   $results = mysql_query($sql, $link);
   return mysql_num_rows($results);
}

function proj_totalCheckedOut($proj) {
   global $link, $uname;
   $sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents where document_out='1' AND document_project_id='$proj'";
   $results = mysql_query($sql, $link);
   return mysql_num_rows($results);
}

function totalProjectFiles($proj) {
   global $link, $uname;
   $sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents where document_project_id='$proj'";
   $results = mysql_query($sql, $link);
   return mysql_num_rows($results);
}

function checkedOutByUser($id) {
   global $link, $uname;
   $sql = "select document_name from ".XOOPS_DB_PREFIX."_cvs_documents where document_id=$id and document_out_with='$uname'";
   $results = mysql_query($sql, $link);
   return mysql_num_rows($results) == 1;
}

function check_isimage($name){
   	if (strstr(".gif",$name)) $isimage = true;
   	if (strstr(".jpg",$name)) $isimage = true;
   	if (strstr(".jpeg",$name)) $isimage = true;
   	if (strstr(".png",$name)) $isimage = true;
   	if (strstr(".bmp",$name)) $isimage = true;
   	if (strstr(".pcx",$name)) $isimage = true;
   	if (strstr(".tif",$name)) $isimage = true;
   	if (strstr(".tiff",$name)) $isimage = true;
   	return $isimage;
}
?>