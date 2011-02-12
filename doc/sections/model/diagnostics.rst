**********************
Diagnostics
**********************

:file:`application/models/diagnostics.php`

.. class:: Diagnostics
   
   | This model queries the basic Diagnostic Plots information from PDSF.
   | This model is meant to be used together with |Ajax|.
   
   Example usage in the controller:
   
   .. code-block:: php
        
        <?php
        function diagnostics_json_runlist() {            
            $this->Diagnostics->json_runlist();
            // process the returned JSON data within javascript
        }
        
   .. centered:: *Methods*
   
   .. method:: json_runlist()
      
      | Query the list of all processed runs on PDSF
      | Internally calls :meth:`getrunlist`
      
      :return: JSON data of the run list
       
         .. code-block:: javascript
             
            {
            "4366":"1",
            "4373":"1",
            // and all other process runs ...
            }
            
   .. method:: json_figurelist($run)
      
      | Query the list of available figures for a run, excludes individual channel figures
      | Internally calls :meth:`getrunlist`, :meth:`readrun`
      
      :return: JSON data of the figure information
       
         .. code-block:: javascript
            
            {
            "rootPath": "http://*****/root",
            "detectors": {
                "SABAD1":[
                    {
                    "figname":"triggerRate",
                    "path":"http://*****/triggerRate.png"
                    },
                    {
                    "figname":"dtTrigger",
                    "path":"http://*****/dtTrigger.png"
                    },
                    // and all other plots' names and urls ...
                ],
                // and all other detectors ...
            }
            }  

   .. method:: json_channels($run, $detname)
      
      | Query the list of live channels of a detector for a run
      | Internally calls :meth:`getdict_detectors`, :meth:`getdict_channels`
      
      :return: JSON data of the live channel list
       
         .. code-block:: javascript
         
             {
             "board05_connector01":"1",
             "board05_connector02":"1",
             // and all other live channels ...
             }

   .. method:: json_runtype()
      
      | Query the run type of all runs
      | This method requires explicitly loading offline database in advance
      | **TODO** This method should be moved to the :class:`Runlist` class
      
      :return: JSON data of the run type list
       
         .. code-block:: javascript
         
             {
             "00006":"Pedestal",
             "00007":"FEEDiag",
             // and all other runs ...
             }         
            
   .. note:: The following attributes and methods are meant to be used internally only.

   .. centered:: *Attributes*


   .. attribute:: site_nopasswd
      
      The server to host the dianostic plots. This can be diffrent from the ODM server. 
      
      * ``'http://portal.nersc.gov/project/dayabay/'``
      * ``'http://blinkin.krl.caltech.edu/~chao/'``
   
   .. attribute:: prefix
   
      default to ``'dybprod'``
          
   .. attribute:: runs_xml
   
      default to ``'runs.xml'``            

   .. attribute:: runlist
   
      a dictionary of diagnostic plots info of all processed runs
      
      .. code-block:: php
      
          <?php
          $runlist = array(
              '5773' => array(
                  'runindex' => '',
                  'detectors' => array(
                      array(
                          'detname' => '',
                          'figures' => array(
                              array(
                                  'path' => '',
                                  'figname' => '',
                                  'rootPath' => '',
                                  'figtitle' => '',
                              ),
                              // more figures ...
                          ),
                          'channels' => array(
                              array(
                                  'channelname' => '',
                                  'figures' => array(
                                      array(
                                          'path' => '',
                                          'figname' => '',
                                          'rootPath' => '',
                                          'figtitle' => '',
                                      ),
                                      // more figures ...
                                  ),
                              ),
                              // more channels ...
                          ),
                      ),
                      // more detectors ...
                  ),
              ),
              // more runs ... 
          );


   .. centered:: *Methods*
          
   
   .. method:: getrunlist()
            
      | Query the list of all processed runs on PDSF
      | From http://portal.nersc.gov/project/dayabay/dybprod/runs.xml
      
   .. method:: readrun($run)
            
      | Query the diagnostic plots info of a run
      | Internally calls :meth:`readrun_xml`                   

   .. method:: readrun_xml($run, $runindex)
            
      Query the diagnostic plots info from the ``$runindex`` xml file
      
   .. method:: getdict_detectors($run)
            
      :return: a map of {detname => detector index in :attr:`runlist`}

   .. method:: getdict_channels($run, $detector)
            
      :return: a map of {channelname => channel index in :attr:`runlist`}      

         