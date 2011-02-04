<?php include 'header.php'; ?>
<?php include 'bookmarks.php'; ?>
<?php include 'breadcrumb.php'; ?>

<div id="content">
	
	<?php echo form_open('dybdb/search');?>
		<label for="tables">Available tables:</label>
		<?php echo form_dropdown('tables', $table_array, $selected, 'id="tables"');?>
		<br/>
		<label for="username" class="required">Query text:</label><br />
		<textarea name="texts" id="texts"><?php echo $last_texts;?></textarea>
		<input name='current_table' id='current_table' class="hidden"></input>
		<br />
		<input type="submit" value="Submit" id="submit" class="button"/>
	<?php echo form_close();?>

	<code>
		<?php echo $query_str;?>
	</code>
	
	<h3>
	    <?php echo 'Results: ' . $query_num_rows . ' out of ' . $total_counts . ' records '; ?>
	    <div id="csv"><a id="csv_link" href="#">.csv</a></div>
	</h3>
	<?php echo $query_result_table;?>

</div>

<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-1.4.2.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-ui-1.8.4.custom.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/common.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/table2CSV.js")?>"></script>

<script type="text/javascript"> 

var tables = document.getElementById('tables');

tables.onchange = function() {   
  table_name = this.options[this.selectedIndex].innerHTML; 
  var str = "SELECT * FROM " + table_name + " LIMIT 10";
  var texts = document.getElementById('texts');
  texts.value = str;
} 

var table_name = $("#tables :selected").text();
$("#texts").val("SELECT * FROM " + table_name + " LIMIT 10");

$("#submit").click(
	function() {
     table_name = $("#tables :selected").text(); 
     $("#current_table").val(table_name);     
	}
);

$("#csv_link").click(function() {
    $('table.tableborder').table2CSV();
    return false;
});
	
</script>

<?php include 'footer.php'; ?>