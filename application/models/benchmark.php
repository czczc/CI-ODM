<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Benchmark extends Model 
{
    var $current_db = '';
    
	function Benchmark() {
		parent::Model();
	}

    function DoBenchMark($test, $db) {
        $this->benchmark->mark('login_start');
        $this->load->database($db);
        $this->current_db = $db;
        $run = 5773; // a 3-day long run
        
        $this->benchmark->mark('start');
        if ($test == 'log_in') {
            $this->benchmark->mark('end');
            return $this->benchmark->elapsed_time('login_start', 'end');
        }
        elseif ($test == 'load_file') {
            $this->Runinfo->FindRawDataFileInfo($run);
        }
        elseif ($test == 'load_daq') {
            $this->Daqinfo->LoadDaqInfo($run);
        }
        elseif ($test == 'load_dcs') {
            $last2week = date("m/d/Y", time() - 86400*14);
            $today = date("m/d/Y", time() + 86400);
            $this->session->set_userdata('date_from', $last2week);
            $this->session->set_userdata('date_to', $today);
            $this->session->set_userdata('points', '1500');
            $this->Slowmonitor->GetTemperatureInfo();
        }
        elseif ($test == 'load_diagnostics_list') {
            $this->Diagnostics->getrunlist();
        }
        elseif ($test == 'load_pqm_list') {
            $this->Pqm->GetRunList();
        }
        else {
            return -1;
        }
        $this->benchmark->mark('end');
        return $this->benchmark->elapsed_time('start', 'end');
    }
    
    function json_benchmark($test, $db) {
        $json = "{\n";
        
        $json .= ('"benchmark":"' . $this->DoBenchMark($test, $db) . '",' . "\n");
        $json .= ('"db":"' . $this->current_db . '"' . "\n");
        
        $json .= "}\n";
        echo $json;
    }
    
}