</p>
<?php

if (!empty($_FILES)) {

if(isset($_GET['user'])) {
$usernumber = $_GET['user'];
$upload_dir = '../media/Users';
} else if(isset($_GET['program'])) {
$program = $_GET['program'];
$upload_dir = '../media/Programs';
} else {
return false;exit;}

 $tempFile = $_FILES['file']['tmp_name'];
 // using DIRECTORY_SEPARATOR constant is a good practice, it makes your code portable.
 $targetPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $upload_dir . DIRECTORY_SEPARATOR;
 // Adding timestamp with image's name so that files with same name can be uploaded easily.
 if($usernumber) { $filename = 'user'.$usernumber.'.jpg'; } else { $filename = $program.'.png'; }
 $mainFile = $targetPath.$filename;
 move_uploaded_file($tempFile,$mainFile);

}
?>
<p style="text-align: justify;">