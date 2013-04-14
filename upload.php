<?php

include("edit/persona.php");

error_log("UPLOAD email set as:" . $email);
date_default_timezone_set('UTC');
umask(002);

if (! valid($email)) {
	die("Sorry $email does not have write permissions");
}

if (! is_uploaded_file($_FILES['f']['tmp_name'])) {
	die("Upload fail: Missing file.");
}

$dir = getcwd();
if (is_writable($dir)) {
	$dir = $dir . '/static/' . date("Y-m-d");
} else {
	die("No write permission.\n Fix with: chown -R www-data $dir\n");
}
@mkdir($dir, 0777,true);
move_uploaded_file($_FILES["f"]['tmp_name'], "$dir/" . $_FILES['f']['name']);
header("Location: http://" . $_SERVER["HTTP_HOST"] . '/static/' . basename($dir));

?>
