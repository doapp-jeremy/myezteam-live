<?php
class MailerComponent extends Object
{
  /**
   * PHPMailer object.
   *
   * @access private
   * @var object
   */
  var $m;

  /**
   * Creates the PHPMailer object and sets default values.
   * Must be called before working with the component!
   *
   * @access public
   * @return void
   */
  function init($from = 'admin@myezteam.com', $fromName = 'My EZ Team')
  {
    // Include the class file and create PHPMailer instance
    vendor('phpmailer/class.phpmailer');
    $this->m = new PHPMailer;

    $this->m->Mailer = "smtp";
    $this->m->Host = "ssl://smtp.gmail.com";
    $this->m->Port = 465;
    $this->m->SMTPAuth = true; // turn on SMTP authentication
    $this->m->Username = "myezteam@gmail.com"; // SMTP username
    $this->m->Password = "mit08rap"; // SMTP password
    
//    print_r($this->m);
//    exit();

    // Set default PHPMailer variables (see PHPMailer API for more info)
//    $this->From = 'admin@myezteam.com';
//    $this->FromName ='My EZ Team';
    $this->From = $from;
    $this->FromName = $fromName;
    // set more PHPMailer vars, for smtp etc.
  }

  function __set($name, $value)
  {
    $this->m->{$name} = $value;
  }

  function __get($name)
  {
    if (isset($this->m->{$name})) {
      return $this->m->{$name};
    }
  }
   
  function __call($method, $args)
  {
    if (method_exists($this->m, $method)) {
      return call_user_func_array(array($this->m, $method), $args);
    }
  }
}
?>