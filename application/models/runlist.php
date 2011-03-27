<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Runlist extends Model
{
    var $query_str         = '';
    var $query_select      = 'SELECT DaqRunInfo.runNo, DaqRunInfo.runType, DaqRunInfo.partitionName, DaqRunInfoVld.TIMESTART, DaqRunInfoVld.TIMEEND ';
    var $query_from        = ' FROM DaqRunInfo INNER JOIN DaqRunInfoVld ON DaqRunInfo.SEQNO=DaqRunInfoVld.SEQNO WHERE 1=1 ';
    var $query_runtype     = "";
    var $query_runrange    = "";
    var $query_daterange   = "";
    var $query_orderby     = ' ORDER BY DaqRunInfo.runNo DESC ';
    var $query_limit       = "";
    var $query_offset      = "";
    
    var $count             = 0;
    var $num_of_rows       = 0;
    var $run_array         = array();
    
    var $csv_list          = array(); 
    var $diagnostics_list  = array();
    var $pqm_list  = array();

    function Runlist() {
        parent::Model();
    }
    
    function set_select($str) {
        $this->query_select = $str;
    }
    
    function set_runtype($runtype) {
        if ($runtype == 'All') { return; }
        $this->query_runtype = " AND DaqRunInfo.runType = '" . $runtype . "' ";
    }

    function set_runrange($from, $to) {
        $this->query_runrange = " AND DaqRunInfo.runNo >= " 
            . $from 
            . " AND DaqRunInfo.runNo <= "
            . $to
            . " ";
    }

    function set_daterange($from, $to) {
        // It's necessary to minus one day to include the 'to' day.
        $this->query_daterange = " AND DaqRunInfoVld.TIMESTART >= STR_TO_DATE('" 
            . $from 
            . "', '%m/%d/%Y') AND DATE_SUB(DaqRunInfoVld.TIMESTART, INTERVAL 1 DAY) <= STR_TO_DATE('"
            . $to
            . "', '%m/%d/%Y') ";
    }
    
    function set_orderby($order) {
        if ($order == 'asc') {
            $this->query_orderby = " ORDER BY DaqRunInfo.runNo ASC ";
        }
    }
    
    function set_limit($limit) {        
        $this->query_limit = " LIMIT " . $limit . " ";
    }
    
    function set_offset($offset) {
        $this->query_offset = " OFFSET " . $offset . " ";
    }
    
    function get_count() {
        $old_query_select = $this->query_select;
        $this->set_select('SELECT COUNT(DaqRunInfo.runNo) ');
        
        $this->query_str = ' ' 
            . $this->query_select
            . $this->query_from
            . $this->query_runtype
            . $this->query_runrange 
            . $this->query_daterange;

        $query = $this->db->query($this->query_str);
        $query_array = $query->result_array();
        $this->count = $query_array[0]['COUNT(DaqRunInfo.runNo)'];
        
        $this->set_select($old_query_select);
        return $this->count;
    }
    
    function get_runlist() { 
        // $this->Diagnostics->getrunlist();
        // $this->diagnostics_list = $this->Diagnostics->runlist;
        // $this->Pqm->GetRunList();
        // $this->pqm_list = $this->Pqm->runlist;
        $this->get_csvlist();    
        
        $this->get_count();   
        $this->query_str = ' ' 
            . $this->query_select
            . $this->query_from
            . $this->query_runtype
            . $this->query_runrange 
            . $this->query_daterange 
            . $this->query_orderby
            . $this->query_limit
            . $this->query_offset;

        $query = $this->db->query($this->query_str);
        $this->num_of_rows = $query->num_rows();
        if ($this->count > 0) {
            $this->run_array = $query->result_array();
        }
    }
    
    function get_missing_diagnostics() {
        $this->Diagnostics->getrunlist();
        $this->diagnostics_list = $this->Diagnostics->runlist;
        $query_str = 'SELECT runNo FROM DaqRunInfo ORDER by runNo DESC';
        $query = $this->db->query($query_str);
        
        $missing_list = array();
        foreach ($query->result_array() as $row) {
            $runNo = $row['runNo'];
            if (!array_key_exists($runNo, $this->diagnostics_list)) {
                array_push($missing_list, $runNo);
            }
        }
        
        echo count($missing_list) . " missing runs: <br/><br/>";
        foreach ($missing_list as $run) {
            echo $run . "<br/>";
        }
    }
    
    function get_csvlist() {
        $csv_files = array(
            'dayabay_config/AD#1-dry-run-DAQ-run-list-1.csv',
            'dayabay_config/AD#1-dry-run-DAQ-run-list-2.csv',
            'dayabay_config/AD#2-dry-run-DAQ-run-list.csv',
            'dayabay_config/AD#1-finaldryrun.csv',
            'dayabay_config/AD#2-finaldryrun.csv',
            'dayabay_config/AD#3-dry-run-DAQ-run-list.csv',
            'dayabay_config/AD#4-dry-run-DAQ-run-list.csv',
            'dayabay_config/EH#1-WPminiDryrun.csv',
        );
        $field = array(
            'RunNumber' => 0,
            'DataCategory' => 1,
            'Comments' => 23,
            'Description' => 24
        );
        
        foreach ($csv_files as $csv_file) {
            if (($handle = fopen($csv_file, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $run = $data[$field['RunNumber']];
                    if (is_numeric($run)) {
                        $this->csv_list[$run] = array(
                            'DataCategory' => $data[$field['DataCategory']],
                            'Comments' => $data[$field['Comments']],
                            'Description' => $data[$field['Description']]
                        );
                    }
                }
                fclose($handle);
            }
        } // foreach done
    }
    
    function TestCSV() {
        echo "<pre>\n";
        print_r($this->csv_list);
        echo "</pre>\n";
        
        $row=1;
        if (($handle = fopen("upload/AD#2-dry-run-DAQ-run-list.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $c . ' ' . $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }
}