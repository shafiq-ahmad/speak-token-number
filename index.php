<?php
define('_MEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', dirname(__FILE__) );
require_once BASE_PATH . DS . 'includes' . DS . 'defines.php';
require_once BASE_PATH . DS . 'includes' . DS . 'functions.php';


?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Speak System</title>
<script src="media/js/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="container-fluid">
<div class="left_wrap">
	<ul id="token_number_list" style="overflow:hidden;">
	</ul>
	<div id="sound"></div>
	
</div>
<?php
//$spk = json_encode(speak(203434014));
$rs=null;
if(isset($_GET['number']) && $_GET['number']){
	$rs = speak($_GET['number']);
	
}
//$spk = json_encode($rs);
?>
</div>
<div class="container-fluid">
<div class="footer_wrapper">
<center>
<form method="get" action="index.php">
<input name="number" value="" />
<input type="submit" value="Speak" />

</form>
<audio id="audio-magic" preload class="numbers"><source src="audio/magic.mp3" type="audio/mpeg" /></audio>
<?php
if($rs){
foreach($rs as $r){
?>
<audio id="num-<?php echo $r ?>" preload class="numbers"><source src="audio/numbers/<?php echo $r; ?>.wav" type="audio/mpeg" /></audio>
<?php
}
}
?>
</center>

</div>
</div>
<script>/*
$(document).ready(function (){
var audioArray = document.getElementsByClassName('numbers');
var i = 0;
audioArray[i].play();
for (i = 0; i < audioArray.length - 1; ++i) {
audioArray[i].addEventListener('ended', function(e){
	var currentSong = e.target;
	var next = $(currentSong).nextAll('audio');
	if (next.length) $(next[0]).trigger('play');
});
}
});
*/



var audioArray = document.getElementsByClassName('numbers');
var i = 0;
audioArray[i].play();
for (i = 0; i < audioArray.length - 1; ++i) {
audioArray[i].addEventListener('ended', function(e){
	var currentSong = e.target;
	var next = $(currentSong).nextAll('audio');
	if (next.length) $(next[0]).trigger('play');
});
}
</script>

</body>
</html>