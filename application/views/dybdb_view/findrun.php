<?php include 'header.php'; ?>
<?php include 'bookmarks.php'; ?>
<?php include 'breadcrumb.php'; ?>

<div id="content">
    
   <?php if (isset($runlist)): ?>
      <div class = "subject">
         <h2>
             List of <?php echo $comments; ?> Runs
             (<?php echo $runlist->count; ?>) &nbsp;&nbsp; 
             <div id="pagination"><?php echo $pagination; ?></div>
             <div class="inline_right"><a id="show_options" href="#">search options ...</a></div>
        </h2>
        
        <?php include 'search_runlist_div.php'; ?>
        
         <table id="list_of_runs" border = "0" cellpadding="0" cellspacing="1", style="width:99%" class="tableborder">
            <thead><tr>
               <th>Run No</th>
               <th class='RunType'>Run Type</th>
               <th class='StartTime hidden'>Start Time<br /> [UTC]</th>
               <th class='StartTimeBeijing'>Start Time<br /> [Beijing]</th>
               <th class='RunLength'>Run Length [h]</th>
               <th class='PQMPlots'>PQM Plots</th>
               <th class='DiagnosticPlots'>Diagnostic Plots</th>
               <!-- <th class='DataCategory'>Data Category</th>
               <th class='Description'>Description</th> -->
               <th class='Comments'>Comments</th>
               <th class='Elog'>Elog Search</th>
            </tr></thead>
            <tbody>
               <?php foreach($runlist->run_array as $row): ?>
                  <tr>
                     <td><?php echo anchor('dybdb/findrun/' . $row['runNo'], $row['runNo']); ?></td>
                     <td class='RunType'><?php echo $row['runType']; ?></td>
                     <td class='StartTime hidden'><?php echo $row['TIMESTART']; ?></td>
                     <td class='StartTimeBeijing'>
                     <?php 
                        echo date('Y-m-d H:i:s', strtotime($row['TIMESTART'])+8*3600); 
                     ?>
                     </td>
                     <td class='RunLength'><?php echo sprintf("%.2f", (strtotime($row['TIMEEND']) - strtotime($row['TIMESTART']))/3600); ?></td>
                     <td class='PQMPlots' id=<?php echo '"pqm_' . $row['runNo'] . '"';?>>
                     <?php echo anchor('dybdb/findrun/' . $row['runNo'] . '#pqm', 'PQM'); ?>
                     </td>
                     <td class='DiagnosticPlots' id=<?php echo '"diag_' . $row['runNo'] . '"';?>>
                     <?php echo anchor('dybdb/findrun/' . $row['runNo'] . '#diagnostics', 'Diagnostics'); ?>
                     </td>
                     <!-- <td class='DataCategory'><?php echo $runlist->csv_list[$row['runNo']]['DataCategory']; ?></td>
                     <td class='Description'><?php echo $runlist->csv_list[$row['runNo']]['Description']; ?></td> -->
                     <td class='Comments'>
                     <?php 
                        echo $runlist->csv_list[$row['runNo']]['DataCategory'] 
                        . " . " . $runlist->csv_list[$row['runNo']]['Description']
                        . " . " . $runlist->csv_list[$row['runNo']]['Comments']; 
                     ?>
                     </td>
                     <td class='Elog'><?php echo '<a href="http://web.dyb.ihep.ac.cn:8099/Commissioning/?mode=full&reverse=0&reverse=1&npp=20&subtext=' . $row['runNo'] . '">Elog</a>'; ?></td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   <?php endif; ?>
   
   
   <?php if (isset($runinfo)): ?>
      <?php if($currentrun =='sim'): ?>
        <h1 id='sim_data'>Simulation Data</h1>
      <?php endif?>
      <?php if ($runinfo->num_rows == 0): ?>
         <?php if (!isset($currentrun)): ?>
             <h3 id="run_not_exists">Run <?php echo $run; ?> is not in the offline database.</h3>        
         <?php endif; ?>
      <div class = "subject">
        <h2>General Information Run <span id='runno'><?php echo $runinfo->runNo; ?></span></h2>
      </div>
      <?php else: ?>
          <div class = "subject" id="general_div">
            <h2>General Information Run <span id='runno'><?php echo $runinfo->runNo; ?></span></h2>
            <table border = "0" cellpadding="0" cellspacing="1", style="width:99%" class="tableborder">
               <tbody>
                  <tr>
                      <td><h6>Run Number</h6></td><td class='value'><?php echo $runinfo->runNo; ?></td>
                      <td><h6>Start Time [Beijing]</h6></td><td class='value'><?php echo date('Y-m-d H:i:s', strtotime($runinfo->timestart)+8*3600);?></td>
                      <td><h6>Start Time [UTC]</h6></td><td class='value' id='run_start_time'><?php echo $runinfo->timestart; ?></td>
                  </tr>  
                  <tr>
                      <td><h6></h6></td><td class='value'></td>
                      <td><h6>Stop Time [Beijing]</h6></td><td class='value'><?php echo date('Y-m-d H:i:s', strtotime($runinfo->timeend)+8*3600); ?></td>
                      <td><h6>Stop Time [UTC]</h6></td><td class='value'><?php echo $runinfo->timeend; ?></td>
                  </tr>             
                  <tr>
                      <td><h6>Run Type</h6></td><td class='value'><?php echo $runinfo->runType; ?></td>
                      <td><h6>Partition Name</h6></td><td class='value'><?php echo $runinfo->partitionName; ?></td>
                      <td><h6>Schema-Data-Base Version</h6></td><td class='value'><?php echo ($runinfo->schemaVersion . '-' . $runinfo->dataVersion . '-' . $runinfo->baseVersion); ?></td>
                  </tr>
                                  
                  <tr>
                      <td><h6>PQM Plots</h6></td>
                      <td class='value'>
                      <?php 
                      if ($runinfo->has_pqm) { echo '<a href="#pqm">PQM</a>'; }
                      else { echo 'N/A'; }
                      ?>
                      </td>                      
                      
                      <td><h6>Diagnostic Plots</h6></td>
                      <td class='value'>
                      <?php 
                      if ($runinfo->has_diagnostics) { echo ('<a href="#diagnostics">Diagnostics</a>'); }
                      else {echo 'N/A';} 
                      ?>
                      </td>
                      
                      <td><h6>File Information</h6></td><td class='value'><?php echo '<a href="#fileinfo">Files</a>'; ?></td>
                  </tr>                                    
               </tbody>
            </table>
            
            <p></p>
            <table border = "0" cellpadding="0" cellspacing="1", style="width:99%" class="tableborder">
                <tbody>
                    <tr>
                        <td><h6>Data Category</h6></td><td class='value'><?php echo $csv_list[$runinfo->runNo]['DataCategory']; ?></td>
                    </tr>                           
                    <tr>
                        <td><h6>Description</h6></td><td class='value'><?php echo $csv_list[$runinfo->runNo]['Description']; ?></td>
                    </tr>
                    <tr>
                        <td><h6>Comments</h6></td><td class='value'><?php echo $csv_list[$runinfo->runNo]['Comments']; ?></td>
                    </tr>
                    <tr>
                        <td><h6>Elog Search</h6></td>
                        <td class='value'>
                        <?php echo '<a href="http://web.dyb.ihep.ac.cn:8099/Commissioning/?mode=full&reverse=0&reverse=1&npp=20&subtext=' . $runinfo->runNo . '">Elog</a>'; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <p></p>
            <div id='daq_table'>
                <table border = "0" cellpadding="0" cellspacing="1", style="width:99%" class="tableborder">
                    <tbody>
                        <tr>
                            <td><h6>Trigger Type</h6></td><td class='loading' id='LTB_triggerSource'><?php 
                                if ($runinfo->calib_array[0]['ltbMode'] == 1) {echo 'External (Forced)';}
                                else {echo 'loading ...';}
                                ?></td>
                            <td><h6>NHit Threshold</h6></td><td class='loading' id='HSUM_trigger_threshold'>loading ...</td>
                            <td><h6>Esum Threshold</h6></td><td class='loading' id='DAC_total_value'>loading ...</td>
                        </tr>   
                        <tr>
                            <td><h6>FEE Board Version</h6></td><td class='loading' id='FEEBoardVersion'>loading ...</td>
                            <td><h6>FEE CPLD Version</h6></td><td class='loading' id='FEECPLDVersion'>loading ...</td>
                            <td><h6>LTB Firmware Version</h6></td><td class='loading' id='LTBfirmwareVersion'>loading ...</td>
                        </tr>                            
                    </tbody>
                </table>

                <p></p>
                <table border = "0" cellpadding="0" cellspacing="1", style="width:99%" class="tableborder">
                    <tbody>
                        <tr>
                            <td><h6>FEE Board</h6></td>
                            <?php foreach(array('05','06','07','08','09','10','11','12','13','14','15','16','17') as $name): ?>
                                <td><h6><?php echo $name; ?></h6></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td><h6>DAC Threshold</h6></td>
                            <?php foreach(array('_5','_6','_7','_8','_9','_10','_11','_12','_13','_14','_15','_16','_17') as $name): ?>
                                <td class='loading' id=<?php echo '"' . $name . '_DACUniformVal' . '"'; ?> >...</td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td><h6>MaxHitPerTrigger</h6></td>
                            <?php foreach(array('_5','_6','_7','_8','_9','_10','_11','_12','_13','_14','_15','_16','_17') as $name): ?>
                                <td class='loading' id=<?php echo '"' . $name . '_MaxHitPerTrigger' . '"'; ?> >...</td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            
         </div>
        
         <?php if ($runinfo->runType == 'ADCalib'): ?>
            <div class = "subject" id="calib_div">
               <h2>Calibration Information Run <?php echo $runinfo->runNo?></h2>
               <table border = "0" cellpadding="0" cellspacing="1", style="width:99%" class="tableborder">
                  <tbody>
                     <?php foreach($runinfo->calib_array as $row): ?>
                         <tr>
                             <td><h6>Source A</h6></td>
                             <td class='value'><?php echo $row['sourceIdA_str']; ?></td>
                             <td><h6>Source B</h6></td>
                             <td class='value'><?php echo $row['sourceIdB_str']; ?></td>
                             <td><h6>Source C</h6></td>
                             <td class='value'><?php echo $row['sourceIdC_str']; ?></td>
                         </tr>
                         <tr>
                             <td><h6>Source A Voltage [mV]</h6></td>
                             <td class='value'><?php echo $row['ledVoltageA']; ?></td>
                             <td><h6>Source B Voltage [mV]</h6></td>
                             <td class='value'><?php echo $row['ledVoltageB']; ?></td>
                             <td><h6>Source C Voltage [mV]</h6></td>
                             <td class='value'><?php echo $row['ledVoltageC']; ?></td>
                         </tr>
                         <tr>
                             <td><h6>Source A Position Z [mm]</h6></td>
                             <td class='value'><?php echo $row['zPositionA']; ?></td>
                             <td><h6>Source B Position Z [mm]</h6></td>
                             <td class='value'><?php echo $row['zPositionB']; ?></td>
                             <td><h6>Source C Position Z [mm]</h6></td>
                             <td class='value'><?php echo $row['zPositionC']; ?></td>
                         </tr>              
                         <tr>
                             <td><h6>Source A at Home?</h6></td>
                             <td class='value'><?php echo $row['HomeA']; ?></td>
                             <td><h6>Source B at Home?</h6></td>
                             <td class='value'><?php echo $row['HomeB']; ?></td>
                             <td><h6>Source C at Home?</h6></td>
                             <td class='value'><?php echo $row['HomeC']; ?></td>
                         </tr>           
                         <tr>
                             <td><h6>LED Frequency [Hz]</h6></td>
                             <td class='value'><?php echo $row['ledFreq']; ?></td>
                             <td><h6>LED Pulse Separation [ns]</h6></td>
                             <td class='value'><?php echo $row['ledPulseSep']; ?></td>
                             <td><h6>Run Duration [s]</h6></td>
                             <td class='value'><?php echo $row['duration']; ?></td>
                         </tr>                                     
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>            
         <?php endif; ?>
        
     <?php endif; ?>

     <div id="div_autoreload" class='hidden'>
         <input id="chk_autoreload" type="checkbox" name="autoreload" value="autoreload" style="margin-right:5px; padding:0; width:auto;" />
         <h6>Auto-reload Page</h6>
         <span class="currentrun_update"></span>
     </div>
     
     <?php if ($runinfo->has_diagnostics): ?>
         <?php include 'diag_div.php'; ?>
     <?php endif; ?>

     <?php if ($runinfo->has_pqm): ?>
         <?php include 'pqm_div.php'; ?>
     <?php endif; ?>
     
     <!-- <?php if ($runinfo->num_rows > 0): ?>
         <div class = "subject">
             <h2>
                 <a name="fileinfo"></a>File Information Run <?php echo $runinfo->runNo?>
                 <div class="totop"><a href="#pagetop">^Top</a></div>
             </h2>
             <table border = "0" cellpadding="0" cellspacing="1", style="width:60%" class="tableborder">
                 <caption>Raw Data Files : <?php echo $runinfo->num_raw_files; ?></caption>
                 <thead><tr>
                     <th>File No</th><th>File Name</th><th>File Size</th>
                 </tr></thead>
                 <tbody>
                     <?php foreach($runinfo->rawfile_array as $row): ?>
                         <tr>
                             <td><?php echo $row['fileNo']; ?></td>
                             <td><?php echo $row['fileName']; ?></td>
                             <td><?php echo $row['fileSize']; ?></td>
                         </tr>               
                     <?php endforeach; ?>
                 </tbody>
             </table>
         </div>
     <?php endif; ?> -->

<?php endif; ?>

<?php 
echo form_error('runno_from') 
. '<br />' . form_error('runno_to')
. '<br />' . form_error('date_from')
. '<br />' . form_error('date_to')
. '<br />' . form_error('per_page'); 
?>

</div>

<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-1.4.2.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-ui-1.8.4.custom.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery.simplemodal.1.4.1.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/common.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/sprintf.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/findrun.js")?>"></script>

<?php include 'footer.php'; ?>