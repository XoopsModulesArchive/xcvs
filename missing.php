<?php

// Based on DCM v2.0 by Richard James Kendall
// richard@richardjameskendall.com

//require("global/_vars.php");
?>
<html>
<head>
	<title>Missing Information</title>
	<link rel="stylesheet" type="text/css" href="global/style.css">
</head>
<body>
<span class="pagetitle">Missing Information</span>
<br><br>
The fields (listed below) are blank, they must be filled in.
<ul>
<?php print($missing); ?>
</ul>
</body>
</html>
<?php
?>