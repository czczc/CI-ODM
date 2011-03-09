**********************
Basic
**********************

.. rubric:: Remove "index.php?" from the url

You will need to create a :file:`.htacess` file under the :file:`ODM/` directory that you just checked out with the following content:

:file:`.htacess`

.. code-block:: apache

    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteBase /path/to/ODM/
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php/$1 [L]
    </IfModule>

    <IfModule !mod_rewrite.c>
        ErrorDocument 404 /index.php
    </IfModule>

Replace :file:`/path/to/ODM/` with the correct relative url of your test ODM site.

.. rubric:: Setup Auto Base_url

:file:`application/config/config.php`

.. code-block:: php
     
     <?php
     $http_host = "http://".$_SERVER['HTTP_HOST'];
     
     // IHEP server specific : still need "index.php"
     $config['index_page'] = "";
     if (strlen(strstr($http_host, 'ihep.'))>0) {
         $config['index_page'] = "index.php";
     }
     
     $config['base_url'] = $http_host . str_replace(
        basename($_SERVER['SCRIPT_NAME']),
        "", $_SERVER['SCRIPT_NAME']
     );
     
     // Compared with the old way that
     // you have to uncomment a specific line every time 
     // before you deploy to a different server
     
     // $config['base_url']  = "http://blinkin.krl.caltech.edu/~chao/dayabay/";
     // $config['base_url']  = "http://portal.nersc.gov/project/dayabay/dybruns/";
     // $config['base_url']  = "http://dayabay.ihep.ac.cn/ODM/";


.. rubric:: Setup Autoload

To simplify the code, you can autoload models on every page load. However be aware that it increases the response time of every page! The recommended autoload setup for ODM is:

:file:`application/config/autoload.php`

.. code-block:: php
     
     <?php
     // only load system libraries when necessary in the controller
     // especially don't autoload the database library
     $autoload['libraries'] = array();
     
     // it's OK to load the useful helpers
     $autoload['helper'] = array('url', 'form', 'file');


.. rubric:: Setup Session Expire Time

To avoid annoying people too much, ODM sets the session expire time to 12 hours:

:file:`application/config/config.php`

.. code-block:: php

   <?php
   $config['sess_expiration'] = 43200;