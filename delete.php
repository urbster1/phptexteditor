<?php

// set file to read
$filename ='txt/'.$_GET['p'].'.txt';
// check if file exists
if (file_exists($filename)) {
  //    i realized many years later not to delete the actual file. instead, rename with timestamp appended
//    unlink($filename);
  $archive = $filename.date("YmdHis");
  rename($filename, $archive);
}
foreach (glob('txt/*.txt') as $txtfile) {
    $txtfile = basename($txtfile, ".txt");
    echo '<script>window.location = "edit.php?p='.$txtfile.'"</script>';
    break;
}
