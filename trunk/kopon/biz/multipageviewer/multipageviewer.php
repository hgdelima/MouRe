<?PHP

/*
	Compiled by bizLang compiler version 3.2 [DB added] (March 26 2011) By Reza Moussavi

	Author: Max Mirkia
	Date:	2/21/2010
	Version: 1.0
	------------------
	Author: Max Mirkia
	Date:	2/7/2010
	Version: 0.1

*/
require_once 'biz/productviewer/productviewer.php';
require_once 'biz/productlistviewer/productlistviewer.php';
require_once 'biz/adminpanel/adminpanel.php';
require_once 'biz/userpanel/userpanel.php';

class multipageviewer {

	//Mandatory Variables for a biz
	var $_fullname;
	var $_curFrame;
	var $_tmpNode;
	var $_frmChanged;

	//Variables

	//Nodes (bizvars)
	var $productviewer;
	var $productlistviewer;
	var $adminpanel;
	var $userpanel;

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
			$_SESSION['osMsg']['tab_tabChanged'][$this->_fullname]=true;
			$_SESSION['osMsg']['client_page'][$this->_fullname]=true;
		}

		$_SESSION['osNodes'][$fullname]['sleep']=false;
		//default frame if exists
		if(!isset($_SESSION['osNodes'][$fullname]['_curFrame']))
			$_SESSION['osNodes'][$fullname]['_curFrame']='frmMain';
		$this->_curFrame=&$_SESSION['osNodes'][$fullname]['_curFrame'];

		$this->productviewer=new productviewer($this->_fullname.'_productviewer');

		$this->productlistviewer=new productlistviewer($this->_fullname.'_productlistviewer');

		$this->adminpanel=new adminpanel($this->_fullname.'_adminpanel');

		$this->userpanel=new userpanel($this->_fullname.'_userpanel');

		if(!isset($_SESSION['osNodes'][$fullname]['biz']))
			$this->init(); //Customized Initializing
		$_SESSION['osNodes'][$fullname]['node']=$this;
		$_SESSION['osNodes'][$fullname]['biz']='multipageviewer';
	}

	function gotoSleep() {
		if($this->_tmpNode)
			unset($_SESSION['osNodes'][$this->_fullname]);
		else
			unset($_SESSION['osNodes'][$this->_fullname]['node']);
	}


	function message($message, $info) {
		switch($message){
			case 'tab_tabChanged':
				$this->onTabChanged($info);
				break;
			case 'client_page':
				$this->onPage($info);
				break;
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
			case 'frmMain':
				$_style='';
				break;
			case 'frmPrevious':
				$_style='';
				break;
			case 'frmHow':
				$_style='';
				break;
			case 'frmMyAcc':
				$_style='';
				break;
			case 'frmCPanel':
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
		$this->productviewer->bookProductUID(0);
	}
	function onTabChanged($info){
		$this->_bookframe("frm".$info['tabName']);
	}
	function onPage($info){
	}
	function frmMain(){
		$toShow = $this->productviewer->_backframe();
		$html=<<<PHTMLCODE

			$toShow
		
PHTMLCODE;

		return $html;
	}
	function frmPrevious(){
		$toShow = $this->productlistviewer->_backframe();
		$html=<<<PHTMLCODE

			$toShow
		
PHTMLCODE;

		return $html;
	}
	function frmHow(){
		$html=<<<PHTMLCODE

			How to 
		
PHTMLCODE;

		return $html;
	}
	function frmCPanle(){
		$toShow = $this->adminpanel->_backframe();
		$html=<<<PHTMLCODE

			$toShow
		
PHTMLCODE;

		return $html;
	}
	function frmMyAcc(){
		$toShow = $this->userpanel->_backframe();
		$html=<<<PHTMLCODE

 			$toShow				
		
PHTMLCODE;

		return $html;
	}

}

?>