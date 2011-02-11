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
        function json_pmt($run) {
            $this->load->database($this->session->userdata('database'));
            $this->Pmtinfo->json_pmt();
            // process the returned JSON data within javascript
        }