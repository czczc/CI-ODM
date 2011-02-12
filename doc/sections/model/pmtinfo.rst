**********************
Pmtinfo
**********************

:file:`application/models/pmtinfo.php`

.. class:: Pmtinfo
   
   | This model queries the basic PMT information. 
   | This model requires explicitly loading offline database in advance
   | This model is meant to be used together with |Ajax|
   
   Example usage in the controller:
   
   .. code-block:: php
        
        <?php
        function json_pmtinfo($detname) {
            $this->load->database($this->session->userdata('database'));
            $this->Pmtinfo->json_pmtinfo($detname);
            // process the returned JSON data within javascript
        }
        
   .. centered:: *Methods*
   
   .. method:: json_pmtinfo($detname)
      
      | Query the PMT infomation of a detector
      | Internally calls :meth:`LoadPmtInfo`, :meth:`LoadPMTCalib`
      
      :return: JSON data of the PMT information
       
         .. code-block:: javascript
         
             {
             "SABAD2-board05-connector01":{
                 "detector":"SABAD2",
                 "board":"05",
                 "connector":"01",
                 "ring":"01",
                 "column":"01",
                 "in_out":"",
                 "seqno":"25",
                 "status":"1",
                 "speHigh":"39.428",
                 "speLow":"2.022",
                 "timeOffset":"0.134"
            },
            // and all other PMT channels ...
            }


   .. note:: The following attributes and methods are meant to be used internally only.

   .. centered:: *Attributes*


   .. attribute:: FEE_to_PMT
      
      a dictinary of ``{FEECHANNELDESC => SENSORDESC}``
   
   .. attribute:: PMT_to_FEE
   
      a dictinary of ``{SENSORDESC => FEECHANNELDESC}``
          
   .. attribute:: PMT_Calib
      
      an array of calibration information of PMTs
      
      .. code-block:: php
      
          <?php
          PMT_Calib = array(
              'SABAD2-board05-connector01' = array(
                  'seqno' => $seqno,
                  'detector-ring-column' => $row->PMTDESCRIB,
                  'status' => $row->PMTSTATUS,
                  'speHigh' => $row->PMTSPEHIGH,
                  'speLow' => $row->PMTSPELOW,
                  'timeOffset' => $row->PMTTOFFSET,
              ),
              // more channels ...
          );
   


   .. centered:: *Methods*
          
   
   .. method:: LoadPmtInfo()
            
      | Query the FEE board_connector <=> PMT ring_column | wall_spot map 
             
   .. method:: LoadPMTCalib()
            
      | Query the PMT calibration information based on ``$this->input->post('run_start_time')`` 
      
      