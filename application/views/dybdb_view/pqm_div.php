<div class="subject" id="pqm_div">    
    <h2>
        <a name="pqm"></a> PQM Plots Run <?php echo $runinfo->runNo; ?>
        <img id="pqm_loading" style="display:inline" src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" />
        <?php if (isset($currentrun)): ?>
            &nbsp;&nbsp;&nbsp;<span class="currentrun_update"></span>
        <?php endif; ?>       
        <div class="totop"><a href="#pagetop">^Top</a></div> 
    </h2>

    <div id="pqm_site_det" class="left" style="margin-top:10px">
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
    
    <div class="right" style="margin-top: 10px; left: 680px; width: 250px; clear: right;">
        <h3 id="pqm_detector" style="text-align:center; padding: 5px; width:auto; color:white; background:#666;">
        </h3>
    </div>
    
    <p style="clear: both;"></p>       
    
    <!-- <p id="pqm_detectors">
    </p> -->
    
    <table id="pqm_plots" border = "0" cellpadding="0" cellspacing="1", style="width:920px; text-align:center; margin-top: 50px;" class="tableborder">
    </table>
    
</div>
