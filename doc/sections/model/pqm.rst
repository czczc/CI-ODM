**********************
Pqm
**********************

:file:`application/models/pqm.php`

.. class:: Pqm
   
   | This model queries the basic PQM Plots information from IHEP
   | This model is meant to be used together with |Ajax|
   
   Example usage in the controller:
   
   .. code-block:: php
        
        <?php
        function pqm_json_runlist() {
            $this->Pqm->json_runlist();
            // process the returned JSON data within javascript
        }
        
   
   .. centered:: *Methods*
   
   
   .. method:: json_runlist()
      
      | Query the list of all processed runs on IHEP
      | Internally calls :meth:`GetRunList`
      
      :return: JSON data of the run list
       
         .. code-block:: javascript
             
            {
            "7192":"1",
            "7191":"1",
            // and all other process runs ...
            }
            
   .. method:: json_figurelist($run)
      
      | Query the list of available figures for a run
      
      :return: JSON data of the figure information
       
         .. code-block:: javascript
            
            {
            "SABAD2":[
                {
                "figname":"DarkRate",
                "path":"http://*****/h_sum_DarkRate.png"
                },
                {
                "figname":"ADCMean",
                "path":"http://*****/h_sum_ADCMean.png"
                },
                // and all other figures ...
            ],
            // and all other detectors ...
            }
            
   .. method:: GetCurrentRun()
   
      :return: current run number (possibly not in the offline database)


   .. note:: The following attributes and methods are meant to be used internally only.

   .. centered:: *Attributes*


   .. attribute:: site_nopasswd
      
      | The server to host the PQM plots, default to ``'http://web.dyb.ihep.ac.cn/dqm/'``
      
   
   .. centered:: *Methods*
          
   
   .. method:: GetRunList()
            
      | Query the list of all processed runs on IHEP   
      

            