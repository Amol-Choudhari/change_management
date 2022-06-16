
<?php ?>
<div class="card-header alert alert-dark">
	<h3 class="card-title"><strong>Recent Comments</strong></h3>

	<!--<div class="card-tools">
	  <span data-toggle="tooltip" title="3 New Messages" class="badge badge-primary">3</span>
	  <button type="button" class="btn btn-tool" data-card-widget="collapse">
		<i class="fas fa-minus"></i>
	  </button>
	  <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"
			  data-widget="chat-pane-toggle">
		<i class="fas fa-comments"></i>
	  </button>
	  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	  </button>
	</div>-->
</div>
  <!-- /.card-header -->
  <div class="card-body">
	<!-- Conversations are loaded here -->
	<div class="direct-chat-messages">
	  <!-- Message. Default to the left -->
	  
	  <?php 
		if(!empty($dashboard_comments)){
		foreach($dashboard_comments as $each_comment){ 
		
			if(!empty($each_comment['comment'])){
			?>
			  <div class="direct-chat-msg">
				<div class="direct-chat-infos clearfix">
				  <span class="direct-chat-name float-left"><b>User : </b><?php echo $each_comment['username']; ?></span>
				  <span class="direct-chat-timestamp float-right"><?php echo $each_comment['date']; ?></span>
				</div>
				<!-- /.direct-chat-infos -->
				<img class="direct-chat-img" src="<?php echo $each_comment['profile_pic']; ?>" alt="message user image">
				<!-- /.direct-chat-img -->
				<div class="direct-chat-text">
				  <?php echo '<b>Application Id:</b> '.$each_comment['customer_id']; ?> <br /><b>Comment: </b> <?php echo $each_comment['comment']; ?>
				</div>
				<!-- /.direct-chat-text -->
			  </div>	  
			<?php }
	  
			}
		}else{?>
			<p>No Recent Comments From Last Month</p>
		<?php }	  ?>
	  <!-- /.direct-chat-msg -->

  </div>
  <!-- /.card-body -->
  
  <!-- /.card-footer-->
</div>
<!--/.direct-chat -->

 <p> &nbsp; </p>