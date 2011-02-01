<?php
	date_default_timezone_set('GMT');
	require_once "../bizbank/eBoardPortal/eBoardPortal.php";
	$bizbank=NULL;
	if(isset($_GET['kill'])){
		$_SESSION['osNodes']=array();
		$bizbank=NULL;
	}

	if(!isset($_SESSION['osNodes'])){
		$_SESSION['osNodes']=array();
	}

	// if(! isset($_POST['_message'])){
		// $bizbank=new eBoardPortal("");
	// }
		$bizbank=new eBoardPortal("");

	if(!isset($_SESSION['user'])){
		$_SESSION['user']['UID']=-1;
		$_SESSION['user']['Email']='';
		$_SESSION['user']['Name']='';
	}

	function osBackLink($node,$curLink,$linkto){
		$ret="?";
		if(! isset($_SESSION['osLink']))
			$_SESSION['osLink']=array();
		$_SESSION['osLink'][$node]=$curLink;
		foreach($_SESSION['osLink'] as $n=>$v){
			if($ret!="?"){
				$ret.="&";
			}
			if($n==$node)
				$ret.=$n."=".$linkto;
			else
				$ret.=$n."=".$v;
		}
		return $ret;
	}

	function osRegMsg($fullname,$message){
		if(!isset($_SESSION['broadcast'][$message])){
			$_SESSION['broadcast'][$message]=array();
		}
		$_SESSION['broadcast'][$message][]=$fullname;
	}

	function osBookUser($user){
		$_SESSION['user']=$user;
	}

	function osBackUser(){
		return $_SESSION['user'];
	}

	function osBroadcast($msg,$info){
		foreach($_SESSION['osMsg'][$msg] as $node=>$v){
			osMessage($node,$msg,$info);
		}
	}
	function osMessage($to,$msg,$info){
		$node=$_SESSION['osNodes'][$to]['node'];
		if(!$node){
			$biz=$_SESSION['osNodes'][$to]['biz'];
			if($biz){
				$node=new $biz($to);
				$_SESSION['osNodes'][$to]['node']=$node;
			}
		}
		if($node)
			$node->message($msg,$info);
	}
	function osBackBizness(){
		return 1;
		global $bizbank;
		return $bizbank->bizness_id;
	}
	function osBackBizbank(){
		return 1;
		global $bizbank;
		return $bizbank->bizbank_id;
	}
	
	function osShow($callingBiz)
	{
		return '<div id="' . $callingBiz->_fullname . '">' . $callingBiz->html . '</div>';
	}

?>