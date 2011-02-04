<?php include 'header.php'; ?>
<?php include 'bookmarks.php'; ?>
<?php include 'breadcrumb.php'; ?>

<div id="content">
    <div class = "subject">
        <h2>
            <a name="spade"></a>Spade Monitor 
            <div class="totop"><a href="#pagetop">^Top</a></div>
        </h2>
        
        <iframe src="http://dayabay.lbl.gov/spade-status/dbay_2_pdsf/" style="border: 0;" width=960 height=310>
        </iframe>
        
        <div class="annoucement" id="spaderss">
            <h4 align='center'>loading ...</h4>
        </div>
    </div>
    
    <div class = "subject" style='margin-top: 40px'>
        <h2>
            <a name="temperature"></a>Temperature Monitor 
            <div class="inline_right"><a id="show_temperature_options" href="#">[draw a box to zoom] ...</a></div>
        </h2>
        
        <div id="temperature_options" class="slowoptions">
            <?php echo form_open('dybdb/slowmonitor');?>
            <table  cellpadding="0" cellspacing="0" border="0" style="width:99%">

                <tr>
                    <td><h6>Sensor</h6>
                        <input type="checkbox" name="Temp_PT1" value="Temp_PT1" checked>1
                        <input type="checkbox" name="Temp_PT2" value="Temp_PT2" checked>2
                        <input type="checkbox" name="Temp_PT3" value="Temp_PT3" checked>3
                        <input type="checkbox" name="Temp_PT4" value="Temp_PT4" checked>4
                        <input type="checkbox" name="Temp_PT5" value="Temp_PT5">5
                    </td>
                    
                    <td><h6>Date</h6>
                        <input type="text" name="date_from" id="date_from" value="<?php echo set_value('date_from'); ?>" size="10"/>
                        <h6>&ensp;-&ensp;</h6> 
                        <input type="text" name="date_to" id="date_to" value="<?php echo set_value('date_to'); ?>" size="10" />
                    </td>
                
                <!-- <tr>
                    <td><h5>Show</h5></td>
                    <td>
                        <input type="text" name="points" id="points" value="<?php echo set_value('points'); ?>" size="5"/>
                        <h5>&nbsp;&nbsp;Points </h5>
                    </td>
                </tr> -->

                    <td>
                        <input type="submit" id="temperature_submit" value="Update" class="button" />
                        <input type="submit" id="temperature_reset" value="Reset" class="button" />  
                    </td>
                    
               </tr>
            </table> 
            <?php echo form_close();?>
        </div>

        <div id="Temp_All" class="holder" style="width:99%;height:400px;"></div>

    </div>
</div>


<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo(base_url() . "js/flot/excanvas.min.js")?>"></script><![endif]-->
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-1.4.2.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-ui-1.8.4.custom.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/common.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery.jfeed.pack.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/flot/jquery.flot.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/flot/jquery.flot.selection.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/slowmonitor.js")?>"></script>

<?php include 'footer.php'; ?>