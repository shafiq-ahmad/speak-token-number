<?php
defined('_MEXEC') or die ('Restricted Access');

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

function speak_num_text($index){
	//if(!$index){return false;}
	$data=array();
	$data[0]='zero';
	$data[1]='one';
	$data[2]='two';
	$data[3]='three';
	$data[4]='four';
	$data[5]='five';
	$data[6]='six';
	$data[7]='seven';
	$data[8]='eight';
	$data[9]='nine';
	$data[10]='ten';
	$data[11]='eleven';
	$data[12]='twelve';
	$data[13]='thirteen';
	$data[14]='fourteen';
	$data[15]='fifteen';
	$data[16]='sixteen';
	$data[17]='seventeen';
	$data[18]='eighteen';
	$data[19]='nineteen';
	$data[20]='twenty';
	$data[]='four';
	$data[]='four';
	return $data[$index];
}

function speak_number($number, $type, &$result=array()){
	$num_br = get_mili($number);
	$c=count($num_br)-1;
	$write=array();
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
//$rs = speak(1021);
//$spk = json_encode($rs);
