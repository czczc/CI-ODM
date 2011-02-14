*********************
Dybdb  
*********************

:file:`application/controllers/dybdb.php`


Overview
-----------

Most of the controller functions are simple wrappers around the models and views for the sole purpose of URL mapping. Besides that, access control is handled by the controller method :meth:`Dybdb.login`,  and form validations are applied inside the corresponding controller methods.


Details
-----------
.. class:: Dybdb
   
   The ODM Controller
   
   .. method:: Dybdb()
      
      The construtor. The following system libraries are loaded on every page under ``dybdb/``. All ODM models are loaded in the constructor as well since all their own constructors are empty anyway.

      .. code-block:: php

           <?php
           function Dybdb() {
               parent::Controller();
       
               $this->load->library('session');
               $this->load->library('form_validation');
               $this->load->library('table');

               // load the database only when needed
               // $this->load->database('lbl');
       
               $this->load->model('Pqm');
               $this->load->model('Diagnostics');
               $this->load->model('Pmtinfo');
               $this->load->model('Slowmonitor');
               $this->load->model('Runinfo');
               $this->load->model('Runlist');
               $this->load->model('Daqinfo');
               $this->load->model('Benchmark');
           }
   
   .. method:: index()
      
      Default Page. Redirect to ``runtype/All``   
      
   .. method:: login()
   .. method:: logout()
   .. method:: unset_session_options()
   .. method:: findrun($run, $currentrun)
   .. method:: getrunlist_record($offset, $comments)
   .. method:: runtype($runtype)
   .. method:: lastweek_runs()
   .. method:: currentrun()
   .. method:: channel($run, $detname, $board, $connector)
   .. method:: search_diagnostics()
   .. method:: search_pqm()
   .. method:: announcement()
   .. method:: slowmonitor()
   .. method:: json_temperature()
   .. method:: diagnostics_json_runlist()
   .. method:: diagnostics_json_figurelist($run)
   .. method:: pqm_json_runlist()
   .. method:: pqm_json_figurelist($run)
   .. method:: json_runtype()
   .. method:: json_daq()
   .. method:: json_pmtinfo($detname)
   .. method:: json_channels($run, $detname)
   .. method:: json_benchmark($test, $db)
   .. method:: xml_runlist()
   .. method:: xml_figurelist($xml_url)
   .. method:: xml_figureurl($run, $figname, $channelname, $xml_url)
   
   
   



   
