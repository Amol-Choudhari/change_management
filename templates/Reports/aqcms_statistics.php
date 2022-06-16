<?php echo $this->Html->css('Reports/aqcms_statistics') ?>

<div class="content-wrapper bg-bg">
    <div class="content-header page-header" id="page-load">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h4 class="m-0"><?php echo $report_name; ?></h4></div>
                <div class="col-sm-6 my-auto">
                  <ol class="breadcrumb float-sm-right">
                    <span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
					<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
					<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header"><?php echo $report_name; ?></span></span>
                  </ol>
              </div>
			</div>
        </div>
    </div>

    <!-- <section class="content form-middle"> -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 bg-bg">
                <div class="px-4 page-header">
                    <?php echo $this->Form->create(null); ?>
                        <div class="bg-transparent">
                            <div class="" id="search_by_options">
                                <div class="row report-filter ro_report-filter pt-2">
                                  <div class="col-sm-1">
                                    <div class="btn text-light option-menu-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Stats were last updated at <?php echo $statistics_counts[0]['modified']; ?>. Use 'Search' option for lastest figures">
                                      <i class="fas fa-info-circle"></i>
                                    </div>
                                  </div>
                                  <div class="col-sm-3">
                                    <div class="form-group">
	                                  <?php echo $this->form->input('ro_id', array('type'=>'select', 'value'=>'','options'=>$ro_name_list, 'label'=>false, 'id'=>'office', 'empty'=>'RO Incharge List', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
								    </div>
							      </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <!-- <label>From Date</label> -->
                                    <?php echo $this->form->input('from_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'From Date')); ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <!-- <label id="to_date_label">To Date</label> -->
                                    <?php echo $this->form->input('to_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'To Date')); ?>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <!-- <i class="fas fa-search"></i> -->
                                    <button id="search_btn" type="submit" name="search" class="btn text-light option-menu-btn" value="Search" data-bs-toggle="tooltip" data-bs-placement="top" title="Search"><i class="fas fa-search mr-2 mx-auto"></i></button>
                                    <!-- <input id="search_btn" type="submit" name="search" class="form-control " value="Search" > -->
                                </div>
                                <div class="col-sm-1">
                                    <?php echo $this->element('download_report_excel_format/report_download_button'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <!-- </section> -->

    <div class="bg-bg">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mx-5">
                        <?php ?> <span class="badge bg-light shadow">RESULT</span><i class="fas fa-chevron-right px-2" ></i> <?php
                              if(empty($ro_id) && empty($from_date) && empty($to_date)) {
                                ?> <span class="badge bg-grad2 mr-3 shadow"> <?php echo "All"; ?> </span> <?php
                              }

                              if(!empty($ro_id)) {
                                ?>  <span class="badge rounded-pill bg-grad1 shadow">RO Incharge</span>
                                    <i class="fas fa-arrow-right"></i>
                                    <span class="mr-3"> <?php $explode_RO = explode('(',$ro_name_list[$ro_id]); ?>
                                    <span class="badge bg-grad2 shadow"><?php echo "$explode_RO[0]"; ?> </span>
                                    <span class="badge bg-grad1 shadow"><?php $explode_email = explode(')',$explode_RO[1]); ?>
                                     <?php echo $explode_email[0]; ?> </span>
                                    <span class="badge bg-grad2 shadow"><?php $explode_ROcity = explode(')',$explode_RO[2]); ?>
                                     <?php echo "$explode_ROcity[0]"; ?> </span></span> <?php
                              }

                              if(!empty($from_date)) {
                                ?>  <span class="badge rounded-pill bg-grad1 shadow">From Date</span>
                                    <i class="fas fa-caret-right"></i>
                                    <span class="badge bg-grad2 mr-3 shadow"> <?php echo "$from_date"; ?> </span> <?php
                              }

                              if(!empty($to_date)) {
                                ?> <span class="badge rounded-pill bg-grad1 shadow">To Date</span>
                                    <i class="fas fa-caret-right"></i>
                                    <span class="badge bg-grad2 shadow"> <?php echo "$to_date"; ?> </span> <?php
                              }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content form-middle">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                                if (empty($aqcms_statistics_report)) {
                                    echo $this->element('aqcms_statistic/aqcms_statistics_without_search');
                                } else {
                                    echo $this->element('aqcms_statistic/aqcms_statistics_with_search');
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </section>

            <div class="ml-3 mt-3">
        		<h5><a href="<?php echo $this->request->getAttribute('webroot');?>reports/<?php echo $backAction; ?>" class="report-back-button btn back-btn shadow" role="button">Back</a></h5>
           </div>
       </div>
   </div>

   <?php echo $this->Html->script('Reports/aqcms_statistics'); ?>
