<?php echo $this->Html->css('dashboard/common-count-css'); ?>

<?php if(!empty($_SESSION['current_level'])){ echo $this->Html->css('dashboard/common-count-bg-css'); } ?>	
<div class="clear">
<div class="panel panel-default">
<div class="panel-heading">
	
	<h4 class="panel-title"><?php echo $status_title; ?></h4>
	
</div>
</div>
<div class="panel-body">
<div class="content-wrapper">
	<section class="content">
	<div class="container-fluid">
	<div class="row">
					
    
            <div  class="col-lg-2">	
			<a id="pending_count_box" oncontextmenu="return false;" title="These Applications are Pending to Process by you.">
                    <div class="count_box">
                            <div class="col-lg-12 widget-left">
                                    <span class="glyphicon glyphicon-lock" ></span>
                                    <div id="pending_count_no" class="large count_no"></div>

                            </div>
                            <div class="clear"></div>
                            <div class="text-muted">Pending</div>
                    </div>
			 </a>
            </div>
   

    <?php if($_SESSION['current_level']=='level_2'){ ?>
   
            <div  class="col-lg-2">	
			 <a id="reports_filed_count_box" oncontextmenu="return false;" title="These are Filed Site-inspection Reports, and Pending to Process by Office Incharge.">
                    <div class="count_box">
                            <div class="col-lg-12 widget-left">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                    <div id="reports_filed_count_no" class="large count_no"></div>

                            </div>
                            <div class="clear"></div>
                            <div class="text-muted" >Reports Filed</div>
                    </div>
			</a>
            </div>
    
    <?php } ?>

    
            <div  class="col-lg-2">			
			<a id="ref_back_count_box" oncontextmenu="return false;" title="These Applications are Referred back for Some Discrepancies.">
                    <div class="count_box">
                            <div class="col-lg-12 widget-left">
                                    <span class="glyphicon glyphicon-edit"></span>
                                    <div id="ref_back_count_no" class="large count_no"></div>

                            </div>
                            <div class="clear"></div>
                            <div class="text-muted" >Refered Back</div>
                    </div>
			 </a>
            </div>
   


    
            <div  class="col-lg-2">	
			<a id="replied_count_box" oncontextmenu="return false;" title="These Applications are Replied with Updated Information on Some Discrepancies.">
                    <div class="count_box" >
                            <div class="col-lg-12 widget-left">
                                    <span class="glyphicon glyphicon-share"></span>
                                    <div id="replied_count_no" class="large count_no"></div>

                            </div>
                            <div class="clear"></div>
                            <div class="text-muted" >Replied</div>
                    </div>
			</a>
            </div>
    

	<!-- No need to show approved tab to level 3 and 4 user as showing granted applications list already 
	applied on 03-12-2021 -->
    <?php if($_SESSION['current_level']!='level_3' && $_SESSION['current_level']!='level_4'){ ?>
             <div  class="col-lg-2">	
			 <a id="approved_count_box" oncontextmenu="return false;" title="These Applications are Approved on Different Levels (eg. Scrutized, Reports Approved, and Granted).">
                    <div class="count_box">
                            <div class="col-lg-12 widget-left">
                                    <span class="glyphicon glyphicon-check"></span>
                                    <div id="approved_count_no" class="large count_no"></div>

                            </div>
                            <div class="clear"></div>
                            <div class="text-muted" >Approved</div>
                    </div>
			</a>
            </div>
    <?php } ?>


    <?php if($_SESSION['current_level']=='level_3'){ ?>
    
            <div  class="col-lg-2">
			<a id="rejected_count_box" oncontextmenu="return false;" title="These Applications are Rejected for Some reason, and Stopped to Process further.">
                    <div class="count_box">
                            <div class="col-lg-12 widget-left">
                                    <span class="glyphicon glyphicon-remove-circle"></span>
                                    <div id="rejected_count_no" class="large count_no"></div>

                            </div>
                            <div class="clear"></div>
                            <div class="text-muted" >Rejected</div>
                    </div>
			 </a>
            </div>
   
    <?php } ?>


    <?php if($_SESSION['current_level']=='level_3' || 
                    ($_SESSION['current_level'] == 'level_4' && $current_user_roles['dy_ama'] == 'yes')){ ?>
    
            <div  class="col-lg-2">
			<a id="allocations_count_box" oncontextmenu="return false;" title="These are the applications which are ready to Allocate/Reallocate">
                    <div class="count_box" >
                            <div class="col-lg-12 widget-left">
                                    <span class="glyphicon glyphicon-lock"></span>
                                    <div id="allocation_count_no" class="large count_no"></div>

                            </div>
                            <div class="clear"></div>
                            <div class="text-muted" >Allocations</div>
                    </div>
			</a>
            </div>
    

    <?php }?>
	
	<?php if($_SESSION['current_level'] == 'level_4' && $current_user_roles['jt_ama'] == 'yes'){ ?>
					
		
			<!--	<div  class="col-lg-2">
				 <a id="jat_status_count_box" oncontextmenu="return false;" title="These are laboratory(Export) applications for site inspection">
						<div class="count_box" style="background-image:linear-gradient(#ddd,#46bbdc,#46bbdc);">
								<div class="col-lg-12 widget-left">
										<span class="glyphicon glyphicon-th-list"></span>
										<div class="large count_no">2<?php //echo $all_count; ?></div>

								</div>
								<div class="clear"></div>
								<div class="text-muted" style="">JAT Status</div>
						</div>
				</a>
				</div>-->
						
					
	<?php }?>
<!--	
    <a id="all_count_box" oncontextmenu="return false;" title="These are Groups of All Applications at One Place (eg. Pending, Referred back, Replied and Approved etc.)">
            <div  class="col-xs-6 col-md-4 col-lg-2 col-sm-6">
                    <div class="count_box" style="background-image:linear-gradient(#ddd,#46bbdc,#46bbdc);">
                            <div class="col-lg-12 widget-left">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                    <div class="large count_no"><?php //echo $all_count; ?></div>

                            </div>
                            <div class="clear"></div>
                            <div class="text-muted" style="">All</div>
                    </div>
            </div>
    </a>
    -->
	
	
	</div>
	</div>
	</section>
</div>	
	
</div>

<!-- to show loading gif while ajax loading -->
		<!--<div style="text-align:center;">
			<?php //echo $this->Html->image('ajax-loader.gif', array('id'=>'common_ajax_loader', 'style'=>'display:none;margin-top:50px;')); ?>
		</div>-->
	<div class="loader"></div>
	<div class="loadermsg">Please Wait...</div>
	
		<h4 id="list_heading_text" ><!-- This List heading will append from above ajax code --></h4>
		<div id="common_applications_list">
		
			
		
			<?php /* if(!empty($show_reg_off_counts_and_list) || !empty($show_sub_off_counts_and_list)){
				
				//for RO/So dashboard
				echo $this->element('common_counts_and_list_elements/ro_so_common_elements/ro_so_common_dashboard_tabs');
				echo $this->element('common_counts_and_list_elements/allocation_popup_models/scrutiny_allocation_popup');
				
			}else{ ?>
		
				<div id="show_all"><?php echo $this->element('common_counts_and_list_elements/all_app_list_element'); ?></div>
				
				<div id="show_common"><?php echo $this->element('common_counts_and_list_elements/common_app_list_element'); ?></div>
			
			<?php } */ ?>
		</div>
	
<input type="hidden" id="current_level_script_id" value="<?php echo $_SESSION['current_level']; ?>">
<?php if(!empty($_SESSION['show_list_for'])){ $show_list_for = $_SESSION['show_list_for']; }else{ $show_list_for='';} ?>
<input type="hidden" id="show_list_for_script_id" value="<?php echo $show_list_for; ?>">
	
<?php 
//now all other scripts are converted to functions in this js and called as per need on ajax success when view rendered.
//on 21-10-2021 by Amol
echo $this->Html->script('dashboard/common-count-js');
//exit; //intensionally added exit; on 07-10-2021 by Amol, as CSRF blocking the next request after rendering the view through ajax ?>	
