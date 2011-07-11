<?PHP

/*
	Compiled by bizLang compiler version 4.0 [JQuery] (May 5 2011) By Reza Moussavi

	Author: Reza Moussavi
	Date:	3/10/2010
	Version: 1.5
    ------------------
	Author: Max Mirkia
	Date:	2/14/2010
	Version: 1.0
    ------------------
    Author: Max Mirkia
	Date:	2/7/2010
	Version: 0.1

*/

class tabbank {

	//Mandatory Variables for a biz
	var $_fullname;
	var $_curFrame;
	var $_tmpNode;

	//Variables
	var $tabs;
	var $curTabName;

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
			$_SESSION['osMsg']['client_tab'][$this->_fullname]=true;
		}

		//default frame if exists
		if(!isset($_SESSION['osNodes'][$fullname]['_curFrame']))
			$_SESSION['osNodes'][$fullname]['_curFrame']='frm';
		$this->_curFrame=&$_SESSION['osNodes'][$fullname]['_curFrame'];

		if(!isset($_SESSION['osNodes'][$fullname]['tabs']))
			$_SESSION['osNodes'][$fullname]['tabs']='';
		$this->tabs=&$_SESSION['osNodes'][$fullname]['tabs'];

		if(!isset($_SESSION['osNodes'][$fullname]['curTabName']))
			$_SESSION['osNodes'][$fullname]['curTabName']='';
		$this->curTabName=&$_SESSION['osNodes'][$fullname]['curTabName'];

		$_SESSION['osNodes'][$fullname]['node']=$this;
		$_SESSION['osNodes'][$fullname]['biz']='tabbank';
	}

	function gotoSleep() {
		if($this->_tmpNode)
			unset($_SESSION['osNodes'][$this->_fullname]);
		else
			unset($_SESSION['osNodes'][$this->_fullname]['node']);
	}


	function message($message, $info) {
		switch($message){
			case 'client_tab':
				$this->onTabSelected($info);
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
				$_style=' class="pric_cont"  ';
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


    function bookContent($content){//String[]
        $this->tabs=array();
        foreach($content as $c){
            $this->tabs[]=$c;
        }
	}
	function bookSelected($sel){
		$this->curTabName=$sel;
		osBroadcast("tab_tabChanged",array("tabName"=>$sel));
    }
	/*
	*	This frm show all tabs (including selected one) as link
	*/
    function frm(){
		$html=<<<PHTMLCODE

                <ul id="menu_list">
		
PHTMLCODE;

		foreach($this->tabs as $t){
			$link=osBackPageLink($t);
			$html.=<<<PHTMLCODE

				<li><a href="{$link}">{$t}</a></li>
			
PHTMLCODE;

		}
		return $html."</ul>";
	}
    function onTabSelected($info){
		if(array_search($info["name"],$this->tabs)!==false){
			$this->bookSelected($info["name"]);
		}
	}

}

?>