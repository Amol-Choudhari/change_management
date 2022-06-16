<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Datasource\ConnectionManager;
use phpDocumentor\Reflection\Types\This;

	class ChemistverificationsController extends AppController{


        var $name = 'Chemistverifications';

        //to initialize our custom requirements
        public function initialize(): void {
            parent::initialize();

            //Load Components
            $this->loadComponent('RequestHandler');
            $this->loadComponent('Createcaptcha');
            //Set helpers
            $this->viewBuilder()->setHelpers(['Form','Html','Time']);
            //Set Layout
            $this->viewBuilder()->setLayout('admin_dashboard');
            //Set Session
            $this->Session = $this->getRequest()->getSession();

            //Load Models
            $this->loadModel('DmiUserRoles');
            $this->loadModel('DmiChemistFinalSubmits');
            $this->loadModel('DmiChemistAllocations');
            $this->loadModel('DmiChemistRegistrations');

        }


        //Before Filter
        public function beforeFilter($event) {
            parent::beforeFilter($event);

            if ($this->Session->read('username') == null) {

                echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot');?>users/login_user">Please Login</a><?php
                exit();

            } else {
                //checkif user have HO level roles

                $user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('OR'=>array('ro_inspection'=>'yes','so_inspection'=>'yes','user_email_id'=>$this->Session->read('username')))))->first();

                if (empty($user_access)) {

                    echo "Sorry.. You don't have permission to view this page";
                    exit();

                }
            }

            $user_name = $this->Session->read('username');

            $allocated_chemist =  $this->DmiChemistAllocations->find('list',array('valueField'=>'customer_id','conditions'=>array('level_1 IS'=>$user_name,'current_level IS'=>$user_name)))->toArray();

            $chemist_total_count = count($allocated_chemist);
            $chemist_pending_count = 0;
            $chemist_referred_back_count = 0;
            $chemist_replied_count = 0;
            $chemist_approved_count = 0;


            if (!empty($allocated_chemist)) {

                foreach ($allocated_chemist as $each_customer) {

                    $final_submit_list = $this->DmiChemistFinalSubmits->find('all', array('conditions' => array('customer_id IS'=>$each_customer),'order'=>'id desc'))->first();

                    if ($final_submit_list['status'] == 'pending') {

                        $chemist_pending_count = $chemist_pending_count + 1;

                    } elseif ($final_submit_list['status'] == 'referred_back') {

                        $chemist_referred_back_count = $chemist_referred_back_count + 1;

                    }elseif($final_submit_list['status'] == 'replied') {

                        $chemist_replied_count = $chemist_replied_count + 1;

                    }elseif($final_submit_list['status'] == 'approved') {

                        $chemist_approved_count = $chemist_approved_count + 1;
                    }

                }
            }


            $this->set('chemist_total_count',$chemist_total_count);
            $this->set('chemist_pending_count',$chemist_pending_count);
            $this->set('chemist_referred_back_count',$chemist_referred_back_count);
            $this->set('chemist_replied_count',$chemist_replied_count);
            $this->set('chemist_approved_count',$chemist_approved_count);

        }


        //Home Method
        public function home(){
            //Set the Layout
            $this->viewBuilder()->setLayout('admin_dashboard');
        }


        //Pending Chemist Verfication Method
        public function pendingChemistVerification() {


            $user_name = $this->Session->read('username');

            $allocated_chemist =  $this->DmiChemistAllocations->find('list',array('valueField'=>'customer_id','conditions'=>array('level_1 IS'=>$user_name,'current_level IS'=>$user_name)))->toArray();

            $i=0;
            $chemist_list =  array();

            if (!empty($allocated_chemist)) {

                foreach ($allocated_chemist as $each_customer) {

                    $final_submit_list = $this->DmiChemistFinalSubmits->find('all', array('conditions' => array('customer_id IS'=>$each_customer),'order'=>'id desc'))->first();

                    if ($final_submit_list['status'] == 'pending') {

                        $chemist_details = $this->DmiChemistRegistrations->find('all',array('fields'=>array('id','chemist_id','chemist_fname','chemist_lname','created_by'),'conditions'=>array('chemist_id IS'=>$each_customer)))->first();
                        $chemist_list[$i] = $chemist_details;

                    }

                }
            }

            $this->set('chemist_list',$chemist_list);

            $this->render('/elements/chemist_elements/chemist_applications_list_in_verified_mode');

        }


        //Referred Back Chemist Verification Method
        public function referredBackChemistVerification() {

            $user_name = $this->Session->read('username');

            $allocated_chemist =  $this->DmiChemistAllocations->find('list',array('valueField'=>'customer_id','conditions'=>array('level_1 IS'=>$user_name,'current_level IS'=>$user_name)))->toArray();

            $i=0;
            $chemist_list =  array();

            if (!empty($allocated_chemist)) {

                foreach ($allocated_chemist as $each_customer) {

                    $final_submit_list = $this->DmiChemistFinalSubmits->find('all', array('conditions' => array('customer_id IS'=>$each_customer),'order'=>'id desc'))->first();

                    if($final_submit_list['status'] == 'referred_back'){

                        $chemist_details = $this->DmiChemistRegistrations->find('all',array('fields'=>array('id','chemist_id','chemist_fname','chemist_lname','created_by'),'conditions'=>array('chemist_id IS'=>$each_customer)))->toArray();
                        $chemist_list[$i] = $chemist_details;

                    }
                }
            }



            $this->set('chemist_list',$chemist_list);

            $this->render('/elements/chemist_elements/chemist_applications_list_in_verified_mode');

        }


        //Replied Chemist Details Method
        public function repliedChemistDetails(){

            $user_name = $this->Session->read('username');

            $allocated_chemist =  $this->DmiChemistAllocations->find('list',array('valueField'=>'customer_id','conditions'=>array('level_1 IS'=>$user_name,'current_level IS'=>$user_name)))->toArray();

            $i=0;
            $chemist_list =  array();

            if (!empty($allocated_chemist)) {

                foreach ($allocated_chemist as $each_customer) {

                    $final_submit_list = $this->DmiChemistFinalSubmits->find('all', array('conditions' => array('customer_id IS'=>$each_customer),'order'=>'id desc'))->first();

                    if ($final_submit_list['status'] == 'replied') {

                        $chemist_details = $this->DmiChemistRegistrations->find('all',array('fields'=>array('id','chemist_id','chemist_fname','chemist_lname','created_by'),'conditions'=>array('chemist_id IS'=>$each_customer)))->first();
                        $chemist_list[$i] = $chemist_details;

                    }
                }
            }

            $this->set('chemist_list',$chemist_list);

            $this->render('/elements/chemist_elements/chemist_applications_list_in_verified_mode');
        }


        //Chemist Confirmed
        public function chemistConfirmed() {

            $user_name = $this->Session->read('username');

            $allocated_chemist =  $this->DmiChemistAllocations->find('list',array('valueField'=>'customer_id','conditions'=>array('level_1 IS'=>$user_name,'current_level IS'=>$user_name)))->toArray();

            $i=0;
            $chemist_list =  array();

            if (!empty($allocated_chemist)) {

                foreach ($allocated_chemist as $each_customer) {

                    $final_submit_list = $this->DmiChemistFinalSubmits->find('all', array('conditions' => array('customer_id IS'=>$each_customer),'order'=>'id desc'))->first();

                    if($final_submit_list['status'] == 'approved'){

                        $chemist_details = $this->DmiChemistRegistrations->find('all',array('fields'=>array('id','chemist_id','chemist_fname','chemist_lname','created_by'),'conditions'=>array('chemist_id IS'=>$each_customer)))->first();
                        $chemist_list[$i] = $chemist_details;

                    }
                }
            }

            $this->set('chemist_list',$chemist_list);

            $this->render('/elements/chemist_elements/chemist_applications_list_in_verified_mode');
        }


    }
?>
