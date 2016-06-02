<?php
// set file to read 
$filename ='txt/'.$_GET['p'].'.txt'; 
// check if file exists
if (file_exists($filename)) {
unlink($filename);
}
foreach(glob('txt/*.txt') as $txtfile)
{
$txtfile = basename($txtfile,".txt");
echo '<script>window.location = "editor.php?p='.$txtfile.'"</script>';
break;
}