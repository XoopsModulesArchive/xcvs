Current Version System v1.0
By Lonny Luberts
Email: lonny@pqcomp.com
Based on Document Content Management System v2.0
By Richard James Kendall
Email: richard@richardjameskendall.com
Copyright (C) 2004 Richard James Kendall, (C) 2005 Lonny Luberts

"A solution for working on documents in small group environments"

##################
# HOW TO INSTALL #
##################

This system requires the following:

 * PHP 4.3.0 or higher (http://www.php.net)
 * MySQL 3.x or higher (http://www.mysql.com)
 * Apache or another web server capable of providing HTTP Authentication (.htaccess) (http://httpd.apache.org)

exctract into the modules folder and install from administration panel

create temp folder and chmod it to 0777... this folder is used to store build zip file for download

users in the webmaster group will have delete and edit access..... set access by group to this
module as normal.  For us we have a developer group that can access cvs.

Enjoy.... and comments or questions should be pm'ed via our xoops site at http://www.pqcomp.com to Lonny


############
# OPTIONAL #
############

You may want to increase the amount of data that can be sent to MySQL be changing the max_allowed_packet server
parameter.  You can find out how to do this here: http://dev.mysql.com/doc/mysql/en/Server_parameters.html

You may also want the maximum file size that PHP will accecpt for upload, details on this can be found here:
http://uk.php.net/manual/en/ini.sect.file_uploads.php#ini.upload-max-filesize

Ver 1.2
Added build distro with download of zip file....
This does not support subdirectories, you will need to re-organize your files
and package appropriately.  The CVS system does NOT support image files (yet).

Ver 1.3 Additions
Now supports more file types including images and zip files. There is a new field in the documents table that contains folder information so a build will now put files into proper subfolders. This means that CVS can now build a distributable project. Add build a snapshot which will build a zip file with ALL files included and building a dirstro now only builds files that are at implimentation stage. Added a help section to better explain some of the functions. Moved some commands to the xoops menu block. Added more file type icons as well....

Version 1.4
Added Multiple project capability. Cleaned up more of the original code from the DCM pagage that I started this module from. This is the first release that the download was created by a running version of the CVS system.

Version 1.5 
Added Mysql file type... backup and restore working.  Fixed a number of small errors.
