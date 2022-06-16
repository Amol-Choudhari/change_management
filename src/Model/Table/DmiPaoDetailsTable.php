<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
class DmiPaoDetailsTable extends Table{
	
	var $name = "DmiPaoDetails";
	
	var $useTable = 'dmi_pao_details';
	
	public $validate = array(
	
		'pao_user_id'=>array(
			'rule1'=>array(
					'rule'=>array('maxLength',100),
					'allowEmpty'=>false,
					'last'=>false,
				),
			'rule2'=>array(
					'rule'=>'Numeric',
				)					
		),
		
		'pao_alias_name'=>array(
					'rule'=>array('maxLength',200),	
					'allowEmpty'=>false,	
			),
		
		'user_email_id'=>array(
					'rule'=>array('maxLength',100),				
				),

		'user_once_no	'=>array(
					'rule'=>array('maxLength',100),				
				),			
	
	);


	//getPaoDetails

	public function getPaoDetails($username,$customer_id) {
		
		$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiApplicantPaymentDetails = TableRegistry::getTableLocator()->get('DmiApplicantPaymentDetails');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		
		if (!empty($customer_id)) {

			$getPaoIds = $DmiApplicantPaymentDetails->find('all', array('fields'=>'pao_id','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			
			
			if(empty($getPaoIds)){
				$firm_details = $DmiFirms->firmDetails($customer_id);
				$pao_id = $DmiDistricts->find('all',array('fields'=>'pao_id','conditions'=>array('id IS'=>$firm_details['district'])))->first();
				$getPaoIds['pao_id'] = $pao_id['pao_id'];
			}

			$paoID = $getPaoIds['pao_id'];
			$getPaoDetails = $this->find('all')->select(['pao_user_id'])->where(['id IS' => $paoID])->first();
			$paoNameDetails = $DmiUsers->find('all')->where(['id IS' => $getPaoDetails['pao_user_id'],'status'=>'active'])->toArray();

		} else {
		
			$posted_ro_office = $DmiUsers->find('all')->select(['posted_ro_office'])->where(['email' => $username,'status'=>'active'])->first();
			$office_type = $DmiRoOffices->find()->select(['office_type'])->where(['id IS' => $posted_ro_office['posted_ro_office']])->first();
		
			if ($office_type['office_type'] == 'SO') {
				$getPaoIds = $DmiDistricts->find('all')->select(['pao_id','pao_id'])->where(['so_id IS' => $posted_ro_office['posted_ro_office']])->group('pao_id')->combine('pao_id','pao_id')->toArray();
			} else {
				$getPaoIds = $DmiDistricts->find('all')->select(['pao_id','pao_id'])->where(['ro_id IS' => $posted_ro_office['posted_ro_office']])->group('pao_id')->combine('pao_id','pao_id')->toArray();
			}
			
			$getPaoDetails = $this->find('all')->select(['pao_user_id','pao_user_id'])->where(['id IN' => $getPaoIds])->combine('pao_user_id','pao_user_id')->toArray();
			$paoNameDetails = $DmiUsers->find('all')->where(['id IN' => $getPaoDetails,'status'=>'active'])->toArray();
		}
		
		return $paoNameDetails;
	}
}

?>