******************
Get Started
******************

.. Comment .................................

.. index:: Language

Language Prerequisite
======================

* PHP & SQL
* JavaScript
* HTML / CSS

You don't need to read too deep into the languages, just enough to get you started. The best way to learn a language is by using it in your project, not by reading the book.

.. Comment .................................

.. index:: Framework

Framework Prerequisite
===========================

* PHP Framework: `CodeIgniter <http://codeigniter.com/>`_
* JavaScript Framework: `JQuery <http://jquery.com/>`_


After you have learned some basics of the languages, you can start trying out the two wonderful frameworks that are used in ODM. There are two excellent video tutorial series for beginners (
`CodeIgniter From Scratch <http://net.tutsplus.com/sessions/codeigniter-from-scratch/>`_ | `JQuery For Absolute Beginners <http://net.tutsplus.com/articles/web-roundups/jquery-for-absolute-beginners-video-series/>`_) that should get you started.

.. Comment .................................

.. index:: Test Web Server

Setup a Test Web Server
=================================
You will need a test web server during development. The recommended way to setup the web server on each platform is:

* Windows: `XAMPP <http://www.apachefriends.org/en/xampp-windows.html>`_
* Mac: `MAMP <http://www.mamp.info/en/index.html>`_
* Linux: following google to setup the **LAMP** server 

To make sure the web server is set up correctly, write a simple php file, for example

.. code-block:: php

    <?php echo phpinfo(); ?>

put it to the correct document root directory, and see if it can be displayed on your web browser with the correct url. 

.. Comment .................................

.. index:: SVN

Setup a Code Development Environment
======================================
The ODM code is hosted on the official Daya Bay svn repository. Change to your web document root directory and check out the code 

.. code-block:: sh

    svn co http://dayabay.ihep.ac.cn/svn/dybsvn/groups/Web/ODM

Try to see if you can understand the basic code structure. 

You will need to create a ".htaccess" file under the "ODM" directory that you just checked out with the following content:

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

Replace "/path/to/ODM/" with the correct relative url of your test ODM site. Does the ODM site show up in your web browser?

You will need a good Code Editor or IDE for the developing. While *vi* or *emacs* has been the long time favorite by the programmers, web developers tend to use something more 'modern'. 
`Aptana <http://www.aptana.com/products/studio2>`_ is a good free IDE that is available on all platforms. If you are using a mac, then `TextMate <http://macromates.com/>`_ is highly recommended even though it's not free.

Congratulations! Now you are ready for the actual coding. The fun part begins.


