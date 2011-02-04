<div class="subject">    
     <h2>
         <a name="diagnostics"></a> Diagnostic Plots Run <span id='run_no'><?php echo $run?></span>&nbsp;&nbsp;
         <img id="diagnostics_loading" style="display:inline" src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" />
         <?php if (isset($currentrun)): ?>
             &nbsp;&nbsp;&nbsp;<span class="currentrun_update"></span>
         <?php endif; ?>
         <div class="totop"><a href="#pagetop">^Top</a></div> 
     </h2>
     
     <div id="diagnostics_site_det" class="left" style="margin-top:10px">
         <table border = "0" cellpadding="0" cellspacing="0", style="width:600px" class="info_table">
             <tr>
                 <td></td>
                 <td><h6>AD1</h6></td>
                 <td><h6>AD2</h6></td>
                 <td><h6>AD3</h6></td>
                 <td><h6>AD4</h6></td>
                 <td><h6>IWS</h6></td>
                 <td><h6>OWS</h6></td>
                 <td><h6>RPC</h6></td>
             </tr>

             <tr class="DayaBay">
                 <td><h6>DayaBay</h6></td>
                 <td class="AD1"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD2"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD3"><a class="no_det">X</a></td>
                 <td class="AD4"><a class="no_det">X</a></td>
                 <td class="IWS"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="OWS"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="RPC"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
             </tr>

             <tr class="LingAo">
                 <td><h6>LingAo</h6></td>
                 <td class="AD1"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD2"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD3"><a class="no_det">X</a></td>
                 <td class="AD4"><a class="no_det">X</a></td>
                 <td class="IWS"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="OWS"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="RPC"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
             </tr>

             <tr class="Far">
                 <td><h6>Far</h6></td>
                 <td class="AD1"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD2"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD3"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD4"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="IWS"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="OWS"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="RPC"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
             </tr>

             <tr class="SAB">
                 <td><h6>SAB</h6></td>
                 <td class="AD1"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD2"><a class="det">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                 <td class="AD3"><a class="no_det">X</a></td>
                 <td class="AD4"><a class="no_det">X</a></td>
                 <td class="IWS"><a class="no_det">X</a></td>
                 <td class="OWS"><a class="no_det">X</a></td>
                 <td class="RPC"><a class="no_det">X</a></td>
             </tr>

         </table>
     </div>

     <div class="right" style="margin-top: 10px; left: 680px; width: 280px; clear: right;">
         <h3 id="diagnostics_detector" style="text-align:center; padding: 5px; width:auto; color:white; background:#666;">
         </h3>
         <button id="diagnostics_rootfile"><h5>Root Files</h5></button>
         <button id="show_pmtmap"><h5>FEE / PMT Map</h5></button><br/>
     </div>

     <p style="clear: both;"></p>
     
     <div class="left hidden" style="margin-top:10px;">
         <table id="feemap_table" border = "0" cellpadding="0" cellspacing="0", style="width:100%" class="info_table">
         </table>
     </div>
     <div id="pmt_info" class="right hidden" style="margin-top: 50px; left: 680px; width: 250px; clear: right;">
         <table border = "0" cellpadding="0" cellspacing="0" style="width: 100%;" class="tableborder">
             <thead><tr>
                 <th id="th_pmtinfo" colspan="4" style="text-align:center;">PMT Information</th>
             </tr></thead>
             <tr>
                 <td><h6>Board</h6></td><td id="td_board" class="value">--</td>
                 <td><h6>Connector</h6></td><td id="td_connector" class="value">--</td>
             </tr>
             <tr>
                 <td><h6>Ring</h6></td><td id="td_ring" class="value">--</td>
                 <td><h6>Column</h6></td><td id="td_column" class="value">--</td>
             </tr>
             <tr>
                 <td colspan="3"><h6>SPE &nbsp;[High Gain ADC]</h6></td>
                 <td colspan="1" id="td_speHigh" class="value">--</td>
             </tr>
             <tr>
                 <td colspan="3"><h6>SPE &nbsp;[Low Gain ADC]</h6></td>
                 <td colspan="1" id="td_speLow" class="value">--</td>
             </tr>
             <tr>
                 <td colspan="3"><h6>Time Offset &nbsp;[TDC]</h6></td>
                 <td colspan="1" id="td_timeOffset" class="value">--</td>
             </tr>
         </table>
     </div>
     <p style="clear: both;"></p>
     <div class="hidden">
         <table id="pmtmap_table" border = "0" cellpadding="0" cellspacing="0", style="width:720px" class="info_table">
         </table>
     </div>
     

     <table id="diagnostics_plots" border = "0" cellpadding="0" cellspacing="1", style="width:920px; text-align:center; margin-top: 50px;" class="tableborder">
     </table>
        
</div>
