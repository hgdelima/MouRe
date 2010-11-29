<?php

//DB Info : Specify what informaton this BIZ is going to use from DB

require_once '../biz/login/login.php';
require_once '../biz/dummie/dummie.php';

class eBoardPortal {

    //all of our biz classes should define these two variable
    var $_bizbankname;

    /*     * **************************FIELDS*************************** */
    //CALSS FIELDS
    var $bizness_id;
    
    //FIELDS WHICH HAVE TYPE OF OTHER BIZES
    var $login;
    var $dummie;


    /*     * **************************CONSTRUCTOR*************************** */

    //function __construct($data){
    //SET CLASS FIELDS WITH $data
    //IF REQUIRED BIZES HAVE NOT BEEN USED,INITIALISE
    /* if(!(isset($data["bizVN"]))){
      $data[$bizVN][$_fullname] = (this->$_fullname."_".$bizVN);
      $data[$bizVN][$_bizname] = $_bizname;
      }
      this->$bizVN = new "$bizVN"(&$data[$bizVN]);
      } */



    //}
    function __construct($data) {
        $this->_bizbankname = "eBoardPortal";
        if (!(isset($data['login']))) {
            $data['login']['fullname'] = ($this->_bizbankname . "_" . "login"); //$this->user
            $data['login']['bizname'] = 'login';
            $data['login']['parent'] = $this;
        }
        
        if (!(isset($data['dummie']))) {
            $data['dummie']['fullname'] = ($this->_bizbankname . "_" . "dummie"); //$this->user
            $data['dummie']['bizname'] = 'dummie';
            $data['dummie']['parent'] = $this;
        }
        
        $this->login = new login(&$data['login']);
        $this->dummie = new dummie(&$data['dummie']);
        
        $this->bizness_id = 1;
    }

    /*     * **************************MESSAGE HANDELING*************************** */


    function message($to, $message, $info) {
        $this->login->message(&$to, &$message, &$info);
        if ($to != $this->_bizbankname) {
            return;
        }
        show();
        // handle possible messages for this
    }

    function broadcast($msg, $info) {
        $this->login->broadcast(&$msg, &$info);
        $this->dummie->broadcast(&$msg, &$info);
    }

    /*     * **************************HTML HANDELING*************************** */

    function show() {
    
    //print out the html head
    echo '<?xml version="1.0" encoding="UTF-8"?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
            <head>
   
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link href="../bizbank/eBoardPortal/layout.css" rel="stylesheet" type="text/css" />
            </head>';

    //include the actual page content
    include 'eBoardPortal_view.php';
   
    //close html document
    echo '</html>';

    }

}

?>