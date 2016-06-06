<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
 		<title>Text Editor</title>
		<meta name="description" content="Editor">
		<meta name="viewport" content="width=device-width">
		<!-- <link rel="shortcut icon" href="img/icon.ico" type="image/x-icon" />  -->
		<!-- CSS -->
		<!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
		<link rel="stylesheet" href="styles.css">
		<!-- JS 
		<script src="js/vendor/jquery.min.js"></script>
		<script src="js/script.js"></script>-->
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
// now we check if we are renaming the file
// check if variable has been set
$renamed = @$_GET['renamed'];
if ( isset($renamed) ){
// and lowercase filename
$renamed = strtolower($renamed);
// strip whitespace
$renamed = preg_replace('/\s+/', '', $renamed);
// check to see if other files with the same name exist
foreach(glob('txt/*.txt') as $txtfile){
$txtfile = basename($txtfile,".txt");
// echo $txtfile;
// echo $renamed;
// if we do find the same name then we have to figure out wtf we're doing with it
if ( $renamed == $txtfile ) {
// then what? run the rename function again to get a new variable
echo '<script>var renamed = prompt("There is already a file named that! Try again");
							var filename = "'.$renamed.'";
							if (renamed != "" && renamed != filename) {
								var renamed = renamed.toLowerCase();
								var renamed = renamed.replace(/\s/g, "");
								window.location = "edit.php?p='.$gettext.'&renamed=" + renamed;
								} else {
								alert("Could not rename file");
								window.location = "edit.php";
								}
							</script>';
$renamed = "";
}
}
if ( $renamed != "" ) {
// make it reference an actual file
$renamedFile = 'txt/'.$renamed.'.txt';
// and rename it
rename($filename,$renamedFile);
// we still have to set this filename
$filename = $renamedFile;
// actually I give up and just want to reload with the new filename
// duhh I think this should work
echo '<script>window.location = "edit.php?p='.$renamed.'"</script>';
}
}
// now open the file
$fh = @fopen($filename, "r");
// read file contents
$data = @fread($fh, filesize($filename));
// then close it
  fclose($fh);
} else {
// if not, create it
  fopen($filename, "w+");
// read file contents 
  $data = @fread($fh, filesize($filename)); 
// close file 
  @fclose($fh); 
}
// and if we got no p variable, take us to the first text file
} else {
foreach(glob('txt/*.txt') as $txtfile)
{
// get filename only
$txtfile = basename($txtfile,".txt");

echo '<script>window.location = "edit.php?p='.$txtfile.'"</script>';
break;
}
}
	echo '<body>
		<div class="main-container">
				<div class="editor-view">
					<form action="" method= "post" > 
						<textarea rows="20" name="newd">'.$data.'</textarea>
						<input type="submit" value="Save" align="right">
					</form>
				</div>
				<div class="menu-view">
				<font style="font-size: 70%; text-align: center;"><center>Saved Files - (x) to delete, DL to download</center></font>
					<table align="center">';
						//<ul id="notes-list">';
						// get list of txt files (I think this works)
						// loop text files
						foreach(glob('txt/*.txt') as $txtfile)
						{
						$txtfile = basename($txtfile,".txt");
						// take out .txt
						// put some fucking tables in here
						// also we can add direct links to each text file to download
						// don't add a link for the currently open file
						if ( $gettest == $txtfile ) {
						echo '<tr><td>'.$txtfile.'</td><td align="center"><a href="delete.php?p='.$txtfile.'"><font color="red">(x)</font></a></td><td><a href="txt/'.$txtfile.'.txt">DL</a></td><td><a href="view.php?p='.$txtfile.'">V</a></td></tr>';
						} else {
						echo '<tr><td><a href="edit.php?p='.$txtfile.'">'.$txtfile.'</a></td><td align="center"><a href="delete.php?p='.$txtfile.'"><font color="red">(x)</font></a></td><td><a href="txt/'.$txtfile.'.txt">DL</a></td><td><a href="view.php?p='.$txtfile.'">V</a></td></tr>';
						}
						}
						//</ul>
						echo '<script>
						function newFile() {
							var txtfile = prompt("Name of new text file");
							if (txtfile != "") {
								var txtfile = txtfile.toLowerCase();
								var txtfile = txtfile.replace(/\s/g, "");
								window.location = "edit.php?p=" + txtfile;
							}
						}
						function renameFile() {
							var renamed = prompt("New name to rename file");
							if (renamed != "") {
								var renamed = renamed.toLowerCase();
								var renamed = renamed.replace(/\s/g, "");
								window.location = "edit.php?p='.$gettest.'&renamed=" + renamed;
							}
						}</script>
						<tr><td>
						<button onclick="newFile()">New File</button>
						</td><td>
<button onclick="renameFile()" align="right">Rename</button>
</td></tr>
						</table>
				</div>
		</div>
	</body>';
$newdata = @$_POST['newd']; 

if (isset($newdata)) { 

// open file  
$fw = fopen($filename, 'w') or die('Could not open file!'); 
// write to file 
// added stripslashes to $newdata 
$fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file'); 
// close file 
fclose($fw);
echo '<script>
location = location
</script> ';
}
?>
</html>
