
<?php ?>
<!-- on 23-10-2017, Below noscript tag added to check if browser Scripting is working or not, if not provided steps --> 
<noscript>
    <?php echo $this->element('javascript_disable_msg_box'); ?>
</noscript>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php
      
    echo $this->Html->meta('icon');
    echo $this->Html->charset();
    
    echo $this->Html->css('custom-style');
    echo $this->Html->css('../site-home-page/layout/styles/layout');
    echo $this->Html->css('bootstrap.min');   
    echo $this->Html->css('document_checklist');
    echo $this->Html->script('jquery_main.min'); //newly added on 24-08-2020 updated js
    echo $this->Html->script('bootstrap.min');
    echo $this->Html->script('no_back');
    
 echo $this->html->css('customers/customer_information'); 
 echo $this->html->script('customers/customer_information'); 
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
  ?>
  
<title>Directorate of Marketing & Inspection</title>
</head>

<body id="top">
<header><?php echo $this->element('home-page-elements/home-page-header'); ?> </header>
  <div class="clear"></div>
<div class="container site-page">

  <form method="POST" enctype="form-data">
  <div class="row">
    <div class="col-sm-3">
      <input type="radio" id="primary" name="primary" class="box">
  <label for="primary">Primary</label>
    </div>
    <div class="col-sm-3">
     <input type="radio" id="firm" name="firm" class="box">
  <label for="firm">Firm ID</label>
    </div>
    
</div>
<div class="clearfix">&nbsp;</div>
<div class="row">
  <div class="col-sm">
  <input type="text" name="search_text" class="form-control serach-box box" placeholder="Enter ID for serach here." id="search_text">

</div>
<div class="col-sm">
  <input type="submit" value="search" name="search" class="btn btn-submit search-btn" id="search_btn">
  </div>
</div>
</form>
</div>
<?php echo $this->element('home-page-elements/home-page-footer'); ?>


</body>
</html>