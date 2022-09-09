<div class="box">
    <!-- form start -->
    <div id="printablediv">
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                    <div class="col-sm-12">
                        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
                    </div>
                    <div class="box-header bg-gray">
                        <h3> <?=$this->lang->line('routinereport_report_for')?> - <?=$this->lang->line('routinereport_routine')?> ( <?=ucwords($routinefor)?> ) </h3>
                    </div><!-- /.box-header -->

                    <?php if(($routinefor == 'student') && count($classes) && isset($sections[$get_section])) { ?>
                        <div class="col-sm-12">
                            <p class="pull-left"><?=$this->lang->line('routinereport_class')?> : <?=isset($classes->classes) ? $classes->classes : ''?></p>                         
                            <p class="pull-right"><?=$this->lang->line('routinereport_section')?> : <?=isset($sections[$get_section]) ? $sections[$get_section] : '' ?></p>                        
                        </div>
                    <?php } elseif(($routinefor == 'teacher') && count($teacher)) { ?>
                        <div class="col-sm-12">
                            <p class="pull-left"><?=$this->lang->line('routinereport_name')?> : <?=$teacher->name?></p>   
                            <p class="pull-right"><?=$this->lang->line('routinereport_designation')?> : <?=$teacher->designation?></p>                        
                        </div>
                    <?php } ?>

                <div class="col-sm-12">
                    <?php
                        $us_days = array('LUNES' => $this->lang->line('monday'), 'MARTES' => $this->lang->line('tuesday'), 'MIERCOLES' => $this->lang->line('wednesday'), 'JUEVES' => $this->lang->line('thursday'), 'VIERNES' => $this->lang->line('friday'), 'SABADO' => $this->lang->line('saturday'), 'DOMINGO' => $this->lang->line('sunday'));
                    ?>
                
                    <?php if(count($routines)) { ?>
                        <?php 
                            $maxClass = 0; 
                            foreach ($routines as $routineKey => $routine) { 
                                if(count($routine) > $maxClass) {
                                    $maxClass = count($routine);
                                }
                            }
                            $dayArrays = array('LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO');
                            $weekenDayArray = array(
                                'DOMINGO'     => '0',
                                'LUNES'     => '1',
                                'MARTES'    => '2',
                                'MIERCOLES'  => '3',
                                'JUEVES'   => '4',
                                'VIERNES'     => '5',
                                'SABADO'   => '6',
                            );
                            $weekends = explode(',', $siteinfos->weekends);
                        ?>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('routinereport_day');?></th>
                                        <?php 
                                            for($i=1; $i <= $maxClass; $i++) {
                                               ?>
                                                    <th><?= addOrdinalNumberSuffix($i)." ".$this->lang->line('routinereport_period');?></th>
                                               <?php
                                            }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dayArrays as $dayArray) { 
                                        if(!in_array($weekenDayArray[$dayArray], $weekends)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $us_days[$dayArray]; ?>
                                            </td>
                                            <?php if(isset($routines[$dayArray])) { ?>
                                                <?php $i=0; foreach ($routines[$dayArray] as $routineDayArrayKey => $routineDayArray) { $i++; ?>
                                                    <td class="text-center">
                                                        <p><?= $routineDayArray->start_time;?>-<?=$routineDayArray->end_time;?></p>
                                                        <p>
                                                            <span class="left"><?=$this->lang->line('routinereport_subject')?> :</span>
                                                            <span class="right"> 
                                                            <?php 
                                                                if(isset($subjects[$routineDayArray->subjectID])) {
                                                                    echo $subjects[$routineDayArray->subjectID];
                                                                }
                                                            ?>
                                                            </span>
                                                        </p>
                                                        <?php if($routinefor == 'student') { ?>
                                                            <p>
                                                                <span class="left"><?=$this->lang->line('routinereport_teacher')?> :</span>
                                                                <span class="right">
                                                                <?php 
                                                                    if(isset($teachers[$routineDayArray->teacherID])) {
                                                                        echo $teachers[$routineDayArray->teacherID];
                                                                    }
                                                                ?>
                                                                </span>
                                                            </p>
                                                        <?php } elseif($routinefor == 'teacher') { ?>
                                                            <p>
                                                                <span class="left"><?=$this->lang->line('routinereport_class')?> :</span>
                                                                <span class="right">
                                                                <?php 
                                                                    if(isset($classes[$routineDayArray->classesID])) {
                                                                        echo $classes[$routineDayArray->classesID];
                                                                    }
                                                                ?>
                                                                </span>
                                                            </p>
                                                            <p>
                                                                <span class="left"><?= $this->lang->line('routinereport_section')?> :</span>
                                                                <span class="right">
                                                                <?php 
                                                                    if(isset($sections[$routineDayArray->sectionID])) {
                                                                        echo $sections[$routineDayArray->sectionID];
                                                                    }
                                                                ?>
                                                                </span>
                                                            </p>
                                                        <?php }?>
                                                        <p><span class="left"><?= $this->lang->line('routinereport_room')?> : </span><span class="right"><?= $routineDayArray->room;?></span></p>
                                                    </td>
                                                <?php } 
                                                    $j = ($maxClass - $i);  if($i < $maxClass) { ?>
                                                    <?php for($i = 1; $i <= $j; $i++) { ?>
                                                    <td align="center">
                                                        S/A
                                                    </td>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php for($i=1; $i<=$maxClass; $i++) { ?>
                                                    <td align="center">
                                                        S/A
                                                    </td>
                                                <?php } ?>                    
                                            <?php } ?>
                                        </tr> 
                                    <?php } else { ?>
                                        <tr>
                                            <td style="font-weight: bold;"><?php echo $us_days[$dayArray]; ?></td>
                                            <?php for($i = 1; $i <= $maxClass; $i++) { ?>
                                            <td style="font-weight: bold;" align="center"><?=$this->lang->line('routinereport_holiday');?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else {  ?>
                        <div class="notfound">
                            <?php echo $this->lang->line('routinereport_data_not_found'); ?>
                        </div>
                   <?php } ?>
                </div>
                <div class="col-sm-12">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>
 


