<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Datasource\ConnectionManager;

class CmsController extends AppController{

	var $name = 'Cms';

	public function beforeFilter($event) {
	parent::beforeFilter($event);

		$this->loadComponent('Commonlistingfunctions');
		$this->loadComponent('Customfunctions');
		$this->loadComponent('Mastertablecontent');
		$this->viewBuilder()->setHelpers(['Form','Html','Time']);
		$this->viewBuilder()->setLayout('admin_dashboard');
		$username = $this->getRequest()->getSession()->read('username');

		if ($username == null) {
			echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getRequest()->getAttribute('webroot');?>users/login_user">Please Login</a><?php
			exit();
		} else {
			$this->loadModel('DmiUsers');
			//check if user entry in Dmi_users table for valid user
			$check_user = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();

			if (empty($check_user)) {
				echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getRequest()->getAttribute('webroot');?>users/login_user">Please Login</a><?php
				exit();
			}
		}
	}


	public function authenticateUserForPagesCms() {

		$this->loadModel('DmiUserRoles');
		$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('OR'=>array('page_draft'=>'yes','page_publish'=>'yes'),'user_email_id IS'=>$this->Session->read('username'))))->first();

		if (!empty($user_access)) {
			//proceed
		} else {
			echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot'); ?>"> Please Login</a><?php
			exit;
		}
	}

	//list all pages
	public function allPages() {

		$this->set('current_menu', 'menu_all_pages');
		//authenticate User
		$this->authenticateUserForPagesCms();

		$this->loadModel('DmiPages');
		$all_pages = $this->DmiPages->find('all',array('conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>'id ASC'))->toArray();

		$this->set('all_pages',$all_pages);

	}

	//to add new page
	public function addPage() {
		
		$this->set('current_menu','menu_add_page');

		//authenticate User
		$this->authenticateUserForPagesCms();

		//load Models
		$this->loadModel('DmiUserRoles');
		$this->loadModel('DmiUserFileUploads');

		//check user role for access
		$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('OR'=>array('page_draft'=>'yes','page_publish'=>'yes'),'user_email_id IS'=>$this->Session->read('username'))))->first();

		//check page role to show status drop down
		if ($user_access['page_draft'] == 'yes' && $user_access['page_publish'] == 'no') {

			$list_status = array('draft'=>'draft');

		} elseif ($user_access['page_publish'] == 'yes') {

			$list_status = array('draft'=>'draft','publish'=>'publish');
		}

		$this->set('list_status',$list_status);

		$uploaded_files = $this->DmiUserFileUploads->find('list',array('keyField'=>'file','valueField'=>'file_name', 'conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();
		$this->set('uploaded_files',$uploaded_files);

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		if ($this->request->is('post')) {

			if ($this->Mastertablecontent->addEditCmsPages($this->request->getData())) {

				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('Add Page','Success');
				$message = 'You have created new page successfully.';
				$message_theme = 'success';
				$redirect_to = 'all_pages';

			} else {

  				//Added this call to save the user action log on 22-02-2022
                $this->Customfunctions->saveActionPoint('Add Page','Failed');
				$message = 'Sorry.. Please Check all fileds are proper.';
				$message_theme = 'failed';
				$redirect_to = 'add_page';

			}
		}

		// set variables to show popup messages from view file
		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);

	}



	//to fetch record id redirect to edit function
	public function fetchPageId($record_id) {

		$this->autoRender = false;
		$this->Session->write('record_id',$record_id);
		$this->Redirect('/cms/edit-page');
	}


	//to Edit page
	public function editPage() {

		//authenticate User
		$this->authenticateUserForPagesCms();

		//load Models
		$this->loadModel('DmiUserRoles');
		$this->loadModel('DmiUserFileUploads');
		$this->loadModel('DmiPages');

		$record_id = $this->Session->read('record_id');

		//check user role for access
		$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('OR'=>array('page_draft'=>'yes','page_publish'=>'yes'),'user_email_id IS'=>$this->Session->read('username'))))->first();

		//check page role to show status drop down
		if ($user_access['page_draft'] == 'yes' && $user_access['page_publish'] == 'no') {

			$list_status = array('draft'=>'draft');

		} elseif ($user_access['page_publish'] == 'yes') {

			$list_status = array('draft'=>'draft','publish'=>'publish');
		}

		$this->set('list_status',$list_status);

		$uploaded_files = $this->DmiUserFileUploads->find('list',array('keyField'=>'file','valueField'=>'file_name', 'conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();
		$this->set('uploaded_files',$uploaded_files);

		//fetch select page record
		$page_details = $this->DmiPages->find('all',array('conditions'=>array('id IS'=>$record_id)))->first();
		$this->set('page_details',$page_details);

		// set variables to show popup messages from view file
		$message = '';
		$redirect_to = '';
		$message_theme = '';

		if ($this->request->is('post')) {

			$postData = $this->request->getData();

			if ($this->Mastertablecontent->addEditCmsPages($postData,$record_id)) {
				
				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('Edit Page','Success');
				$message = 'You have Edited selected page successfully.';
				$message_theme = 'success';

			} else {
				
  				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('Edit Page','Failed');
				$message = 'Sorry.. Please Check all fileds are proper.';
				$message_theme = 'failed';
			}

			$redirect_to = 'edit_page';
		}

		// set variables to show popup messages from view file
		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);

	}


	//function to Delete Record
	public function deletePage($record_id) {

		$this->autoRender = false;
		$this->loadModel('DmiPages');

		$message = '';
		$message_theme = '';
		$redirect_to = '';

		$DmiPagesEntity = $this->DmiPages->newEntity(array(
			'id'=>$record_id,
			'delete_status'=>'yes',
			'modified'=>date('Y-m-d H:i:s')
		));
		
		if ($this->DmiPages->save($DmiPagesEntity)) {

			//Added this call to save the user action log on 22-02-2022
			$this->Customfunctions->saveActionPoint('Page Delete','Success');
			$message = 'The Selected Page is Deleted Successfully !';
			$message_theme = 'success';
			$redirect_to = '../all_pages';
		}

		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);

		if ($message != null) {
            $this->render('/element/message_boxes');
        }
	}

	//to preview page from list
	public function pagePreview($record_id) {

		//authenticate User
		$this->authenticateUserForPagesCms();
		$this->viewBuilder()->setLayout('default');
		$this->loadModel('DmiPages');
		$pagecontents = $this->DmiPages->find('all', array('conditions'=>array('id IS'=>$record_id)))->first();

		$meta_keyword = $pagecontents['meta_keyword'];
		$meta_description = $pagecontents['meta_description'];
		$pagetitle = $pagecontents['title'];
		$pagedata = $pagecontents['content'];

		$this->set(compact('meta_keyword','meta_description','pagetitle','pagedata','pagecontents'));

	}



	// methods for site menus
	//authenticate User
	public function authenticateUserForMenusCms() {

		$this->loadModel('DmiUserRoles');
		$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('menus'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();

		if (!empty($user_access)) {
			//proceed
		} else {
			echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot'); ?>"> Please Login</a><?php
			exit;
		}

	}

	//to list all site memus
	public function allMenus() {

		$this->set('current_menu','menu_all_menus');
		//authenticate User
		$this->authenticateUserForMenusCms();

		$this->loadModel('DmiMenus');
		$all_menus = $this->DmiMenus->find('all',array('conditions'=>array('user_email_id IS'=>$this->Session->read('username'), 'OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order' => 'id ASC'))->toArray();
		$this->set('all_menus',$all_menus);

	}

	//to add new site menu
	public function addMenu() {

		$this->set('current_menu','menu_add_menu');
		//authenticate User
		$this->authenticateUserForMenusCms();
		//load Models
		$this->loadModel('DmiPages');
		$this->loadModel('DmiMenus');

		$list_pages = $this->DmiPages->find('list',array('valueField'=>'title','conditions'=>array('status'=>'publish', 'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();
		//to show side menu list for order no.
		$side_menu_list = $this->DmiMenus->find('all',array('conditions'=>array('position'=>'side','OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>'order_id ASC'))->toArray();
		//to show bottom menu list for order no.
		$bottom_menu_list = $this->DmiMenus->find('all',array('conditions'=>array('position'=>'bottom','OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>'order_id ASC'))->toArray();

		$this->set(compact('bottom_menu_list','side_menu_list','list_pages'));

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		if ($this->request->is('post')) {

			$postData = $this->request->getData();

			if ($this->Mastertablecontent->addEditCmsMenus($postData)) {

				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('Add Menu','Success');
				$message = 'You have created new Menu Succesfully.';
				$message_theme = 'success';
				$redirect_to = 'all_menus';

			} else {

				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('Edit Menu','Failed');
				$message = 'Sorry.. Please Check all fileds are proper.';
				$message_theme = 'failed';
				$redirect_to = 'add_menu';
			}

		}

		// set variables to show popup messages from view file
		$this->set(compact('message','message_theme','redirect_to'));

	}


	//to fetch record id redirect to edit function
	public function fetchMenuId($record_id) {

		$this->autoRender = false;
		$this->Session->write('record_id',$record_id);
		$this->Redirect('/cms/edit-menu');
	}


	//to edit selected site menu
	public function editMenu() {

		//authenticate User
		$this->authenticateUserForMenusCms();
		//load Models
		$this->loadModel('DmiPages');
		$this->loadModel('DmiMenus');

		$record_id = $this->Session->read('record_id');

		$menu_details = $this->DmiMenus->find('all',array('conditions'=>array('id IS'=>$record_id)))->first();

		$list_pages = $this->DmiPages->find('list',array('valueField'=>'title','conditions'=>array('status'=>'publish', 'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();
		//to show side menu list for order no.
		$side_menu_list = $this->DmiMenus->find('all',array('conditions'=>array('position'=>'side','OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>'order_id ASC'))->toArray();
		//to show bottom menu list for order no.
		$bottom_menu_list = $this->DmiMenus->find('all',array('conditions'=>array('position'=>'bottom','OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>'order_id ASC'))->toArray();

		$this->set(compact('menu_details','bottom_menu_list','side_menu_list','list_pages'));

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		if ($this->request->is('post')) {

			$postData = $this->request->getData();

			if ($this->Mastertablecontent->addEditCmsMenus($postData,$record_id)) {

				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('Edit Menu','Success');
				$message = 'You have Updated Menu Succesfully.';
				$message_theme = 'success';

			} else {

				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('Edit Menu','Failed');
				$message = 'Sorry.. Please Check all fileds are proper.';
				$message_theme = 'failed';

			}

			$redirect_to = 'edit_menu';
		}

		// set variables to show popup messages from view file
		$this->set(compact('message','message_theme','redirect_to'));

	}


	//function to Delete Record
	public function deleteMenu($record_id) {

		$this->autoRender = false;
		$this->loadModel('DmiMenus');

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		$DmiMenusEntity = $this->DmiMenus->newEntity(array(
			'id'=>$record_id,
			'delete_status'=>'yes',
			'modified'=>date('Y-m-d H:i:s')
		));

		if ($this->DmiMenus->save($DmiMenusEntity)) {

			//Added this call to save the user action log on 22-02-2022
			$this->Customfunctions->saveActionPoint('Menu Delete','Success');
			// set variables to show popup messages from view file
			$message = 'The Selected Menu is Deleted Successfully';
			$message_theme = 'success';
			$redirect_to = '../all_menus';
		}

		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);
		
		if ($message != null) {
            $this->render('/element/message_boxes');
        }
		
	}


//method to upload file on dashbaord

	//authenticate User
	public function authenticateUserForFileUpload() {

		$this->loadModel('DmiUserRoles');
		$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('file_upload'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();

		if (!empty($user_access)) {
			//proceed
		} else {
			echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot'); ?>"> Please Login</a><?php
			exit;
		}

	}


	public function fileUploads() {

		$this->set('current_menu', 'menu_file_uploads');
		$this->authenticateUserForFileUpload();
		$this->loadModel('DmiUserFileUploads');

		// set variables to show popup messages from view file
		//$message_theme = ""; // set variable for holding message theme value like 'success', 'failed' @by Aniket Ganvir dated 15th DEC 2020
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		$all_files = $this->DmiUserFileUploads->find('all',array('conditions'=>array('delete_status IS NULL'),'order'=>'id desc'))->toArray();
		$this->set('all_files',$all_files);

		if (null !== ($this->request->getData('upload'))) {

			$check_duplicate_filename = $this->DmiUserFileUploads->find('all',array('fields'=>'file_name', 'conditions'=>array('file_name'=>$this->request->getData('file')->getClientFilename())))->first();

			if (!empty($check_duplicate_filename)) {
				
				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('File Upload','Failed');
				$message = 'File with same name is already exist... Please change file name and try again!!';
				$message_theme = 'failed';
				$redirect_to = 'file_uploads';

			} else {
				//file uploading
				if (!empty($this->request->getData('file')->getClientFilename())) {

					$file_name = $this->request->getData('file')->getClientFilename();
					$file_size = $this->request->getData('file')->getSize();
					$file_type = $this->request->getData('file')->getClientMediaType();
					$file_local_path = $this->request->getData('file')->getStream()->getMetadata('uri');
					$file = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path);
				}

				if (!empty($file)) {

					$DmiUserFileUploadsEntity = $this->DmiUserFileUploads->newEntity(array(

						'file'=>$file,
						'file_name'=>$this->request->getData('file')->getClientFilename(),
						'user_email_id'=>$this->Session->read('username'),
						'user_once_no'=>$this->Session->read('once_card_no')
					));

					if ($this->DmiUserFileUploads->save($DmiUserFileUploadsEntity)) {

						//Added this call to save the user action log on 21-02-2022
						$this->Customfunctions->saveActionPoint('File Upload','Success');
						$message = 'Your File is Uploaded Successfully.';
						$message_theme = "success";
						$redirect_to = 'file_uploads';
					}

				} else {
					$message = 'Please select proper file!!';
					$redirect_to = 'file_uploads';
				}
			}
		}

		// set variables to show popup messages from view file
		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);

	}

	//to fetch file id
	public function fetchFileId($id) {

		$this->Session->write('file_id',$id);
		$this->redirect(array('controller'=>'cms','action'=>'file_view'));

	}

	//to preview uploaded file
	public function fileView() {

		$this->authenticateUserForFileUpload();
		$this->loadModel('DmiUserFileUploads');

		$file_id = $this->Session->read('file_id');
		$get_file_path = $this->DmiUserFileUploads->find('all',array('fields'=>'file','conditions'=>array('id IS'=>$file_id)))->first();

		$view_file = $get_file_path['file'];

		$this->set('view_file',$view_file);

	}

	//function to Delete uploaded file
	public function deleteUploadedFile($record_id) {

		$this->autoRender = false;
		$this->loadModel('DmiUserFileUploads');

		$message = "";
		$message_theme = "";
		$redirect_to = "";

		$DmiUserFileUploadsEntity = $this->DmiUserFileUploads->newEntity(array(
			'id'=>$record_id,
			'delete_status'=>'yes',
			'modified'=>date('Y-m-d H:i:s')
		));

		if ($this->DmiUserFileUploads->save($DmiUserFileUploadsEntity)) {

			//Added this call to save the user action log on 21-02-2022
			$this->Customfunctions->saveActionPoint('Uploaded File Delete','Success');
			$message = 'Selected File is Deleted Successfully';
			$message_theme = 'success';
			$redirect_to = '../file_uploads';
		}
			

		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);
		
		if ($message != null) {
            $this->render('/element/message_boxes');
        }
	
	}


}



?>
