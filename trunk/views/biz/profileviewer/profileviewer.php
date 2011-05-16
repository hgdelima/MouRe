<?PHP

/*
	Compiled by bizLang compiler version 4.0 [JQuery] (May 5 2011) By Reza Moussavi

	Author:	Reza Moussavi
	Date:	5/6/2011
	Ver:		1.0

*/
require_once 'biz/user/user.php';
require_once 'biz/adlink/adlink.php';

class profileviewer {

	//Mandatory Variables for a biz
	var $_fullname;
	var $_curFrame;
	var $_tmpNode;

	//Variables
	var $message;

	//Nodes (bizvars)

	function __construct($fullname) {
		$this->_tmpNode=false;
		if($fullname==null){
			$fullname='_tmpNode_'.count($_SESSION['osNodes']);
			$this->_tmpNode=true;
		}
		$this->_fullname=$fullname;
		if(!isset($_SESSION['osNodes'][$fullname])){
			$_SESSION['osNodes'][$fullname]=array();
			//If any message need to be registered will placed here
			$_SESSION['osMsg']['frame_updateInfo'][$this->_fullname]=true;
			$_SESSION['osMsg']['frame_changePassword'][$this->_fullname]=true;
		}

		//default frame if exists
		if(!isset($_SESSION['osNodes'][$fullname]['_curFrame']))
			$_SESSION['osNodes'][$fullname]['_curFrame']='frm';
		$this->_curFrame=&$_SESSION['osNodes'][$fullname]['_curFrame'];

		if(!isset($_SESSION['osNodes'][$fullname]['message']))
			$_SESSION['osNodes'][$fullname]['message']="";
		$this->message=&$_SESSION['osNodes'][$fullname]['message'];

		$_SESSION['osNodes'][$fullname]['node']=$this;
		$_SESSION['osNodes'][$fullname]['biz']='profileviewer';
	}

	function gotoSleep() {
		if($this->_tmpNode)
			unset($_SESSION['osNodes'][$this->_fullname]);
		else
			unset($_SESSION['osNodes'][$this->_fullname]['node']);
	}


	function message($message, $info) {
		switch($message){
			case 'frame_updateInfo':
				$this->onUpdateInfo($info);
				break;
			case 'frame_changePassword':
				$this->onChangePassword($info);
				break;
			default:
				break;
		}
	}

	function _bookframe($frame){
		$this->_curFrame=$frame;
		//$this->show(true);
	}
	function _backframe(){
		return $this->show(false);
	}

	function show($echo){
		$_style='';
		switch($this->_curFrame){
			case 'frm':
				$_style='';
				break;
		}
		$html='<script type="text/javascript" language="Javascript">';
		$html.=<<<JAVASCRIPT

JAVASCRIPT;
		$html.=<<<JSONDOCREADY
function {$this->_fullname}(){}
JSONDOCREADY;
		$html.='</script>
<div '.$_style.' id="' . $this->_fullname . '">'.call_user_func(array($this, $this->_curFrame)).'</div>';
		if($_SESSION['silentmode'])
			return;
		if($echo)
			echo $html;
		else
			return $html;
	}


//########################################
//         YOUR FUNCTIONS GOES HERE
//########################################


	/*---------------------------------------------------------
	-
	------------------------------------------------------------*/
	function frm(){
		$user=osBackUser();
		$formName=$this->_fullname."ApplyBtn";
		$formPass=$this->_fullname."Pass";
		if(!osUserLogedin()){
			$html="Please Login First";
		}else{
			$al=new adlink("");
			if(!$paid=$al->backTotalPaid())
				$paid='0';
			$html=<<<PHTMLCODE

				{$this->message}
				<div style="float:left;">
					<form id="$formName" method="post" style="margin:10px;background-color:#FFCCFF;float:left;">
						<input type="hidden" name="_message" value="frame_updateInfo" /><input type = "hidden" name="_target" value="{$this->_fullname}" />
						Real Name: <input name="RealName" value="{$user['userName']}" size=20><br>
						Birth Date: <input name="BDate" value="{$user['BDate']}" size=20 ><br>
						Address: <br><textarea name="Address" rows=4 cols=30>{$user['Address']}</textarea><br>
						Password: <input name="Password" type="password" size=20><br>
						<div align=right><input type="button" value="Apply" onclick='Javascript:sndmsg("$formName")'></div>
					</form>
					<form id="$formPass" method="post" style="margin:10px;background-color:#CCFFFF;float:left;">
						<input type="hidden" name="_message" value="frame_changePassword" /><input type = "hidden" name="_target" value="{$this->_fullname}" />
						New Password: <input name="NewPass1" size=20 type="password"><br>
						Confirm : <input name="NewPass2" size=20 type="password"><br>
						Old Password: <input name="Password" type="password" size=20><br>
						<div align=right><input type="button" value="Change Password" onclick='Javascript:sndmsg("$formPass")'></div>
					</form>
				</div>
				<div style="float:left;margin:10px;background-color:#FFFFCC;">
					Balance: <br>
					Paid: $paid<br>
					Earned:
				</div>
			
PHTMLCODE;

		}
		$this->message="";
		return $html;
	}
	/*---------------------------------------------------------------
	-		Message Handlers
	---------------------------------------------------------------*/
	function onChangePassword($info){
		//check password match
		if($info['NewPass1']!=$info['NewPass2']){
			$this->message="<font color=red>Password and Confirm password dismatch! try again</font>";
			return;
		}
		//applypass
		$u=new user("");
		$this->message=($u->changePass($info['Password'],$info['NewPass1']))?"<b><center><font color=green>Password has been Changed!</font></center></b>":"<b><center><font color=red>ERROR! Password NOT Changed!</font></center></b>";
	}
	function onUpdateInfo($info){
		$curU=osBackUser();
		$info['email']=$curU['email'];
		$u=new user("");
		$u->bookInfo($info);
		$this->message="<center><b><font color=green>Update Successfully!</font></b></center>";
		$this->_bookframe("frm");
	}

}

?>