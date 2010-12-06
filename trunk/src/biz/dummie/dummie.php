<?php

class dummie
{
   //all of our biz classes should define these three variables
   var $_fullname;
   var $_bizname;
   var $_parent;
   
   //local
   var $loggedIn;
   var $userEmail;
   
   var $html;
    
   function __construct($data)
   {
      $this->_bizname = &$data["bizname"];
      $this->_fullname = &$data["fullname"];
      
      if(!isset($data["loggedIn"]))
      {
         $data["loggedIn"] = 0;
      }
      $this->loggedIn = &$data["loggedIn"];
      $this->userEmail = &$data["userEmail"];
      
   }
   
   function message($to, $message, $info)
   {
        if ($to != $this->_fullname)
        {
            //pass msg to childs
            return;
        }
      
      $this->show(0);
    }

    function broadcast($msg, $info)
    {
      switch($msg)
      {
         case 'login':
            $this->loggedIn = 1;
            $this->userEmail = $info["email"];
            $this->show(1);
            break;
         
         case 'logout':
            $this->loggedIn = 0;
            $this->show(1);
            break;
      }
      
        //pass to child bizes
        //no children
        
    }
   
   function show($echo)
   {

      
      
      if($this->loggedIn > 0)
      {
         $this->html .= "<h2>:) </h2> " . $this->userEmail;
      }
      else
      {
         $this->html .= "<h2>:(</h2>";
      }
      
      if($echo)
      {
         echo osShow($this);
      }
      else
      {
         return osShow($this);
      }
   }
   
}

?>