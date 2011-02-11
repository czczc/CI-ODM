**********************
Runinfo
**********************

:file:`application/models/runinfo.php`

.. class:: Runinfo
   
   | This model queries the basic run information. 
   | This model requires explicitly loading offline database in advance
   
   Example usage in the controller:
   
   .. code-block:: php
        
        <?php
        $this->Runinfo->FindRun(5773);
        $data['runinfo'] = $this->Runinfo;
        $this->load->view('dybdb_view/findrun', $data);
        // Now you can access all attributes from $runinfo in the view
   
   .. centered:: *Attributes*
   
   .. attribute:: $runNo
   .. attribute:: $runType
   .. attribute:: $detectorMask
   .. attribute:: $partitionName
   .. attribute:: $schemaVersion
   .. attribute:: $dataVersion
   .. attribute:: $baseVersion
   .. attribute:: $timestart
   .. attribute:: $timeend
   .. attribute:: $calib_array
      
      an array of calbration information
      
      .. code-block:: php
      
          <?php
          $calib_array = array(
              array(
                  'sourceIdA_str' => '',
                  'sourceIdB_str' => '',
                  'sourceIdC_str' => '',  // see $sourceID_dict and $ledID_dict
                  'ledVoltageA' => '',
                  'ledVoltageB' => '',    
                  'ledVoltageC' => '',  // LED voltage [mV]
                  'zPositionA' => '',
                  'zPositionB' => '',
                  'zPositionC' => '',  // source z postion [mm]
                  'HomeA' => '',
                  'HomeB' => '',
                  'HomeC' => '',    // 'Yes' or 'No'
                  'duration' => '',  // run duration [s]
                  'ledFreq' => '',  // LED Frequceny [Hz]
                  'ledPulseSep' => '',  // pulse separation [ns] if there are two LEDs
                  'ltbMode' => '',  // if 1, then force trigger
              ),
              // ...
          );
          // if only one run, $calib_array[0] gives you the dictionary
      
   .. attribute:: $sourceID_dict
      
      a dictionary of the radioactive source IDs
      
      .. code-block:: php
          
          <?php
          var $sourceID_dict = array(
               '0' => 'Unknown',
               '1' => 'LED',
               '2' => 'Ge68',
               '3' => 'AmC_Co60'
          );
          
   
   .. attribute:: $ledID_dict
      
      a dictionary of the LED IDs
      
      .. code-block:: php
         
         <?php
         var $ledID_dict = array(
              '0' => 'Unknown',
              '1' => 'A',
              '2' => 'B',
              '3' => 'C',
              '4' => 'MO_BOT',
              '5' => 'MO_MID',
              '6' => 'MO_TOP'
         );
   
   .. centered:: *Methods*
  
   .. method:: FindRun($run)
      
      | Query the basic run infomation.
      | Inside calls :meth:`.FindCalibrationInfo` if this run is a calibration run.
      
      :param $run: run number
   
   .. method:: FindRawDataFileInfo($run)
   
      Query the raw data file infomation  
      
      :param $run: run number  
   
   .. method:: FindCalibrationInfo($run)

      Query additional information for a calibration run.
      
      :param $run: run number
      
      