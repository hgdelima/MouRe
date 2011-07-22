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
require_once 'biz/adlink/adlink.php';

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
			$_SESSION['osMsg']['frame_stopBtn'][$this->_fullname]=true;
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
			case 'frame_stopBtn':
				$this->onStopBtn($info);
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
				$_style=' ';
				break;
			case 'frmToPublish':
				$_style=' class="video_box"  ';
				break;
			case 'frmMyAd':
				$_style=' ';
				break;
			case 'frmMyPub':
				$_style=' ';
				break;
			case 'frmShort':
				$_style=' ';
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


	/******************************************
	*		Frames
	******************************************/
	function frm(){
		return "[ Video Bar! ]";
	}
	function frmToPublish(){
		$frmName=$this->_fullname.$this->data['adUID'];
		$scv=($this->showScript)?$this->scv->_backframe():" ";
		$EPV=$this->data['AOPV']*$this->data['APRate'];
		$Title=htmlspecialchars($this->data['title'], ENT_QUOTES);
		$lastDate=strlen($this->data['lastDate']." ")>3?"Last Date : ".$this->data['lastDate']:"";
		$country="";
		if(strlen($this->data['country'])>3)
			$country="<br />only in ".$this->data['country'];
		return <<<PHTMLCODE

			<div class="box_close">
				<div class="box_image">
					<a href="http://www.sam-rad.com/watch?v={$this->data['videoCode']}" target="_blank">
						<img class="video_image" src="{$this->data['img']}" />
					</a>
				</div>
				<div class="other_info_container">
					<div class="video_header">
						<div class="video_title">{$Title}</div>
						<div class="details_btn_div">
							<form id="$frmName" method="post">
								<input class="getlink_btn" type="button" value="Get link to publish" onclick='JavaScript:sndmsg("$frmName")'/>
								<input type="hidden" name="_message" value="frame_getLink" /><input type = "hidden" name="_target" value="{$this->_fullname}" />
							</form>
						</div>
					</div>
					<div class="other_n_embed">
						<div class="video_other_info" >
							<div class="video_details">
								Earn/View : $EPV $ $country
							</div>
							<div class="video_details">
								viewed {$this->data['viewed']} of {$this->data['maxViews']}
							</div>
							<div class="video_details">
								$lastDate
							</div>
        	    		</div>
						$scv
        	    	</div>
        	    </div>
			</div>
		
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
		$country=$this->data['country'];
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
				Earn Per View (in $country only): {$EPV}<br />
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
		*	Has been Removed
		*/
		if($this->data['running']==0){
			$html= <<<PHTMLCODE

				<span style="height:90px;width:90px;text-align:center;">
					Has been Removed!
				</span>
			
PHTMLCODE;

		}
		/*
		*	Has been Stoped
		*/
		if($this->data['running']==-1){
			$html= <<<PHTMLCODE

				<span style="height:90px;width:90px;text-align:center;">
					Will be stop at {$this->data['lastDate']}
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
			$frmName1=$frmName."1";
			$html= <<<PHTMLCODE

				<input id="stopBtn1st" type="button" value="STOP" style="height:90px;width:90px;text-align:center;" onclick='document.getElementById("stopBtn1st").style.display="none";document.getElementById("confirmForm").style.display="inline"'/>
				<span id="confirmForm" style="display:none;">
					<form id="$frmName" method="post" style="display:inline;">
						<font size=1px>this video will stop in {$this->data['minLifeTime']} days. Are you sure you want to stop rocketing the views?</font>
						<input type="button" value="yes" style="height:30px;width:40px;text-align:center;" onclick='JavaScript:sndmsg("$frmName")'/>
						<input type="hidden" name="_message" value="frame_stopBtn" /><input type = "hidden" name="_target" value="{$this->_fullname}" />
					</form>
					<form id="$frmName1" method="post" style="display:inline;">
						<input type="button" value="no" style="height:30px;width:40px;text-align:center;" onclick='JavaScript:sndmsg("$frmName1")'/>
					</form>
				</span>
			
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
	function onStopBtn($info){
		$al=new adlink("");
		if($al->stop($this->data['adUID'])){
			$this->data=$al->backLinkByID($this->data['adUID']);
		}
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