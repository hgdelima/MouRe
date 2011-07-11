<?PHP

/*
	Compiled by bizLang compiler version 4.0 [JQuery] (May 5 2011) By Reza Moussavi

	Author:	Reza Moussavi
	Date:	6/13/2011
	Ver:	1.0
	----------------------
	Author:	Reza Moussavi
	Date:	5/1/2011
	Ver:	0.1

*/
require_once 'biz/scriptviewer/scriptviewer.php';
require_once 'biz/ipviewer/ipviewer.php';

class videobar {

	//Mandatory Variables for a biz
	var $_fullname;
	var $_curFrame;
	var $_tmpNode;

	//Variables
	var $data;
	var $showScript;
	var $showStat;

	//Nodes (bizvars)
	var $scv;

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
			$_SESSION['osMsg']['frame_getLink'][$this->_fullname]=true;
			$_SESSION['osMsg']['frame_StatBtn'][$this->_fullname]=true;
			$_SESSION['osMsg']['user_login'][$this->_fullname]=true;
			$_SESSION['osMsg']['user_logout'][$this->_fullname]=true;
		}

		//default frame if exists
		if(!isset($_SESSION['osNodes'][$fullname]['_curFrame']))
			$_SESSION['osNodes'][$fullname]['_curFrame']='frm';
		$this->_curFrame=&$_SESSION['osNodes'][$fullname]['_curFrame'];

		$this->scv=new scriptviewer($this->_fullname.'_scv');

		if(!isset($_SESSION['osNodes'][$fullname]['data']))
			$_SESSION['osNodes'][$fullname]['data']='';
		$this->data=&$_SESSION['osNodes'][$fullname]['data'];

		if(!isset($_SESSION['osNodes'][$fullname]['showScript']))
			$_SESSION['osNodes'][$fullname]['showScript']=false;
		$this->showScript=&$_SESSION['osNodes'][$fullname]['showScript'];

		if(!isset($_SESSION['osNodes'][$fullname]['showStat']))
			$_SESSION['osNodes'][$fullname]['showStat']=false;
		$this->showStat=&$_SESSION['osNodes'][$fullname]['showStat'];

		$_SESSION['osNodes'][$fullname]['node']=$this;
		$_SESSION['osNodes'][$fullname]['biz']='videobar';
	}

	function gotoSleep() {
		if($this->_tmpNode)
			unset($_SESSION['osNodes'][$this->_fullname]);
		else
			unset($_SESSION['osNodes'][$this->_fullname]['node']);
	}


	function message($message, $info) {
		switch($message){
			case 'frame_getLink':
				$this->onGetLink($info);
				break;
			case 'frame_StatBtn':
				$this->onStatBtn($info);
				break;
			case 'user_login':
				$this->onLoginStatusChanged($info);
				break;
			case 'user_logout':
				$this->onLoginStatusChanged($info);
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
			case 'frmToPublish':
				$_style='';
				break;
			case 'frmMyAd':
				$_style='';
				break;
			case 'frmMyPub':
				$_style='';
				break;
			case 'frmShort':
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


	/******************************************
	*		Frames
	******************************************/
	function frm(){
		return "[ Video Bar! ]";
	}
	function frmToPublish(){
		$frmName=$this->_fullname.$this->data['adUID'];
		$scv=($this->showScript)?$this->scv->_backframe():" ";
		$btnCaption="Get Link&#13;&#10;to&#13;&#10;Publish";
		$EPV=$this->data['AOPV']*$this->data['APRate'];
		$Title=htmlspecialchars($this->data['title'], ENT_QUOTES);
		return <<<PHTMLCODE

		<div style="width:650px;height:120;">
			<div style="float:left;height:120px;width:150px;text-align:left;">
				<a href="{$this->data['link']}" target="_blank">
					<img src="{$this->data['img']}" />
				</a>
				<br /><font size=2>viewd {$this->data['viewed']} of {$this->data['maxViews']}</font>
			</div>
			<div style="float:left;height:120px;width:400px;align:right;">
				{$Title}<br />
				Last Date : {$this->data['lastDate']}
				<br />Earn/View : {$EPV}
			</div>
			<div style="float:left;height:120px;width:100px;text-align:right;">
				<form id="$frmName" method="post" style="display:inline;">
					<input type="button" value="$btnCaption" style="height:90px;width:90px;text-align:center;" onclick='JavaScript:sndmsg("$frmName")'/>
					<input type="hidden" name="_message" value="frame_getLink" /><input type = "hidden" name="_target" value="{$this->_fullname}" />
				</form>
			</div>
		</div>
			$scv
		
PHTMLCODE;

	}
	function frmMyPub(){
		$frmName=$this->_fullname.$this->data['adUID'];
		$AOPV=$this->data['AOPV'];
		$APRate=$this->data['APRate'];
		$EPV=$AOPV*$APRate;
		$VN=$this->data['viewed'];
		$RM=$this->data['maxViews']-$VN;
		$SD=$this->data['startDate'];
		$LD=$this->data['lastDate'];
		$UVN=$this->data['totalView'];
		$UE=$UVN*$EPV;
		return <<<PHTMLCODE

		<div style="width:650px;height:100;">
			<div style="float:left;height:100px;width:150px;text-align:left;">
				<a href="{$this->data['link']}" target="_blank">
					<img src="{$this->data['img']}" />
				</a>
			</div>
			<div style="float:left;height:100px;width:300px;align:right;">
				<u>{$this->data['title']}</u><br />
				Earn Per View : {$EPV}<br />
				Viewed: $VN<br />
				Remaining: $RM<br />
				StartDate:$SD - Last Date : $LD
			</div>
			<div style="float:left;height:75px;width:150px;text-align:left;border:1px solid #FFFFFF;margin:5px;padding:5px;background-color:#E0E0FF;">
				Viewed by you: $UVN<br />
				You earned: $UE<br />
			</div>
		</div>
		
PHTMLCODE;

	}
	function frmShort(){
		$Title=htmlspecialchars($this->data['title'], ENT_QUOTES);
		return <<<PHTMLCODE

		<div style="width:650px;height:120;">
			<div style="float:left;height:120px;width:150px;text-align:left;">
				<a href="{$this->data['link']}" target="_blank">
					<img src="{$this->data['img']}" />
				</a>
			</div>
			<div style="float:left;height:120px;width:400px;align:right;">
				Title : {$Title}
			</div>
		</div>
		
PHTMLCODE;

	}
	function frmMyAd(){
		$Title=$this->data['title'];
		$AOPV=$this->data['AOPV'];
		$VN=$this->data['viewed'];
		$TV=$this->data['maxViews'];
		$RM=$TV-$VN;
		$StatisticsBtn=$this->showStat?"[-]":"[+]";
		$StatisticsBtn.=" Stats";
		$frmStatName=$this->_fullname."Stat";
		$statFrm=$this->backStatFrame();
		$stopBtn=$this->frmStopBtn();
		return <<<PHTMLCODE

		<div style="width:650px;height:125;">
			<div style="float:left;height:120px;width:150px;text-align:left;">
				<a href="{$this->data['link']}" target="_blank">
					<img src="{$this->data['img']}" />
				</a><br />
				<form id="$frmStatName" method="post">
					<input type="hidden" name="_message" value="frame_StatBtn" /><input type = "hidden" name="_target" value="{$this->_fullname}" />
					<input type="button" value="$StatisticsBtn" onclick="javascript:sndmsg('$frmStatName')" style="border:0px;cursor:pointer;background-color:transparent;" />
				</form>
			</div>
			<div style="float:left;height:100px;width:400px;align:right;">
				<u>$Title</u>
				<br />Total Paid: {$this->data['paid']} - Pay Per View : {$AOPV}
				<br />Viewed: $VN
				<br />Remaining: $RM
				<br />Total: $TV
				<br />StartDate:{$this->data['startDate']} - Last Date : {$this->data['lastDate']}
			</div>
			<div style="float:left;height:100px;width:100px;text-align:right;">
				$stopBtn
			</div><br />
		</div>
		<div style="float:left;">$statFrm</div>
		<br />
		
PHTMLCODE;

	}
	function frmStopBtn(){
		$html="";
		$frmName=$this->_fullname.$this->data['adUID'];
		/*
		*	Has been Stoped
		*/
		if($this->data['running']==0){
			$html= <<<PHTMLCODE

				<span style="height:90px;width:90px;text-align:center;">
					Has been Stop!
				</span>
			
PHTMLCODE;

		}
		/*
		*	Not yet to be able to stop
		*
		* Today-minCancelTime>startDate
		*/
		elseif(date("Y/m/d",mktime(0,0,0,date("m"),date("d") - $this->data['minCancelTime'],date("Y"))) < $this->data['startDate']){
			$month=substr($this->data['startDate'],5,2);
			$day=substr($this->data['startDate'],8,2);
			$year=substr($this->data['startDate'],0,4);
			$stopDate=date("Y/m/d",mktime(0,0,0,$month,$day + $this->data['minCancelTime'],$year));
			$html= <<<PHTMLCODE

				<span style="height:90px;width:90px;text-align:center;">
					Cannot stop till $stopDate
				</span>
			
PHTMLCODE;

		}
		/*
		*	Show Stop
		*/
		else{
			$html= <<<PHTMLCODE

				<form id="$frmName" method="post" style="display:inline;">
					<input type="button" value="STOP" style="height:90px;width:90px;text-align:center;" onclick='JavaScript:sndmsg("$frmName")'/>
					<input type="hidden" name="_message" value="frame_stop" /><input type = "hidden" name="_target" value="{$this->_fullname}" />
				</form>
			
PHTMLCODE;

		}
		return $html;
	}
	function backStatFrame(){
		if(!$this->showStat)
			return "";
		$ipv=new ipviewer("");
		$ipv->bookInfo($this->data['adUID'],$this->data['title']);
		return $ipv->_backframe();
	}
	/******************************************
	*		Message Handlers
	******************************************/
	function onGetLink($info){
		$this->showScript=!$this->showScript;
		$this->_bookframe($this->_curFrame);
	}
	function onLoginStatusChanged($info){
		$this->generateScript();
	}
	function onStatBtn($info){
		$this->showStat=!$this->showStat;
		$this->_bookframe($this->_curFrame);
	}
	/******************************************
	*		Functionalities
	******************************************/
	function bookMode($mode){
		switch($mode){
			case "topublish":
				$this->_bookframe("frmToPublish");
				break;
			case "myad":
				$this->_bookframe("frmMyAd");
				break;
			case "mypub":
				$this->_bookframe("frmMyPub");
				break;
			case "short":
				$this->_bookframe("frmShort");
				break;
			default:
				$this->_bookframe("frm");
		}
	}
	function bookInfo($data){
		$this->data=$data;
		$this->generateScript();
	}
	function generateScript(){
		$this->scv->generateScript($this->data);
	}

}

?>