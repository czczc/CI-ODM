<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Daqinfo extends Model
{
    var $runNo = '';
    var $runType = '';
    var $num_rows = 0;
    
    var $base_table = array(); // default DAQ configs
    var $data_table = array(); // only records changes from defaults
    
    var $active_detector = 'SAB-AD1'; // TODO:: multiple active detectors
    var $Detector_table = array(); // 'SAB-AD?' => array( 'firmware' => '', ... )
    var $LTB_table = array(); // 'LTB-SAB-AD?' => array( 'NHit' => '', ... ),
    var $FEE_table = array(); // 'FEE_name' => array( 'threshold' => '', ... )
    
    var $triggerDict = array(
         'Unknown'   => 0,
         'Manual'    => 1,
         'External'  => 2,
         'Periodic'  => 4,
         'Reserved'  => 8,
         'Reserved'  => 16,
         'Reserved'  => 32,
         'Reserved'  => 64,
         'Reserved'  => 128,
         'NHit'      => 256,
         'Esum_ADC'  => 512,
         'Esum_High' => 1024,
         'Esum_Low'  => 2048,
         'Esum'      => 4096,
    ); // defined in LTB manual
    
    function Daqinfo() {
 		parent::Model();
 	}
 	
 	function LoadDaqInfo($run) {
 	    $this->_LoadRawTable($run, 'base');
 	    $this->_LoadRawTable($run, 'data');
 	    
 	    $this->_ConstructDaqTable();

        $tables = array($this->base_table, $this->data_table); 
        //load base table first, then data table
        foreach ($tables as $i => $table) {
            foreach ($table as $row) {
                $this->_LoadActiveDetector($row);
                $this->_LoadDetectorTable($row);
                $this->_LoadLTBTable($row);
                $this->_LoadFEETable($row);
            }
        }
        
        $this->_FinalizeTables();
        
        // $this->_TestDaqInfo();
 	}
 	
 	function json_daq($run) {
 	    $this->LoadDaqInfo($run);
 	    
        $json = "{\n";
        
        $json .= ('"active": "' . $this->active_detector . '",' . "\n");
        
        foreach ($this->Detector_table as $name=>$data) {
            $json .= ('"' . $name . '":{');
            foreach ($data as $key => $value) {
                $json .= ('"' . $key . '":"' . $value . '",');
            }
            $json = rtrim($json, ","); 
            $json .= ( "},\n");
        }
        
        foreach ($this->LTB_table as $name=>$data) {
            $json .= ('"' . $name . '":{');
            foreach ($data as $key => $value) {
                $json .= ('"' . $key . '":"' . $value . '",');
            }
            $json = rtrim($json, ","); 
            $json .= ( "},\n");
        }
   
        foreach ($this->FEE_table as $name=>$data) {
            $json .= ('"' . $name . '":{');
            foreach ($data as $key => $value) {
                $json .= ('"' . $key . '":"' . $value . '",');
            }
            $json = rtrim($json, ","); 
            $json .= ( "},\n");
        }
        
        $json = rtrim($json, "\n,");       
        $json .= "\n}";
        echo $json;
 	}
 	
    function _LoadRawTable($run, $version) {
        $this->runNo = $run;
        $version_str = '';
        
        if ($version == 'base') { $version_str = 'DaqRunInfo.baseVersion'; }
        elseif ($version == 'data') { $version_str = 'DaqRunInfo.dataVersion'; }
        else {}
        
        $query_str = "SELECT DaqRunInfo.runType, DaqRunConfig.className, DaqRunConfig.name, DaqRunConfig.objectID, DaqRunConfig.intValue, DaqRunConfig.stringValue "
        . " FROM DaqRunInfo INNER JOIN DaqRunConfig "
        . " ON DaqRunInfo.schemaVersion=DaqRunConfig.schemaVersion "
        . " AND " . $version_str . "=DaqRunConfig.dataVersion "
        . " AND DaqRunInfo.runNo=" . $run;
        
        $query = $this->db->query($query_str);
        $this->num_rows = $query->num_rows();
        
        if ($this->num_rows > 0) {
            if ($version == 'base') { 
                $this->base_table = $query->result_array(); 
                $this->runType = $this->base_table[0]['runType'];
            }
            elseif ($version == 'data') { $this->data_table = $query->result_array(); }
            else {}
        }

    }
    
    function _ConstructDaqTable() {
        $detectorArray = array("SAB-AD1", "SAB-AD2", "SAB-AD3", "SAB-AD4", "EH1-WPI", "EH1-WPO");
        foreach ($detectorArray as $detector_name) {
            $this->Detector_table[$detector_name] = array();
        }
        $this->Detector_table["SAB-AD1"]["FEE_prefix"] = "0_";
        $this->Detector_table["SAB-AD2"]["FEE_prefix"] = "98_";
        $this->Detector_table["SAB-AD3"]["FEE_prefix"] = "99_";
        $this->Detector_table["SAB-AD4"]["FEE_prefix"] = "100_";
        $this->Detector_table["EH1-WPI"]["FEE_prefix"] = "1_5_";
        $this->Detector_table["EH1-WPO"]["FEE_prefix"] = "1_6_";
        
        $this->Detector_table["SAB-AD1"]["LTB_prefix"] = "";
        $this->Detector_table["SAB-AD2"]["LTB_prefix"] = "LTB_98_";
        $this->Detector_table["SAB-AD3"]["LTB_prefix"] = "LTB_99_";
        $this->Detector_table["SAB-AD4"]["LTB_prefix"] = "LTB_100_";
        $this->Detector_table["EH1-WPI"]["LTB_prefix"] = "LTB_1_5_";
        $this->Detector_table["EH1-WPO"]["LTB_prefix"] = "LTB_1_6_";
        
        foreach ($detectorArray as $detector_name) {
            $LTB_name = 'LTB-' . $detector_name;
            $this->LTB_table[$LTB_name] = array();
        }
        
        foreach ($detectorArray as $detector_name) {
            $prefix = $this->Detector_table[$detector_name]["FEE_prefix"];
            $name = $prefix . '1';
            $this->FEE_table[$name] = array(); // XXX_1 is used as default board setting.
            $this->FEE_table[rtrim($prefix, '_')] = array(); // XXX is also used as default board setting.
            for ($i=5; $i<=17; $i++) {
                $name = $prefix . $i;
                $this->FEE_table[$name] = array();
            }
        }
        // print_r($this->FEE_table)
    }
    
    function _LoadActiveDetector($row) {
        $className = "ROSConfiguration";
        $name = "childObjectId_Detectors";
        $objectID = "ROSconfig1";
        
        if ($row['className'] == $className 
            && $row['name'] == $name
            && $row['objectID'] == $objectID) {
                $this->active_detector = $row['stringValue']; ;
        }
    }
    
    function _LoadDetectorTable($row) {
        $className = "Crate";
        if ($row['className'] == $className) {
            $nameArray = array(
                "FEEBoardVersion",
                "FEECPLDVersion",
                "LTBfirmwareVersion",
            );
            foreach ($nameArray as $name) {
                if ($row['name'] == $name) {
                    foreach ($this->Detector_table as $detector_name => $detector) {
                        $objectID = $detector_name;
                        if ($row['objectID'] == $objectID) { $this->Detector_table[$detector_name][$name] = $row['intValue']; }
                    }
                } // if ($row['name'] == $name) done
            }
        } // if ($row['className'] == $className) done
    }
    
    function _LoadLTBTable($row) {
        $className = "LTB_variableReg";
        if ($row['className'] == $className) {
            $nameArray = array(
                "HSUM_trigger_threshold", // NHit
                "DAC_total_value", // ESum
                "LTB_triggerSource", // Trigger Type
            ); 
            foreach ($nameArray as $name) {
                if ($row['name'] == $name) {
                    foreach ($this->Detector_table as $detector_name => $detector) {
                        $objectID = $detector["LTB_prefix"] . $this->runType . "Mode";
                        $LTB_name = 'LTB-' . $detector_name;
                        if ($row['objectID'] == $objectID) { $this->LTB_table[$LTB_name][$name] = $row['intValue']; }
                    }
                } // if ($row['name'] == $name) done
            }
        } // if ($row['className'] == $className) done
    }
    
    function _LoadFEETable($row) {
        $className = "FEEDACThreshold";
        $name = "DACUniformVal";
        $objectID = $this->runType . "Threshold_";
        if ($row['className'] == $className
            && $row['name'] == $name
            && strlen(strstr($row['objectID'], $objectID)) > 0 ) {               
            $FEEname = str_replace($objectID, '', $row['objectID']); 
            if (array_key_exists($FEEname, $this->FEE_table)) { 
            $this->FEE_table[$FEEname][$name] = $row['intValue']; }
        }
        
        $className = "FEE";
        if ($row['className'] == $className) {
            $nameArray = array(
                "MaxHitPerTrigger" 
                // "EnableTriggerDelay", 
                // "TriggerDelay",
                // "EnableCBLT",
                // "Position",
                // "ModuleEnable",
                // "ModuleType",
            );
            foreach ($nameArray as $name) {
                if ($row['name'] == $name) {
                    $FEEname = str_replace('FEE_', '', $row['objectID']);
                    if (array_key_exists($FEEname, $this->FEE_table)) { $this->FEE_table[$FEEname][$name] = $row['intValue']; }
                }
            }
        } // if ($row['className'] == $className) done
    }

 	function _FinalizeTables() {
        //convert trigger type to strings
        foreach ($this->LTB_table as $detname => $LTB) {
            $this->LTB_table[$detname]['LTB_triggerSource'] = $this->_GetTriggerTypeString($LTB['LTB_triggerSource']); 
        }
        
	    //set default FEE settings
	    for ($i=5; $i<=17; $i++) {
	        foreach ($this->Detector_table as $detector_name => $detector) {
                $name = $detector["FEE_prefix"] . $i;
                $default = $detector["FEE_prefix"] . '1';
                $default_2 = rtrim($detector["FEE_prefix"], '_');
                if (!isset($this->FEE_table[$name]['DACUniformVal'])) { 
                    if (isset($this->FEE_table[$default]['DACUniformVal'])) {
                        $this->FEE_table[$name]['DACUniformVal'] = $this->FEE_table[$default]['DACUniformVal']; 
                    }
                    else {
                        $this->FEE_table[$name]['DACUniformVal'] = $this->FEE_table[$default_2]['DACUniformVal']; 
                    }
                }
            }
        }
	}
 	
 	// Convert trigger code to a string. Diffrent trigger types joined by ' | '.
 	function _GetTriggerTypeString($trigger) {
        $trigger_str ='';
	    foreach ( $this->triggerDict as $trigger_name => $trigger_bit) {
	        if ($trigger & $trigger_bit) {
	            $trigger_str .= ($trigger_name . ' | ');
	        }
	    }
	    if ($trigger_str == '') { return 'Unknown'; }
	    else { return substr($trigger_str, 0, -3); }
	}
	
	function _TestDaqInfo() {	    
        echo "<pre>\n";
        echo "Active Detector: " . $this->active_detector . "\n";
        echo "Detector Table:\n"; print_r($this->Detector_table); echo "\n";
        echo "LTB Table:\n"; print_r($this->LTB_table); echo "\n";
        echo "FEE Table:\n"; print_r($this->FEE_table); echo "\n";
        echo "</pre>";
	}
}