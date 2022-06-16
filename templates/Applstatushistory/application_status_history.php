<?php
?>
<style>
#mainDiv .report-filter div { margin-top: 0px; }

#mainDiv .report-filter label {
    margin-left: 0%;
    width: 100%;
    font-size: 14px;
    margin-bottom: -4px !important;
}

.color_div{
			width: 35px;
			height: 12px;
			display: inline-block;
			margin-right : 5px;
		}
.color_text {
			color:black;
			font-size: 14px;
			font-weight: bold;
		}
#mainDiv .report-back-button { margin-top: 0px !important; }
#search_field .ro_report-filter input[type="submit"] { width: 74px; }

	.oval {
		height: 70px;
		width: 125px;
		background-color: #555;
		border-radius: 50%;
		color : white;
		font-size : 14px;
		font-weight : bold;
		text-align : center;
		padding-top : 22px;
		margin-top: 8px;
		margin-bottom: 5px;
		z-index: 1;
		border: 2px solid #ffff00;
	}

	.pointer{
		width: 119px;
		height: 60px;
		background : #555;
		position: relative;
		text-align : center;
		padding-top : 18px;
		color : white;
		font-size : 14px;
		font-weight : bold;
		margin-top: 8px;
		margin-bottom: 5px;
		border-radius: 10px;
		border: 2px solid #ffff00;
	}

	.pointer-arrow-right{
		width: 37px;
		height: 10px;
		background:#555;
		position: relative;
		color:white;
		font-size: 16px;
		font-weight: bold;
	}

	.pointer-arrow-right:before{
		content: "";
		position: absolute;
		right: 33px;
		bottom: -13px;
		width: 0;
		height: 0;
		border-right: 16px solid #555;
		border-top: 18px solid transparent;
		border-bottom: 18px solid
		transparent;
	}

	.pointer-arrow-right:after{
		content: "";
		position: absolute;
		left: 33px;
		bottom: -13px;
		width: 0;
		height: 0;
		border-left: 16px solid #555;
		border-top: 18px solid transparent;
		border-bottom: 18px solid
		transparent;
	}

	.up-arrow{
		display : inline-block;
		position : relative;
		background : #555;
		padding : 10px 0px;
		width : 10px;
		height : 30px;
		text-align : center;
		margin-left: 53px;
		margin-top: 10px;
	}

	.up-arrow:after{
		content : "";
		display : block;
		position :  absolute;
		left : -13;
		top: -11px;
		bottom : 100%;
		width : 0;
		height : 0;
		border-left : 18px solid transparent;
		border-right : 18px solid transparent;
		border-bottom : 16px solid #555;
	}

	.down-arrow{
		display : inline-block;
		position : relative;
		background : #555;
		padding : 10px 0px;
		width : 10px;
		height : 30px;
		text-align : center;
		margin-left: -14px;
	}

	.down-arrow:after{
		content : "";
		display : block;
		position :  absolute;
		left : -13;
		top : 83%;
		width : 0;
		height : 0;
		border-left : 18px solid transparent;
		border-right : 18px solid transparent;
		border-top : 16px solid #555;
	}

	.applicant-cls{ min-height: 332px; background: #ffe6cc; }
	.ro-cls{ width: 14.666667%;min-height: 332px;background: #cfdadb; }
	.ho-cls{ min-height: 332px;background: #ffcccc; }

	#mainDiv .col-md-4 { padding: 0px;}
	#mainDiv .col-md-3 { padding: 0px;}
	#mainDiv .col-md-2 { padding: 0px}
	#mainDiv .col-md-5 { padding: 0px}
	#mainDiv .col-md-6 { padding: 0px}

	#applicant_pointer { background:#c0504d; }
	#applicant .pointer-arrow-right{ background:#9bbb59; }
	#applicant .pointer-arrow-right:after{ border-left: 0px; }
	#applicant .pointer-arrow-right:before{ border-right: 0px; }

	#applicant #fd-up-dr{ background:#9bbb59; }
	#applicant #fd-down-dr{ background:#9bbb59; }
	#applicant #fd-up-dr:after{ border-bottom: 0px; }
	#applicant #fd-down-dr:after{ border-top: 0px; }


	#toapplicant .pointer-arrow-right:after{ border-left: 0px; }
	#toapplicant .pointer-arrow-right:before{ border-right: 0px; }
	#toro .pointer-arrow-right:after{ border-left: 0px; }
	#toro .pointer-arrow-right:before{ border-right: 0px; }
	#todyama .pointer-arrow-right:after{ border-left: 0px; }
	#todyama .pointer-arrow-right:before{ border-right: 0px; }
	#tojtama .pointer-arrow-right:after{ border-left: 0px; }
	#tojtama .pointer-arrow-right:before{ border-right: 0px; }
	#toama .pointer-arrow-right:after{ border-left: 0px; }
	#toama .pointer-arrow-right:before{ border-right: 0px; }

	#ddo-up-dr:after{ border-bottom: 0px; }
	#ddo-down-dr:after{ border-top: 0px; }
	#level_1_up_dr:after{ border-bottom: 0px; }
	#level_1_down_dr:after{ border-top: 0px; }
	#ins-up-dr:after{ border-bottom: 0px; }
	#ins-down-dr:after{ border-top: 0px; }
	#dy_ama-up-dr:after{ border-bottom: 0px; }
	#ho_mo_smo-down-dr:after{ border-top: 0px; }
	#jat-up-dr:after{ border-bottom: 0px; }
	#jat-down-dr:after{ border-top: 0px; }

	.popover {max-width:400px;}

	.popover-content {
		padding: 9px 14px;
		width: 320px;
	}

    .mtminus11{margin-top: -11px;}
    .lh31h77b428bcacWhite{line-height: 31px; height: 77px; background: #428bca; color: white;}
    .b747474cfff{background:#747474; color:#fff;}
    .lh31b5e7580{line-height: 31px; background: #5e7580;}
    .taleft{text-align:left}
    .pr0{padding-right:0px;}
    .pt4{padding-top: 4px;}
    .tacml60{text-align:center;margin-left: 60px; color: tomato;}
    .tact{text-align:center;color: tomato;}
    .ml60{margin-left: 60px;}
    .p0mt120{padding: 0px; margin-top: 120px;}
</style>

<?php echo $this->Form->create('application_journey', array('type'=>'file', 'url'=>'firms_list', 'enctype'=>'multipart/form-data', 'id'=>'application_journey')); ?>

<div id="search_field mtminus11">
<div class="panel-heading lh31h77b428bcacWhite">
<div id="search_by_options" class="col-md-12">
	<div class="report-filter ro_report-filter " class="col-md-12">
		<div class="col-md-3">
			<label>Application Type</label>
			<?php echo $this->form->input('application_type', array('type'=>'select', 'options'=>$certificate_type, 'label'=>false, 'id'=>'application_type', 'empty'=>'All', 'escape'=>false, 'class'=>'search_field')); ?>
		</div>

		<div class="col-md-3">
			<label>RO/SO Office</label>
			<?php echo $this->form->input('office_type', array('type'=>'select', 'options'=>$ro_office_list, 'label'=>false, 'id'=>'office_type', 'empty'=>'All', 'escape'=>false, 'class'=>'search_field')); ?>
		</div>

		<div class="col-md-2" id="office_all">
			<label>Status</label>
			<?php echo $this->form->input('result_for', array('type'=>'select', 'options'=>$result_for, 'label'=>false, 'id'=>'result_for',  'empty'=>'All', 'escape'=>false, 'class'=>'search_field')); ?>
		</div>
		<div class="col-md-3" id="pending_div">
			<label>Pending With</label>
			<?php echo $this->form->input('pending_with', array('type'=>'select', 'options'=>$pending_with, 'label'=>false, 'id'=>'pending_with',  'empty'=>'All', 'escape'=>false, 'class'=>'search_field')); ?>
		</div>
		<div class="col-md-1">
			<input id="search_btn" type="submit" name="search_logs" class="form-control b747474cfff" value="Search" >
		</div>
	</div>
	<div class="clearfix"></div>
</div>
</div>
</div>

<div class="clearfix"></div>

<div class="panel panel-primary report-filterable">
	<div class="panel-heading lh31b5e7580">
		<div id="search_by_options" class="col-md-12">
			<div class="clearfix"></div>
			<div class="report-filter" class="col-md-12">
				<div class="col-md-5" ><label>Firm Name : <?php echo $firm_details['Dmi_firms']['firm_name'];?></label></div>
				<div class="col-md-2 pr0"><label>Firm ID : <?php echo $application_id;?></label></div>
				<div class="col-md-5" ><label>Certification Type : <?php echo $certification_type_label;?></label></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="col-md-12 report-filter">
			<div class="col-md-5" ><label>Application Type : <?php echo $application_type[1]; ?></label></div>
			<div class="col-md-6" ><label id="pending_with_text"></label></div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix"></div>

<div class="col-md-12 pt4" id="mainDivHeading ">
	<div class="col-md-3 tacml60"><label>Applicant Side</label></div>
	<div class="col-md-2 tact"><label>RO/SO Office Side</label></div>
	<div class="col-md-6 tact" id="level_4Heading"><label>Head Office Side</label></div>
</div>
<div class='col-md-12' id="mainDiv">
		<div class="col-md-3 applicant-cls ml60" id="applicant">

			<div class="col-md-5 p0mt120">
				<div class='oval' style="padding-top: 10px;background:#9bbb59" id="primary_oval" data-container="body" data-toggle="popover" data-placement="right">Primary Registration</div>
				<div id="primarycontent" class="hide">
					<label>Name : <?php echo $primary_details['Dmi_customer']['f_name'].' '.$primary_details['Dmi_customer']['l_name'];?></label><br>
					<label>Primary ID : <?php echo $primary_details['Dmi_customer']['customer_id'];?></label><br>
					<label>Registration ON : <?php $explode = explode(' ',$primary_details['Dmi_customer']['created']); echo $explode[0]; ?></label><br>
					<label>Mobile : <?php echo base64_decode($primary_details['Dmi_customer']['mobile']);?></label><br>
					<label>State: <?php echo $state_list[$primary_details['Dmi_customer']['state']];?></label><br>
					<label>District: <?php echo $district_list[$primary_details['Dmi_customer']['district']];?></label>
				</div>
			</div>
			<div class='col-md-2' id="toapplicant" style="padding: 40px 4.7px; margin-top: 120px;">
				<div class='pointer-arrow-right'></div>
			</div>
			<div class='col-md-5' style="padding-left: 4.5px;">
				<div class='oval' id="firm_oval" style="background:#9bbb59" data-container="body" data-toggle="popover" data-placement="right">Firm Added</div>
				<div id="firmcontent" class="hide">
					<label>Name : <?php echo $firm_details['Dmi_firms']['firm_name'];?></label><br>
					<?php if($firm_details['Dmi_firms']['certification_type'] == 1){ ?>
					<label>Category : <?php echo $commodity_category_details[$firm_details['Dmi_firms']['commodity']];?></label><br>
					<?php } ?>
					<label>Firm Added ON : <?php $explode = explode(' ',$firm_details['Dmi_firms']['created']); echo $explode[0]; ?></label><br>
					<label>State: <?php echo $state_list[$firm_details['Dmi_firms']['state']];?></label><br>
					<label>District: <?php echo $district_list[$firm_details['Dmi_firms']['district']];?></label>
				</div>
				<div class="up-arrow" id="fd-up-dr"></div>
				<div class="down-arrow" id="fd-down-dr"></div>
				<div class='pointer' id='applicant_pointer' data-container="body" data-toggle="popover" data-placement="right">Applicant</div>
				<div id="applicantcontent" class="hide">
					<label>Name : <?php echo $primary_details['Dmi_customer']['f_name'].' '.$primary_details['Dmi_customer']['l_name'];?></label><br>
					<label>Email : <?php echo $firm_details['Dmi_firms']['email'];?></label><br>
					<label>Mobile : <?php echo base64_decode($firm_details['Dmi_firms']['mobile_no']);?></label><br>
					<?php if(!empty($use_actions['date']['applicant'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['applicant'];?></label><br><?php } ?>
					<?php if(!empty($use_actions['count']['applicant_to_ddo'])){ ?><label>Replied To DDO : <?php echo $use_actions['count']['applicant_to_ddo'];?></label><br><?php } ?>
					<?php if(!empty($use_actions['count']['applicant_to_ro'])){ ?><label>Replied To RO : <?php echo $use_actions['count']['applicant_to_ro'];?></label><br><?php } ?>
				</div>
				<div class="up-arrow" id="ddo-up-dr"></div>
				<div class="down-arrow" id="ddo-down-dr"></div>
				<div class='oval' id='ddo_oval' data-container="body" data-toggle="popover" data-placement="top">DDO</div>
				<?php if(!empty($get_userdetails[0])){ ?>
					<div id="ddocontent" class="hide">
						<label>Name : <?php echo $get_userdetails[0]['user']['f_name'].' '.$get_userdetails[0]['user']['l_name'];?></label><br>
						<label>Email : <?php echo $get_userdetails[0]['user']['email'];?></label><br>
						<label>Mobile : <?php echo base64_decode($get_userdetails[0]['user']['phone']);?></label><br>
						<label>Code : <?php echo $get_userdetails[0]['pao']['pao_alias_name'];?></label><br>
						<label>Office : <?php echo $get_userdetails[0]['roo']['ro_office'];?></label><br>
						<?php if(!empty($use_actions['date']['ddo'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['ddo'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['ddo'])){ ?><label>Referred Back : <?php echo $use_actions['count']['ddo'];?></label><br><?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class='col-md-2 ro-cls' id="level_3" >

			<div class='col-md-3' id="toro" style="padding: 40px 8.7px; margin-top: 120px;">
				<div class='pointer-arrow-right' id="pointer_app_ro"></div>
			</div>
			<div class='col-md-5' style="padding-left: 12px;">
				<div class='oval' id="level_1_oval" data-container="body" data-toggle="popover" data-placement="right">Scrutiny</div>
				<?php if(!empty($get_userdetails[1])){ ?>
					<div id="mocontent" class="hide">
						<label>Name : <?php echo $get_userdetails[1]['user']['f_name'].' '.$get_userdetails[1]['user']['l_name'];?></label><br>
						<label>Email : <?php echo $get_userdetails[1]['user']['email'];?></label><br>
						<label>Mobile : <?php echo base64_decode($get_userdetails[1]['user']['phone']);?></label><br>
						<label>Office : <?php echo $get_userdetails[1]['roo']['ro_office'];?></label><br>
						<?php if(!empty($use_actions['date']['mo'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['mo'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['mo'])){ ?><label>Replied : <?php echo $use_actions['count']['mo'];?></label><br><?php } ?>
					</div>
				<?php } ?>
				<div class="up-arrow" id="level_1_up_dr"></div>
				<div class="down-arrow" id="level_1_down_dr"></div>
				<div class='pointer' id='ro_pointer' data-container="body" data-toggle="popover" data-placement="right">RO</div>
				<?php if(!empty($get_userdetails[3])){ ?>
					<div id="rocontent" class="hide">
						<label>Name : <?php echo $get_userdetails[3]['user']['f_name'].' '.$get_userdetails[3]['user']['l_name'];?></label><br>
						<label>Email : <?php echo $get_userdetails[3]['user']['email'];?></label><br>
						<label>Mobile : <?php echo base64_decode($get_userdetails[3]['user']['phone']);?></label><br>
						<label>Office : <?php echo $get_userdetails[3]['roo']['ro_office'];?></label><br>
						<?php if(!empty($use_actions['date']['ro'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['ro'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['ro_to_applicant'])){ ?><label>Referred Back To Applicant : <?php echo $use_actions['count']['ro_to_applicant'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['ro_to_mo'])){ ?><label>Referred Back To Scrutiny : <?php echo $use_actions['count']['ro_to_mo'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['ro_to_io'])){ ?><label>Referred Back To IO : <?php echo $use_actions['count']['ro_to_io'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['ro_to_ho'])){ ?><label>Referred Back To Head Office : <?php echo $use_actions['count']['ro_to_ho'];?></label><br><?php } ?>
					</div>
				<?php } ?>
				<div class="up-arrow" id="ins-up-dr"></div>
				<div class="down-arrow" id="ins-down-dr"></div>
				<div class='oval' id="level_2_oval" data-container="body" data-toggle="popover" data-placement="top">Inspection</div>
				<?php if(!empty($get_userdetails[2])){ ?>
					<div id="iocontent" class="hide">
						<label>Name : <?php echo $get_userdetails[2]['user']['f_name'].' '.$get_userdetails[2]['user']['l_name'];?></label><br>
						<label>Email : <?php echo $get_userdetails[2]['user']['email'];?></label><br>
						<label>Mobile : <?php echo base64_decode($get_userdetails[2]['user']['phone']);?></label><br>
						<label>Office : <?php echo $get_userdetails[2]['roo']['ro_office'];?></label><br>
						<?php if(!empty($use_actions['date']['io'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['io'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['io'])){ ?><label>Replied : <?php echo $use_actions['count']['io'];?></label><br><?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class='col-md-6 ho-cls' id="level_4" >

			<div class='col-md-2' id="todyama" style="padding: 40px 12px; width: 13%; margin-top: 120px;">
				<div class='pointer-arrow-right' id="pointer_ro_dyama"></div>
			</div>
			<div class='col-md-4' style="width: 20%;">
				<div class='oval' id='ho_mo_smo-oval' data-container="body" data-toggle="popover" data-placement="right">Scrutiny(HO)</div>
				<?php if(!empty($get_userdetails[7])){ ?>
					<div id="homocontent" class="hide">
						<label>Name : <?php echo $get_userdetails[7]['user']['f_name'].' '.$get_userdetails[7]['user']['l_name'];?></label><br>
						<label>Email : <?php echo $get_userdetails[7]['user']['email'];?></label><br>
						<label>Mobile : <?php echo base64_decode($get_userdetails[7]['user']['phone']);?></label><br>
						<label>Office : <?php echo $get_userdetails[7]['roo']['ro_office'];?></label><br>
						<?php if(!empty($use_actions['date']['homo'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['homo'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['homo'])){ ?><label>Replied : <?php echo $use_actions['count']['homo'];?></label><br><?php } ?>
					</div>
				<?php } ?>

				<div class="up-arrow" id="dy_ama-up-dr"></div>
				<div class="down-arrow" id="ho_mo_smo-down-dr"></div>
				<div class='pointer' id='dyama_pointer' data-container="body" data-toggle="popover" data-placement="right">DY.AMA</div>
				<?php if(!empty($get_userdetails[4])){ ?>
					<div id="dyamacontent" class="hide">
						<label>Name : <?php echo $get_userdetails[4]['user']['f_name'].' '.$get_userdetails[4]['user']['l_name'];?></label><br>
						<label>Email : <?php echo $get_userdetails[4]['user']['email'];?></label><br>
						<label>Mobile : <?php echo base64_decode($get_userdetails[4]['user']['phone']);?></label><br>
						<label>Office : <?php echo $get_userdetails[4]['roo']['ro_office'];?></label><br>
						<?php if(!empty($use_actions['date']['dyama'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['dyama'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['dyama'])){ ?><label>Comment : <?php echo $use_actions['count']['dyama'];?></label><br><?php } ?>
					</div>
				<?php } ?>
			</div>
			<div class='col-md-2' id="tojtama" style="padding: 40px 12px; width: 12%; margin-top: 120px;">
				<div class='pointer-arrow-right' id="dy_ama_jtama_pointer"></div>
			</div>
			<div class='col-md-4' style="width: 20%; margin-top: 120px;">
				<div class='oval' id='jtama_oval' data-container="body" data-toggle="popover" data-placement="top">Jt.AMA</div>
				<?php if(!empty($get_userdetails[5])){ ?>
					<div id="jtamacontent" class="hide">
						<label>Name : <?php echo $get_userdetails[5]['user']['f_name'].' '.$get_userdetails[5]['user']['l_name'];?></label><br>
						<label>Email : <?php echo $get_userdetails[5]['user']['email'];?></label><br>
						<label>Mobile : <?php echo base64_decode($get_userdetails[5]['user']['phone']);?></label><br>
						<label>Office : <?php echo $get_userdetails[5]['roo']['ro_office'];?></label><br>
						<?php if(!empty($use_actions['date']['jtama'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['jtama'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['jtama'])){ ?><label>Comment : <?php echo $use_actions['count']['jtama'];?></label><br><?php } ?>
					</div>
				<?php } ?>
				<!--<div class="up-arrow" id="jat-up-dr"></div>
				<div class="down-arrow" id="jat-down-dr"></div>
				<div class='oval' id='jat_oval'>JAT</div>-->
			</div>
			<div class='col-md-2' id="toama" style="padding: 40px 12px; width: 12%; margin-top: 120px;">
				<div class='pointer-arrow-right' id="ama_jtama_pointer" ></div>
			</div>
			<div class='col-md-2' style="margin-top: 120px;">
				<div class='oval' id='ama_oval' data-container="body" data-toggle="popover" data-placement="left">AMA</div>
				<?php if(!empty($get_userdetails[6])){ ?>
					<div id="amacontent" class="hide">
						<label>Name : <?php echo $get_userdetails[6]['user']['f_name'].' '.$get_userdetails[6]['user']['l_name'];?></label><br>
						<label>Email : <?php echo $get_userdetails[6]['user']['email'];?></label><br>
						<label>Mobile : <?php echo base64_decode($get_userdetails[6]['user']['phone']);?></label><br>
						<label>Office : <?php echo $get_userdetails[6]['roo']['ro_office'];?></label><br>
						<?php if(!empty($use_actions['date']['ama'])){ ?><label>Last Action Date : <?php echo $use_actions['date']['ama'];?></label><br><?php } ?>
						<?php if(!empty($use_actions['count']['ama'])){ ?><label>Comment : <?php echo $use_actions['count']['ama'];?></label><br><?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-12" style="margin-top:3px">
		<div class="col-md-11" style="text-align: center;">
			<span class="color_div" style="background:#c0504d"></span><span class="color_text">Currently Pending With</span>
			<span class="color_div" style="background:#ffc000"></span><span class="color_text">Referred Back / Replied By</span>
			<span class="color_div" style="background:#9bbb59"></span><span class="color_text">Process Complete</span>
			<span class="color_div" style="background:#555555"></span><span class="color_text">Application Not Reached Here</span>
		</div>
		<div class="col-md-1" style="margin-top: -31px;">
			<h5>
				<a href="<?php echo $this->request->getAttribute('webroot');?>applicationjourney/firms_list" class="report-back-button btn btn-info" role="button" style="padding: 0px; height:20px !important;">Back</a>
			</h5>
		</div>
	</div>


	<?php if(!empty($current_position_level)&&!empty($current_user_role)){ ?>
		<input type="hidden" id="current_pos" value="<?php echo $current_position_level;?>">
	<?php } ?>
	<?php if(!empty($ho_allocation_details)){  ?>
		<input type="hidden" id="usr_role_dyama" value="<?php echo $current_user_role['dy_ama'];?>">
		<input type="hidden" id="usr_role_jtama" value="<?php echo $current_user_role['jt_ama'];?>">
		<input type="hidden" id="usr_role_ama" value="<?php echo $current_user_role['ama'];?>">
		<input type="hidden" id="usr_role_ho_mo_smo" value="<?php echo $current_user_role['ho_mo_smo'];?>">
		<input type="hidden" id="level_4_from" value="<?php echo $level_4_from;?>">
		<input type="hidden" id="level_4_to" value="<?php echo $level_4_to;?>">
		<input type="hidden" id="level_4_homosmo" value="<?php echo $level_4_homosmo;?>">
	<?php } ?>

<?php echo $this->Form->end(); ?>

<script>
	var ops = {
		'html':true,
		content: function(){
			return $('#firmcontent').html();
		}
	};
	var ops1 = {
		'html':true,
		content: function(){
			return $('#applicantcontent').html();
		}
	};

	var ops2 = {
		'html':true,
		content: function(){
			return $('#ddocontent').html();
		}
	};
	var ops3 = {
		'html':true,
		content: function(){
			return $('#mocontent').html();
		}
	};

	var ops4 = {
		'html':true,
		content: function(){
			return $('#rocontent').html();
		}
	};

	var ops5 = {
		'html':true,
		content: function(){
			return $('#iocontent').html();
		}
	};

	var ops6 = {
		'html':true,
		content: function(){
			return $('#homocontent').html();
		}
	};

	var ops7 = {
		'html':true,
		content: function(){
			return $('#dyamacontent').html();
		}
	};

	var ops8 = {
		'html':true,
		content: function(){
			return $('#jtamacontent').html();
		}
	};

	var ops9 = {
		'html':true,
		content: function(){
			return $('#amacontent').html();
		}
	};

	var ops10 = {
		'html':true,
		content: function(){
			return $('#primarycontent').html();
		}
	};

	$(function(){
		$('#firm_oval').popover(ops);
		$('#applicant_pointer').popover(ops1);
		$('#ddo_oval').popover(ops2);
		$('#level_1_oval').popover(ops3);
		$('#ro_pointer').popover(ops4);
		$('#level_2_oval').popover(ops5);
		$('#ho_mo_smo-oval').popover(ops6);
		$('#dyama_pointer').popover(ops7);
		$('#jtama_oval').popover(ops8);
		$('#ama_oval').popover(ops9);
		$('#primary_oval').popover(ops10);
	});




	$("#pending_div").hide();

	$("#result_for").change(function(){
		var result_for = $("#result_for").val();
	  if(result_for == 'pending'){
		  $("#pending_div").show();
	  }else{
		  $("#pending_with").val('');
		  $("#pending_div").hide();
	  }
	});


	$('body').on('click', function (e) {
		if ($(e.target).data('toggle') !== 'popover'
			&& $(e.target).parents('.popover.in').length === 0) {
			$('[data-toggle="popover"]').popover('hide');
		}
	});

</script>
<?php if($forward_ho_btn == ''){  ?>
	<script>
		$('#level_4').hide();
		$('#level_4Heading').hide();
		$("#mainDiv").addClass("col-md-offset-3");
		$("#mainDivHeading").addClass("col-md-offset-3");
	</script>
<?php } ?>

<?php if($application_type[0] == 'old' ){  ?>
	<script>
		$('#ddo_oval').hide();	 $('#ddo-up-dr').hide();	 $('#ddo-down-dr').hide();
		$('#level_1_oval').css('opacity', '0');	 $('#level_1_up_dr').css('opacity', '0'); $('#level_1_down_dr').css('opacity', '0');
		$('#ins-up-dr').hide();	 $('#ins-down-dr').hide();  $('#level_2_oval').hide();
	</script>
<?php } ?>

<?php if($application_type[0] == 'renewal' && $firm_details['Dmi_firms']['certification_type'] != 2){  ?>
	<script>
		$('#ins-up-dr').hide();	 $('#ins-down-dr').hide();  $('#level_2_oval').hide();
	</script>
<?php } ?>

<?php if($payment_confirmation_status == ''){ ?>
	<script>
		$('#level_3').css('opacity', '0.1');
		$('#level_4').css('opacity', '0.1');
	</script>
<?php } ?>

<?php if($payment_confirmation_status == 'pending'){ ?>
	<script>
		$('#level_3').css('opacity', '0.1');
		$('#level_4').css('opacity', '0.1');
		$('#ddo_oval').css('background', '#c0504d');
		$('#ddo-down-dr').append("<style>#ddo-down-dr:after{ border-top: 16px solid #c0504d !important; }</style>");
		$('#ddo-down-dr').css('background', '#c0504d');
		$('#applicant_pointer').css('background', '#9bbb59');
		$('#pending_with_text').text("Pending With : DDO ("+"<?php echo $get_userdetails[0]['user']['f_name'].' '.$get_userdetails[0]['user']['l_name'].'('.$get_userdetails[0]['roo']['ro_office'].')';?>)");
	</script>
<?php } ?>
<?php if($payment_confirmation_status == 'not_confirmed'){ ?>
	<script>
		$('#level_3').css('opacity', '0.1');
		$('#level_4').css('opacity', '0.1');
		$('#ddo_oval').css('background', '#ffc000');
		$('#ddo-up-dr').append("<style>#ddo-up-dr:after{ border-bottom: 16px solid #ffc000 !important; }</style>");
		$('#ddo-down-dr').css('background', '#ffc000');
		$('#applicant_pointer').css('background', '#c0504d');
	</script>
<?php } ?>
<?php if($payment_confirmation_status == 'replied'){ ?>
	<script>
		$('#level_3').css('opacity', '0.1');
		$('#level_4').css('opacity', '0.1');
		$('#ddo_oval').css('background', '#c0504d');
		$('#ddo-down-dr').append("<style>#ddo-down-dr:after{ border-top: 16px solid #ffc000 !important; }</style>");
		$('#ddo-down-dr').css('background', '#ffc000');
		$('#applicant_pointer').css('background', '#ffc000');
		$('#pending_with_text').text("Pending With : DDO ("+"<?php echo $get_userdetails[0]['user']['f_name'].' '.$get_userdetails[0]['user']['l_name'].'('.$get_userdetails[0]['roo']['ro_office'].')';?>)");
	</script>
<?php } ?>
<?php if($payment_confirmation_status == 'confirmed'){  ?>
				<script>
					$('#ddo-down-dr').css('background', '#9bbb59');
					$('#ddo_oval').css('background', '#9bbb59');
					$('#applicant_pointer').css('background', '#9bbb59');
					$('#pointer_app_ro').css('background', '#9bbb59');
				</script>
		<?php if($allocation_status['level_1'] != ''){  ?>
				<script>
					$('#level_1_oval').css('background', '#9bbb59');
					$('#level_1_down_dr').css('background', '#9bbb59');
				</script>
		<?php } if($allocation_status['level_2'] != ''){  ?>
				<script>
					$('#level_2_oval').css('background', '#9bbb59');
					$('#ins-down-dr').css('background', '#9bbb59');
				</script>
		<?php } if($allocation_status['level_3'] != ''){  ?>
				<script>
					$('#ro_pointer').css('background', '#9bbb59');
				</script>
		<?php } if(in_array("ho_mo_smo", $ho_comment_inarray)){ ?>
				<script>
					$('#ho_mo_smo-oval').css('background', '#9bbb59');
					$('#ho_mo_smo-down-dr').css('background', '#9bbb59');
				</script>
		<?php } if(in_array("dy_ama", $ho_comment_inarray)){ ?>
				<script>
					$('#dyama_pointer').css('background', '#ffc000');
					$('#pointer_ro_dyama').css('background', '#ffc000');
				</script>
		<?php } if(in_array("jt_ama", $ho_comment_inarray)){ ?>
				<script>
					$('#jtama_oval').css('background', '#ffc000');
					$('#dy_ama_jtama_pointer').css('background', '#ffc000');
				</script>
		<?php } if(in_array("ama", $ho_comment_inarray)){ ?>
				<script>
					$('#ama_oval').css('background', '#ffc000');
					$('#ama_jtama_pointer').css('background', '#ffc000');
				</script>
		<?php } if(!empty($ama_approved)){ ?>
				<script>
					$('#ama_oval').css('background', '#ffc000');
					$('#ama_jtama_pointer').css('background', '#ffc000');
				</script>
		<?php } ?>


		<?php if($current_position_level == 'level_4'){  ?>
			<script>
				var current_pos = $('#current_pos').val();
				var usr_role_dyama = $('#usr_role_dyama').val();
				var usr_role_jtama = $('#usr_role_jtama').val();
				var usr_role_ama = $('#usr_role_ama').val();
				var usr_role_ho_mo_smo = $('#usr_role_ho_mo_smo').val();
				var level_4_from = $('#level_4_from').val();
				var level_4_to = $('#level_4_to').val();
				var level_4_homosmo = $('#level_4_homosmo').val();

						$('#ddo_oval').css('background', '#9bbb59');
						$('#applicant_pointer').css('background', '#9bbb59');
						$('#ddo-down-dr').css('background', '#9bbb59');
						$('#level_1_oval').css('background', '#9bbb59');
						$('#level_2_oval').css('background', '#9bbb59');
						$('#level_1_down_dr').css('background', '#9bbb59');
						$('#ins-down-dr').css('background', '#9bbb59');
						$('#pointer_app_ro').css('background', '#9bbb59');
						$('#ro_pointer').css('background', '#30a5ff');
						$('#pointer_ro_dyama').css('background', '#9bbb59');

						if(usr_role_dyama=='yes'){

							$('#dyama_pointer').css('background', '#c0504d');
							$('#'+level_4_to+'-up-dr').append("<style>#"+level_4_to+"-up-dr:after{ border-bottom: 0px; }</style>");
							$('#'+level_4_from+'-down-dr').css('background', '#c0504d');
							$('#'+level_4_from+'-oval').css('background', '#ffc000');
							$('#'+level_4_from+'-down-dr').append("<style>#"+level_4_from+"-down-dr:after{ border-top: 16px solid #c0504d;}</style>");
							$('#pending_with_text').text("Pending With : RO ("+"<?php echo $get_userdetails[4]['user']['f_name'].' '.$get_userdetails[4]['user']['l_name'].'('.$get_userdetails[4]['roo']['ro_office'].')';?>)");

						}else if(usr_role_jtama=='yes'){

							$('#jtama_oval').css('background', '#c0504d');
							$('#pending_with_text').text("Pending With : RO ("+"<?php echo $get_userdetails[5]['user']['f_name'].' '.$get_userdetails[5]['user']['l_name'].'('.$get_userdetails[5]['roo']['ro_office'].')';?>)");
							if(level_4_from == 'dy_ama'){
								if(level_4_homosmo != ''){
									$('#ho_mo_smo-oval').css('background', '#9bbb59');
									$('#ho_mo_smo-down-dr').css('background', '#9bbb59');
								}
								$('#dy_ama_jtama_pointer').append("<style>#dy_ama_jtama_pointer:after{ border-left: 16px solid #ffc000 !important; }</style>");
								$('#dy_ama_jtama_pointer').css('background', '#ffc000');
								$('#dyama_pointer').css('background', '#ffc000');
							}else if(level_4_from == 'ama'){
								$('#ama_jtama_pointer').append("<style>#ama_jtama_pointer:before{ border-right: 16px solid #ffc000; }</style>");
							}

						}else if(usr_role_ama=='yes'){

							$('#ama_oval').css('background', '#c0504d');
							$('#pending_with_text').text("Pending With : RO ("+"<?php echo $get_userdetails[6]['user']['f_name'].' '.$get_userdetails[6]['user']['l_name'].'('.$get_userdetails[6]['roo']['ro_office'].')';?>)");

						}else if(usr_role_ho_mo_smo=='yes'){

							$('#ho_mo_smo-oval').css('background', '#c0504d');
							$('#'+level_4_from+'-up-dr').append("<style>#"+level_4_from+"-up-dr:after{ border-bottom: 16px solid #c0504d; }</style>");
							$('#'+level_4_to+'-down-dr').css('background', '#c0504d');
							$('#'+level_4_to+'-down-dr').append("<style>#"+level_4_to+"-down-dr:after{ border-top: 0px;}</style>");
							$('#pending_with_text').text("Pending With : RO ("+"<?php echo $get_userdetails[7]['user']['f_name'].' '.$get_userdetails[7]['user']['l_name'].'('.$get_userdetails[7]['roo']['ro_office'].')';?>)");
						}
			</script>
		<?php } if($current_position_level == 'level_3'){ ?>
				<script>
					$('#pending_with_text').text("Pending With : RO ("+"<?php echo $get_userdetails[3]['user']['f_name'].' '.$get_userdetails[3]['user']['l_name'].'('.$get_userdetails[3]['roo']['ro_office'].')';?>)");
				</script>
		<?php } if($current_position_level == 'level_3' && $application_came_from=='applicant' && $final_submit_status == 'pending'){ ?>
				<script>
					$('#ro_pointer').css('background', '#c0504d');
					$('#pointer_app_ro').css('background', '#c0504d');
					$('#pointer_app_ro').append("<style>#pointer_app_ro:after{ border-left: 16px solid #c0504d !important; }</style>");
				</script>
		<?php } if($current_position_level == 'level_3' && $application_came_from=='applicant' && $final_submit_status == 'replied'){ ?>
				<script>
					$('#ro_pointer').css('background', '#c0504d');
					$('#pointer_app_ro').css('background', '#ffc000');
					$('#pointer_app_ro').append("<style>#pointer_app_ro:after{ border-left: 16px solid #ffc000 !important; }</style>");
				</script>
		<?php } if($current_position_level == 'level_3' && $application_came_from=='MO'){ ?>
				<script>
					$('#ro_pointer').css('background', '#c0504d');
					$('#level_1_oval').css('background', '#ffc000');
					$('#level_1_down_dr').css('background', '#ffc000');
					$('#level_1_down_dr').append("<style>#level_1_down_dr:after{ border-top: 16px solid #ffc000 !important; }</style>");
				</script>
		<?php } if($current_position_level == 'level_3' && $application_came_from=='IO' && $final_submit_status == 'pending'){ ?>
				<script>
					$('#ro_pointer').css('background', '#c0504d');
					$('#level_2_oval').css('background', '#9bbb59');
					$('#ins-down-dr').css('background', '#c0504d');
					$('#ins-up-dr').append("<style>#ins-up-dr:after{ border-bottom: 16px solid #c0504d; }</style>");
				</script>
		<?php } if($current_position_level == 'level_3' && $application_came_from=='IO' && $final_submit_status == 'replied'){ ?>
				<script>
					$('#ro_pointer').css('background', '#c0504d');
					$('#level_2_oval').css('background', '#ffc000');
					$('#ins-down-dr').css('background', '#ffc000');
					$('#ins-up-dr').append("<style>#ins-up-dr:after{ border-bottom: 16px solid #ffc000; }</style>");
				</script>
		<?php } if($current_position_level == 'level_3' && $application_came_from=='HO'){ ?>
				<script>

					$('#ro_pointer').css('background', '#c0504d');
					$('#dyama_pointer').css('background', '#ffc000');
					$('#pointer_ro_dyama').css('background', '#ffc000');
					$('#pointer_ro_dyama').append("<style>#pointer_ro_dyama:before{ border-right: 16px solid #ffc000; }</style>");
				</script>
		<?php } if($current_position_level == 'level_1'){ ?>
				<script>
					$('#pending_with_text').text("Pending With : MO ("+"<?php echo $get_userdetails[1]['user']['f_name'].' '.$get_userdetails[1]['user']['l_name'].'('.$get_userdetails[1]['roo']['ro_office'].')';?>)");
				</script>
		<?php } if($current_position_level == 'level_1' && empty($mo_pending)){ ?>
				<script>
					$('#level_1_oval').css('background', '#c0504d');
					$('#ro_pointer').css('background', '#30a5ff');
					$('#level_1_down_dr').css('background', '#c0504d');
					$('#level_1_up_dr').append("<style>#level_1_up_dr:after{ border-bottom: 16px solid #c0504d; }</style>");
				</script>
		<?php } if($current_position_level == 'level_1' && !empty($mo_pending)){ ?>
				<script>
					$('#level_1_oval').css('background', '#c0504d');
					$('#ro_pointer').css('background', '#ffc000');
					$('#level_1_down_dr').css('background', '#ffc000');
					$('#level_1_up_dr').append("<style>#level_1_up_dr:after{ border-bottom: 16px solid #ffc000; }</style>");
				</script>
		<?php } if($current_position_level == 'level_2'){ ?>
				<script>
					$('#pending_with_text').text("Pending With : IO ("+"<?php echo $get_userdetails[2]['user']['f_name'].' '.$get_userdetails[2]['user']['l_name'].'('.$get_userdetails[2]['roo']['ro_office'].')';?>)");
				</script>
		<?php } if($current_position_level == 'level_2' && empty($io_pending)){ ?>
				<script>
					$('#level_2_oval').css('background', '#c0504d');
					$('#ro_pointer').css('background', '#30a5ff');
					$('#ins-down-dr').css('background', '#c0504d');
					$('#ins-down-dr').append("<style>#ins-down-dr:after{ border-top: 16px solid #c0504d !important; }</style>");
				</script>
		<?php } if($current_position_level == 'level_2' && !empty($io_pending)){ ?>
				<script>
					$('#level_2_oval').css('background', '#c0504d');
					$('#ro_pointer').css('background', '#ffc000');
					$('#ins-down-dr').css('background', '#ffc000');
					$('#ins-down-dr').append("<style>#ins-down-dr:after{ border-top: 16px solid #ffc000 !important; }</style>");
				</script>
		<?php } if($current_position_level == 'applicant'){ ?>
				<script>
					$('#applicant_pointer').css('background', '#c0504d');
					$('#ro_pointer').css('background', '#ffc000');
					$('#pointer_app_ro').css('background', '#ffc000');
					$('#pointer_app_ro').append("<style>#pointer_app_ro:before{ border-right: 16px solid #ffc000 !important; }</style>");
				</script>
		<?php } ?>
<?php } ?>
