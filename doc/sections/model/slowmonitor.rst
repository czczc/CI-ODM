**********************
Slowmonitor
**********************

:file:`application/models/slowmonitor.php`

.. class:: Slowmonitor
   
   | This model queries the basic Slow Monitor information. 
   | This model requires explicitly loading offline database in advance
   | This model is meant to be used together with |Ajax|
   
   Example usage in the controller:
   
   .. code-block:: php
        
        <?php
        function json_temperature() {
            $this->load->database($this->session->userdata('database'));         
            $this->unset_session_options();
            
            $last2week = date("m/d/Y", time() - 86400*14);
            $today = date("m/d/Y", time() + 86400);
            $this->session->set_userdata('date_from', $last2week);
            $this->session->set_userdata('date_to', $today);
            $this->session->set_userdata('points', '1500');
            
            $this->Slowmonitor->json_temperature();
            // process the returned JSON data within javascript
        }