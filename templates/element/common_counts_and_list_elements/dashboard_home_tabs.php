<?php

use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;

?>

<div class="col-md-9 home_status_tabs">

<?php

//For MO Dashboard
if ($current_user_roles['mo_smo_inspection'] == 'yes' || $current_user_roles['ho_mo_smo'] == 'yes') {

	echo $this->Form->submit('Scrutiny', array('name'=>'scrutiny','class'=>'btn btn-default bg-info', 'id'=>'scrutiny_btn','label'=>false,'title'=>'Click to Check Status of Applications Scrutiny'));
}

// For IO Dashboard
if ($current_user_roles['io_inspection'] == 'yes') {

	echo $this->Form->submit('Inspection', array('name'=>'inspection','class'=>'btn btn-default bg-info', 'id'=>'inspection_btn','label'=>false,'title'=>'Click to Check Status of Site-inspection Reports'));
}

//For RO Dashboard
if ($current_user_roles['ro_inspection'] == 'yes') {

	echo $this->Form->submit('Regional Office', array('name'=>'regional_office','class'=>'btn btn-default bg-info dropdown-toggle dropdown-icon', 'id'=>'regional_office_btn','label'=>false,'title'=>'Click to Check Status of Applications/Reports, Which are Submitted to Reg. Office for Processing.'));
}

//For SO Dashboard
if ($current_user_roles['so_inspection'] == 'yes') {

	echo $this->Form->submit('Sub Office', array('name'=>'sub_office','class'=>'btn btn-default bg-info', 'id'=>'sub_office_btn','label'=>false,'title'=>'Click to Check Status of Applications/Reports, Which are Submitted to Sub Office for Processing.'));
}

//For HO Dashboard
if ($current_user_roles['dy_ama'] == 'yes' || $current_user_roles['jt_ama'] == 'yes' || $current_user_roles['ama'] == 'yes') {

	echo $this->Form->submit('HO Quality Control', array('name'=>'ho_quality_control','class'=>'btn btn-default bg-info', 'id'=>'hO_quality_control_btn','label'=>false,'title'=>'Click to Check Status of Applications, Which are Submitted to HO(QC) Office for Approval.'));
}

//For PAO/DDO Dashboard
if ($current_user_roles['pao'] == 'yes') {

	echo $this->Form->submit('PAO/DDO Office', array('name'=>'pao_ddo_office','class'=>'btn btn-default bg-info', 'id'=>'pao_ddo_office_btn','label'=>false,'title'=>'Click to Check Status of Applications Payment, to Confirm for Further Processing.'));
}

$user_role_arr = json_encode($current_user_roles);
?>


<div class="clear"></div>
</div>
