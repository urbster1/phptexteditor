<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
 		<title>Text Editor</title>
		<meta name="description" content="Editor">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="styles.css">
	</head>
<?php
// first we need to see if we actually GET anything
$gettest = $_GET['p'];
if (isset($gettest)) {
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
        if (isset($renamed)) {
            // and lowercase filename
            $renamed = strtolower($renamed);
            // strip whitespace
            $renamed = preg_replace('/\s+/', '', $renamed);
            // check to see if other files with the same name exist
            foreach (glob('txt/*.txt') as $txtfile) {
                $txtfile = basename($txtfile, ".txt");
                // echo $txtfile;
                // echo $renamed;
                // if we do find the same name then we have to figure out wtf we're doing with it
                if ($renamed == $txtfile) {
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
            if ($renamed != "") {
                // make it reference an actual file
                $renamedFile = 'txt/'.$renamed.'.txt';
                // and rename it
                rename($filename, $renamedFile);
                // we still have to set this filename
                $filename = $renamedFile;
                // actually I give up and just want to reload with the new filename
                // duhh I think this should work
                echo '<script>window.location = "edit.php?p='.$renamed.'"</script>';
            }
        }
        // check if file is writable
        //if (is_writable($filename))  {
        //  echo  "The file is writable<br>";
        //}
        //else {
        //  echo "The file is not writable<br>";
        //}
        // now open the file
        $fh = @fopen($filename, "r") or die("Unable to open file!<br>");
        // read file contents
        $data = @fread($fh, filesize($filename));
        // then close it
        fclose($fh);
        $data = base64_decode($data, true);
    } else {
        // if not, create it
        fopen($filename, "a+") or die("Unable to open file!<br>");
        // read file contents
        $data = @fread($fh, filesize($filename));
        $data = base64_decode($data, true);
        // close file
        @fclose($fh);
    }
    // and if we got no p variable, take us to the first text file
} else {
    foreach (glob('txt/*.txt') as $txtfile) {
        // get filename only
        $txtfile = basename($txtfile, ".txt");
        $gettest = $txtfile;
        echo '<script>window.location = "edit.php?p='.$txtfile.'"</script>';
        break;
    }
}
    echo '<body>
<script>document.title = "Edit '.$gettest.'";</script>
		<div class="main-container">
				<div class="editor-view">
					<form action="" method= "post" enctype="multipart/mixed" onsubmit="prettySubmit()"> 
						<textarea rows="25" name="newd" id="newd">'.$data.'</textarea><br />
						<input type="submit" value="Save" style="float: right;" id="save">
					</form>
				</div>
                                <div style="display: flex; flex-direction: column;">
				<div class="menu-view">
					<table align="center">';
                        //<ul id="notes-list">';
                        // get list of txt files (I think this works)
                        // loop text files
                        foreach (glob('txt/*.txt') as $txtfile) {
                            $txtfile = basename($txtfile, ".txt");
                            // take out .txt
                            // put some fucking tables in here
                            // also we can add direct links to each text file to download
                            // don't add a link for the currently open file
                            if ($gettest == $txtfile) {
                                echo '<tr><td>'.$txtfile.'</td><td align="center"><a href="delete.php?p='.$txtfile.'" onclick="return confirm(\'Are you sure?\')" class="delete"> &nbsp;&#10006;&nbsp; </a></td><td><a style="text-decoration: none;" target="_blank" href="view.php?p='.$txtfile.'">&#128269;</a></td></tr>';
                            } else {
                                echo '<tr><td><a href="edit.php?p='.$txtfile.'">'.$txtfile.'</a></td><td align="center"><a href="delete.php?p='.$txtfile.'" onclick="return confirm(\'Are you sure?\')" class="delete"> &nbsp;&#10006;&nbsp; </a></td><td><a style="text-decoration: none;" target="_blank" href="view.php?p='.$txtfile.'">&#128269;</a></td></tr>';
                            }
                        }
                        //</ul>
                        echo '<script>
						function base64EncodeUnicode(str) {
  // Firstly, escape the string using encodeURIComponent to get the UTF-8 encoding of the characters, 
  // Secondly, we convert the percent encodings into raw bytes, and add it to btoa() function.
  utf8Bytes = encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function (match, p1) {
    return String.fromCharCode(\'0x\' + p1);
  });

  return btoa(utf8Bytes);
}
						function prettySubmit() {
						  document.getElementById("newd").style.display = "none";
						  document.getElementById("save").style.display = "none";
              if (newd.value == "") {
                newd.value = "EMPTY";
              } else {
  						  newd.value = base64EncodeUnicode(newd.value);
              }
						}
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
						</table>
				</div>
			  <button onclick="newFile()">New File</button>
                          <button onclick="renameFile()" align="right">Rename '.$gettest.'</button>
                     </div>
		</div>
	</body>';
$newdata = $_POST['newd'];
//$content = $_POST['contents'];
//$content = strtr($content, ' ', '+');
//$content = base64_decode($content);
//
if (isset($newdata)) {
    $newdata = strtr($newdata, '-_', '+/');
    // open file
    $fw = fopen($filename, 'w') or die('Could not open file!');
    // write to file
    if ($newdata == 'EMPTY') {
        @ftruncate($fb, 0);
    } else {
        $fb = fwrite($fw, $newdata) or die('Could not write to file');
    }
    // close file
    fclose($fw);
    echo '<script>
location = location
</script> ';
}
?>
</html>
