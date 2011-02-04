<?php

class Dybdb extends Controller {

    var $title = 'dybdb';
    private $options = array(); // search options
    
    function Dybdb() {
        parent::Controller();
        
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('table');

        // load the database only when needed to increase the speed
        // $this->load->database('dayabay');

        $this->load->model('Simpledb');
        $this->load->model('Pqm');
        $this->load->model('Diagnostics');
        $this->load->model('Pmtinfo');
        $this->load->model('Slowmonitor');
        $this->load->model('Runinfo');
        $this->load->model('Runlist');
        $this->load->model('Daqinfo');
        $this->load->model('Benchmark');
        $this->load->model('Poll');        
    }

    function index() {
        if ( ! $this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        redirect('dybdb/runtype/All', 'refresh');
        // $data = array();
        // $data['login_errors'] = '';
        // 
        // $this->load->view('dybdb_view/login', $data);
    }

    function logout() {
        $this->session->unset_userdata('logged_in'); 
        $this->session->unset_userdata('database'); 
        $this->login();
    }
    
    function login() {
        $data['login_errors'] = '';

        $config = array(
            array(
                'field'   => 'username',
                'label'   => 'Username',
                'rules'   => 'trim|required|xss_clean'
            ),
            array(
                'field'   => 'password',
                'label'   => 'Password',
                'rules'   => 'trim|required|xss_clean|md5'
            )						
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if( ! $this->form_validation->run() ) {
            $this->load->view('dybdb_view/login', $data);
        }
        else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $select_db = $this->input->post('select_db');

            if ($username === "dayabay" && $password === md5("3quarks")) {
                //this page should only be viewed after login
                $this->session->set_userdata('logged_in', TRUE);
                $this->session->set_userdata('database', $select_db);
                
                // $this->Poll->RecordIp();
                redirect('dybdb/runtype/All', 'refresh');
                // $this->lastweek_runs();
            }
            else {
                $data['login_errors'] = 'ERROR: WRONG USERNAME/PASSWORD.';
                $this->load->view('dybdb_view/login', $data);
            }
        } // else done		
    }

    function search() {
        if ( ! $this->session->userdata('logged_in')) {
            $this->login();
            return;
        }

        $this->load->database($this->session->userdata('database'));

        $this->load->model('Simpledb');
        $table_array = $this->db->list_tables();
        sort($table_array);

        $current_table = $this->input->post('current_table');
        $query = $this->db->query('SELECT COUNT(*) FROM ' . $current_table);
        $rows = $query->result_array();
        $count = $rows[0]['COUNT(*)'];

        $raw_str = $this->input->post('texts');
        $query_str = $this->Simpledb->validate_query_string($raw_str);

        if (!$this->Simpledb->is_select_only($query_str)) {
            echo 'Query only, please.';
            return;
        }

        $query = $this->db->query($query_str);

        $selected = $this->input->post('tables');
        $data = array(
            'query' => $query,
            'query_num_rows' => $query->num_rows(),
            'total_counts' => $count,
            'last_texts' => $raw_str,
            'query_str' => $query_str,
            'query_result_table' => $this->Simpledb->generate_table($query),
            'table_array' => $table_array,
            'selected' => $selected
            );
        $this->load->view('dybdb_view/search',  $data);
    }

    function search_interface() {
        if ( ! $this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        
        $this->load->database($this->session->userdata('database'));

        $table_array = $this->db->list_tables();
        sort($table_array);

        $this->load->view('dybdb_view/search',  
        array(
            'query' => '',
            'query_num_rows' => 0,
            'total_counts' => 0,
            'last_texts' => '',
            'query_str' => '',
            'query_result_table' => '',
            'table_array' => $table_array,
            'selected' => 0
            ));
    } 

    function findrun($run, $currentrun) {
        if (!$this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        $this->load->database($this->session->userdata('database'));
        
        $data['errors'] = '';		
        $config = array(
            array(
                'field'   => 'findrun',
                'label'   => 'Run Number',
                'rules'   => 'trim|required|numeric'
            ),					
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if((!$this->form_validation->run()) && (!isset($run)) ) {
            $this->load->view('dybdb_view/findrun', $data);   		
        }
        else {
            if (!isset($run) ) { $run = $this->input->post('findrun'); }
            $data['run'] = $run;
            $this->Runinfo->FindRun($run);
            $data['runinfo'] = $this->Runinfo;
            
            $this->Runlist->get_csvlist();
            $data['csv_list'] = $this->Runlist->csv_list;
            
            $data['pqm'] = $this->Pqm;
            if(isset($currentrun)) { $data['currentrun'] = $currentrun; }
            
            // if ($this->Runinfo->has_diagnostics) {
            //     $this->Diagnostics->getrunlist();
            //     $this->Diagnostics->readrun($run);
            //     $data['diagnostics'] = $this->Diagnostics->runlist[$run];
            //     
            //     foreach($data['diagnostics']['detectors'] as $i => $detector) {
            //         $channelsname = $this->Diagnostics->getarray_channelsname($run, $i);
            //         $data['channelsname'][$i] = $channelsname;
            //         $data['pmts'][$i] = $this->Diagnostics->getarray_pmtname($run, $i, $channelsname, $this->Pmtinfo->pmtFEE_dict);        
            //     }
            // }
            $this->load->view('dybdb_view/findrun', $data);
        }
    }

    function unset_session_options() {
        $this->session->unset_userdata('runtype'); 
        $this->session->unset_userdata('runno_from'); 
        $this->session->unset_userdata('runno_to'); 
        $this->session->unset_userdata('date_from'); 
        $this->session->unset_userdata('date_to'); 
        $this->session->unset_userdata('order');
    }
    
    function lastweek_runs() {
        $this->unset_session_options();
        
        $this->session->set_userdata('runtype', 'All');        
        $lastweek = date("m/d/Y", time() - 86400*6);
        $today = date("m/d/Y", time() + 86400);
        $this->session->set_userdata('date_from', $lastweek);
        $this->session->set_userdata('date_to', $today);
        $this->session->set_userdata('per_page', 500);
        
        $this->getrunlist_record(0, "Last Week");
    }
    
    function runtype($runtype) {
        $this->unset_session_options();
                      
        $this->session->set_userdata('runtype', $runtype);
        $this->session->set_userdata('per_page', 500);
        
        $this->getrunlist_record(0, $runtype);
    }
    
    function getrunlist_with_options() {
        $this->unset_session_options();
        
        $config = array(
            array(
                'field'   => 'runno_from',
                'label'   => 'Run No. (From)',
                'rules'   => 'trim|numeric'
            ),
            array(
                'field'   => 'runno_to',
                'label'   => 'Run No. (To)',
                'rules'   => 'trim|numeric'
            ),
            array(
                'field'   => 'date_from',
                'label'   => 'Date (From)',
                'rules'   => 'trim|callback_check_date'
            ),
            array(
                'field'   => 'date_to',
                'label'   => 'Date (To)',
                'rules'   => 'trim|callback_check_date'
            ),
            array(
                'field'   => 'per_page',
                'label'   => 'Records per page',
                'rules'   => 'trim|numeric'
            ),						
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if( !$this->form_validation->run() ) {
            $this->load->view('dybdb_view/findrun', $data);
            return;
        }
        
        $this->options['runtype'] = $this->input->post('runtype');
        $this->options['runno_from'] = $this->input->post('runno_from');
        $this->options['runno_to'] = $this->input->post('runno_to');
        $this->options['date_from'] = $this->input->post('date_from');
        $this->options['date_to'] = $this->input->post('date_to');
        $this->options['order'] = $this->input->post('order');
        if($this->input->post('per_page') > 0) {
            $this->options['per_page'] = $this->input->post('per_page');
        }
        else { $this->options['per_page'] = 500; }
        $this->session->set_userdata($this->options);
        
        $this->getrunlist_record(0, '');
    }
    
    function getrunlist_record($offset, $comments) {
        if ( ! $this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        
        $data['errors'] = '';	
        $data['comments'] = $comments;	
        if (!$this->session->userdata('runtype') ) {
            $this->load->view('dybdb_view/findrun', $data);
            return;
        }
        $this->load->database($this->session->userdata('database'));        
        
        // set up the query
        $this->Runlist->set_runtype($this->session->userdata('runtype'));
        if($this->session->userdata('runno_from') > 0 && $this->session->userdata('runno_to') > 0) {
            $this->Runlist->set_runrange($this->session->userdata('runno_from'), $this->session->userdata('runno_to'));
        }
        if($this->session->userdata('date_from') && $this->session->userdata('date_to')) {
            $this->Runlist->set_daterange($this->session->userdata('date_from'), $this->session->userdata('date_to'));
        }        
        $this->Runlist->set_orderby($this->session->userdata('order'));
        
        if($this->session->userdata('per_page') > 0) {
            $this->Runlist->set_limit($this->session->userdata('per_page'));
        }
        else {
            $this->session->set_userdata('per_page', 500);
            $this->Runlist->set_limit($this->session->userdata('per_page'));
        }
        
        if( isset($offset) ) { $this->Runlist->set_offset($offset); }
        else { $this->Runlist->set_offset(0); }
        
        // doing the query      
        $this->Runlist->get_runlist();        
        $data['runlist'] = $this->Runlist;
        
        $this->load->library('pagination');
        $config['base_url'] = site_url("dybdb/getrunlist_record");
        $config['total_rows'] = $this->Runlist->count;
        $config['per_page'] = $this->session->userdata('per_page');
        $config['num_links'] = 5; // 5 left, 5 right
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('dybdb_view/findrun', $data);
    }
    
    function check_date($the_date) {
        if (!$the_date) { return TRUE; }
        if ( ! preg_match("/\d\d\/\d\d\/\d\d\d\d/", $the_date) ) {
			$this->form_validation->set_message('check_date', 'The %s field format is not correct.');
			return FALSE;
		}
		else { return TRUE; }
    }
    
    function currentrun() {
        $run = $this->Pqm->GetCurrentRun();
        $this->findrun($run, $run);
    }
    
    function diagnostics($run, $detector, $channel) {
        if (!$this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        
        $this->Diagnostics->getrunlist();
        $this->Diagnostics->readrun($run);
        
        $data['diagnostics'] = $this->Diagnostics->runlist[$run];
        $data['run'] = $run;
        if(isset($detector) && $detector>=0 && isset($channel) && $channel>=0 ) {
            $data['detector'] = $detector;
            $data['channel'] = $channel;
            $this->load->view('dybdb_view/channel_view', $data);
            return;
        }
        
        foreach($data['diagnostics']['detectors'] as $i => $detector) {
            $channelsname = $this->Diagnostics->getarray_channelsname($run, $i);
            $data['channelsname'][$i] = $channelsname;
        }
        
        $this->load->view('dybdb_view/diagnostics_view', $data);        
    }
    
    function channel($run, $detname, $board, $connector) {
        if (!$this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        
        $this->Diagnostics->getrunlist();
        $this->Diagnostics->readrun($run);
        
        $detectors = $this->Diagnostics->getdict_detectors($run);
        $detector = $detectors[$detname];
        
        $channels = $this->Diagnostics->getdict_channels($run, $detector);
        $channel = $channels['board'.$board.'_connector'.$connector];
        
        $diagnostics = $this->Diagnostics->runlist[$run];
        $data['diagnostics'] = $diagnostics;
        $data['run'] = $run;
        $data['detector'] = $detector;
        $data['channel'] = $channel;
        
        $this->load->view('dybdb_view/channel_view', $data);
        return;
        
        echo '<pre>';
        print_r($diagnostics);
        echo '</pre>';
                
    }
    
    function search_diagnostics() {
        if ( ! $this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        $this->Diagnostics->getrunlist();
        $data['runlist'] = $this->Diagnostics->getarray_runlist();
        $this->load->view('dybdb_view/search_diagnostics_view', $data);
    }
    
    function search_pqm() {
        if ( ! $this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        $this->Pqm->GetRunList();
        $data['runlist'] = array_keys($this->Pqm->runlist);
        $this->load->view('dybdb_view/search_pqm_view', $data);
    }
    
    function missing() {
        if (!$this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        
        $this->load->database($this->session->userdata('database'));        
        $this->Runlist->get_missing_diagnostics();
    }
    
    function print_results() {
        // dummy
    }
    
    function announcement() {
        if (!$this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        $this->load->view('dybdb_view/announcement_view');
    }
        
    function slowmonitor() {
        if ( ! $this->session->userdata('logged_in')) {
            $this->login();
            return;
        }           
        $this->load->view('dybdb_view/slowmonitor_view');
    }
    
    function json_temperature() {
        $this->load->database($this->session->userdata('database'));         
        $this->unset_session_options();
        
        if($this->input->post('date_from') && $this->input->post('date_to')) {
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }        
        else {
            //last 2 week
            $last2week = date("m/d/Y", time() - 86400*14);
            $today = date("m/d/Y", time() + 86400);
            $this->session->set_userdata('date_from', $last2week);
            $this->session->set_userdata('date_to', $today);
        }
        
        if($this->input->post('points')) { 
            $this->session->set_userdata('points', $this->input->post('points')); 
        }
        else { $this->session->set_userdata('points', '1500'); }
        
        $this->Slowmonitor->json_temperature();
    }
    
    function spaderss() {
        $this->Slowmonitor->SpadeRSS();
    }
        
    function xml_runlist() {
        $this->Diagnostics->xml_runlist();
    }
    
    function diagnostics_json_runlist() {
        $this->Diagnostics->json_runlist();
    }
    
    function diagnostics_json_figurelist($run) {
        $this->Diagnostics->json_figurelist($run);
    }
    
    function pqm_xml_runlist() {
        $this->Pqm->xml_runlist();
    }
    
    function pqm_json_runlist() {
        $this->Pqm->json_runlist();
    }
    
    function pqm_json_figurelist($run) {
        $this->Pqm->json_figurelist($run);
    }
    
    function xml_figurelist($xml_url) {
        $xml_url = str_replace('-', '/', $xml_url);        
        $this->Diagnostics->xml_figurelist($xml_url);
    }
    
    function pqm_xml_figurelist() {
        $this->Pqm->xml_figurelist();
    }
    
    function json_runtype() {
        $this->load->database($this->session->userdata('database'));
        $this->Diagnostics->json_runtype();
    }
    
    function xml_figureurl($run, $figname, $channelname, $xml_url) {
        $xml_url = str_replace('-', '/', $xml_url);  
        $this->Diagnostics->xml_figureurl($run, $figname, $channelname, $xml_url);
    }
    
    function json_daq($run) {
        $this->load->database($this->session->userdata('database'));
        $this->Daqinfo->LoadDaqInfo($run);
        $this->Daqinfo->json_daq();
    }
    
    function json_pmt($run) {
        $this->load->database($this->session->userdata('database'));
        $this->Pmtinfo->json_pmt();
    }
    
    function json_pmtinfo($detname) {
        $this->load->database($this->session->userdata('database'));
        $this->Pmtinfo->json_pmtinfo($detname);
    }
    
    function json_channels($run, $detname) {
        $this->load->database($this->session->userdata('database'));
        $this->Diagnostics->json_channels($run, $detname);
    }
    
    function test_pmt() {
        $this->load->database($this->session->userdata('database'));
        $this->Pmtinfo->LoadPMTInfo();
        $this->Pmtinfo->LoadPMTCalib();
    }
          
    function benchmark() {
        if ( ! $this->session->userdata('logged_in')) {
            $this->login();
            return;
        }
        $this->load->view('dybdb_view/benchmark_view');        
    }
    
    function json_benchmark($test, $db) {
        $this->Benchmark->json_benchmark($test, $db);
    }

}