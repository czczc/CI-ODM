***********************
Database
***********************

.. rubric:: Available Databases

Currently three (identical) offline databases can be used. 

:file:`application/config/database.php`

.. code-block:: php
     
     <?php
     $db['dayabay']['hostname'] = "dybdb1.ihep.ac.cn";
     $db['ihep']['hostname'] = "dybdb2.ihep.ac.cn";
     $db['lbl']['hostname'] = "dayabaydb.lbl.gov";
     
They are given different names and you should load the correct one in your controller based on which server the ODM is hosted on.

:file:`application/controllers/dybdb.php`

.. code-block:: php
     
     <?php
     // $this->load->database('dayabay');
     // $this->load->database('ihep');
     $this->load->database('lbl');
     
     
.. rubric:: Common Database Configuration

:file:`application/config/database.php`

.. code-block:: php
     
     <?php
     $db['lbl']['username'] = "xxxxx";
     $db['lbl']['password'] = "xxxxx";
     $db['lbl']['database'] = "offline_db";
     $db['lbl']['dbdriver'] = "mysql";
     $db['lbl']['dbprefix'] = "";
     $db['lbl']['pconnect'] = FALSE; // IMPORTANT
     $db['lbl']['db_debug'] = FALSE;
     $db['lbl']['cache_on'] = FALSE;
     $db['lbl']['cachedir'] = "";
     $db['lbl']['char_set'] = "utf8";
     $db['lbl']['dbcollat'] = "utf8_general_ci";
     
.. warning::

   The default 'pconnect' is set to TRUE, which will NOT close the connection on exit. Change it to FALSE otherwise you will leave tons of stale connections in the database.     
     
     