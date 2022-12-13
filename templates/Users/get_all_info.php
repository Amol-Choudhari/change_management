

<?php 
if (empty($dde)) {
    $dde['cus'] = null;
    $dde['current_level'] = null;
    $dde['current_user_email_id'] = null;
    $dde['firm_type'] = null;
    $dde['oldornew'] = null;
    $dde['office'] = null;
    $dde['bevo'] = null;
    $dde['export_unit'] = null;
    $dde['current_status'] = null;
    $dde['valid_for_renewal'] = null;
    $dde['formDes'] = null;
} 
    
?>


<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data','id'=>'user_profile')); ?>
<label for="field3">Customer ID</label>
<?php echo $this->Form->control('customer_id', array('type'=>'text','label'=>false, 'escape'=>false, 'id'=>'customer_id', 'class'=>'input-field form-control')); ?>
<?php echo $this->Form->control('See', array('type'=>'submit', 'name'=>'submit', 'label'=>false,'class'=>'btn btn-success submit_btn float-left')); ?>

<?php echo $this->Form->end(); ?>

<table class="table table-bordered mt-4 ">
  <tbody>
    <tr><td >Customer ID</td><td ><?php echo $dde['cus']; ?></td></tr>
    <tr><td >Current Level</td><td ><?php echo $dde['current_level']; ?></td></tr>
    <tr><td >Current User Email</td><td ><?php echo $dde['current_user_email_id']; ?></td></tr>
    <tr><td >Firm Type</td><td ><?php echo $dde['firm_type']; ?></td></tr>
    <tr><td >Form Type</td><td ><?php echo $dde['formDes']; ?></td></tr>
    <tr><td >OLD/NEW</td><td ><?php echo strtoupper($dde['oldornew']); ?></td></tr>
    <tr><td >Office Type</td><td ><?php echo $dde['office']; ?></td></tr>
    <tr><td >BEVO</td><td ><?php echo $dde['bevo']; ?></td></tr>
    <tr><td >EXPORT</td><td ><?php echo $dde['export_unit']; ?></td></tr>
    <tr><td >STATUS</td><td ><?php echo $dde['current_status']; ?></td></tr>
    <tr><td >RENEWAL</td><td ><?php echo $dde['valid_for_renewal']; ?></td></tr>

  </tbody>
</table>