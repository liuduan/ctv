<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

</head>
<body>
<!-- <body background="images/Carpet.jpg"> -->
<div id="container" style = "padding-bottom: ;  ">
<?php
if($_GET['type'] == "About"){
	echo '<div style="margin:auto; text-align: center; ">CTV Predictor Version 0.9 (Beta)</div>';
}
if($_GET['type'] == "Contact"){
	echo '<div style="margin:auto; text-align: center; ">';
	echo 'For any questions or to be notified of future updates to ToxValue.org, please send an email to <a href="mailto:conditionaltoxvalue@gmail.com" target="_blank">conditionaltoxvalue@gmail.com</a>.';
	echo '</div>';
}
?>



</body>