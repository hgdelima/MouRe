<?php

//DB Info : Specify what informaton this BIZ is going to use from DB

/*
 * might be possible to solve multiple biz-spec with a config file...
 */

//import bizes
$bizes = array('login', 'dummie', 'eblistviewer', 'ebframe');

$bizpath = '../biz/';
foreach($bizes as $bizname)
{
    $biz = $bizpath.$bizname."/".$bizname.".php";
    require_once($biz);
}

class eBoardPortal {

    //all of our biz classes should define these two variable
    var $_bizbankname;
	var $_fullname;

    /*     * **************************FIELDS*************************** */
    //CALSS FIELDS
    var $bizness_id;
    
    //FIELDS WHICH HAVE TYPE OF OTHER BIZES
    //var $login;
    //var $dummie;
    var $bizes = array('login' => 'login',
                       'eblist' => 'eblistviewer',
                       'd1' => 'dummie',
					   'frm'=> 'ebframe');
    var $myBizes = array();

    /*     * **************************CONSTRUCTOR*************************** */

    function __construct($fullname) {
        $this->_fullname = $fullname;
        
        foreach($this->bizes as $bizname=>$biz)
        {
            $this->myBizes[$bizname] = new $biz($bizname);
        }
        
        $this->bizness_id = 1;
    }

    /*     * **************************MESSAGE HANDELING*************************** */


    function message($to, $msg, $info) {
        if ($to == $this->_bizbankname) {
			// handle possible messages for this
			//show();
            return;
        }
        foreach($this->myBizes as $abiz)
        {
            $abiz->message(&$to, &$msg, &$info);
        }        
    }

    function broadcast($msg, $info)
    {
        foreach($this->myBizes as $abiz)
        {
            $abiz->broadcast(&$msg, &$info);
        }
    }

	function sleep(){
	}

    /*     * **************************HTML HANDELING*************************** */

    function show() {
    
    //print out the html head
    echo '<?xml version="1.0" encoding="UTF-8"?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
            <head>
   
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link href="../os/css/layout.css" rel="stylesheet" type="text/css" />
            <link href="../os/css/eBoardPortal.css" rel="stylesheet" type="text/css" />

            <script type="text/javascript" src="../os/js/bloop.js"></script>
            </head>
            <body>';

    //include the actual page content
    include 'eBoardPortal_view.php';
   
    //close html document
    echo '</body></html>';

    }

}

?>