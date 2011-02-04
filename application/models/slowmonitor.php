<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Slowmonitor extends Model 
{
    var $query_str         = '';
    var $query_select      = 'SELECT UNIX_TIMESTAMP(DcsAdTempVld.TIMESTART), UNIX_TIMESTAMP(DcsAdTempVld.TIMEEND), DcsAdTemp.Temp_PT1, DcsAdTemp.Temp_PT2, DcsAdTemp.Temp_PT3, DcsAdTemp.Temp_PT4, DcsAdTemp.Temp_PT5 ';
    var $query_from        = ' FROM DcsAdTemp INNER JOIN DcsAdTempVld ON DcsAdTemp.SEQNO=DcsAdTempVld.SEQNO WHERE 1=1 ';
    var $query_runrange    = "";
    var $query_daterange   = "";
    var $query_skip        = "";
    var $query_orderby     = "";
    var $query_limit       = "";
    var $query_offset      = "";
    
    var $count = 0;
    var $num_rows = 0;
    var $temperature_array = array();
    
	function Slowmonitor() {
		parent::Model();
	}
	
	function GetCount() {
        $old_query_select = $this->query_select;
        $this->SetSelect('SELECT COUNT(DcsAdTemp.Temp_PT1) ');
        
        $this->query_str = ' ' 
            . $this->query_select
            . $this->query_from
            . $this->query_runrange 
            . $this->query_daterange; 

        $query = $this->db->query($this->query_str);
        $query_array = $query->result_array();
        $this->count = $query_array[0]['COUNT(DcsAdTemp.Temp_PT1)'];
        
        $this->SetSelect($old_query_select);
        return $this->count;
    }
    
	function GetTemperatureInfo() {  
        $this->SetDateRange($this->session->userdata('date_from'), $this->session->userdata('date_to'));
        
        $skip = floor($this->GetCount() / $this->session->userdata('points'));
        $this->SetSkip($skip);
        
        $this->query_str = ' ' 
            . $this->query_select
            . $this->query_from
            . $this->query_runrange 
            . $this->query_daterange 
            . $this->query_skip;

        $query = $this->db->query($this->query_str);
        $this->num_rows = $query->num_rows();
        if ($this->num_rows > 0) {
            $this->temperature_array = $query->result_array();
        }
    }
	
	function json_temperature() {
	    $this->GetTemperatureInfo();
	    $json = "{\n";
	    
	    foreach ($this->temperature_array as $row) {
            $json .= ('"' . $row['UNIX_TIMESTAMP(DcsAdTempVld.TIMESTART)'] . '":{');
            
            $json .= ('"' . 'Temp_PT1' . '":"' . $row['Temp_PT1'] . '",');
            $json .= ('"' . 'Temp_PT2' . '":"' . $row['Temp_PT2'] . '",');
            $json .= ('"' . 'Temp_PT3' . '":"' . $row['Temp_PT3'] . '",');
            $json .= ('"' . 'Temp_PT4' . '":"' . $row['Temp_PT4'] . '",');
            $json .= ('"' . 'Temp_PT5' . '":"' . $row['Temp_PT5'] . '",');
            
            $json = rtrim($json, ","); 
            $json .= ( "},\n");
        }
	    
        $json = rtrim($json, "\n,");       
        $json .= "\n}";
        echo $json;
	}
	
	function SetSelect($str) {
        $this->query_select = $str;
    }
    
	function SetDateRange($from, $to) {
        // It's necessary to minus one day to include the 'to' day.
        $this->query_daterange = " AND DcsAdTempVld.TIMESTART >= STR_TO_DATE('" 
            . $from 
            . "', '%m/%d/%Y') AND DATE_SUB(DcsAdTempVld.TIMESTART, INTERVAL 1 DAY) <= STR_TO_DATE('"
            . $to
            . "', '%m/%d/%Y') ";
    }
    
    function SetSkip($skip) {
        if ($skip == 0) { return; }
        $this->query_skip = " AND DcsAdTemp.SEQNO %" . $skip . " = 0 ";
    }
    
    function SpadeRSS() {
        header("Content-Type: text/xml");
        echo file_get_contents("http://dayabay.lbl.gov/sentry-status/dybdmz_dbay/recentSyphoning.jsp");
    }
    
    function TestTemperatureInfo($skip) {
        echo $skip;
        echo "<pre>\n" . $this->num_rows;
        echo " out of " . $this->count . "\n";
        print_r($this->temperature_array);
        echo "</pre>\n";
    }
}