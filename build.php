<?php
// multiple project ready

require("global/_top.php");
print ("<html><head><title>".$dcm_name."</title></head>");
if ($op == ""){
print ("<p align='center'>");
print ("<b><font size='5' color='#00FF00'>Build ".$mode."!</font></b></p>");
print ("<p align='center'>");
print ("Are you sure you want to build and download a ".$mode."?</p>");
print ("<form method='POST' action='build.php?op=yes&mode=".$mode."'>");
print ("<p align='center'>");
$sql = "SELECT project_dist_name from ".XOOPS_DB_PREFIX."_cvs_projects WHERE project_id = '".$curr_project."'";
$result = mysql_query($sql, $link);
$projs = mysql_fetch_assoc($result);
print ("Distro Filename (no extension) <input type='text' size='20' name='filename' value='".$projs[project_dist_name]."'><br><br>");
print ("<input type='submit' value='Yes' name='yes'></p>");
print ("</form>");
print ("<form method='POST' action='index.php'>");
print ("<p align='center'>");
print ("<input type='submit' value='No' name='no'></p>");
print ("</form>");
}elseif ($op == "yes"){
	$zipfile = new zipfile();  
	$zipfile -> add_dir($filename."/");
	$sql = "select * from ".XOOPS_DB_PREFIX."_cvs_documents WHERE document_project_id = '".$curr_project."'";
    $file = mysql_query($sql, $link);
    for ($i=0;$i < mysql_num_rows($file);$i++){
      	$row = mysql_fetch_assoc($file);
   	  	$data = $row[document_data];
   	  	//check if file is in implimentation stage!
   	  	if ($mode == "snapshot" or ($mode == "distro" and $row[document_cat] == "Implementation")){
	   	  	$isimage = check_isimage($row[document_file_name]);
		    if (!$isimage) $data = base64_decode($data);
		    if ($row[document_location] <> ""){
			    $subdirs = "";
			    $location_dirs = explode(",",$row[document_location]);
			    for ($i=0;$i < count($location_dirs);$i++){
				    $zipfile -> add_dir($filename."/".$subdirs.$location_dirs[$i]."/");
				    //print ("Debug - adding ".$subdirs.$location_dirs[$i]." folder.<br>");
				    $subdirs .= $location_dirs[$i]."/";
		    	}
			    $zipfile -> add_file($data, $filename."/".$subdirs.$row[document_file_name]);  
		    }else{
	   	  		$zipfile -> add_file($data, $filename."/".$row[document_file_name]);
		  	}
  		}
	}
	//check temp directory exist and see if writable report error if not
	//add a description text file automatically with file listing
	$fd = fopen ("temp/".$filename.".zip", "wb");
	$out = fwrite ($fd, $zipfile -> file());
	fclose ($fd);
	echo "<a href='temp/".$filename.".zip'>Click here to download the new zipped distro file.</a><br>";
	echo "<br><a href='build.php?op=cleanup&filename=".$filename."'><font size='4'><b>Continue - after your download finishes, it is important to click here to clean up your temp directory!</b></font></a><br><br>";
}elseif ($op == "cleanup"){
  	if (unlink("temp/".$filename.".zip")){
	      	print ("Deleted ".$filename.".zip.<br>");
      	}else{
	      	print ("Could not delete ".$filename.".zip.<br>");
      	}
  	echo "<br><a href='index.php'><font size='4'><b>Continue...</b></font></a><br><br>";
}else{
	print ("Error - unknown function!<br>");
	echo "<br><a href='index.php'><font size='4'><b>Return to main CVS page.</b></font></a><br><br>";
}
require("global/_bottom.php");

class zipfile{   
	//zipfile class by Eric Mueller @ http://www.themepark.com  
    var $datasec = array(); 
    var $ctrl_dir = array(); 
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; 
    var $old_offset = 0;  

    function add_dir($name){   
        $name = str_replace("\\", "/", $name);   

        $fr = "\x50\x4b\x03\x04";  
        $fr .= "\x0a\x00";    // ver needed to extract
        $fr .= "\x00\x00";    // gen purpose bit flag
        $fr .= "\x00\x00";    // compression method
        $fr .= "\x00\x00\x00\x00"; // last mod time and date

        $fr .= pack("V",0); // crc32
        $fr .= pack("V",0); //compressed filesize
        $fr .= pack("V",0); //uncompressed filesize
        $fr .= pack("v", strlen($name) ); //length of pathname
        $fr .= pack("v", 0 ); //extra field length
        $fr .= $name;   
        $fr .= pack("V",$crc); //crc32
        $fr .= pack("V",$c_len); //compressed filesize
        $fr .= pack("V",$unc_len); //uncompressed filesize

        $this -> datasec[] = $fr;  
        $new_offset = strlen(implode("", $this->datasec));  
        $cdrec = "\x50\x4b\x01\x02";  
        $cdrec .="\x00\x00";    // version made by
        $cdrec .="\x0a\x00";    // version needed to extract
        $cdrec .="\x00\x00";    // gen purpose bit flag
        $cdrec .="\x00\x00";    // compression method
        $cdrec .="\x00\x00\x00\x00"; // last mod time & date
        $cdrec .= pack("V",0); // crc32
        $cdrec .= pack("V",0); //compressed filesize
        $cdrec .= pack("V",0); //uncompressed filesize
        $cdrec .= pack("v", strlen($name) ); //length of filename
        $cdrec .= pack("v", 0 ); //extra field length    
        $cdrec .= pack("v", 0 ); //file comment length
        $cdrec .= pack("v", 0 ); //disk number start
        $cdrec .= pack("v", 0 ); //internal file attributes
        $ext = "\x00\x00\x10\x00";  
        $ext = "\xff\xff\xff\xff";   
        $cdrec .= pack("V", 16 ); //external file attributes  - 'directory' bit set
        $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header
        $this -> old_offset = $new_offset;  
        $cdrec .= $name;   
        $this -> ctrl_dir[] = $cdrec;   
    }  

    function add_file($data, $name){    
        $name = str_replace("\\", "/", $name);   
        $fr = "\x50\x4b\x03\x04";  
        $fr .= "\x14\x00";    // ver needed to extract
        $fr .= "\x00\x00";    // gen purpose bit flag
        $fr .= "\x08\x00";    // compression method
        $fr .= "\x00\x00\x00\x00"; // last mod time and date

        $unc_len = strlen($data);   
        $crc = crc32($data);   
        $zdata = gzcompress($data);   
        $zdata = substr( substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
        $c_len = strlen($zdata);   
        $fr .= pack("V",$crc); // crc32
        $fr .= pack("V",$c_len); //compressed filesize
        $fr .= pack("V",$unc_len); //uncompressed filesize
        $fr .= pack("v", strlen($name) ); //length of filename
        $fr .= pack("v", 0 ); //extra field length
        $fr .= $name;   
        $fr .= $zdata;   
        $fr .= pack("V",$crc); //crc32
        $fr .= pack("V",$c_len); //compressed filesize
        $fr .= pack("V",$unc_len); //uncompressed filesize
        $this -> datasec[] = $fr;  
        $new_offset = strlen(implode("", $this->datasec));  
        $cdrec = "\x50\x4b\x01\x02";  
        $cdrec .="\x00\x00";    // version made by
        $cdrec .="\x14\x00";    // version needed to extract
        $cdrec .="\x00\x00";    // gen purpose bit flag
        $cdrec .="\x08\x00";    // compression method
        $cdrec .="\x00\x00\x00\x00"; // last mod time & date
        $cdrec .= pack("V",$crc); // crc32
        $cdrec .= pack("V",$c_len); //compressed filesize
        $cdrec .= pack("V",$unc_len); //uncompressed filesize
        $cdrec .= pack("v", strlen($name) ); //length of filename
        $cdrec .= pack("v", 0 ); //extra field length    
        $cdrec .= pack("v", 0 ); //file comment length
        $cdrec .= pack("v", 0 ); //disk number start
        $cdrec .= pack("v", 0 ); //internal file attributes
        $cdrec .= pack("V", 32 ); //external file attributes - 'archive' bit set
        $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header
        $this -> old_offset = $new_offset;  
        $cdrec .= $name;   
        $this -> ctrl_dir[] = $cdrec;   
    }  

    function file(){ // dump out file    
        $data = implode("", $this -> datasec);   
        $ctrldir = implode("", $this -> ctrl_dir);   

        return    
            $data.   
            $ctrldir.   
            $this -> eof_ctrl_dir.   
            pack("v", sizeof($this -> ctrl_dir)).     // total # of entries "on this disk"
            pack("v", sizeof($this -> ctrl_dir)).     // total # of entries overall
            pack("V", strlen($ctrldir)).             // size of central dir
            pack("V", strlen($data)).                 // offset to start of central dir
            "\x00\x00";                             // .zip file comment length
    }  
}   
?>