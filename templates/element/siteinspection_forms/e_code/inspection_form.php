<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	<div id="form_outer_main" class="col-md-10 form-middle">
		<h5 class="mt-1 mb-2 tacfw700">Site Inspection Details for Approval & Issue of E-Code</h5>
		<div id="form_inner_main" class="card card-success">
		
			<div class="card-header"><h3 class="card-title">Site Inspection Details</h3></div>
			<div class="form-horizontal">
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Packer has inbuilt and automatic system of control and fast speed automatic packing lines?</p>
					<div class="row">
						<div class="col-sm-6">

							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'is_automatic_system', 'value'=>$section_form_details[0]['is_automatic_system'], 'label'=>true);
									echo $this->form->radio('is_automatic_system',$options,$attributes);
								?>
								<div id="error_is_automatic_system"></div>
						</div>
						<div class="col-sm-6">
						
							<label for="inputEmail3">Relevant Doc : </label>
								<?php if (!empty($section_form_details[0]['automatic_system_docs'])) { ?>
									<a id="automatic_system_docs_value" target="blank" href="<?php echo $section_form_details[0]['automatic_system_docs']; ?>">Preview</a>
								<?php } ?>

							<?php echo $this->Form->control('automatic_system_docs',array('type'=>'file', 'id'=>'automatic_system_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
								<div id="error_automatic_system_docs"></div>
								<div id="error_size_automatic_system_docs"></div>
								<div id="error_type_automatic_system_docs"></div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
						
						</div>
					</div>
				
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Separate records has been maintained in separate sections of unit by different section in-charges?</p>
					<div class="row">
						<div class="col-sm-6">

							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'is_separate_records', 'value'=>$section_form_details[0]['is_separate_records'], 'label'=>true);
									echo $this->form->radio('is_separate_records',$options,$attributes);
								?>
								<div id="error_is_separate_records"></div>
						</div>
						<div class="col-sm-6">
						
							<label for="inputEmail3">Relevant Doc : </label>
								<?php if (!empty($section_form_details[0]['separate_records_docs'])) { ?>
									<a id="separate_records_docs_value" target="blank" href="<?php echo $section_form_details[0]['separate_records_docs']; ?>">Preview</a>
								<?php } ?>

							<?php echo $this->Form->control('separate_records_docs',array('type'=>'file', 'id'=>'separate_records_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
								<div id="error_separate_records_docs"></div>
								<div id="error_size_separate_records_docs"></div>
								<div id="error_type_separate_records_docs"></div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
						
						</div>
					</div>
				
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Copies of letters placing order for replica printing?</p>
					<div class="row">
						<div class="col-sm-6">

							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'is_copy_of_orders', 'value'=>$section_form_details[0]['is_copy_of_orders'], 'label'=>true);
									echo $this->form->radio('is_copy_of_orders',$options,$attributes);
								?>
								<div id="error_is_copy_of_orders"></div>
						</div>
						<div class="col-sm-6">
						
							<label for="inputEmail3">Relevant Doc : </label>
								<?php if (!empty($section_form_details[0]['copy_of_orders_docs'])) { ?>
									<a id="copy_of_orders_docs_value" target="blank" href="<?php echo $section_form_details[0]['copy_of_orders_docs']; ?>">Preview</a>
								<?php } ?>

							<?php echo $this->Form->control('copy_of_orders_docs',array('type'=>'file', 'id'=>'copy_of_orders_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
								<div id="error_copy_of_orders_docs"></div>
								<div id="error_size_copy_of_orders_docs"></div>
								<div id="error_type_copy_of_orders_docs"></div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
						
						</div>
					</div>
				
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Copies of printing order carried out by the printing press?</p>
					<div class="row">
						<div class="col-sm-6">

							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'is_copy_of_printing', 'value'=>$section_form_details[0]['is_copy_of_printing'], 'label'=>true);
									echo $this->form->radio('is_copy_of_printing',$options,$attributes);
								?>
								<div id="error_is_copy_of_printing"></div>
						</div>
						<div class="col-sm-6">
						
							<label for="inputEmail3">Relevant Doc : </label>
								<?php if (!empty($section_form_details[0]['copy_of_printing_docs'])) { ?>
									<a id="copy_of_printing_docs_value" target="blank" href="<?php echo $section_form_details[0]['copy_of_printing_docs']; ?>">Preview</a>
								<?php } ?>

							<?php echo $this->Form->control('copy_of_printing_docs',array('type'=>'file', 'id'=>'copy_of_printing_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
								<div id="error_copy_of_printing_docs"></div>
								<div id="error_size_copy_of_printing_docs"></div>
								<div id="error_type_copy_of_printing_docs"></div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
						
						</div>
					</div>
				
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Stock register of empty containers (packing material)?</p>
					<div class="row">
						<div class="col-sm-6">

							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'reg_of_empty_container', 'value'=>$section_form_details[0]['reg_of_empty_container'], 'label'=>true);
									echo $this->form->radio('reg_of_empty_container',$options,$attributes);
								?>
								<div id="error_reg_of_empty_container"></div>
						</div>
						<div class="col-sm-6">
						
							<label for="inputEmail3">Relevant Doc : </label>
								<?php if (!empty($section_form_details[0]['empty_container_docs'])) { ?>
									<a id="empty_container_docs_value" target="blank" href="<?php echo $section_form_details[0]['empty_container_docs']; ?>">Preview</a>
								<?php } ?>

							<?php echo $this->Form->control('empty_container_docs',array('type'=>'file', 'id'=>'empty_container_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
								<div id="error_empty_container_docs"></div>
								<div id="error_size_empty_container_docs"></div>
								<div id="error_type_empty_container_docs"></div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
						
						</div>
					</div>
				
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Issue register of empty containers size-wise and commodity-wise?</p>
					<div class="row">
						<div class="col-sm-6">

							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'issue_of_empty_container', 'value'=>$section_form_details[0]['issue_of_empty_container'], 'label'=>true);
									echo $this->form->radio('issue_of_empty_container',$options,$attributes);
								?>
								<div id="error_issue_of_empty_container"></div>
						</div>
						<div class="col-sm-6">
						
							<label for="inputEmail3">Relevant Doc : </label>
								<?php if (!empty($section_form_details[0]['issue_of_empty_container_docs'])) { ?>
									<a id="issue_of_empty_container_docs_value" target="blank" href="<?php echo $section_form_details[0]['issue_of_empty_container_docs']; ?>">Preview</a>
								<?php } ?>

							<?php echo $this->Form->control('issue_of_empty_container_docs',array('type'=>'file', 'id'=>'issue_of_empty_container_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
								<div id="error_issue_of_empty_container_docs"></div>
								<div id="error_size_issue_of_empty_container_docs"></div>
								<div id="error_type_issue_of_empty_container_docs"></div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
						
						</div>
					</div>
				
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Stock register of raw material?</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'reg_of_raw_materials', 'value'=>$section_form_details[0]['reg_of_raw_materials'], 'label'=>true);
									echo $this->form->radio('reg_of_raw_materials',$options,$attributes);
								?>
								<div id="error_reg_of_raw_materials"></div>
						</div>
					</div>			
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Register Showing Daily Production?</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'reg_daily_production', 'value'=>$section_form_details[0]['reg_daily_production'], 'label'=>true);
									echo $this->form->radio('reg_daily_production',$options,$attributes);
								?>
								<div id="error_reg_daily_production"></div>
						</div>
					</div>			
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Registers maintained in packing section showing daily account of quantity packed size-wise?</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'reg_daily_account_qty', 'value'=>$section_form_details[0]['reg_daily_account_qty'], 'label'=>true);
									echo $this->form->radio('reg_daily_account_qty',$options,$attributes);
								?>
								<div id="error_reg_daily_account_qty"></div>
						</div>
					</div>			
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Register showing date-wise and packsize-wise damaged containers, if any (during packing)?</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'reg_damaged_container', 'value'=>$section_form_details[0]['reg_damaged_container'], 'label'=>true);
									echo $this->form->radio('reg_damaged_container',$options,$attributes);
								?>
								<div id="error_reg_damaged_container"></div>
						</div>
					</div>			
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Stock register in the store room/cold storage showing daily stock?</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'reg_showing_daily_stock', 'value'=>$section_form_details[0]['reg_showing_daily_stock'], 'label'=>true);
									echo $this->form->radio('reg_showing_daily_stock',$options,$attributes);
								?>
								<div id="error_reg_showing_daily_stock"></div>
						</div>
					</div>			
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Sale register/sale invoice?</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'reg_sale_invoice', 'value'=>$section_form_details[0]['reg_sale_invoice'], 'label'=>true);
									echo $this->form->radio('reg_sale_invoice',$options,$attributes);
								?>
								<div id="error_reg_sale_invoice"></div>
						</div>
						
						<div class="col-sm-6">
						
							<label for="inputEmail3">Relevant Doc : </label>
								<?php if (!empty($section_form_details[0]['reg_sale_invoice_docs'])) { ?>
									<a id="reg_sale_invoice_docs_value" target="blank" href="<?php echo $section_form_details[0]['reg_sale_invoice_docs']; ?>">Preview</a>
								<?php } ?>

							<?php echo $this->Form->control('reg_sale_invoice_docs',array('type'=>'file', 'id'=>'reg_sale_invoice_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
								<div id="error_reg_sale_invoice_docs"></div>
								<div id="error_size_reg_sale_invoice_docs"></div>
								<div id="error_type_reg_sale_invoice_docs"></div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
						
						</div>
					</div>			
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Packer should have graded during the previous year a minimum prescribed quantity for each commodity</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'graded_min_quantity', 'value'=>$section_form_details[0]['graded_min_quantity'], 'label'=>true);
									echo $this->form->radio('graded_min_quantity',$options,$attributes);
								?>
								<div id="error_graded_min_quantity"></div>
						</div>
						
						<div class="col-sm-6">
							<label for="inputEmail3">Relevant Doc : </label>
								<?php if (!empty($section_form_details[0]['graded_min_qty_docs'])) { ?>
									<a id="graded_min_qty_docs_value" target="blank" href="<?php echo $section_form_details[0]['graded_min_qty_docs']; ?>">Preview</a>
								<?php } ?>

							<?php echo $this->Form->control('graded_min_qty_docs',array('type'=>'file', 'id'=>'graded_min_qty_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
								<div id="error_graded_min_qty_docs"></div>
								<div id="error_size_graded_min_qty_docs"></div>
								<div id="error_type_graded_min_qty_docs"></div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
						</div>
					</div>			
				</div>
				
				<div class="card-body">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Packer shall have to grade 100% of the production of the commodity?</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Conditions Fulfilled?</span>	</label>
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'grade_100_per_prod', 'value'=>$section_form_details[0]['grade_100_per_prod'], 'label'=>true);
									echo $this->form->radio('grade_100_per_prod',$options,$attributes);
								?>
								<div id="error_grade_100_per_prod"></div>
						</div>
					</div>			
				</div>
				
				<div class="card-body form-group">
					<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Any recommendation regarding the site inspection?</p>
					<div class="row">
						<div class="col-sm-6">
							<label for="field3"><span>Recommendations</span>	</label>
								<?php
									echo $this->form->control('recommendations',array('type'=>'textarea','id'=>'recommendations','value'=>$section_form_details[0]['recommendations'],'label'=>false,'class'=>'form-control'));
								?>
								<div id="error_recommendations"></div>
						</div>
					</div>			
				</div>
				
			</div>
		</div>
	</div>
	
	<?php echo $this->Html->script('element/siteinspection_forms/e_code/report_form_js'); ?>
