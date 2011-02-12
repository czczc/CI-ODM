*********************
Deploying ODM 
*********************

It is recommended to use ``rsync`` to deploy the local |ODM| site to the production site:

.. code-block:: sh
   
   rsync -avz -e ssh --update \
         --exclude '.htaccess' \
         --exclude '.svn/' \
         /path/to/local/ODM/  \
         /path/to/remote/ODM
   
Be careful about the trailing ``/`` in the above command. In general it's a good idea to try the deployment on your own institution's web server first before deploy it to the two production sites that are currently hosting ODM:

* PDSF: ``pdsf.nersc.gov:/project/projectdirs/dayabay/www/dybruns``
* IHEP: ``dayabay.ihep.ac.cn:/home/webroot/ODM``


