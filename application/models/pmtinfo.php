<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Pmtinfo extends Model 
{
    var $pmtFEE_dict = array(); // deprecated
    var $FEEpmt_dict = array(); // deprecated
    
    var $FEE_to_PMT = array();
    var $PMT_to_FEE = array();
    
    var $PMT_Calib = array(); // 'detname-board-connector' => array();
    var $calibr_seqno = 0;
    
	function Pmtinfo() {
		parent::Model();
        $this->_ReadPMT_FEE_map();
	}

    function _ReadPMT_FEE_map() { // temprarily still use csv file
        $csv_file = 'dayabay_config/PMT_FEE_map.csv';
        
        if (($handle = fopen($csv_file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $this->pmtFEE_dict[$data[0]] = $data[1];
                $this->FEEpmt_dict[$data[1]] = $data[0];
            }
            fclose($handle);
        }
    }
    
    function LoadPmtInfo() {
        // $this->db->select('SEQNO');
        // $this->db->from('FeeCableMapVld');
        // $this->db->order_by('SEQNO', "desc");
        // 
        // $query = $this->db->get();
        // if ($query->num_rows() > 0) {
        //     $seqno = $query->row(0)->SEQNO;
        // }
        
        $this->db->select('FEECHANNELDESC, SENSORDESC');
        $this->db->from('FeeCableMap');
        $this->db->order_by('SEQNO');
        
        // $this->db->where('SEQNO', $seqno);

        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $this->FEE_to_PMT[$row->FEECHANNELDESC] = $row->SENSORDESC;
            $this->PMT_to_FEE[$row->SENSORDESC] = $row->FEECHANNELDESC;
        }
    }
    
    function LoadPMTCalib() {
        $rundate = $this->input->post('run_start_time');
        // '2010-10-29 18:11:26';
        
        $this->db->select('SEQNO');
        $this->db->from('CalibPmtSpecVld');
        $this->db->where('TIMESTART <', $rundate);
        $this->db->where('TIMEEND >', $rundate);
        $this->db->order_by('SEQNO', "desc");
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $seqno = $query->row(0)->SEQNO;
            $this->db->select('*');
            $this->db->from('CalibPmtSpec');
            $this->db->where('SEQNO', $seqno);

            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $key = $this->PMT_to_FEE[$row->PMTDESCRIB];
                $this->PMT_Calib[$key] = array(
                    'seqno' => $seqno,
                    'detector-ring-column' => $row->PMTDESCRIB,
                    'status' => $row->PMTSTATUS,
                    'speHigh' => $row->PMTSPEHIGH,
                    'speLow' => $row->PMTSPELOW,
                    'timeOffset' => $row->PMTTOFFSET,
                );
            }
            $this->calibr_seqno = $seqno;
        } // if ($query->num_rows() > 0) done 
    }
    
    function json_pmt($run) {
        $this->LoadPmtInfo();
        $this->LoadPMTCalib();
        
        $json = "{\n";
                
        foreach ($this->FEE_to_PMT as $FEEname=>$PMTname) {
            $json .= ('"' . $FEEname . '":{');
            list($detname, $board, $connector) = split('-', $FEEname);
            list($detname, $ring, $column) = split('-', $PMTname);
            $json .= ('"detector":"' . $detname . '",');
            $json .= ('"board":"' . str_replace('board', '', $board) . '",');
            $json .= ('"connector":"' . str_replace('connector', '', $connector) . '",');
            $json .= ('"ring":"' . str_replace('ring', '', $ring) . '",');
            $json .= ('"column":"' . str_replace('column', '', $column) . '",');
            $json .= ('"seqno":"' . $this->PMT_Calib[$FEEname]['seqno'] . '",');
            $json .= ('"status":"' . $this->PMT_Calib[$FEEname]['status'] . '",');
            $json .= ('"speHigh":"' . $this->PMT_Calib[$FEEname]['speHigh'] . '",');
            $json .= ('"speLow":"' . $this->PMT_Calib[$FEEname]['speLow'] . '",');
            $json .= ('"timeOffset":"' . $this->PMT_Calib[$FEEname]['timeOffset'] . '",');
            
            $json = rtrim($json, ","); 
            $json .= ( "},\n");
        }

        $json = rtrim($json, "\n,");       
        $json .= "\n}";
        echo $json;
 	}
 	
    function json_pmtinfo($detname) {
        $this->LoadPmtInfo();
        $this->LoadPMTCalib();
        
        $json = "{\n";
                
        foreach ($this->FEE_to_PMT as $FEEname=>$PMTname) {
            if (strlen(strstr($FEEname,$detname))==0) {
                continue;
            }
            $json .= ('"' . $FEEname . '":{');
            list($detname, $board, $connector) = split('-', $FEEname);
            list($detname, $ring, $column, $in_out) = split('-', $PMTname);
            $json .= ('"detector":"' . $detname . '",');
            $json .= ('"board":"' . str_replace('board', '', $board) . '",');
            $json .= ('"connector":"' . str_replace('connector', '', $connector) . '",');
            
            $ring = str_replace('wall', '', str_replace('ring', '', $ring));
            $ring = sprintf('%02s', $ring);
            $column = str_replace('spot', '', str_replace('column', '', $column));
            $column = sprintf('%02s', $column);
            $json .= ('"ring":"' . $ring . '",');
            $json .= ('"column":"' . $column . '",');
            $json .= ('"in_out":"' . $in_out . '",');
            
            $json .= ('"seqno":"' . $this->PMT_Calib[$FEEname]['seqno'] . '",');
            $json .= ('"status":"' . $this->PMT_Calib[$FEEname]['status'] . '",');
            $json .= ('"speHigh":"' . $this->PMT_Calib[$FEEname]['speHigh'] . '",');
            $json .= ('"speLow":"' . $this->PMT_Calib[$FEEname]['speLow'] . '",');
            $json .= ('"timeOffset":"' . $this->PMT_Calib[$FEEname]['timeOffset'] . '",');
            
            $json = rtrim($json, ","); 
            $json .= ( "},\n");
        }

        $json = rtrim($json, "\n,");       
        $json .= "\n}";
        echo $json;
 	}

}