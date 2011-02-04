<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Runinfo extends Model
{
   var $runNo = '';
   var $num_rows = 0;
   
   var $runType = '';
   var $detectorMask = '';
   var $partitionName = '';
   var $schemaVersion = '';
   var $dataVersion = '';
   var $baseVersion = ''; 
   var $timestart = '';
   var $timeend = '';
      
   var $has_diagnostics = TURE;
   var $has_pqm = TRUE;
   
   var $num_raw_files = 0;
   var $rawfile_array = array(); // arrays of array("fileNo", "fileName", "fileSize")
   
   var $num_calib_rows = 0;
   var $calib_array = array();
   var $sourceID_dict = array(
        '0' => 'Unknown',
        '1' => 'LED',
        '2' => 'Ge68',
        '3' => 'AmC_Co60'
   );
   var $ledID_dict = array(
        '0' => 'Unknown',
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'MO_BOT',
        '5' => 'MO_MID',
        '6' => 'MO_TOP'
   );
   
   function Runinfo() {
		parent::Model();
	}
	
	function FindRun($run) {
	   $this->runNo = $run;
	   
	   $query_str = "SELECT DaqRunInfo.runType, DaqRunInfo.detectorMask, DaqRunInfo.detectorMask, DaqRunInfo.partitionName, DaqRunInfo.schemaVersion, DaqRunInfo.dataVersion, DaqRunInfo.baseVersion, DaqRunInfoVld.TIMESTART, DaqRunInfoVld.TIMEEND FROM DaqRunInfoVld INNER JOIN DaqRunInfo ON DaqRunInfoVld.SEQNO=DaqRunInfo.SEQNO WHERE RunNo = " . $run;
	   
	   $query = $this->db->query($query_str);
	   $this->num_rows = $query->num_rows();
		
       if ($this->num_rows > 0) {
           $result_array = $query->result_array();
           $this->runType = $result_array[0]['runType'];
           $this->detectorMask = $result_array[0]['detectorMask'];
           $this->partitionName = $result_array[0]['partitionName'];
           $this->schemaVersion = $result_array[0]['schemaVersion'];
           $this->dataVersion = $result_array[0]['dataVersion'];
           $this->baseVersion = $result_array[0]['baseVersion'];
    	   $this->timestart = $result_array[0]['TIMESTART'];
           $this->timeend = $result_array[0]['TIMEEND'];
           
           // $this->FindRawDataFileInfo($run);
           $this->FindCalibrationInfo($run);
       }
	   
       // $this->Diagnostics->getrunlist();
       // if ($this->Diagnostics->runlist[$run]) { $this->has_diagnostics = TRUE; }
       // 
       // $this->Pqm->GetRunList();
       // if ($this->Pqm->runlist[$run]) { $this->has_pqm = TRUE; }
	}
		
	function FindRawDataFileInfo($run) {
	   $query_str = "SELECT fileNo, fileName, fileSize FROM DaqRawDataFileInfo WHERE RunNo = " . $run;
	   $query = $this->db->query($query_str);
	   
	   $this->num_raw_files = $query->num_rows();
	   $this->rawfile_array = $query->result_array();
	}
	
    function FindCalibrationInfo($run) {
        $query_str = "SELECT sourceIdA, zPositionA, sourceIdB, zPositionB, sourceIdC, zPositionC, duration, ledNumber1, ledNumber2, ledVoltage1, ledVoltage2, ledFreq, ledPulseSep, ltbMode, HomeA, HomeB, HomeC FROM DaqCalibRunInfo WHERE RunNo = " . $run;
        $query = $this->db->query($query_str);

        $this->num_calib_rows = $query->num_rows();
        $this->calib_array = $query->result_array();

        foreach ($this->calib_array as $i => $row) {
            $this->calib_array[$i]['sourceIdA_str'] = $this->sourceID_dict[$row['sourceIdA']];
            $this->calib_array[$i]['sourceIdB_str'] = $this->sourceID_dict[$row['sourceIdB']];
            $this->calib_array[$i]['sourceIdC_str'] = $this->sourceID_dict[$row['sourceIdC']];
            $this->calib_array[$i]['ledVoltage' . $this->ledID_dict[$row['ledNumber1']]] = $row['ledVoltage1'];
            $this->calib_array[$i]['ledVoltage' . $this->ledID_dict[$row['ledNumber2']]] = $row['ledVoltage2'];
            if (isset($row['HomeA'])) { if ($row['HomeA'] == 1) { $this->calib_array[$i]['HomeA'] = 'Yes'; } else { $this->calib_array[$i]['HomeA'] = 'No'; } } 
            else { $this->calib_array[$i]['HomeA'] = 'Undefined'; }
            if (isset($row['HomeB'])) { if ($row['HomeB'] == 1) { $this->calib_array[$i]['HomeB'] = 'Yes'; } else { $this->calib_array[$i]['HomeB'] = 'No'; }} 
            else { $this->calib_array[$i]['HomeB'] = 'Undefined'; }
            if (isset($row['HomeC'])) { if ($row['HomeC'] == 1) { $this->calib_array[$i]['HomeC'] = 'Yes'; } else { $this->calib_array[$i]['HomeC'] = 'No'; }} 
            else { $this->calib_array[$i]['HomeC'] = 'Undefined'; } 
            
            if (!isset($this->calib_array[$i]['ledVoltageA']) && $this->calib_array[$i]['sourceIdA_str'] == 'LED') {
                $this->calib_array[$i]['sourceIdA_str'] = '';
                $this->calib_array[$i]['ledVoltageA'] = '';
                $this->calib_array[$i]['zPositionA'] = '';
                $this->calib_array[$i]['HomeA'] = '';
            }
            if (!isset($this->calib_array[$i]['ledVoltageB']) && $this->calib_array[$i]['sourceIdB_str'] == 'LED') {
                $this->calib_array[$i]['sourceIdB_str'] = '';
                $this->calib_array[$i]['ledVoltageB'] = '';
                $this->calib_array[$i]['zPositionB'] = '';
                $this->calib_array[$i]['HomeB'] = '';
            }
            if (!isset($this->calib_array[$i]['ledVoltageC']) && $this->calib_array[$i]['sourceIdC_str'] == 'LED') {
                $this->calib_array[$i]['sourceIdC_str'] = '';
                $this->calib_array[$i]['ledVoltageC'] = '';
                $this->calib_array[$i]['zPositionC'] = '';
                $this->calib_array[$i]['HomeC'] = '';
            }
            if ($row['ledNumber1'] == 4 || $row['ledNumber1'] == 5 || $row['ledNumber1'] == 6) {
                $this->calib_array[$i]['sourceIdA_str'] = $this->ledID_dict[$row['ledNumber1']];
                $this->calib_array[$i]['ledVoltageA'] = $row['ledVoltage1'];
            }
        } // foreach done
    }
	
}

