**********************
Daqinfo
**********************

:file:`application/models/daqinfo.php`

.. class:: Daqinfo
   
   | This model queries the basic DAQ information. 
   | This model requires explicitly loading offline database in advance
   | This model is meant to be used together with |Ajax|
   
   Example usage in the controller:
   
   .. code-block:: php
        
        <?php
        function json_daq($run) {
            $this->load->database($this->session->userdata('database'));
            $this->Daqinfo->json_daq($run);
            // process the returned JSON data within javascript   
        }
        

   .. centered:: *Methods*
   
   .. method:: json_daq($run)
      
      | Query the basic DAQ information
      | Internally calls :meth:`LoadDaqInfo`
      
      :return: JSON data of the DAQ information
       
         .. code-block:: javascript

            {
            "active": "SAB-AD2",
            
            "SAB-AD1":{
                "FEE_prefix":"0_",
                "LTB_prefix":"",
                "FEEBoardVersion":"218760961",
                "FEECPLDVersion":"218696198",
                "LTBfirmwareVersion":"268837697"
                },
            // and daq info for all other detectors ...
           
           "LTB-SAB-AD1":{
               "DAC_total_value":"40",
               "HSUM_trigger_threshold":"50",
               "LTB_triggerSource":"NHit"
               },
            // and LTB info for all other detectors ...
            
            "0_1":{"DACUniformVal":"750"},
            "0":{},
            "0_5":{"MaxHitPerTrigger":"3","DACUniformVal":"750"},
            // ...
            "0_17":{"MaxHitPerTrigger":"3","DACUniformVal":"750"},
            // and FEE setting for all other detectors ...
            }  
            
   .. note:: The following attributes and methods are meant to be used internally only.

   .. centered:: *Attributes*


   .. attribute:: runNo
   .. attribute:: runType

   .. attribute:: base_table
      
      an array of default DAQ settings, based on this run's Schema-Base version
      
       .. code-block:: php
   
           <?php
           $base_table = array(
               array(
                   'runType' => '',
                   'className' => '',
                   'name' => '',
                   'intValue' => '',
                   'stringValue' => '',
               ),
               // more settings ...
           );
       
   .. attribute:: data_table
      
      same structure as :attr:`base_table`, but only records changes, based on this run's Schema-Data version
      
   .. attribute:: active_detector
      
      name of the active detector. TODO: need to record multiple active detectors
      
   .. attribute:: Detector_table

      a dictionary of detector DAQ settings
      
      .. code-block:: php
      
          <?php
          $Detector_table = array(
              'SAB-AD1' => array(
                  'FEE_prefix' => '',
                  'LTB_prefix' => '',
                  'FEEBoardVersion' => '',
                  'FEECPLDVersion' => '',
                  'LTBfirmwareVersion' => '',
              ),
              // more detectors ... 
          );

   .. attribute:: LTB_table
   
      a dictionary of LTB settings
      
      .. code-block:: php
      
          <?php
          $LTB_table = array(
              'LTB-SAB-AD1' => array(
                  'HSUM_trigger_threshold' => '',
                  'DAC_total_value' => '',
                  'LTB_triggerSource' => '',
              ),
              // more detectors ... 
          );   
   
   .. attribute:: FEE_table
   
      a dictionary of FEE settings
      
      .. code-block:: php
      
          <?php
          $FEE_table = array(
              '0_1' => array(
                  'MaxHitPerTrigger' => '',
                  'DACUniformVal' => '',
              ),
              // more FEEs ... 
          );      
   
   .. attribute:: triggerDict

      a dictionary of trigger codes
      
      .. code-block:: php
      
          <?php
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
          
          
   .. centered:: *Methods*
          
   
   .. method:: LoadDaqInfo($run)
            
      | Query the basic DAQ information   
      | Internally calls :meth:`_LoadRawTable`, :meth:`_ConstructDaqTable`, :meth:`_LoadActiveDetector`, :meth:`_LoadDetectorTable`, :meth:`_LoadLTBTable`, :meth:`_LoadFEETable`, :meth:`_FinalizeTables`
      
   
   .. method:: _LoadRawTable($run, $version)         
      
      construct :attr:`base_table` or :attr:`data_table`
      
      :param $version: ``'base'`` or ``'data'``
            
   .. method:: _ConstructDaqTable($run)
      
      | Initialize :attr:`Detector_table`, :attr:`LTB_table`, :attr:`FEE_table`
      | Detector_name hard-coded
      | FEE_prefix hard-coded
      | LTB_prefix hard-coded
      | FEE board hard-coded (5 - 17)
      
   .. method:: _LoadActiveDetector($row)
   .. method:: _LoadDetectorTable($row)
   .. method:: _LoadLTBTable($row)
   .. method:: _LoadFEETable($row)
   .. method:: _FinalizeTables()
      
      Clean up the tables.
      
   .. method:: _GetTriggerTypeString($trigger)
      
      Convert trigger code to a string based on :attr:`triggerDict`. Diffrent trigger types are joined by ' | '.
            
            
            