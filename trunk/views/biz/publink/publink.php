<?PHP

/*
	Compiled by bizLang compiler version 4.0 [JQuery] (May 5 2011) By Reza Moussavi

	Author:	Reza Moussavi
	Date:	5/12/2011
	Ver:	0.1

*/

class publink {

	//Mandatory Variables for a biz
	var $_fullname;
	var $_curFrame;
	var $_tmpNode;

	//Variables

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
		}

		$_SESSION['osNodes'][$fullname]['node']=$this;
		$_SESSION['osNodes'][$fullname]['biz']='publink';
	}

	function gotoSleep() {
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
		$this->_curFrame=$frame;
		//$this->show(true);
	}
	function _backframe(){
		return $this->show(false);
	}

	function show($echo){
	}


//########################################
//         YOUR FUNCTIONS GOES HERE
//########################################


	function generateScript($adLinkData){
		$userID=osBackUserID();
		$code=0;
		if(osUserLogedin()){
			/* User is logedin */
			query("SELECT * FROM publink_info WHERE adLinkUID=".$adLinkData['adUID']." AND publisher=$userID");
			$code="!";
			if($row=fetch()){
				/* pubLink exist */
				$code=$row['pubUID'];
			}else{
				/* pubLink does not exist */
				$PPV=$adLinkData['AOPV']*$adLinkData['APRate'];
				$q="INSERT INTO publink_info(adLinkUID,YTID,publisher,totalView,AOPV,PPV) ";
				$q.="VALUES('".$adLinkData['adUID']."','".$adLinkData['videoCode']."','$userID','0','".$adLinkData['AOPV']."','$PPV')";
				query($q);
				$code=mysql_insert_id();
			}
		}
		return $code;
	}
	function backStat($adUID){
		$data=array();
		query("SELECT countryCode,countryName,views FROM publink_stat WHERE adUID=$adUID ORDER BY views DESC");
		while($row=fetch()){
			$data[]=$row;
		}
		return $data;
	}
	function backEarned($UID){
		$earned=0;
		query("SELECT SUM(PPV * totalView) as earned FROM publink_info WHERE publisher=".$UID);
		if($row=fetch()){
			$earned=$row['earned'];
		}
		return $earned;
	}

}

?>