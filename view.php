<?php
$gettest = $_GET['p'];
echo '<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
 		<title>'.$gettest.'</title>
		<meta name="description" content="Viewer">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="styles.css">
	</head>';
// first we need to see if we actually GET anything
if ( isset($gettest) ){
// THEN do this shit
// need to lowercase the filename
$gettest = strtolower($gettest);
// remove all whitespace
$gettest = preg_replace('/\s+/', '', $gettest);
// set file to read 
$filename ='txt/'.$gettest.'.txt';
$data = "not found";
// check if file exists
if (file_exists($filename)) {
// now open the file
$fh = @fopen($filename, "r") or die("Unable to open file!<br>");
// read file contents
$data = @fread($fh, filesize($filename));
// then close it
  fclose($fh);
$data = nl2br(base64_decode($data));
}
echo '<body style="font-family: monospace;">';
echo $data;
echo '</body>';
// and if we got no p variable don't do anything
} else {
echo "<h1>No file to view</h1>";
}
?>
</html>
