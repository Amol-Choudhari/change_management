	<?php 
	
		$Dmi_ca_export_siteinspection_report = new Dmi_ca_export_siteinspection_report();
		$ca_export_report_details = $Dmi_ca_export_siteinspection_report->firm_siteinspection_report_details($customer_id);
	
	?>
	
	<?php  echo $this->element('siteinspection/report-top-heading-section'); ?>
 
	 <div>
		 <h4 class="lh40tac"> Inspection Report for Approval of Certification of Authorisation(Export)</h4></label>
    </div>


	<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'siteinspection_report')); ?>
	<div id="form_outer_main" class="form-style-3">
		<div id='form_inner_main'>
		<h2>Certification of Authorisation(Export) Report upload</h2>
		
		
		<!-- Below code added on 10-10-2017 by Amol to get/show Directors details for IO/RO -->
		
	
		<!-- this view is for IO Window -->
			<div class="card-header"><h3 class="card-title-new">Director/Partner/Proprietor/Owner Details</h3></div>
				<div class="tank_table">
				<!-- call table view form element with ajax call -->
				<?php echo $this->element('siteinspection\directors_details_table_view'); ?>
				</div>
				

						
		
		<fieldset>
			<legend><?php if($report_edit_mode != 'No'){ echo 'Give Remark and Upload Report'; }else{ echo 'Given Remark and Uploaded Report'; } ?></legend>
		
			<label for="field3"><p><span><?php if($report_edit_mode != 'No'){ echo 'Give Remark'; }else{ echo 'Given Remark'; } ?></span></p>
				
				<?php 
						if(!empty($ca_export_report_details)){$remark_on_report = $ca_export_report_details['remark_on_report']; }else{ $remark_on_report = null; }
						echo $this->form->input('remark_on_report', array('type'=>'textarea', 'id'=>'remark_on_report', 'value'=>$remark_on_report, 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Remark for this report')); ?>
			</label>
			<div id="error_remark_on_report"></div>
			
			<div>
			<label for="field3"><p><span><?php if($report_edit_mode != 'No'){ echo 'Upload Report'; }else{ echo 'Uploaded Report'; } ?></span></p>	
				
				<span class="float-left"><?php if($report_edit_mode != 'No'){ echo 'Attach File'; }else{ echo 'Attached File'; } ?> :
					<?php if(!empty($ca_export_report_details['report_docs'])){ ?>
						<a id="report_docs_value" target="blank" href="<?php echo $ca_export_report_details['report_docs']; ?>">Preview</a>
					<?php }else{ echo "No Document Provided" ;} ?>
				</span>
				
					<?php if($report_edit_mode != 'No'){ echo $this->form->input('report_docs',array('type'=>'file', 'id'=>'report_docs', 'onchange'=>'file_browse_onclick(id);return false',  'multiple'=>'multiple', 'label'=>false)); ?>	
						<p class="file_limits">File type: pdf,jpg & Max-size:2mb</p>
					<?php } ?>
			</label>
			<div id="error_type_report_docs"></div> 
			<div id="error_size_report_docs"></div> 
			<div id="error_report_docs"></div>
			</div>	
			
		</fieldset>
		
	</div>	
		<div class="form-buttons">
		
		<?php echo $this->element('siteinspection/communication/buttons'); ?>
		
		</div>
				
	<?php echo $this->Form->end(); ?>
	</div>	
	
	
	<script>

	<?php if($report_edit_mode == 'No'){ ?>
	
		$( document ).ready(function() {
			
			$("#form_inner_main :input").prop("disabled", true);
			$("#form_inner_main :input[type='submit']").css('display','none');
			$("#form_inner_main :input[type='textarea']").prop("disabled", true);
			$("#form_inner_main :input[type='file']").prop("disabled", true);
			
			$(".director_edit").css('display','none');
			$(".director_delete").css('display','none');
			$("#add_new_row").css('display','none');
			$("#add_directors_details").css('display','none');
				
		});
		
	<?php } ?>	

	</script>	
	