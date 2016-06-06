<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
 		<title>Text Viewer</title>
		<meta name="description" content="Viewer">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="styles.css">
	</head>
<?php
// first we need to see if we actually GET anything
$gettest = $_GET['p'];
if ( isset($gettest) ){
// THEN do this shit
// need to lowercase the filename
$gettest = strtolower($gettest);
// remove all whitespace
$gettest = preg_replace('/\s+/', '', $gettest);
// set file to read 
$filename ='txt/'.$gettest.'.txt';
// check if file exists
if (file_exists($filename)) {
// now open the file
$fh = @fopen($filename, "r");
// read file contents
$data = @fread($fh, filesize($filename));
// then close it
  fclose($fh);
}
echo '<body style="font-family: monospace;">';
echo nl2br($data);
echo '</body>';
// and if we got no p variable don't do anything
} else {
echo "<h1>No file to view</h1>";
}
?>
</html>