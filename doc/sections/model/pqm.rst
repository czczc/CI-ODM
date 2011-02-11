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