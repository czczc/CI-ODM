**********************
Basic Configuration
**********************

JQuery and JQuery UI should be included on all pages.

.. code-block:: html+php
   
   <body>
   
      ...
   
      <script type="text/javascript" 
              src="<?php echo(base_url() . "js/jquery.min.js")?>">
      </script>
      <script type="text/javascript" 
              src="<?php echo(base_url() . "js/jquery-ui.min.js")?>">
      </script>
   </body>
   
The reason to place the ``<script> ... </script>`` block near the end of the ``<body> ... </body>`` block is to make sure that the |DOM| be fully loaded before the javascript starts to process it. An alternative more cumbersome thus not recommended way is to wrap all javascript codes with:

.. code-block:: js

   $(document).ready(function() {
       // put all your jQuery goodness in here.
   });


  
