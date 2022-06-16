<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiSiteinspectionReportPdfRecordsTable extends Table{
	
	var $name = "DmiSiteinspectionReportPdfRecords";
	var $useTable = 'dmi_siteinspection_report_pdf_records';
}

?>