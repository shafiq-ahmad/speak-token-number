<?php
defined('_MEXEC') or die ('Restricted Access');


?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Speak System</title>
<script src="<?php echo SCRIPTS_PATH ?>jquery-3.1.1.min.js"></script>
</head>
<body>
<div class="container-fluid">
<div class="left_wrap">
	<ul id="token_number_list" style="overflow:hidden;">
	</ul>
	<div id="sound"></div>
	
</div>
<?php 
function get_10s($tens){
	$res='1';
	if($tens >0){
		$tens-=1;
		while($tens){
			$res .= '0';
			$tens-=1;
		}
		return intval($res);
	}
	return false;
	
}

function get_mili($number,$rev=-3,&$res=array()){
	if(!$number){return false;}
	//if($number > 1,000,000,000){echo "number not supported";return false;}
	$len = strlen($number);
	$l = $len -3;
	if($l >0){
		$res[] = substr($number,$rev,3);
		$number = substr($number,0,$l);
		get_mili($number,$rev,$res);
	}else{
		$res[] = substr($number,0);
	}
	return $res;
}

function get_num_text($index){
	//if(!$index){return false;}
	$data=array();
	$data[0]='';
	$data[1]='thousand';
	$data[2]='million';
	$data[3]='billion';
	$data[4]='trillion';
	return $data[$index];
}

function get_digit_name($number,$rev=-2,&$res=array()){
	//max number 999
	if(!$number){return false;}
	$len = strlen($number);
	$l = $len -2;
	if($l >0){
		$str = substr($number,$rev);
		$res[] = $str;
		$number = substr($number,0,$l);
		get_digit_name($number,$rev,$res);
		//return true;
	}else{
		$res[] = substr($number,0);
	}
	return $res;
	
}

function break_number($number){
	$len = strlen($number);
	echo $number . '<br/>';
	while($number){
		$tens=get_10s($len);
		//echo 103434%100000;exit;
		$dev = intval(intval($number) / intval($tens));
		$mod = intval($number) % intval($tens);
		echo 'number: ' . $dev . 'x' . $tens . '+' . $mod . '<br/>';
		$number = $number % $tens;
		
		$len = strlen($number);
		//$len-=1;
	}
}

function speak_number($number, $type, &$result=array()){
	$num_br = get_mili($number);
	$c=count($num_br)-1;
	for($x=$c;$x>=0;$x--){
		//$type = get_num_text($x);
		$res = get_digit_name($num_br[$x]);
		$c = count($res)-1;
		for($y=$c;$y>=0;$y--){
			if($y==1){
				//$result[]= 'num-' . $res[1] . '00';
				$result[]= $res[1] . '00';
			}else{
				//$result[]= 'num-' . intval($res[$y]);
				$result[]= intval($res[$y]);
			}
		}
		if($type){
			//$result[] = 'num-' . $type;
			$result[] =  $type;
		}
	}
	
}
function speak($number){
	$result=array();
	$num_br = get_mili($number);
	$c=count($num_br)-1;
	for($x=$c;$x>=0;$x--){
		speak_number($num_br[$x], get_num_text($x),$result);
	}
	return $result;
	
}
//$spk = json_encode(speak(203434014));
$rs = speak(1021);
//$spk = json_encode($rs);
?>
</div>
<style>
	.red {color:red;background-color:gold;}
</style>
<div class="container-fluid">
<div class="footer_wrapper">
<center>
<img src="<?php echo IMAGES_PATH ."logo.png" ?>" style="float:left;">
<div class="latest_ticket" style="width:50%;">
<?php /*?><?php $latest_ticket=$ticket->get_latest_ticket();
foreach($latest_ticket as $details): ?>
		<li class='sub_token latest_ticket_display'>
      <div class='counter_label blue'><span class='counter_no'><?php echo $details['counter']?></span>
	  <span class='counter_text'>Counter</span></div>
      <div class='token_label'><span class='token_no'><span><?php echo $details['ticket_no']?></span>
	  </span><span class='token_text'>Ticket Number</span></div>
    	</li>
<?php endforeach; ?><?php */?>
<ul id="latest_ticket">
<li class='sub_token latest_ticket_display' style=''>
     <div class='counter_label blue'><span class='counter_no'></span>
	 <span class='counter_text'>Counter</span></div>
      <div class='token_label'><span class='token_no'></span>
	  <span class='token_text'>Latest Ticket Number</span></div>
    	</li>
</ul>
</div>

<audio id="audio-magic" preload class="numbers"><source src="audio/magic.mp3" type="audio/mpeg" /></audio>
<?php
foreach($rs as $r){
?>
<audio id="num-<?php echo $r ?>" preload class="numbers"><source src="audio/numbers/<?php echo $r; ?>.wav" type="audio/mpeg" /></audio>
<?php
}
?>
<!--<h1 style="float:left; text-indent:1em; padding-top:2.2em; text-shadow:2px 2px black; font-size:48px; font-weight:bold; color:white;">Al Hikmah Private Schools Ajman</h1>
-->
<!--<h1 style="float:right; padding-right:1em;padding-top:2em">Al Hikmah Private Schools Ajman</h1>-->
</center>
<!--<button onClick="get_tokens()" class="btn btn-sm btn-danger" type="button">get data</button>-->
<div class="foot_bar">
		<marquee direction="right" onClick="check_change()" style="padding-right:1em;">
		Arabic Al Hikma School Ajman.Arabic Al Hikma School Ajman.Arabic Al Hikma School Ajman. Al Hikma School Ajman.
		</marquee>
	</div>
    </div>
    </div>
 <script>
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
 
 
function get_tokens(){
	var all_tokens=$('#token_number_list');
	$('body').css('display', 'block');
		all_tokens.empty();
		/*var counter="#counter-";
		var counter_no,token_no;*/
		
		
	//$('#sound').html("");
	$.ajax({
		url:'get_called.php',
		type:'POST',
		dataType:'JSON',
		success: function(data){
			//console.log(data);
		$.each(data,function(i,token){
			/**********shafique version*********/
			/*var counter_id=counter+token.counter;
			counter_no=counter_id+' .counter_no span'; 
			token_no=counter_id+' .token_no span';
			//alert(token_no);
			var ex_token=$(token_no).text();
			//alert(ex_token);
				//console.log();
			if(ex_token !=token.ticket_no )	{
				//alert('tun tun');
				//console.log(ex_token + ' - ' + token.ticket_no);
				$(counter_no).text(token.counter);
				$(token_no).text(token.ticket_no);
				$('#sound').html("<audio autoplay class='sound_file'><source src='magic.mp3' type='audio/mpeg'></audio>");
				//$(token_no).fadeIn(2000);
			}*/
			
			
			all_tokens.append("<li class='sub_token' style=''><div class='counter_label green'><span class='counter_no'>"+token.counter+"</span>"+
	  "<span class='counter_text'>Counter</span></div><div class='token_label'><span class='token_no'><span>"+token.ticket_no+"</span>"+
	  "</span><span class='token_text'>Ticket Number</span></div></li>");
			});}
			
	});
}
</script>
<script>
function removeClass(){
	$('#latest_ticket li span').removeClass('red');
	//console.log('remove');
}
function playSound(txt){
	//$('#latest_ticket li span').removeClass('red');
	//console.log(txt);
	$('#sound').html(txt);
}
function get_latest_ticket(){
	var latest_ticket=$('.latest_ticket');
	$('body').css('display', 'block');
		//latest_ticket.empty();
		var target="#latest_ticket";
		var counter_no,token_no;
		
		
	//$('#sound').html("");
		
	$.ajax({
		url:'get_latest_ticket.php',
		type:'POST',
		dataType:'JSON',
		success: function(data){
		if(!data){
			$('#latest_ticket .counter_no').text('');
			$('#latest_ticket .token_no').text('');
		}
		//console.log(data);
		$.each(data,function(i,ticket){
			var counter_id=ticket.counter;
			counter_no=target+' .counter_no'; 
			token_no=target+' .token_no';
			//console.log(token_no);
			var ex_token=$(token_no).text();
			//alert(ex_token);
			//console.log(ex_token + ' . ' + ticket.ticket_no);
			if(ex_token !=ticket.ticket_no )	{
				//var tickets = $.cookies("tickets");
				//alert('tun tun');
				//console.log(ex_token + ' - ' + ticket.ticket_no);
				$(counter_no).text(ticket.counter);
				$(token_no).text(ticket.ticket_no);
				var sound_html = "<audio autoplay class='sound_file'>";
				sound_html += "<source src='magic.mp3' type='audio/mpeg'>";
				sound_html += "</audio>";
				//console.log(sound_html);
				//$('#sound').html(sound_html);
				//setTimeout(playSound, 1000, "<audio autoplay class='sound_file'><source src='magic.mp3' type='audio/mpeg'></audio>");
				//setTimeout(playSound, 7000, "<audio autoplay class='sound_file'><source src='audio/100.wav' type='audio/mpeg'></audio>");
				$('#latest_ticket li span').addClass('red');
				setTimeout(removeClass, 5000);
				//$(token_no).fadeIn(2000);
			}
			
			
			
			
			
			/*latest_ticket.append("<li class='sub_token latest_ticket latest_ticket_display' style='display:none'>"+
      "<div class='counter_label blue'><span class='counter_no'>"+ticket.counter+"</span>"+
	  "<span class='counter_text'>Counter</span></div>"+
      "<div class='token_label'><span class='token_no' id='latest_ticket'>"+ticket.ticket_no+"</span>"+
	  "<span class='token_text'>Latest Ticket Number</span></div>"+
    	"</li>"+
		"<audio autoplay class='sound_file'>"+
  		"<source src='magic.mp3' type='audio/mpeg'>"+
		"</audio>");
		$('.sub_token').fadeIn(900);*/
		});}
	});
}
</script>

<script>
//get_latest_called_ticket=setInterval(get_latest_ticket,3000);
</script>
<script>
$(function(){
setInterval(get_tokens, 2000);
setInterval(get_latest_ticket, 2000);
});
</script>

</body>
</html>
<?php /* ?><audio id="num-1" preload class="numbers"><source src="audio/numbers/1.wav" type="audio/mpeg" /></audio>
<audio id="num-2" preload class="numbers"><source src="audio/numbers/2.wav" type="audio/mpeg" /></audio>
<audio id="num-3" preload class="numbers"><source src="audio/numbers/3.wav" type="audio/mpeg" /></audio>
<audio id="num-4" preload class="numbers"><source src="audio/numbers/4.wav" type="audio/mpeg" /></audio>
<audio id="num-5" preload class="numbers"><source src="audio/numbers/5.wav" type="audio/mpeg" /></audio>
<audio id="num-6" preload class="numbers"><source src="audio/numbers/6.wav" type="audio/mpeg" /></audio>
<audio id="num-7" preload class="numbers"><source src="audio/numbers/7.wav" type="audio/mpeg" /></audio>
<audio id="num-8" preload class="numbers"><source src="audio/numbers/8.wav" type="audio/mpeg" /></audio>
<audio id="num-9" preload class="numbers"><source src="audio/numbers/9.wav" type="audio/mpeg" /></audio>
<audio id="num-10" preload class="numbers"><source src="audio/numbers/10.wav" type="audio/mpeg" /></audio>
<audio id="num-11" preload class="numbers"><source src="audio/numbers/11.wav" type="audio/mpeg" /></audio>
<audio id="num-12" preload class="numbers"><source src="audio/numbers/12.wav" type="audio/mpeg" /></audio>
<audio id="num-13" preload class="numbers"><source src="audio/numbers/13.wav" type="audio/mpeg" /></audio>
<audio id="num-14" preload class="numbers"><source src="audio/numbers/14.wav" type="audio/mpeg" /></audio>
<audio id="num-15" preload class="numbers"><source src="audio/numbers/15.wav" type="audio/mpeg" /></audio>
<audio id="num-16" preload class="numbers"><source src="audio/numbers/16.wav" type="audio/mpeg" /></audio>
<audio id="num-17" preload class="numbers"><source src="audio/numbers/17.wav" type="audio/mpeg" /></audio>
<audio id="num-18" preload class="numbers"><source src="audio/numbers/18.wav" type="audio/mpeg" /></audio>
<audio id="num-19" preload class="numbers"><source src="audio/numbers/19.wav" type="audio/mpeg" /></audio>
<audio id="num-20" preload class="numbers"><source src="audio/numbers/20.wav" type="audio/mpeg" /></audio>
<audio id="num-21" preload class="numbers"><source src="audio/numbers/21.wav" type="audio/mpeg" /></audio>
<audio id="num-22" preload class="numbers"><source src="audio/numbers/22.wav" type="audio/mpeg" /></audio>
<audio id="num-23" preload class="numbers"><source src="audio/numbers/23.wav" type="audio/mpeg" /></audio>
<audio id="num-24" preload class="numbers"><source src="audio/numbers/24.wav" type="audio/mpeg" /></audio>
<audio id="num-25" preload class="numbers"><source src="audio/numbers/25.wav" type="audio/mpeg" /></audio>
<audio id="num-26" preload class="numbers"><source src="audio/numbers/26.wav" type="audio/mpeg" /></audio>
<audio id="num-27" preload class="numbers"><source src="audio/numbers/27.wav" type="audio/mpeg" /></audio>
<audio id="num-28" preload class="numbers"><source src="audio/numbers/28.wav" type="audio/mpeg" /></audio>
<audio id="num-29" preload class="numbers"><source src="audio/numbers/29.wav" type="audio/mpeg" /></audio>
<audio id="num-30" preload class="numbers"><source src="audio/numbers/30.wav" type="audio/mpeg" /></audio>
<audio id="num-31" preload class="numbers"><source src="audio/numbers/31.wav" type="audio/mpeg" /></audio>
<audio id="num-32" preload class="numbers"><source src="audio/numbers/32.wav" type="audio/mpeg" /></audio>
<audio id="num-33" preload class="numbers"><source src="audio/numbers/33.wav" type="audio/mpeg" /></audio>
<audio id="num-34" preload class="numbers"><source src="audio/numbers/34.wav" type="audio/mpeg" /></audio>
<audio id="num-35" preload class="numbers"><source src="audio/numbers/35.wav" type="audio/mpeg" /></audio>
<audio id="num-36" preload class="numbers"><source src="audio/numbers/36.wav" type="audio/mpeg" /></audio>
<audio id="num-37" preload class="numbers"><source src="audio/numbers/37.wav" type="audio/mpeg" /></audio>
<audio id="num-38" preload class="numbers"><source src="audio/numbers/38.wav" type="audio/mpeg" /></audio>
<audio id="num-39" preload class="numbers"><source src="audio/numbers/39.wav" type="audio/mpeg" /></audio>
<audio id="num-40" preload class="numbers"><source src="audio/numbers/40.wav" type="audio/mpeg" /></audio>
<audio id="num-41" preload class="numbers"><source src="audio/numbers/41.wav" type="audio/mpeg" /></audio>
<audio id="num-42" preload class="numbers"><source src="audio/numbers/42.wav" type="audio/mpeg" /></audio>
<audio id="num-43" preload class="numbers"><source src="audio/numbers/43.wav" type="audio/mpeg" /></audio>
<audio id="num-44" preload class="numbers"><source src="audio/numbers/44.wav" type="audio/mpeg" /></audio>
<audio id="num-45" preload class="numbers"><source src="audio/numbers/45.wav" type="audio/mpeg" /></audio>
<audio id="num-46" preload class="numbers"><source src="audio/numbers/46.wav" type="audio/mpeg" /></audio>
<audio id="num-47" preload class="numbers"><source src="audio/numbers/47.wav" type="audio/mpeg" /></audio>
<audio id="num-48" preload class="numbers"><source src="audio/numbers/48.wav" type="audio/mpeg" /></audio>
<audio id="num-49" preload class="numbers"><source src="audio/numbers/49.wav" type="audio/mpeg" /></audio>
<audio id="num-50" preload class="numbers"><source src="audio/numbers/50.wav" type="audio/mpeg" /></audio>
<audio id="num-51" preload class="numbers"><source src="audio/numbers/51.wav" type="audio/mpeg" /></audio>
<audio id="num-52" preload class="numbers"><source src="audio/numbers/52.wav" type="audio/mpeg" /></audio>
<audio id="num-53" preload class="numbers"><source src="audio/numbers/53.wav" type="audio/mpeg" /></audio>
<audio id="num-54" preload class="numbers"><source src="audio/numbers/54.wav" type="audio/mpeg" /></audio>
<audio id="num-55" preload class="numbers"><source src="audio/numbers/55.wav" type="audio/mpeg" /></audio>
<audio id="num-56" preload class="numbers"><source src="audio/numbers/56.wav" type="audio/mpeg" /></audio>
<audio id="num-57" preload class="numbers"><source src="audio/numbers/57.wav" type="audio/mpeg" /></audio>
<audio id="num-58" preload class="numbers"><source src="audio/numbers/58.wav" type="audio/mpeg" /></audio>
<audio id="num-59" preload class="numbers"><source src="audio/numbers/59.wav" type="audio/mpeg" /></audio>
<audio id="num-60" preload class="numbers"><source src="audio/numbers/60.wav" type="audio/mpeg" /></audio>
<audio id="num-61" preload class="numbers"><source src="audio/numbers/61.wav" type="audio/mpeg" /></audio>
<audio id="num-62" preload class="numbers"><source src="audio/numbers/62.wav" type="audio/mpeg" /></audio>
<audio id="num-63" preload class="numbers"><source src="audio/numbers/63.wav" type="audio/mpeg" /></audio>
<audio id="num-64" preload class="numbers"><source src="audio/numbers/64.wav" type="audio/mpeg" /></audio>
<audio id="num-65" preload class="numbers"><source src="audio/numbers/65.wav" type="audio/mpeg" /></audio>
<audio id="num-66" preload class="numbers"><source src="audio/numbers/66.wav" type="audio/mpeg" /></audio>
<audio id="num-67" preload class="numbers"><source src="audio/numbers/67.wav" type="audio/mpeg" /></audio>
<audio id="num-68" preload class="numbers"><source src="audio/numbers/68.wav" type="audio/mpeg" /></audio>
<audio id="num-69" preload class="numbers"><source src="audio/numbers/69.wav" type="audio/mpeg" /></audio>
<audio id="num-70" preload class="numbers"><source src="audio/numbers/70.wav" type="audio/mpeg" /></audio>
<audio id="num-71" preload class="numbers"><source src="audio/numbers/71.wav" type="audio/mpeg" /></audio>
<audio id="num-72" preload class="numbers"><source src="audio/numbers/72.wav" type="audio/mpeg" /></audio>
<audio id="num-73" preload class="numbers"><source src="audio/numbers/73.wav" type="audio/mpeg" /></audio>
<audio id="num-74" preload class="numbers"><source src="audio/numbers/74.wav" type="audio/mpeg" /></audio>
<audio id="num-75" preload class="numbers"><source src="audio/numbers/75.wav" type="audio/mpeg" /></audio>
<audio id="num-76" preload class="numbers"><source src="audio/numbers/76.wav" type="audio/mpeg" /></audio>
<audio id="num-77" preload class="numbers"><source src="audio/numbers/77.wav" type="audio/mpeg" /></audio>
<audio id="num-78" preload class="numbers"><source src="audio/numbers/78.wav" type="audio/mpeg" /></audio>
<audio id="num-79" preload class="numbers"><source src="audio/numbers/79.wav" type="audio/mpeg" /></audio>
<audio id="num-80" preload class="numbers"><source src="audio/numbers/80.wav" type="audio/mpeg" /></audio>
<audio id="num-81" preload class="numbers"><source src="audio/numbers/81.wav" type="audio/mpeg" /></audio>
<audio id="num-82" preload class="numbers"><source src="audio/numbers/82.wav" type="audio/mpeg" /></audio>
<audio id="num-83" preload class="numbers"><source src="audio/numbers/83.wav" type="audio/mpeg" /></audio>
<audio id="num-84" preload class="numbers"><source src="audio/numbers/84.wav" type="audio/mpeg" /></audio>
<audio id="num-85" preload class="numbers"><source src="audio/numbers/85.wav" type="audio/mpeg" /></audio>
<audio id="num-86" preload class="numbers"><source src="audio/numbers/86.wav" type="audio/mpeg" /></audio>
<audio id="num-87" preload class="numbers"><source src="audio/numbers/87.wav" type="audio/mpeg" /></audio>
<audio id="num-88" preload class="numbers"><source src="audio/numbers/88.wav" type="audio/mpeg" /></audio>
<audio id="num-89" preload class="numbers"><source src="audio/numbers/89.wav" type="audio/mpeg" /></audio>
<audio id="num-90" preload class="numbers"><source src="audio/numbers/90.wav" type="audio/mpeg" /></audio>
<audio id="num-91" preload class="numbers"><source src="audio/numbers/91.wav" type="audio/mpeg" /></audio>
<audio id="num-92" preload class="numbers"><source src="audio/numbers/92.wav" type="audio/mpeg" /></audio>
<audio id="num-93" preload class="numbers"><source src="audio/numbers/93.wav" type="audio/mpeg" /></audio>
<audio id="num-94" preload class="numbers"><source src="audio/numbers/94.wav" type="audio/mpeg" /></audio>
<audio id="num-95" preload class="numbers"><source src="audio/numbers/95.wav" type="audio/mpeg" /></audio>
<audio id="num-96" preload class="numbers"><source src="audio/numbers/96.wav" type="audio/mpeg" /></audio>
<audio id="num-97" preload class="numbers"><source src="audio/numbers/97.wav" type="audio/mpeg" /></audio>
<audio id="num-98" preload class="numbers"><source src="audio/numbers/98.wav" type="audio/mpeg" /></audio>
<audio id="num-99" preload class="numbers"><source src="audio/numbers/99.wav" type="audio/mpeg" /></audio>
<audio id="num-100" preload class="numbers"><source src="audio/numbers/100.wav" type="audio/mpeg" /></audio>
<audio id="num-200" preload class="numbers"><source src="audio/numbers/200.wav" type="audio/mpeg" /></audio>
<audio id="num-300" preload class="numbers"><source src="audio/numbers/300.wav" type="audio/mpeg" /></audio>
<audio id="num-400" preload class="numbers"><source src="audio/numbers/400.wav" type="audio/mpeg" /></audio>
<audio id="num-500" preload class="numbers"><source src="audio/numbers/500.wav" type="audio/mpeg" /></audio>
<audio id="num-600" preload class="numbers"><source src="audio/numbers/600.wav" type="audio/mpeg" /></audio>
<audio id="num-700" preload class="numbers"><source src="audio/numbers/700.wav" type="audio/mpeg" /></audio>
<audio id="num-800" preload class="numbers"><source src="audio/numbers/800.wav" type="audio/mpeg" /></audio>
<audio id="num-900" preload class="numbers"><source src="audio/numbers/900.wav" type="audio/mpeg" /></audio>
<audio id="num-100" preload class="numbers"><source src="audio/numbers/100.wav" type="audio/mpeg" /></audio>
<audio id="num-hundred" preload class="numbers"><source src="audio/numbers/hundred.wav" type="audio/mpeg" /></audio>
<audio id="num-thousand" preload class="numbers"><source src="audio/numbers/thousand.wav" type="audio/mpeg" /></audio>
<audio id="num-million" preload class="numbers"><source src="audio/numbers/million.wav" type="audio/mpeg" /></audio><?php */ ?>