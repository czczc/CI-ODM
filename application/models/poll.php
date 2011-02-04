<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Poll extends Model 
{
    // this class is not impletmented yet. 
    // nersc server forbids writing permission.
    
    var $current_db = '';
    
    //upload dir for local test, comment out when upload
    // var $files = array(
    //     'ip' => 'upload/ip'
    // );
    
    var $files = array(
        'ip' => 'poll/ip'
    );
    
    var $ips = array(
    );
    
	function Poll() {
		parent::Model();
	}

    function RecordIp() {
        $current_ip = $this->input->ip_address();
        $file = $this->files['ip'];
        
        $handle = fopen($file, "r+");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                list($ip, $count) = explode(" ", $buffer);
                if ($this->input->valid_ip($ip)) { $this->ips[$ip] = (int)$count; };
            }
            $this->ips[$current_ip]++;

            rewind($handle);
            foreach($this->ips as $ip => $count) {
                fwrite($handle, $ip . " " . $count . "\n");       
            }
            fclose($handle);
        }
        else {
            // echo "cannot open.\n";
        }
    }

}