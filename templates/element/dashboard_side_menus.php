<?php ?>

<?php

// SET ACTIVE MENU (HIGHLIGHT CURRENTLY SELECTED MENU) IN LEFT SIDEBAR
// By Aniket Ganvir dated 8th DEC 2020

if(!isset($current_menu) || $current_menu=='')
{
  $current_menu = 'dashboard';
}

$menu_dashboard = '';
$menu_users = '';
$menu_users_open = '';
$menu_all_users = '';
$menu_add_user = '';
$menu_user_role = '';
$menu_user_role_open = '';
$menu_set_roles = '';
$menu_edit_roles = '';
$menu_file_uploads = '';
$menu_site_pages = '';
$menu_site_pages_open = '';
$menu_all_pages = '';
$menu_add_page = '';
$menu_site_menu = '';
$menu_site_menu_open = '';
$menu_all_menus = '';
$menu_add_menu = '';
$menu_masters = '';
$menu_reports = '';
$menu_granted_application = '';
$menu_feedbacks = '';
$menu_transfer_application = '';
$menu_unlock_accounts = '';
$menu_unlock_accounts_open = '';
$menu_dmi_users = '';
$menu_primary_application = '';
$menu_secondary_application = '';
$menu_go_to_lims = '';

if ($current_menu == 'menu_all_users'){
  $menu_all_users = 'active';
  $menu_users = 'active';
  $menu_users_open = 'menu-open';
}else if($current_menu == 'menu_add_user'){
  $menu_renewal = 'active';
  $menu_users = 'active';
  $menu_apply_open = 'menu-open';
}
if ($current_menu == 'menu_set_roles') {
	$menu_set_roles = 'active';
	$menu_user_role = 'active';
	$menu_user_role_open = 'menu-open';
} else if($current_menu == 'menu_edit_roles'){
	$menu_edit_roles = 'active';
	$menu_user_role = 'active';
	$menu_user_role_open = 'menu-open';
}
if ($current_menu == 'menu_all_pages') {
	$menu_all_pages = 'active';
	$menu_site_pages = 'active';
	$menu_site_pages_open = 'menu-open';
} else if($current_menu == 'menu_add_page'){
	$menu_add_page = 'active';
	$menu_site_pages = 'active';
	$menu_site_pages_open = 'menu-open';
}

if ($current_menu == 'menu_all_menus') {
	$menu_all_menus = "active";
	$menu_site_menu = 'active';
	$menu_site_menu_open = 'menu-open';
} elseif($current_menu == 'menu_add_menu') {
	$menu_add_menu = 'active';
	$menu_site_menu = 'active';
	$menu_site_menu_open = 'menu-open';
}


if ($current_menu == 'menu_dmi_users') {
	$menu_dmi_users = 'active';
	$menu_unlock_accounts = 'active';
	$menu_unlock_accounts_open = 'menu-open';
} else if($current_menu == 'menu_primary_application'){
	$menu_primary_application = 'active';
	$menu_unlock_accounts = 'active';
	$menu_unlock_accounts_open = 'menu-open';
}elseif ($current_menu == 'menu_secondary_application') {
	$menu_secondary_application = 'active';
	$menu_unlock_accounts = 'active';
	$menu_unlock_accounts_open = 'menu-open';
}elseif ($current_menu == 'menu_file_uploads') {
	$menu_file_uploads = 'active';
}elseif ($current_menu == 'menu_masters') {
	$menu_masters = 'active';
}elseif ($current_menu == 'menu_reports') {
	$menu_reports = 'active';
}elseif ($current_menu == 'menu_granted_application') {
	$menu_granted_application = 'active';
}elseif ($current_menu == 'menu_feedbacks') {
	$menu_feedbacks = 'active';
}elseif ($current_menu == 'menu_transfer_application') {
	$menu_transfer_application = 'active';
}elseif ($current_menu == 'menu_go_to_lims') {
	$menu_go_to_lims = 'active';
}else{
	$menu_dashboard = 'active';
}




?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="" class="brand-link">
		<?php echo $this->Html->image('AdminLTELogo.png', array('alt'=>'AQCMS Logo', 'class'=>'brand-image img-circle elevation-3 op8')); ?>
		<span class="brand-text font-weight-light">AQCMS</span>
	</a>

	<div class="sidebar">
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<?php echo $this->Html->image('profile.jpg', array("alt"=>"User Image", "height"=>"255", "class"=>"img-circle elevation-2")); ?>
			</div>
			<div class="info">
				<a href="#" class="d-block"><?php echo $_SESSION["f_name"];?> <?php echo $_SESSION["l_name"];?></a>
				<span class="right badge badge-light"><?php echo $_SESSION["username"];?></span>
			</div>
		</div>

		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="<?php echo $this->request->getAttribute("webroot");?>dashboard/home" class="nav-link">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p class="nav-icon-p">Dashboard</p>
					</a>
				</li>

				<?php if (!empty($current_user_roles)) { ?>

					<?php if($current_user_roles['dy_ama'] == 'yes'){ ?>
						<!-- for granted pdf list -->
						<!--	commented below li on 09-10-2017 by Amol temp. to hide lab export
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Granted Lab Export</a>
								<ul class="submenu nav menu">
									<li><a href="<?php //echo $this->request->getAttribute('webroot');?>hoinspections/grant_certificates_list">All Applications</a></li>
								</ul>
							</li>
						-->

				<?php } if ($current_user_roles['add_user'] == 'yes') { ?>

					<li class="nav-item has-treeview">
						<a href="#" class="nav-link ">
							<i class="nav-icon far fa-user"></i>
							<p>Users<i class="fas fa-angle-left right"></i></p>
						</a>
						<ul class="nav nav-treeview" >
							<li class="nav-item has-treeview">
								<li class="nav-item">
									<a href="<?php echo $this->request->getAttribute("webroot");?>users/all_users" class="nav-link <?php echo $menu_all_users; ?>">
										<i class="far fas fa-users"></i>
										<p class="nav-icon-p">All Users</p>
									</a>
								</li>

								<li class="nav-item">
									<a href="<?php echo $this->request->getAttribute("webroot");?>users/add_user" class="nav-link <?php echo $menu_add_user; ?>">
										<i class="far fas fa-user-plus"></i>
										<p class="nav-icon-p">Add User</p>
									</a>
								</li>
							</li>
						</ul>
					</li>

				<?php } if ($current_user_roles['set_roles'] == 'yes') { ?>

					<li class="nav-item">
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-user-tag"></i>
								<p>Users Roles<i class="fas fa-angle-left right"></i></p>
							</a>
							<ul class="nav nav-treeview" >
								<li class="nav-item has-treeview">
									<li class="nav-item">
										<a href="<?php echo $this->request->getAttribute("webroot");?>roles/set_roles" class="nav-link <?php echo $menu_set_roles; ?>">
											<i class="far fas fa-user-cog nav-icon"></i>

											<p class="nav-icon-p">Set Roles</p>
										</a>
									</li>

									<li class="nav-item">
										<a href="<?php echo $this->request->getAttribute("webroot");?>roles/edit_roles" class="nav-link <?php echo $menu_edit_roles; ?>">
											<i class="far fas fa-user-edit nav-icon"></i>

											<p class="nav-icon-p">Edit Roles</p>
										</a>
									</li>
								</li>
							</ul>
						</li>
					</li>


				<?php }if($current_user_roles['file_upload'] == 'yes'){ ?>

					<li class="nav-item">
						<a href="<?php echo $this->request->getAttribute('webroot');?>cms/file_uploads" class="nav-link <?php echo $menu_file_uploads; ?>">

							<i class="far fas fa-file-upload nav-icon"></i>
							<p class="nav-icon-p">File Uploads</p>
						</a>
					</li>

					<?php } if ($current_user_roles['page_draft'] == 'yes' || $current_user_roles['page_publish'] == 'yes') { ?>

						<li class="nav-item">
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fas fa-copy"></i>
									<p>Site Pages<i class="fas fa-angle-left right"></i></p>
								</a>
								<ul class="nav nav-treeview" >
									<li class="nav-item has-treeview">
										<li class="nav-item">
											<a href="<?php echo $this->request->getAttribute("webroot");?>cms/all_pages" class="nav-link <?php echo $menu_all_pages; ?>">
												<i class="far fa-file nav-icon"></i>

												<p class="nav-icon-p">All Pages</p>
											</a>
										</li>

										<li class="nav-item">
											<a href="<?php echo $this->request->getAttribute("webroot");?>cms/add_page" class="nav-link <?php echo $menu_add_page; ?>">
												<i class="far fas fa-plus nav-icon"></i>

												<p class="nav-icon-p">Add Page</p>
											</a>
										</li>
									</li>
								</ul>
							</li>
						</li>

					<?php } if ($current_user_roles['menus'] == 'yes') { ?>

						<li class="nav-item">
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fas far fa-compass"></i>
									<p>Site Menus<i class="fas fa-angle-left right"></i></p>
								</a>
								<ul class="nav nav-treeview" >
									<li class="nav-item has-treeview">
										<li class="nav-item">
											<a href="<?php echo $this->request->getAttribute("webroot");?>cms/all_menus" class="nav-link <?php echo $menu_all_menus; ?>">
												<i class="far fas fa-sitemap nav-icon"></i>

												<p class="nav-icon-p">All Menus</p>
											</a>
										</li>

										<li class="nav-item">
											<a href="<?php echo $this->request->getAttribute("webroot");?>cms/add_menu" class="nav-link <?php echo $menu_add_menu; ?>">
												<i class="far fas fa-plus nav-icon"></i>
												<p class="nav-icon-p">Add Menu</p>
											</a>
										</li>
									</li>
								</ul>
							</li>
						</li>

					<?php } if ($current_user_roles['masters'] == 'yes') { ?>

						<li class="nav-item">
							<a href="<?php echo $this->request->getAttribute('webroot');?>masters/masters_home" class="nav-link <?php echo $menu_masters; ?>">
								<i class="far fas fa-asterisk nav-icon"></i>
								<p>Masters</p>
							</a>
						</li>

					<?php } if ($current_user_roles['view_reports'] == 'yes') { ?>

						<li class="nav-item">
							<a href="<?php echo $this->request->getAttribute('webroot');?>reports/report_types" class="nav-link <?php echo $menu_reports; ?>">
								<i class="far fas fa-poll nav-icon"></i>
								<p>Reports</p>
							</a>
						</li>

					<?php } if ($current_user_roles['ro_inspection'] == 'yes' || $current_user_roles['so_inspection']=='yes' ||

							//added new conditions on 05-05-2021 by Amol, to show all granted applications to HO users
							$current_user_roles['dy_ama'] == 'yes' || $current_user_roles['jt_ama'] == 'yes' ||
							$current_user_roles['ama'] == 'yes' || $current_user_roles['super_admin'] == 'yes') { ?>

							<!-- for granted pdf list -->
							<li class="nav-item">
								<li class="nav-item has-treeview">
									<a href="#" class="nav-link" class="nav-link <?php echo $menu_granted_application; ?>" >
										<i class="far fa-clock nav-icon"></i>
										<p>Granted Applications</p>
									</a>
									<ul class="nav nav-treeview" >
										<li class="nav-item has-treeview">
											<li class="nav-item">
												<a href="<?php echo $this->request->getAttribute("webroot");?>hoinspections/grant_certificates_list/1" class="nav-link <?php echo $menu_all_menus; ?>">
													<i class="far fas fa-sitemap nav-icon"></i>
													<p class="nav-icon-p">New App.</p>
												</a>
											</li>

											<li class="nav-item">
												<a href="<?php echo $this->request->getAttribute("webroot");?>hoinspections/grant_certificates_list/2" class="nav-link <?php echo $menu_add_menu; ?>">
													<i class="far fas fa-plus nav-icon"></i>
													<p class="nav-icon-p">Renewal App.</p>
												</a>
											</li>
										</li>
									</ul>
								</li>
							</li>

					<?php } if ($current_user_roles['old_appln_data_entry'] == 'yes') { ?>

						<li class="nav-item">
							<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/home" class="nav-link <?php echo $menu_granted_application; ?>">
								<i class="far fa-clock nav-icon"></i>
								<p>Old Applications Entry</p>
							</a>
						</li>

					<?php } if ($current_user_roles['feedbacks'] == 'yes') { ?>

						<li class="nav-item">
							<a href="<?php echo $this->request->getAttribute('webroot');?>feedbacks/all_feedback" class="nav-link <?php echo $menu_feedbacks; ?>">
								<i class="far fas fa-comment-dots nav-icon"></i>
								<p>Feedbacks</p>
							</a>
						</li>

					<?php }

						//created this new menu for transfer of application to another RO, on 02-02-2019 by Amol
						if ($current_user_roles['transfer_appl'] == 'yes') { ?>

						<li class="nav-item">
							<a href="<?php echo $this->request->getAttribute('webroot');?>dashboard/transfer_appl" class="nav-link <?php echo $menu_transfer_application; ?>">
								<i class="far fas fa-exchange-alt nav-icon"></i>
								<p>Transfer Application</p>
							</a>
						</li>

					<?php } if ($current_user_roles['unlock_user'] == 'yes') { ?>

						<li class="nav-item">
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fas fa-unlock-alt"></i>
									<p>Unlock Accounts<i class="fas fa-angle-left right"></i></p>
								</a>
								<ul class="nav nav-treeview" >
									<li class="nav-item has-treeview">
										<li class="nav-item">
											<a href="<?php echo $this->request->getAttribute("webroot");?>users/lock-user-redirect/dmiUsers" class="nav-link <?php echo $menu_all_menus; ?>">
												<i class="far fa-plus-square nav-icon"></i>
												<p class="nav-icon-p">DMI Users</p>
											</a>
										</li>

										<li class="nav-item">
											<a href="<?php echo $this->request->getAttribute("webroot");?>users/lock-user-redirect/primary" class="nav-link <?php echo $menu_add_menu; ?>">
												<i class="far fa-plus-square nav-icon"></i>
												<p class="nav-icon-p">Primary Application</p>
											</a>
										</li>

										<li class="nav-item">
											<a href="<?php echo $this->request->getAttribute("webroot");?>users/lock-user-redirect/secondary" class="nav-link <?php echo $menu_add_menu; ?>">
												<i class="far fa-plus-square nav-icon"></i>
												<p class="nav-icon-p">Secondary Application</p>
											</a>
										</li>
									</li>
								</ul>
							</li>
						</li>

					<?php } ?>

					<!-- added for testing user redirect for DMI and LMIS -->
					<?php if ($current_user_division['division'] == 'BOTH') { ?>

						<?php if($current_user_division['f_name'] == 'LMIS') {?>

							<li class="nav-item">
								<a href="<?php echo $this->request->getAttribute('webroot');?>users/common_user_redirect_login/<?php echo $current_user_division['id']; ?>" class="nav-link">
									<i class="far fas fa-arrow-circle-right nav-icon"></i>
									<p class="nav-icon-p">Go To DMI</p>
								</a>
							</li>

						<?php } else { ?>

							<li class="nav-item">
								<a href="../../LIMS4.2/users/common_user_redirect_login/<?php echo $current_user_division['id']; ?>" class="nav-link">
									<i class="far fas fa-arrow-circle-right nav-icon"></i>
									<p class="nav-icon-p">Go To LIMS</p>
								</a>
							</li>

						<?php } ?>

					<?php } ?>

				<?php } ?>

				<!-- show applicant email details for new audit changes, 25-02-2021, Pravin Bhakare -->
				<li class="nav-item">
					<a href="<?php echo $this->request->getAttribute('webroot');?>users/applicant_details">
						<i class="far fas fa-arrow-circle-right nav-icon"></i>
						<p class="nav-icon-p">Applicant Details</p>
					</a>
				</li>


				<li class="nav-item">
					<a href="<?php echo $this->request->getAttribute('webroot');?>users/logout" class="nav-link">
						<i class="far fas fa-arrow-circle-right nav-icon"></i>
						<p class="nav-icon-p">Logout</p>
					</a>
				</li>
			</ul>
		</nav>
	</div>

</aside>


<div class="clear"></div>

<script>

	$(".nav li a").click(function() {

		$(".nav li").removeClass('active');
		$(this).parent().addClass('active');
	});

</script>
