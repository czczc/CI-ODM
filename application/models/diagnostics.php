<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Diagnostics extends Model
{
    var $site_nopasswd = 'http://portal.nersc.gov/project/dayabay/';
    var $prefix = 'dybprod/';
    var $runs_xml = 'runs.xml'; 
    var $is_sim = FALSE;
    
    var $runlist = array(); // runno => { runindex => filename }
    
    function Diagnostic() {
 		parent::Model();
 	}
 	
 	function _SetSite($run) {
 	    if ($run < 7000) { $this->site_nopasswd = 'http://blinkin.krl.caltech.edu/~chao/'; }
 	    else { $this->site_nopasswd = 'http://portal.nersc.gov/project/dayabay/'; }
 	    if ($this->is_sim) { 
 	        $this->prefix = 'dybprodSim/';
 	        $this->site_nopasswd = 'http://portal.nersc.gov/project/dayabay/'; 
 	    }
 	}
 	
 	function _IsPasswdProtected($run) {
        // if ($run < 6500) { return FALSE; }
        // else { return TRUE; }
 	    
 	    return FALSE;
 	}
 	
    function getrunlist() {
        // $xml = simplexml_load_file(
        //     str_replace('http://', 'http://dayabay:3quarks@', 
        //     $this->site_nopasswd . $this->prefix . $this->runs_xml)
        // );
        if ($this->is_sim) { 
 	        $this->prefix = 'dybprodSim/';
 	        $this->site_nopasswd = 'http://portal.nersc.gov/project/dayabay/'; 
 	    }
        // run list should always be from pdsf
        $xml = simplexml_load_file(
            $this->site_nopasswd . $this->prefix . $this->runs_xml
        );
        
        foreach( $xml->run as $run ) {
            $runnumber = $run->runnumber . ''; // the string cat is necessary for conversion
            $runindex = $run->runindex . '';
            $this->_SetSite($runnumber);
            $this->runlist[$runnumber] = array(
                'runindex' => $this->site_nopasswd . $this->prefix . $runindex,
            );
        }

    }
    
    function readrun($run) {
        $runindex = $this->runlist[$run]['runindex'];
        if ($runindex == '') { return; } 
        if ($this->_IsPasswdProtected($run)) { 
            $runindex = str_replace('http://', 'http://dayabay:3quarks@', $runindex); 
        }
        $this->readrun_xml($run, $runindex); 
    }
    
    //specify runnumber and xml_url, this saves time to read runlist
    function readrun_xml($run, $runindex) {
        $this->_SetSite($run);
        $xml = simplexml_load_file($runindex);
        
        $count_detectors = 0;
        foreach( $xml->run->detector as $detector ) {
            $detname = $detector->detname . '';
            $this->runlist[$run]['detectors'][$count_detectors] = array(
                'detname' => $detname,
                'figures' => array(),
                'channels' => array()
            );
            
            $count_figures = 0;
            foreach($detector->figure as $figure) {
                $this->runlist[$run]['detectors'][$count_detectors]['figures'][$count_figures] = array(
                    'path' => $this->site_nopasswd . $this->prefix . ($figure->path . ''),
                    'figname' => $figure->figname . '',
                    'rootPath' => $this->site_nopasswd . $this->prefix . dirname($figure->rootPath),
                    'figtitle' => $figure->figtitle . ''
                );
                $count_figures++;
            } // foreach($detector->figure as $figure) done
            
            $count_channels = 0;
            foreach($detector->channel as $channel) {
                $this->runlist[$run]['detectors'][$count_detectors]['channels'][$count_channels] = array(
                    'channelname' => $channel->channelname . '',
                    'figures' => array()
                );
                
                $count_channel_figures = 0;
                foreach($channel->figure as $figure) {
                    $this->runlist[$run]['detectors'][$count_detectors]['channels'][$count_channels]['figures'][$count_channel_figures] = array(
                        'path' => $this->site_nopasswd . $this->prefix . $figure->path,
                        'figname' => $figure->figname . '',
                        'rootPath' => $this->site_nopasswd . $this->prefix . dirname($figure->rootPath),
                        'figtitle' => $figure->figtitle . ''
                    );
                    $count_channel_figures++;
                }
                $count_channels++;
                
            } // foreach($detector->channel as $channel) done
                        
            $count_detectors++;
        } // foreach( $xml->run->detector as $detector ) done
    }
    
    function getarray_runlist() {
        $runlist = array();
        foreach ($this->runlist as $runnumber => $value ) { 
            array_push($runlist, $runnumber);
        }
        rsort($runlist);
        return $runlist;
    }
    
    function getdict_detectors($run) {
        $detectors = array();
        foreach ($this->runlist[$run]['detectors'] as $i => $detector) {
            $detectors[ $detector['detname'] ] = $i;
        }
        return $detectors;
    }
    
    function getdict_channels($run, $detector) {
        $channels = array();
        foreach ($this->runlist[$run]['detectors'][$detector]['channels'] as $i => $channel) {
            $channels[ $channel['channelname'] ] = $i;
        }
        return $channels;
    }
    
    function getarray_channels($run, $detector) {
        $channels = array();
        foreach ($this->runlist[$run]['detectors'][$detector]['channels'] as $i => $channel) {
            $channels[ $i ] = $channel['channelname'];
        }
        sort($channels);
        return $channels;
    }
 	
 	//map array(channelname => channelid)
 	function getarray_channelsname($run, $detector) {
 	    $channels = array();
        foreach ($this->runlist[$run]['detectors'][$detector]['channels'] as $i => $channel) {
            $channels[ $channel['channelname'] ] = $i;
        }        
        return $channels;
 	}
 	
 	//map array(pmtname => channelid)
 	function getarray_pmtname($run, $detector, $channelsname, $pmtFEE_dict) {
 	    $pmts = array();
 	    foreach ($pmtFEE_dict as $pmtname => $channelname) {
 	        list($detname, $board, $connector) = split('-', $channelname);
 	        $channelname = $board . '_' . $connector;
            $pmts[$pmtname] = $channelsname[$channelname];
 	    }
 	    return $pmts;
 	}
 	 	
    function xml_runlist($is_sim) {
        if($is_sim == 'sim') {
            $this->is_sim = TRUE;
        }
        $this->getrunlist();
        
        header('Content-Type: application/xml');
        echo "<?xml version= \"1.0\" ?>\n";
        echo "<diagnostic_index>\n";
                
        foreach( $this->runlist as $runnumber => $run ) {
            echo "<runnumber>" . $runnumber . "</runnumber>\n";
            echo "<runindex>" . $run['runindex'] . "</runindex>\n";
        }
        
        echo "</diagnostic_index>";
    }
    
    function json_runlist() {
 	    $this->getrunlist();
 	    $json = "{\n";
        foreach ($this->runlist as $runnumber => $run) {
            $json .= '"' . $runnumber 
            . '":"1",' . "\n";
        }     
        $json = rtrim($json, "\n,");       
        $json .= "}\n";
        echo $json;
 	}
    
    function json_figurelist($run, $is_sim) {
        if ($is_sim == 'sim') {
            $this->is_sim = TRUE;
        }
        $this->getrunlist();
        // print_r($this->runlist);
        $this->readrun($run);
        $detectors = $this->runlist[$run]['detectors'];
        
        $figure_list = array(); // array('DayaBayIWS' => array('figname'=>'', 'path' =>'', 'detector'=>'', ..), ...)

        $json = "{\n";
        
        $json .= ('"rootPath": "' . $detectors[0]['figures'][0]['rootPath'] . '",' . "\n");
        
        $json .= '"detectors": {';
        foreach ($detectors as $detector) {
            $detname = $detector['detname'];
            $json .= ('"' . $detname . '":[' . "\n");
            foreach ($detector['figures'] as $figure) {
                $json .= '{';
                $json .= ('"figname":"' . $figure['figname'] . '",');
                $json .= ('"path":"' . $figure['path'] . '"');
                $json .= "},\n";
            }
            $json = rtrim($json, ",\n");
            $json .= "],\n";
        }
        $json = rtrim($json, "\n,");       
        $json .= "},\n";
                
        // finalize json
        $json = rtrim($json, "\n,");       
        $json .= "\n}\n";
        
        echo $json;
        
        // echo "<pre>";        
        // print_r($this->runlist[$run]);
        // echo "</pre>";
    }
    
    function xml_figurelist($xml_url) {
        $run = 0; 
        $this->readrun_xml($run, $xml_url);
        
        header('Content-Type: application/xml');
        echo "<?xml version= \"1.0\" ?>\n";
        echo "<diagnostic_index>\n";
        foreach ($this->runlist[$run]['detectors'][0]['figures'] as $i => $figure ) {
            echo "<figname>" . $figure['figname'] . "</figname>\n";
        }
        foreach ($this->runlist[$run]['detectors'][0]['channels'][0]['figures'] as $i => $figure ) {
            echo "<channel_figname>" . $figure['figname'] . "</channel_figname>\n";
        }
        echo "</diagnostic_index>";
    }
    
    function json_runtype() {
        // this need the db access
        $query_str = 'SELECT runNo, runType FROM DaqRunInfo';
        $query = $this->db->query($query_str);
        $run_array = $query->result_array();
        
        $json = "{\n";
        foreach ($run_array as $i=>$row) {
            $json .= ('"' . sprintf('%05d', $row['runNo']) 
            . '":"' . $row['runType'] 
            . '",' . "\n");
        }     
        $json = rtrim($json, "\n,");       
        $json .= "}\n";
        echo $json;
    }
    
    function xml_figureurl($run, $figname, $channelname, $xml_url, $is_sim) {
        if ($is_sim == 'sim') {
            $this->is_sim = TRUE;
        }
        $this->readrun_xml($run, $xml_url);
        
        header('Content-Type: application/xml');
        echo "<?xml version= \"1.0\" ?>\n";
        echo "<diagnostic_index>\n";
        echo "<runnumber>" . $run . "</runnumber>\n";
        if($channelname == 'detector') { //search detector figure
            foreach ($this->runlist[$run]['detectors'][0]['figures'] as $i => $figure ) {
                if ($figure['figname'] == $figname) {
                    echo "<figname>" . $figname . "</figname>\n";
                    echo "<path>" . $figure['path'] . "</path>\n";
                    break;
                }
            } // foreach done
        }
        else { //search channel figure
            foreach ($this->runlist[$run]['detectors'][0]['channels'] as $i => $channel ) {
                if ($channel['channelname'] == $channelname) {
                    foreach ($channel['figures'] as $j => $figure) {
                        if ($figure['figname'] == $figname) {
                            echo "<figname>" . $figname . "</figname>\n";
                            echo "<path>" . $figure['path'] . "</path>\n";
                            break;
                        }
                    }
                    break;
                }
            } // foreach done
        }
        echo "</diagnostic_index>";
    }
 	
 	function json_channels($run, $detname, $is_sim) {
 	    if ($is_sim == 'sim') {
            $this->is_sim = TRUE;
        }
 	    $this->getrunlist();
        $this->readrun($run);
 	    $detectors = $this->getdict_detectors($run);
        $detector = $detectors[$detname];
 	    $channels = $this->getdict_channels($run, $detector);
 	    
 	    $json = "{\n";
        foreach ($channels as $channelname=>$i) {
            $json .= ('"' . $channelname 
            . '":"1' 
            . '",' . "\n");
        }     
        $json = rtrim($json, "\n,");       
        $json .= "}\n";
        echo $json;
 	}
 	
}