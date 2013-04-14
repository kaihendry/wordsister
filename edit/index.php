<?php
include("persona.php");
include("login.php");

$content = "../index.mdwn";
$style = "../style.css";

umask(002);

if (isset($_REQUEST['q'])) {
	$q = filter_var($_REQUEST['q'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	$content = "../" . $q;
	@mkdir($content, 0777, true);
	$content = "../" . $q . "/index.mdwn";
}

if (valid($email)) {
	if (isset($_POST['style'])) {
		file_put_contents($style, stripslashes($_POST['style']));
	}
	if (isset($_POST['content'])) {
		file_put_contents($content, stripslashes($_POST['content']));
		`cd .. && make`;
		header("Location: http://" . $_SERVER["HTTP_HOST"] . '/' . dirname($content));
	}
} else {
	echo "Sorry $email does not have write permissions";
}


?>
<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: "Helvetica Neue", sans-serif; }
textarea { width: 100%; height: 20em; }
</style>
<link rel="stylesheet" href="/pagedown/demo.css">
<script src="/pagedown/Markdown.Converter.js"></script>
<script src="/pagedown/Markdown.Sanitizer.js"></script>
<script src="/pagedown/Markdown.Editor.js"></script>
<script src="/pagedown/jquery-1.9.1.min.js"></script>
<title>Editing <?php echo $content; ?></title>
</head>
<body>

<form id="login-form" method="POST">
  <input id="assertion-field" type="hidden" name="assertion" value="">
</form>
<?= $body ?>
<script src="https://login.persona.org/include.js"></script>
<script>
navigator.id.watch({
	loggedInUser: <?= $email ? "'$email'" : 'null' ?>,
	onlogin: function (assertion) {
		var assertion_field = document.getElementById("assertion-field");
		assertion_field.value = assertion;
		var login_form = document.getElementById("login-form");
		login_form.submit();
	},
	onlogout: function () {
		window.location = '?logout=1';
	}
});
</script>

<form method=post>
<input type=submit value=Save>
<div class="wmd-panel">
<div id="wmd-button-bar"></div>
<textarea class="wmd-input" id="wmd-input" name="content">
<?php readfile($content); ?>
</textarea>
</div>
<div id="wmd-preview" class="wmd-panel wmd-preview"></div>

<textarea id="styleeditor" name=style>
<?php readfile($style); ?>
</textarea>

</form>

<?php if(file_exists("../upload.php")) { ?>
<form id=upload action="/upload.php" method="post" enctype="multipart/form-data">
<p>Upload a file</p>
<input id="assertion-field2" type="hidden" name="assertion" value="">
<input name="f" type="file" />
<input value="Upload" type="submit">
</form>
<?php } ?>

<script>
var converter1 = Markdown.getSanitizingConverter();
var editor1 = new Markdown.Editor(converter1);
editor1.run();
</script>

</body>
</html>
