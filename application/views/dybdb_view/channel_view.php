<?php include 'header.php'; ?>
<?php include 'bookmarks.php'; ?>
<?php include 'breadcrumb.php'; ?>

<div id="content">    
     <h2>
         Diagnostic Plots Run <?php echo $run?>  
    </h2>
    <table border = "0" cellpadding="0" cellspacing="1", style="width:810px" class="tableborder">
        <caption>
            Detector : <?php echo $diagnostics['detectors'][$detector]['detname']; ?> &nbsp;|&nbsp;  
            Channel  : <?php echo $diagnostics['detectors'][$detector]['channels'][$channel]['channelname']?>
        </caption>
        <?php 
        $column_index = 0;
        foreach($diagnostics['detectors'][$detector]['channels'][$channel]['figures'] as $figure) {
            if ($column_index == 0) { echo '<tr>'; }
            echo( '<td><img class="img_db" src="' 
                . $figure['path'] 
                . '" width=300 height=225/></td>');
            $column_index++; 
            if ($column_index == 3) { echo "</tr>\n"; $column_index=0; }               
        }
        ?> 
    </table>
    
</div>

<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-1.4.2.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-ui-1.8.4.custom.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/common.js")?>"></script>

<script type="text/javascript"> 
	
$(".img_db").dblclick(function() {
    window.location = $(this).attr("src");
    return false;
});
	
</script>

<?php include 'footer.php'; ?>