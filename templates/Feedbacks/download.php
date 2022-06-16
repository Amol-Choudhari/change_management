<?php
//functionality to manage header and all table rows in excel sheet date:18/12/2017//
$list_status = array('Sr.No','Date','Feedback Type','Firstname','Lastname','Email','Mobile Number','Address','Comments');

 $this->CSV->addRow($list_status);
 $srno=1;
 foreach ($orders as $order)
 {      
	  $sr = $srno;
	  $a = $order['created'];   
	  
	  if($order['type'] != 'Other'){
	  $b = ucfirst(str_replace('_',' ',$order['type']));
	  }else{
	  $b = ucfirst(str_replace('_',' ',$order['other_type']));	  
	  }	
	  
	  $c = $order['first_name'];
	  $d = $order['last_name'];
	  $e = $order['email'];
	  $f = base64_decode($order['mobile_no']);
	  $g = $order['address'];
	  $h = $order['comments'];
	  $line = array($sr,$a,$b,$c,$d,$e,$f,$g,$h);
	  
	  $this->CSV->addRow($line);
	  $srno=$srno+1;
 }

 $filename='Feedbacks';
 echo  $this->CSV->render($filename);
 
?>