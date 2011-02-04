<?php include 'header.php'; ?>
<?php include 'bookmarks.php'; ?>
<?php include 'breadcrumb.php'; ?>

<div id="content">    
    <h2>
         All PQM Plots 
         <div id= 'print_results' class="h2right"><a href="print_results">Print Results</a></div>                  
    </h2>
    
    <div id='status_bar'>    
        <span class="pblabel"></span>
    </div>
    
    <div id="fig_display" style="margin: 10px 0 10px 0">
    </div>

     <div class = "left">
         <div id='runtype_legend'>
             <a class='Physics'>&ensp;&ensp;&ensp;</a>&ensp;Physics&ensp;&ensp;
             <a class='ADCalib'>&ensp;&ensp;&ensp;</a>&ensp;ADCalib&ensp;&ensp;
             <a class='Pedestal'>&ensp;&ensp;&ensp;</a>&ensp;Pedestal&ensp;&ensp;
             <a class='FEEDiag'>&ensp;&ensp;&ensp;</a>&ensp;FEEDiag&ensp;&ensp;
         </div>
         <table class="diagnostics_table" border = "0" cellpadding="0" cellspacing="0", style="width:40px" class="tableborder">
             <thead><tr><th colspan="10">
                 <a id='collapse_all' href='#'>[-]</a>
             </th></tr></thead>
         </table>
         <?php 
         $per_coloumn = 10;
         $current_thousands = floor($runlist[0]/1000);
         echo '<table class="diagnostics_table" border = "0" cellpadding="0" cellspacing="0", style="width:480px" class="tableborder">' . "\n";
         echo '<thead><tr><th colspan="10">'
         . "<a class='collapse_one' href='#'>[-]</a>&ensp;" 
         . $current_thousands*1000 . ' - ' . ($current_thousands+1)*1000 
         . "</th></tr></thead>\n<tbody>\n";
         $column_index = 1;
         foreach ($runlist as $run) {
             if (floor($run/1000) < $current_thousands) {
                 if($column_index < $per_coloumn) {
                     for ($t=$column_index; $t<=$per_coloumn; $t++) {
                         echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                     }
                     echo "</tr>\n";
                 }
                 echo "\n</tbody>\n</table>\n\n";
                 echo "</tbody></table>\n\n";                    
                 $current_thousands--;
                 echo '<table class="diagnostics_table" border = "0" cellpadding="0" cellspacing="0", style="width:480px" class="tableborder">' . "\n";
                 echo '<thead><tr class="collapse_tr"><th colspan="10">'
                 . "<a class='collapse_one' href='#'>[-]</a>&ensp;" 
                 . $current_thousands*1000 . ' - ' . ($current_thousands+1)*1000 
                 . "</th></tr></thead>\n<tbody>\n";
                 $column_index = 1;
                 continue;
             }
             
             if ($column_index == 1) { echo '<tr>'; }            
             echo '<td>';
             echo anchor('dybdb/findrun/' . $run . '#pqm', sprintf('%05d', $run));
             echo '</td>';
             $column_index++;
             if ($column_index == $per_coloumn + 1) { echo "</tr>\n"; $column_index=1;}
         }
         if($column_index < $per_coloumn) {
             for ($t=$column_index; $t<=$per_coloumn; $t++) {
                 echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
             }
             echo "</tr>\n";
         }
         echo "\n</tbody>\n</table>\n\n";
         ?>    
         <p></p>       
     </div>

     <div class='right'>
         <?php echo form_open('dybdb/search_pqm');?>
         <table class="hidden" id='options_diagnostics' cellpadding="0" cellspacing="0" border="0">
              
              <tr class='plot_selection_tr'>
                  <td><h6>Plot Name</h6></td>
                  <td>
                      <select name="plotname_1" id='plotname_1' class='plot_selection'>
                          <optgroup label="Detector Figures" class="fig_detectors">
                          </optgroup>
                      </select>
                      <a id='add_button' href='#' style="font-size: 20px">&nbsp;+ </a>
                  </td>
              </tr>
              
               <tr>
                   <td><h6>Run Type</h6></td>
                   <td>
                       <select name="runtype_select" id= "runtype_select">
                           <option value="All" selected>All</option>
                           <option value="ADCalib">ADCalib</option>
                           <option value="Physics">Physics</option>
                           <option value="Pedestal">Pedestal</option>
                           <option value="FEEDiag">FEEDiag</option>
                       </select>
                   </td>
              </tr>
              
              <tr style="vertical-align: top">
                  <td><h6>Run List</h6></td>
                  <td>
              		<textarea name="runlist" id="runlist" style="width:180px; height:8em"></textarea>
                    <div id="example" style="margin-top: 5px; line-height: 1.5em">
                    <span class='tips'>Separate by whitespace</span>: 4375 &nbsp; 4378 <br />
                    <span class='tips'>Or define range</span>: 4000-5000 <br />
                    <span class='tips'>Tips</span>: double click an image to enlarge
                    </div>
                  </td>
              </tr>
                            
              <tr>
                   <td><h6>Num Columns</h6></td>
                   <td>
                       <input type="text" name="num_col" id="num_col" value="1" size="5"/>
                   </td>
              </tr>
              
               <tr>
                   <td><h6>Sort Run</h6></td>
                   <td>
                       <select name="sortrun" id= "sortrun">
                           <option value="ASC" selected>ASC</option>
                           <option value="DESC">DESC</option>
                           <option value="NONE">None</option>
                       </select>
                   </td>
              </tr>
              
             <tr>
                 <td><input id="submit" type="submit" value="Search" class="button" /></td>
                 <td></td>
             </tr> 
             
         </table>
         <?php echo form_close();?>     

     </div>
 </div>
 
 <script type="text/javascript" src="<?php echo(base_url() . "js/jquery-1.4.2.min.js")?>"></script>
 <script type="text/javascript" src="<?php echo(base_url() . "js/jquery-ui-1.8.4.custom.min.js")?>"></script>
 <script type="text/javascript" src="<?php echo(base_url() . "js/common.js")?>"></script>
 <script type="text/javascript" src="<?php echo(base_url() . "js/sprintf.js")?>"></script>
 <script type="text/javascript" src="<?php echo(base_url() . "js/search_pqm.js")?>"></script>
 
 <!-- no footer here -->
</body>
</html>