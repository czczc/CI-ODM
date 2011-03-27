<div id="options" class="hidden" style="width: 99%">
   <?php echo form_open('dybdb/getrunlist_with_options');?>
   <fieldset>
       <legend>Search Options</legend>
       <table id='options_table' cellpadding="0" cellspacing="0" border="0" style="width:100%">
       <tr>
           <td><h6>Run Type&nbsp;&nbsp;</h6></td>
           <td>
               <input class="check" type="radio" name="runtype" value="All" checked>All 
               <input class="check" type="radio" name="runtype" value="Physics">Physics 
               <input class="check" type="radio" name="runtype" value="ADCalib">ADCalib
               <input class="check" type="radio" name="runtype" value="Pedestal">Pedestal 
               <input class="check" type="radio" name="runtype" value="FEEDiag">FEEDiag 
           </td>
       </tr>
   
       <tr>
           <td><h6>Run No.&nbsp;&nbsp;</h6></td>
           <td>
               <input type="text" name="runno_from" id="runno_from" size="4" style="width:4em;"
               value="<?php echo set_value('runno_from'); ?>" />
               <h5>&nbsp;-&nbsp;</h5> 
               <input type="text" name="runno_to" id="runno_to" size="4" style="width:4em;"
               value="<?php echo set_value('runno_to'); ?>" />              
          </td>
      </tr>
      
      <tr>
           <td><h6>Date&nbsp;&nbsp;</h6></td>
           <td>
               <input type="text" name="date_from" id="date_from" value="<?php echo set_value('date_from'); ?>" size="10"/>
               <h5>&nbsp;-&nbsp;</h5> 
               <input type="text" name="date_to" id="date_to" value="<?php echo set_value('date_to'); ?>" size="10" />
           </td>
       </tr>
   
       <tr>
           <td><h6>Show&nbsp;&nbsp;</h6></td>
           <td>
               <input type="text" name="per_page" id="per_page" size="3" style="width:3em;"
               value="<?php echo set_value('per_page'); ?>" />
               <h6>&nbsp;&nbsp;records per page&nbsp;&nbsp;</h6>

               <input class="check" type="radio" name="order" value="desc" checked><h6>DESC</h6> 
               <input class="check" type="radio" name="order" value="asc"><h6>ASC</h6>
           </td>
       </tr>
   
       <tr>
           <td><input type="submit" value="Get List" class="button" /></td>
           <td></td>
       </tr>
       </table> 
   </fieldset>
   <?php echo form_close();?>    
   
   <form id='show_fields'>
       <fieldset>
           <legend>Show Fields</legend>
           <table  border = "0" cellpadding="0" cellspacing="1", style="width:99%">
               <tr>
                   <td><input type="checkbox" name="RunType" value="RunType" checked>Run Type</td>
                   <td><input type="checkbox" name="SiteDet" value="SiteDet" checked>Site-Detector</td>
                   <td><input type="checkbox" name="StartTime" value="StartTime">Start Time [UTC]</td>
                   <td><input type="checkbox" name="StartTime" value="StartTime" checked>Start Time [Beijing]</td>
                   <td><input type="checkbox" name="RunLength" value="RunLength" checked>Run Length</td>
               </tr>
               <tr>
                   <!-- <td><input type="checkbox" name="DataCategory" value="DataCategory" checked>Data Category</td>
                   <td><input type="checkbox" name="Description" value="Description" checked>Description</td> -->
                   <td><input type="checkbox" name="PQMPlots" value="PQMPlots" checked>PQM Plots</td>
                   <td><input type="checkbox" name="DiagnosticPlots" value="DiagnosticPlots" checked>Diagnostic Plots</td>
                   <td><input type="checkbox" name="Comments" value="Comments" checked>Comments</td>
                   <td><input type="checkbox" name="Elog" value="Elog" checked>Elog</td>
                   <td></td>
               </tr>
           </table>
       </fieldset>
   </form> 
</div>