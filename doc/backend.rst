**********************
Back-end Programming
**********************

Back-end programming refers to the scripts that are run on the server side. ODM uses the PHP with the CodeIgniter Framework at the back-end.

Configuration
====================

The default CodeIgniter configurations are mostly good enough. We do need to make a few tweaks for |ODM|.

.. toctree::
   :maxdepth: 2
   
   sections/config/config
   sections/config/database


Models
===============

All heavy queries and complicated algorithms are handled by ODM's Model classes. The followings are the currently implemented ODM models.
 
.. toctree::
   :maxdepth: 2
   
   sections/model/benchmark
   sections/model/daqinfo
   sections/model/diagnostics
   sections/model/pmtinfo
   sections/model/pqm
   sections/model/runinfo
   sections/model/runlist
   sections/model/slowmonitor
   

Views
===============

Views are the basic layouts of the web pages that are loaded by the controllers.

.. toctree::
   :maxdepth: 1
   
   sections/view/views

Controllers
===============

Controller is the bridge between Views and Models. The Controller ``Class::Function(x, y, z)`` maps directly to the URL ``Class/Function/x/y/z`` that is very convenient for building up a dynamic website. Currently the whole ODM is under one Controller class. This may change in the future with the sacrifice of having to annouce new URLs.

.. toctree::
   :maxdepth: 1
   
   sections/controller/dybdb
   
   
   
