<?PHP

/*
	Compiled by bizLang compiler version 1.5 (Feb 21 2011) By Reza Moussavi
	1.1: {Family included}
	1.2: {flatten sleep session}
	1.3: {direct message sending}
	1.3.5: {sleep and decunstructed merged + _tmpNode_ added to fix a bug}
	1.4: {multi parameter in link message}
	1.5: {multi secName support: frm/frame, msg/messages,fun/function/phpfunction}

        Author: Max Mirkia
	Date:	2/14/2010
	Version: 1.0
        ------------------
	Author: Max Mirkia
	Date:	2/7/2010
	Version: 0.1

*/
require_once '../biz/product/product.php';
require_once '../biz/productviewer/productviewer.php';

class productlistviewer {

	//Mandatory Variables for a biz
	var $_fullname;
	var $_curFrame;
	var $_tmpNode;

	//Variables

	//Nodes (bizvars)
	var $productViewers; // array of biz

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
			$_SESSION['osMsg']['client_mode'][$this->_fullname]=true;
		}

		//default frame if exists
		if(!isset($_SESSION['osNodes'][$fullname]['_curFrame']))
			$_SESSION['osNodes'][$fullname]['_curFrame']='frmSmallMode';
		$this->_curFrame=&$_SESSION['osNodes'][$fullname]['_curFrame'];

		//handle arrays
		$this->productViewers=array();
		if(!isset($_SESSION['osNodes'][$fullname]['productViewers']))
			$_SESSION['osNodes'][$fullname]['productViewers']=array();
		foreach($_SESSION['osNodes'][$fullname]['productViewers'] as $arrfn)
			$this->productViewers[]=new productviewer($arrfn);

		$this->init(); //Customized Initializing
		$_SESSION['osNodes'][$fullname]['node']=$this;
		$_SESSION['osNodes'][$fullname]['biz']='productlistviewer';
	}

	function sleep(){
		$_SESSION['osNodes'][$this->_fullname]['slept']=true;
		$_SESSION['osNodes'][$this->_fullname]['productViewers']=array();
		foreach($this->productViewers as $node){
			$_SESSION['osNodes'][$this->_fullname]['productViewers'][]=$node->_fullname;
		}
	}

	function __destruct() {
		$_SESSION['osNodes'][$this->_fullname]['productViewers']=array();
		foreach($this->productViewers as $node){
			$_SESSION['osNodes'][$this->_fullname]['productViewers'][]=$node->_fullname;
		}
		if($this->_tmpNode)
			unset($_SESSION['osNodes'][$this->_fullname]);
		else
			unset($_SESSION['osNodes'][$this->_fullname]['node']);
	}


	function message($message, $info) {
		switch($message){
			case 'client_mode':
				$this->onMode($info);
				break;
			default:
				break;
		}
	}

	function _bookframe($frame){
		$this->_curFrame=$frame;
		$this->show(true);
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


	function init(){
		$product = array();
		$products = $product->backAllUID();
		$i=0;
		foreach($products as $p){
			$this->productViewers[] = new productViewer($this->_fullname.$i++);
			end($this->productViewers)->bookUID($p);
		}
	}
	function onMode($info){
		
	}
	
	function frmBigMode(){
		$html=<<<PHTMLCODE

			
		
PHTMLCODE;

		return $html;
	}
	
	function frmSmallMode(){
	
	}
	

}

?>