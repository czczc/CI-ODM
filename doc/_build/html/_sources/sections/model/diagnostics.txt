**********************
Diagnostics
**********************

:file:`application/models/diagnostics.php`

.. class:: Diagnostics
   
   | This model queries the basic Diagnostic Plots information from PDSF
   | This model is meant to be used together with |Ajax|
   
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
            
            
            