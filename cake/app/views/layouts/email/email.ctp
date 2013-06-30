<?php // views/layouts/email/email.ctp

?>
<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  </head>
  <body>
  <?php echo $this->element('email/header'); ?>
  <?php echo $this->element('email/accountInfo'); ?>
  <?php
  echo $content_for_layout;
  ?>
  This email was sent on <b><?php echo date('l, F j, Y g:i:s a'); ?></b>
  </body>
</html>