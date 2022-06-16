<?php?>
<div class="table-format">

<div class="admin-main-page">
	<h5>Given Below is list of Grant Certificates for CA</h5>
</div>

<table>
	<thead>
		<tr>
			<th>Applicant ID</th>
			<th>Firm Name</th>
			<th>Grant Certificate</th>
			<th>Granted Date</th>
		</tr>
		
		
		<?php
		$ca=0;
		if(!empty($all_ca_grant_certificates)){
			foreach($all_ca_grant_certificates as $each_certificate){ ?>
				
				<tr>
					<td><?php echo $each_certificate['customer_id'];?></td>
					<td><?php echo $f_name['ca'][$ca];?></td>
					<td><?php $split_file_path = explode("/",$each_certificate['pdf_file']);
											$file_name = $split_file_path[count($split_file_path) - 1]; ?>
									
						<a target="blank" href="<?php echo $each_certificate['pdf_file']; ?>">
							<?php echo $file_name; ?>
						</a>
					</td>
					<td><?php echo $each_certificate['modified'];?></td><!-- used modified column insteead od date on 06-06-2019 -->    
				</tr>
				
		<?php $ca=$ca+1; } }else{ ?>
		
				<tr>				
					<td></td>
					<td>Currently there are no Grant Certificates for CA</td>					    
				</tr>
				
		
		
		<?php	} ?>
		
		</thead>
					
						
	</table>

</div>









<div class="table-format">
<div class="inspection">

<div class="admin-main-page">
	<h5>Given Below is list of Grant Certificates for Printing Press</h5>
</div>

<table>
	<thead>
		<tr>
			<th>Applicant ID</th>
			<th>Firm Name</th>
			<th>Grant Certificate</th>
			<th>Granted Date</th>
		</tr>
		
		
		<?php
		$pp=0;
		if(!empty($all_printing_grant_certificates)){
			foreach($all_printing_grant_certificates as $each_certificate){ ?>
				
				<tr>
					<td><?php echo $each_certificate['customer_id'];?></td>
					<td><?php echo $f_name['pp'][$pp];?></td>
					<td><?php $split_file_path = explode("/",$each_certificate['pdf_file']);
											$file_name = $split_file_path[count($split_file_path) - 1]; ?>
									
						<a target="blank" href="<?php echo $each_certificate['pdf_file']; ?>">
							<?php echo $file_name; ?>
						</a>
					</td>
					<td><?php echo $each_certificate['modified'];?></td><!-- used modified column insteead od date on 06-06-2019 -->
					
				</tr>
				
				
				
		<?php $pp=$pp+1; } }else{ ?>
		
				<tr>
				
					<td></td>
					<td>Currently there are no Grant Certificates for Printing press</td>
					    
				</tr>
				
		
		
		<?php	} ?>
		
		</thead>
					
						
	</table>
</div>
</div>


<!-- Find the Final Granded laboratory Application pdf file By pravin (19/05/2017) -->
<div class="table-format">
<div class="inspection">

<div class="admin-main-page">
	<h5>Given Below is list of Grant Certificates for Laboratory</h5>
</div>

<table>
	<thead>
		<tr>
			<th>Applicant ID</th>
			<th>Firm Name</th>
			<th>Grant Certificate</th>
			<th>Granted Date</th>
		</tr>
		
		
		<?php
		$lb=0;
		if(!empty($all_laboratory_grant_certificates)){
			foreach($all_laboratory_grant_certificates as $each_certificate){ ?>
				
				<tr>
					<td><?php echo $each_certificate['customer_id'];?></td>
					<td><?php echo $f_name['lb'][$lb];?></td>
					<td><?php $split_file_path = explode("/",$each_certificate['pdf_file']);
											$file_name = $split_file_path[count($split_file_path) - 1]; ?>
									
						<a target="blank" href="<?php echo $each_certificate['pdf_file']; ?>">
							<?php echo $file_name; ?>
						</a>
					</td>
					<td><?php echo $each_certificate['modified'];?></td><!-- used modified column insteead od date on 06-06-2019 -->
					
				</tr>
				
				
				
		<?php $lb=$lb+1; } }else{ ?>
		
				<tr>
				
					<td></td>
					<td>Currently there are no Grant Certificates for Laboratory</td>
					    
				</tr>
				
		
		
		<?php	} ?>
		
		</thead>
					
						
	</table>
</div>
</div>