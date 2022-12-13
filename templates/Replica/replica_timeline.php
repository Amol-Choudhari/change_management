<?php echo $this->Html->css('element/line'); ?>
<ul class="timeline">
    <li id="aps">Advance Payment Submission</li>
    <li id="apa">Advance Payment Approval</li>
    <li id="cr">Chemist Registration</li>
    <li id="ca">Chemist Approval</li>
    <li id="scic">Set Chemist In-Charge</li>
    <li id="appal">Attach Printing Press and Laboratory</li>
</ul>

<input type="hidden" id="advPaymentStatus_id" value="<?php echo $advPaymentStatus; ?>">
<input type="hidden" id="chemist_registration_id"   value="<?php echo $isChemistRegistered; ?>">
<input type="hidden" id="chemist_approval_id"   value="<?php echo $isChemistApproved; ?>">
<input type="hidden" id="set_chemist_in_charge_id"  value="<?php echo $isChemistIncharge ?>">
<input type="hidden" id="attach_pp_id"  value="<?php echo $isPpAttached ?>">
<input type="hidden" id="attach_lab_id"  value="<?php echo $isLabAttached ?>">

<?php echo $this->Html->script('element/linee'); ?>