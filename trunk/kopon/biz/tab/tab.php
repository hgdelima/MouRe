<?PHP

/*
	Compiled by bizLang compiler version 1.5 (Feb 21 2011) By Reza Moussavi
	1.1: {Family included}
	1.2: {flatten sleep session}
	1.3: {direct message sending}
	1.3.5: {sleep and decunstructed merged + _tmpNode_ added to fix a bug}
	1.4: {multi parameter in link message}
	1.5: {multi secName support: frm/frame, msg/messages,fun/function/phpfunction}

	Author: Reza Moussavi
	Date:	02/15/2011
	Version: 1
	---------------------
	Author: Reza Moussavi
	Date:	02/07/2011
	Version: 0.1

*/

class tab {

	//Mandatory Variables for a biz
	var $_fullname;
	var $_curFrame;
	var $_tmpNode;

	//Variables
	var $label;

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
			$_SESSION['osMsg']['client_selected'][$this->_fullname]=true;
		}

		$_SESSION['osNodes'][$fullname]['sleep']=false;
		//default frame if exists
		if(!isset($_SESSION['osNodes'][$fullname]['_curFrame']))
			$_SESSION['osNodes'][$fullname]['_curFrame']='frmNotSelected';
		$this->_curFrame=&$_SESSION['osNodes'][$fullname]['_curFrame'];

		if(!isset($_SESSION['osNodes'][$fullname]['label']))
			$_SESSION['osNodes'][$fullname]['label']='';
		$this->label=&$_SESSION['osNodes'][$fullname]['label'];

		$_SESSION['osNodes'][$fullname]['node']=$this;
		$_SESSION['osNodes'][$fullname]['biz']='tab';
	}

	function gotoSleep() {
		if($this->_tmpNode)
			unset($_SESSION['osNodes'][$this->_fullname]);
		else
			unset($_SESSION['osNodes'][$this->_fullname]['node']);
	}


	function message($message, $info) {
		switch($message){
			case 'client_selected':
				$this->onSelected($info);
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
		$html='<div id="' . $this->_fullname . '">'.call_user_func(array($this, $this->_curFrame)).'</div>';
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


	function onSelected($info){
		$this->bookSelected(true);
		$d=array("tabName"=>$this->label);
		osBroadcast("tab_tabChanged",$d);
	}
	function bookLabel($label){//String
		$this->label=$label;
		//$this->_bookframe("frmNotSelected");
	}
	function bookSelected($selected){//boolean
		if($selected){
			$this->_bookframe("frmSelected");
		}else{
			$this->_bookframe("frmNotSelected");
		}
	}
	function backLabel(){
		return $this->label;
	}
	function frmSelected(){
		osBackLink($this->_fullname,"selected","");
		$html=<<<PHTMLCODE

			[{$this->label}]
		
PHTMLCODE;

		return $html;
	}
	function frmNotSelected(){
		$link=osBackLink($this->_fullname,"","selected");
		$html=<<<PHTMLCODE

			<a href="$link">{$this->label}</a>
		
PHTMLCODE;

		return $html;
	}

}

?>