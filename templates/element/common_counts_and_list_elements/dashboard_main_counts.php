<?php echo $this->Html->css('dashboard/dashboard-main-counts-css'); ?>
 
 <div class="modal" id="dasboard_main_count_popop">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Status Of Pending Work</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		<p>(<b>P</b>: Pending, <b>R</b>: Replied, <b>RB</b>: Referred Back)</p>
		<?php $pending_work = 'no'; ?>
		
			<?php if($current_user_roles['mo_smo_inspection'] == 'yes'){

					$scrutiny_offc_nodal_cnt = $main_count_array['scrutiny_with_nodal_office']['pending']+$main_count_array['scrutiny_with_nodal_office']['replied'];
					$scrutiny_offc_RO_cnt = $main_count_array['scrutiny_with_reg_office']['pending']+$main_count_array['scrutiny_with_reg_office']['replied']; 
					
					if($scrutiny_offc_nodal_cnt != 0 || $scrutiny_offc_RO_cnt != 0){ 
						$pending_work = 'yes'; ?>
			
						<div class="role_section">
							<h6>As Scrutiny Officer</h6>
							<table>
								<tr>
									<?php if($scrutiny_offc_nodal_cnt != 0){ ?>
										<td>From Nodal Office ( P:<a class="scr_offc_cnt_nodal_P" href="#"><?php echo $main_count_array['scrutiny_with_nodal_office']['pending']; ?></a>, R:<a class="scr_offc_cnt_nodal_R" href="#"><?php echo $main_count_array['scrutiny_with_nodal_office']['replied']; ?></a> )</td>
									<?php }
										if($scrutiny_offc_RO_cnt != 0){ ?>
										<td>From RO Office ( P:<a class="scr_offc_cnt_ro_P" href="#"><?php echo $main_count_array['scrutiny_with_reg_office']['pending']; ?></a>, R:<a class="scr_offc_cnt_ro_R" href="#"><?php echo $main_count_array['scrutiny_with_reg_office']['replied']; ?></a> )</td>
									<?php }?>
								</tr>					
							</table>
							
						</div>
						
			<?php	} 
			
			 }if($current_user_roles['io_inspection'] == 'yes'){ 
			 
				$site_inspect_cnt = $main_count_array['inspection']['pending']+$main_count_array['inspection']['ref_back'];
				if($site_inspect_cnt != 0){ 
					$pending_work = 'yes'; ?>	
				
					<div class="role_section">
						<h6>As Site Inspection Officer( P:<a class="site_inspect_cnt_P" href="#"><?php echo $main_count_array['inspection']['pending']; ?></a>, RB:<a class="site_inspect_cnt_R" href="#"><?php echo $main_count_array['inspection']['ref_back']; ?></a> )</h6>
					</div>
					
			<?php } 
					
			 //for level 3 users
			 }if($current_user_roles['ro_inspection'] == 'yes'){ 
				
					$reg_offc_appl_cnt = $main_count_array['with_applicant']['pending']+$main_count_array['with_applicant']['replied'];
					$reg_offc_scrut_cnt = $main_count_array['scrutiny']['pending']+$main_count_array['scrutiny']['replied'];
					$reg_offc_inspec_cnt = $main_count_array['reports']['pending']+$main_count_array['reports']['replied'];
					$reg_offc_so_cnt = $main_count_array['with_sub_office']['pending']+$main_count_array['with_sub_office']['replied'];
					$reg_offc_ho_cnt = $main_count_array['with_ho_office']['pending']+$main_count_array['with_ho_office']['replied']; 
					
					if($reg_offc_appl_cnt != 0 || $reg_offc_scrut_cnt != 0 || $reg_offc_inspec_cnt != 0 
						|| $reg_offc_so_cnt != 0 || $reg_offc_ho_cnt != 0){
						
						$pending_work = 'yes'; ?>
				
						<div class="role_section">
							<h6>As Regional Office In-Charge</h6>
							<table>
								<tr>
									<?php if($reg_offc_appl_cnt != 0){ ?>
										<td>From Applicant (P:<a class="Applicant_reg_offc_cnt_P" href="#"><?php echo $main_count_array['with_applicant']['pending']; ?></a>,R:<a class="Applicant_reg_offc_cnt_R" href="#"><?php echo $main_count_array['with_applicant']['replied']; ?></a>)</td>
									
									<?php }if($reg_offc_scrut_cnt != 0){ ?>
										<td>From Srutiny Officer (P:<a class="Srutiny_reg_offc_cnt_P" href="#"><?php echo $main_count_array['scrutiny']['pending']; ?></a>,R:<a class="Srutiny_reg_offc_cnt_R" href="#"><?php echo $main_count_array['scrutiny']['replied']; ?></a>)</td>
									
									<?php }if($reg_offc_inspec_cnt != 0){ ?>
										<td>From Inspection Officer (P:<a class="Inspection_reg_offc_cnt_P" href="#"><?php echo $main_count_array['reports']['pending']; ?></a>,R:<a class="Inspection_reg_offc_cnt_R" href="#"><?php echo $main_count_array['reports']['replied']; ?></a>)</td>
									<?php } ?>
								</tr>
								<tr>
									<?php if($reg_offc_so_cnt != 0){ ?>
										<td>From SO Officer (P:<a class="sub_reg_offc_cnt_P" href="#"><?php echo $main_count_array['with_sub_office']['pending']; ?></a>,R:<a class="sub_reg_offc_cnt_R" href="#"><?php echo $main_count_array['with_sub_office']['replied']; ?></a>)</td>
									
									<?php }if($reg_offc_ho_cnt != 0){ ?>
										<td>From HO QC (P:<a class="ho_reg_offc_cnt_P" href="#"><?php echo $main_count_array['with_ho_office']['pending']; ?></a>,R:<a class="ho_reg_offc_cnt_R" href="#"><?php echo $main_count_array['with_ho_office']['replied']; ?></a>)</td>
									<?php } ?>
								</tr>
							
							</table>
							
						</div>
						
					<?php } 
				
				
					$reg_offc_allc_scru_cnt = $main_count_array['scrutiny_allocation_tab']+$main_count_array['scrutiny_allocation_by_level4ro_tab'];
					$reg_offc_allc_inspe_cnt = $main_count_array['inspection_allocation_tab']; 
					
					if($reg_offc_allc_scru_cnt != 0 || $reg_offc_allc_inspe_cnt != 0){ 
					
						$pending_work = 'yes'; ?>
					
						<div class="role_section">
							<h6>Allocations</h6>
							<table>
								<tr>
								<?php if($reg_offc_allc_scru_cnt != 0){ ?>
									<td>For Scrutiny ( <a class="reg_offc_cnt_allc_scr" href="#"><?php echo $reg_offc_allc_scru_cnt; ?></a> )</td>
									
								<?php }if($reg_offc_allc_inspe_cnt != 0){ ?>	
									<td>For Inspection ( <a class="reg_offc_cnt_allc_ins" href="#"><?php echo $reg_offc_allc_inspe_cnt; ?></a> )</td>
									
								<?php } ?>	
								</tr>
							
							</table>
							
						</div>
					<?php } ?>	
						
						
			<?php }elseif($current_user_roles['so_inspection'] == 'yes'){ 
			
					$sub_offc_appl_cnt = $main_count_array['with_applicant']['pending']+$main_count_array['with_applicant']['replied'];
					$sub_offc_scrut_cnt = $main_count_array['scrutiny']['pending']+$main_count_array['scrutiny']['replied'];
					$sub_offc_inspec_cnt = $main_count_array['reports']['pending']+$main_count_array['reports']['replied'];
					$sub_offc_ro_cnt = $main_count_array['with_reg_office']['pending']+$main_count_array['with_reg_office']['replied'];
				
					if($sub_offc_appl_cnt != 0 || $sub_offc_scrut_cnt != 0 || $sub_offc_inspec_cnt != 0 
						|| $sub_offc_ro_cnt != 0){ 
						
						$pending_work = 'yes'; ?>
			
						<div class="role_section">
							<h6>As Sub Office In-Charge</h6>
							<table>
								<tr>
									<?php if($sub_offc_appl_cnt != 0){ ?>
										<td>From Applicant (P:<a class="Applicant_sub_offc_cnt_P" href="#"><?php echo $main_count_array['with_applicant']['pending']; ?></a>,R:<a class="Applicant_sub_offc_cnt_R" href="#"><?php echo $main_count_array['with_applicant']['replied']; ?></a>)</td>
									
									<?php }if($sub_offc_scrut_cnt != 0){ ?>
										<td>From Srutiny Officer (P:<a class="Srutiny_sub_offc_cnt_P" href="#"><?php echo $main_count_array['scrutiny']['pending']; ?></a>,R:<a class="Srutiny_sub_offc_cnt_R" href="#"><?php echo $main_count_array['scrutiny']['replied']; ?></a>)</td>
									
									<?php }if($sub_offc_inspec_cnt != 0){ ?>
										<td>From Inspection Officer (P:<a class="Inspection_sub_offc_cnt_P" href="#"><?php echo $main_count_array['reports']['pending']; ?></a>,R:<a class="Inspection_sub_offc_cnt_R" href="#"><?php echo $main_count_array['reports']['replied']; ?></a>)</td>
									<?php } ?>
								</tr>
								<tr>
									<?php if($sub_offc_ro_cnt != 0){ ?>
										<td><a class="sub_offc_cnt" href="#">From RO Officer ( <?php echo $sub_offc_ro_cnt; ?> )</a></td>
										<td>From RO Officer (P:<a class="reg_sub_offc_cnt_P" href="#"><?php echo $main_count_array['with_reg_office']['pending']; ?></a>,R:<a class="reg_sub_offc_cnt_R" href="#"><?php echo $main_count_array['with_reg_office']['replied']; ?></a>)</td>
									<?php } ?>
								</tr>
							
							</table>
							
						</div>
					<?php } 
					
					$sub_offc_allc_scru_cnt = $main_count_array['scrutiny_allocation_tab']+$main_count_array['scrutiny_allocation_by_level4ro_tab'];
					$sub_offc_allc_inspe_cnt = $main_count_array['inspection_allocation_tab']; 
					
					if($sub_offc_allc_scru_cnt != 0 || $sub_offc_allc_inspe_cnt != 0){ 
					
						$pending_work = 'yes'; ?>
				
						<div class="role_section">
							<h6>Allocations (Scrutiny/Inspection)</h6>
							<table>
								<tr>
								<?php if($sub_offc_allc_scru_cnt != 0){ ?>
									<td>For Scrutiny ( <a class="sub_offc_cnt_allc_scr" href="#"><?php echo $sub_offc_allc_scru_cnt; ?></a> )</td>
									
								<?php }if($sub_offc_allc_inspe_cnt != 0){ ?>	
									<td>For Inspection ( <a class="sub_offc_cnt_allc_ins" href="#"><?php echo $sub_offc_allc_inspe_cnt; ?></a> )</td>
								<?php } ?>
								</tr>
							
							</table>
							
						</div>
					<?php }
						
				} 
					
			 	//for level 4 users
				if($current_user_roles['dy_ama'] == 'yes'){ 
				
					$ho_offc_dyama_cnt = $main_count_array['for_dy_ama']['pending']+$main_count_array['for_dy_ama']['replied'];
					$ho_offc_alloc_cnt = $main_count_array['scrutiny_allocation_tab'];
					
					if($ho_offc_dyama_cnt != 0){ 
					
						$pending_work = 'yes'; ?>
						
							<div class="role_section">
								<h6>As Dy. AMA Officer( P:<a class="ho_offc_cnt_dyama_P" href="#"><?php echo $main_count_array['for_dy_ama']['pending']; ?></a>, R:<a class="ho_offc_cnt_dyama_R" href="#"><?php echo $main_count_array['for_dy_ama']['replied']; ?></a> )</h6>
								
							</div>
						
					<?php }if($ho_offc_alloc_cnt != 0){ 
					
						$pending_work = 'yes'; ?>
						
							<div class="role_section">
								<h6>Allocations (Scrutiny)</h6>
								<table>
									<tr>
										<td><a class="ho_offc_cnt" href="#">For Scrutiny-HO QC ( <?php echo $ho_offc_alloc_cnt; ?> )</a></td>
									</tr>
								
								</table>
								
							</div>
					<?php } ?>
						
						
			<?php }elseif($current_user_roles['jt_ama'] == 'yes'){ 
			
				$ho_offc_jtama_cnt = $main_count_array['for_jt_ama']['pending']+$main_count_array['for_jt_ama']['replied'];
			
				if($ho_offc_jtama_cnt != 0){ 
				
					$pending_work = 'yes'; ?>
					
					<div class="role_section">
						<h6>As Jt. AMA Officer( P:<a class="ho_offc_cnt_jtama_P" href="#"><?php echo $main_count_array['for_jt_ama']['pending']; ?></a>, R:<a class="ho_offc_cnt_jtama_R" href="#"><?php echo $main_count_array['for_jt_ama']['replied']; ?></a> )</h6>
						
					</div>
			<?php }
						
						
			 }elseif($current_user_roles['ama'] == 'yes'){ 
			 
				$ho_offc_ama_cnt = $main_count_array['for_ama']['pending']+$main_count_array['for_ama']['replied'];
				
				if($ho_offc_ama_cnt != 0){ 
				
					$pending_work = 'yes'; ?>
			
					<div class="role_section">
						<h6>As AMA Officer( P:<a class="ho_offc_cnt_ama_P" href="#"><?php echo $main_count_array['for_ama']['pending']; ?></a>, R:<a class="ho_offc_cnt_ama_R" href="#"><?php echo $main_count_array['for_ama']['replied']; ?></a> )</h6>
						
					</div>
			<?php }
						

			}elseif($current_user_roles['ho_mo_smo'] == 'yes'){ 
			
				$ho_offc_scru_cnt = $main_count_array['for_ho_scrutiny']['pending']+$main_count_array['for_ho_scrutiny']['replied'];
				
				if($ho_offc_scru_cnt != 0){ 
				
					$pending_work = 'yes'; ?>
			
					<div class="role_section">
						<h6>As Scrutiny Officer (HO QC)</h6>
						<table>
							<tr>
								<td>From HO Office ( P:<a class="scr_offc_cnt_ho_P" href="#"><?php echo $main_count_array['for_ho_scrutiny']['pending']; ?></a>, R:<a class="scr_offc_cnt_ho_R" href="#"><?php echo $main_count_array['for_ho_scrutiny']['replied']; ?></a> )</td>
							</tr>					
						</table>
						
					</div>
			<?php 	}

			 }elseif($current_user_roles['pao'] == 'yes'){ 
			 
				$pao_offc_cnt = $main_count_array['payment']['pending']+$main_count_array['payment']['replied'];
				
				if($pao_offc_cnt != 0){ 
				
					$pending_work = 'yes'; ?>
					
					<div class="role_section">
						<h6>As PAO/DDO Officer( P:<a class="pao_offc_cnt_P" href="#"><?php echo $main_count_array['payment']['pending']; ?></a>, R:<a class="pao_offc_cnt_R" href="#"><?php echo $main_count_array['payment']['replied']; ?></a> )</h6>
						
					</div>
				<?php }

			 } ?>
			 
			 
			 <?php if($pending_work == 'no'){ ?>No Pending Work<?php } ?>

        </div>
        
        <!-- Modal footer -->
       <!-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div> -->
        
      </div>
    </div>
  </div>

<?php echo $this->Html->script('dashboard/dashboard-main-counts-js'); ?>
