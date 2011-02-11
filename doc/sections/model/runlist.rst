**********************
Runlist
**********************

:file:`application/models/runlist.php`

.. class:: Runlist
   
   | This model queries a filtered run list.
   | This model requires explicitly loading offline database in advance
   
   Example usage in the controller:
   
   .. code-block:: php
        
        <?php
        $this->Runlist->set_runtype('Physics');
        $this->Runlist->set_runrange(4000,7000);
        $this->Runlist->set_limit(100);  // get 100 records
        $this->Runlist->set_offset(0);  // from the 0th record
        
        $this->Runlist->get_runlist();        
        $data['runlist'] = $this->Runlist;
        $this->load->view('dybdb_view/findrun', $data);
        // Now you can access all attributes from $runlist in the view

   .. centered:: *Attributes*
   
   .. attribute:: count
   
      | the total number of runs in the filtered run list (before applying :meth:`.set_limit`)
      | This is needed for pagination.
      
   .. attribute:: run_array

      an array of the filtered run information
      
      .. code-block:: php
      
          <?php
          $run_array = array(
              array(
                  'runNo' => '',
                  'runType' => '',
                  'TIMESTART' => '',
                  'TIMEEND' => '',
              ),
              // more runs ...
          );
   
   .. attribute:: csv_list

      a dictionary of run (un-filtered) information from the runlist excel files
      
      .. code-block:: php
      
          <?php
          $csv_list = array(
              // run number => array of run information
              '5773' => array(
                  'DataCategory' => '',
                  'Comments' => '',
                  'Description' => '',
              ),
              // more runs ... 
          );

   .. centered:: *Methods*
   
   .. method:: get_runlist()
      
      | Construct :attr:`run_array` and :attr:`csv_list`
      | Need call one or a few of the "set" functions below in advance.
      | Internally calls :meth:`get_count`, :meth:`get_csvlist`

   .. method:: get_csvlist()
      
      Construct :attr:`csv_list`
  
   .. method:: set_runtype($runtype)
   
      Set run type of the query.
       
      :param $runtype: ``'Physics'``, ``'ADCalib'``, ``'Pedestal'``, ``'FEEDiag'``, or ``'All'``
      
   .. method:: set_runrange($from, $to)
      
      Set run range of the query.
       
      :param $from: minimum run number inclusive
      :param $to: maximum run number inclusive
   
   .. method:: set_daterange($from, $to)
   .. method:: set_orderby($order)
   .. method:: set_limit($limit)
   .. method:: set_offset($offset)
   .. method:: get_count()
      
             