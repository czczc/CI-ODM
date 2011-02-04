<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Pqm extends Model
{

    var $site_nopasswd = 'http://web.dyb.ihep.ac.cn/dqm/';
    
    var $currentrun = 0;
    var $runlist = array(); // runno => 1;
    var $figure_list = array(
        'ADCMean' => 'Raw/h_sum_ADCMean.png',
        'ADC' => 'Raw/h_sum_ADC_Channel.png',
        'DarkNoise' => 'Raw/h_sum_DarkNoise.png',
        'DarkRate' => 'Raw/h_sum_DarkRate.png',
        'FEEADCSUM' => 'Raw/h_sum_FEEADCSUM.png',
        // 'Gain' => 'Raw/h_sum_Gain.png',
        'TDCMean' => 'Raw/h_sum_TDCMean.png',
        'TDC' => 'Raw/h_sum_TDC_Channel.png',
        'hitRate' => 'Raw/h_sum_hitRate.png',
        'overThrChnPerTrg' => 'Raw/h_sum_overThrChnPerTrg.png',
        'pedestalMean' => 'Raw/h_sum_pedestalMean.png',
        'pedestalRMS' => 'Raw/h_sum_pedestalRMS.png',
        'triggerInterval' => 'Raw/h_sum_triggerInterval.png',
        'triggerType' => 'Raw/h_sum_triggerType.png',
        'ChargePMT' => 'Calib/h_sum_ChargePMT.png',
        'ChargePatton' => 'Calib/h_sum_ChargePatton.png',
        'ChargeRatio' => 'Calib/h_sum_ChargeRatio.png',
        'FEEQSUM' => 'Calib/h_sum_FEEQSUM.png',
        'MeanChargePMT' => 'Calib/h_sum_MeanChargePMT.png',
        'MeanTimePMT' => 'Calib/h_sum_MeanTimePMT.png',
        'RMSChargePMT' => 'Calib/h_sum_RMSChargePMT.png',
        'RMSTimePMT' => 'Calib/h_sum_RMSTimePMT.png',
        'TimePMT' => 'Calib/h_sum_TimePMT.png'
    );
    
    function Pqm() {
 		parent::Model();
 	}
 	
 	function GetCurrentRun() {
 	    $url = $this->site_nopasswd . 'HistLog/current_run_number';
 	    $this->currentrun = (int)file_get_contents($url);
 	    return $this->currentrun; 
 	}
 	
 	function GetRunList() {
 	    $url = $this->site_nopasswd . 'HistLog/available_run_number';
 	    $handle = @fopen($url, "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $this->runlist[(int)$buffer] = 1;
            }
            fclose($handle);
        }
 	}
 	
 	function xml_runlist() {
 	    $this->GetRunList();
 	    
        header('Content-Type: application/xml');
        echo "<?xml version= \"1.0\" ?>\n";
        echo "<root_index>\n";
        echo "<prefix>" . $this->site_nopasswd . "</prefix>\n";
        foreach( $this->runlist as $runnumber => $val ) {
            echo "<runnumber>" . $runnumber . "</runnumber>\n";
        }
        echo "</root_index>";
 	}
 	
 	function xml_figurelist() {
        header('Content-Type: application/xml');
        echo "<?xml version= \"1.0\" ?>\n";
        echo "<root_index>\n";
        foreach ($this->figure_list as $figname => $path ) {
            echo "<figname>" . $figname . "</figname>\n";
            echo "<path>" . $path . "</path>\n";
        }
        echo "</root_index>";
 	}
 	
 	function json_runlist() {
 	    $this->GetRunList();
 	    $json = "{\n";
        foreach ($this->runlist as $runnumber => $val) {
            $json .= '"' . $runnumber 
            . '":"' . $val 
            . '",' . "\n";
        }     
        $json = rtrim($json, "\n,");       
        $json .= "}\n";
        echo $json;
 	}
 	
 	function json_figurelist($run) {
 	    // build up figure list
        $figure_list = array(); // array('DayaBayIWS' => array('figname'=>'', 'path' =>'', 'detector'=>'', ..), ...)
        
        $contents = file_get_contents($this->site_nopasswd . "HistLog/run" . $run . "/available_plots.txt");
        $lines = explode("\n", $contents);
        foreach ($lines as $line) {
            if (!$line) continue;
            $vars = preg_split('/\s+/', $line);
            $path = $vars[1];
            $figname = $vars[0];
            $dirsplits = explode('/', $path);
            $site = $dirsplits[2];
            $detector = $dirsplits[3];
            $detname = $site . $detector;
            if (!$figure_list[$detname]) {
                $figure_list[$detname][0] = array(
                    'figname' => $figname,
                    'path' => $this->site_nopasswd . $path,
                );
            }
            else {
                array_push($figure_list[$detname], array(
                    'figname' => $figname,
                    'path' => $this->site_nopasswd . $path,
                ));
            }
        }
        
        // build up json
        $json = "{\n";
        foreach ($figure_list as $detector=>$figures) {
            $json .= ('"' . $detector . '":[' . "\n");
            foreach ($figures as $figure) {
                $json .= '{';
                foreach ($figure as $key => $value) {
                    $json .= ('"' . $key . '":"' . $value . '",');
                }
                $json = rtrim($json, ",");
                $json .= "},\n";
            }
            $json = rtrim($json, ",\n");
            $json .= "],\n";
        }
        $json = rtrim($json, "\n,");       
        $json .= "\n}";
        
        echo $json;
 	}
 	
}