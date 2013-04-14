<?php

$body = NULL;
if (isset($email)) {
	$body = "<p>Logged in as: " . $email;
	$body .= '&nbsp;<a href="javascript:navigator.id.logout()">Logout</a></p>';
} else {
	$body = "<p><a href=\"javascript:navigator.id.request()\">Login</a></p>";
}


?>
