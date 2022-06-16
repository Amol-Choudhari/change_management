<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiRenewalApplicantPaymentDetailsTable extends Table{
	
	var $name = "DmiRenewalApplicantPaymentDetails";
	
	public $validate = array(
					
					'customer_id'=>array(
						'rule'=>array('maxLength',100),				
					),
					
					'once_no'=>array(
						'rule'=>array('maxLength',100),				
					),					
					
					'amount_paid'=>array(
						'rule'=>'Numeric',
						'allowEmpty'=>false,	
					),
					
					'transaction_id'=>array(
						'rule'=>array('maxLength',50),
						'allowEmpty'=>false,	
					),
					
					
					'payment_receipt_docs'=>array(
						'rule'=>array('maxLength',200),
						'allowEmpty'=>false,
					),
					
					'payment_confirmation'=>array(
						'rule'=>array('maxLength',50),						
					),
					
					'pao_id'=>array(
						'rule'=>'Numeric',
						'allowEmpty'=>false,	
					),
					
					'bharatkosh_payment_done'=>array(
						'rule'=>array('maxLength',10),						
					),
					
					'reason_option_comment'=>array(
						'rule1'=>array(
							'rule'=>array('maxLength',200),							
							'last'=>false,
						),
						'Numeric'=>array(
								'rule'=>'Numeric',
								'allowEmpty'=>true,
							)							
					),
				);
}

?>