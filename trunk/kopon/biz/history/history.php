<?PHP

/*
	Compiled by bizLang compiler version 2.0 (March 4 2011) By Reza Moussavi
	1.1: {Family included}
	1.2: {flatten sleep session}
	1.3: {direct message sending}
	1.3.5: {sleep and decunstructed merged + _tmpNode_ added to fix a bug}
	1.4: {multi parameter in link message}
	1.5: {multi secName support: frm/frame, msg/messages,fun/function/phpfunction}
	2.0: {upload bothe biz and php directly to server (ready to use)}

	Author: Reza Moussavi
	Date:	02/16/2011
	Version: 1
	---------------------
	Author: Reza Moussavi
	Date:	02/07/2011
	Version: 0.1

*/
require_once '../biz/purchaseviewer/purchaseviewer.php';

class history {

	//Mandatory Variables for a biz
	var $_fullname;
	var $_curFrame;
	var $_tmpNode;
	var $_frmChanged;

	//Variables

	//Nodes (bizvars)
	var $purchases; // array of biz

	function __construct($fullname) {
		$this->_frmChanged=false;
		$this->_tmpNode=false;
		if($fullname==null){
			$fullname='_tmpNode_'.count($_SESSION['osNodes']);
			$this->_tmpNode=true;
		}
		$this->_fullname=$fullname;
		if(!isset($_SESSION['osNodes'][$fullname])){
			$_SESSION['osNodes'][$fullname]=array();
			//If any message need to be registered will placed here
		}

		$_SESSION['osNodes'][$fullname]['sleep']=false;
		//default frame if exists
		if(!isset($_SESSION['osNodes'][$fullname]['_curFrame']))
			$_SESSION['osNodes'][$fullname]['_curFrame']='frm';
		$this->_curFrame=&$_SESSION['osNodes'][$fullname]['_curFrame'];

		//handle arrays
		$this->purchases=array();
		if(!isset($_SESSION['osNodes'][$fullname]['purchases']))
			$_SESSION['osNodes'][$fullname]['purchases']=array();
		foreach($_SESSION['osNodes'][$fullname]['purchases'] as $arrfn)
			$this->purchases[]=new purchaseviewer($arrfn);

		if(!isset($_SESSION['osNodes'][$fullname]['biz']))
			$this->init(); //Customized Initializing
		$_SESSION['osNodes'][$fullname]['node']=$this;
		$_SESSION['osNodes'][$fullname]['biz']='history';
	}

	function gotoSleep() {
		$_SESSION['osNodes'][$this->_fullname]['purchases']=array();
		$_SESSION['osNodes'][$this->_fullname]['sleep']=true;
		foreach($this->purchases as $node){
			$_SESSION['osNodes'][$this->_fullname]['purchases'][]=$node->_fullname;
		}
		if($this->_tmpNode)
			unset($_SESSION['osNodes'][$this->_fullname]);
		else
			unset($_SESSION['osNodes'][$this->_fullname]['node']);
	}


	function message($message, $info) {
		switch($message){
			default:
				break;
		}
	}

	function _bookframe($frame){
		$this->_frmChanged=true;
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
		$html='<div '.$_style.' id="' . $this->_fullname . '">'.call_user_func(array($this, $this->_curFrame)).'</div>';
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


	function init(){
		$p=new purchase("");
		$pUIDs=$p->backAllUID();
		$this->purchases=array();
		$i=1;
		foreach($pUIDs as $pUID){
			$this->purchases[]=new purchaseviewer($this->_fullname.$i++);
			end($this->purchases)->bookUID($pUID);
		}
	}
	function frm(){
		$html=' -- History<hr />';
		foreach($this->purchases as $p){
			$pframe=$p->_backframe();
			$html.=<<<PHTMLCODE

				$pframe
			
PHTMLCODE;

		}
		return $html;
	}

}

?>