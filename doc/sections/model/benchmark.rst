**********************
Benchmark
**********************

:file:`application/models/benchmark.php`

.. class:: Benchmark
   
   | This model benchmarks the database performance.
   | This model is meant to be used together with |Ajax|
   
   Example usage in the controller:
   
   .. code-block:: php
      
      <?php
      function json_benchmark($test, $db) {
          // $test = 'log_in'; 
          // $db = 'lbl'; 
          
          $this->Benchmark->json_benchmark($test, $db);
          // process the returned JSON data with javascript
      }   
   
   .. centered:: *Methods*
   
   
   .. method:: json_benchmark($test, $db)
   
      | Benchmarks the Database Performance.
      | Internally calls :meth:`.DoBenchmark`
       
      :param $test: name of the benchmark test. Available tests are:
      
         * ``'log_in'`` : db login time
         * ``'load_file'`` : query time from :meth:`Runinfo.FindRawDataFileInfo`
         * ``'load_daq'``: query time from :meth:`Daqinfo.LoadDaqInfo`
         * ``'load_dcs'`` : query time from :meth:`Slowmonitor.GetTemperatureInfo()`
         * ``'load_diagnostics_list'`` : query time from :meth:`Diagnostics.getrunlist`
         * ``'load_pqm_list'`` : query time from :meth:`Pqm.GetRunList`
                  
      :param $db: name of the database to be benchmarked. ``'lbl'``, ``'ihep'`` or ``'dayabay'``
      
      :return: JSON data of the benchmark result
       
         .. code-block:: javascript

            {
            "benchmark":"0.0165",
            "db":"lbl"
            }
      
   
   .. note:: The following attibutes and methods are meant to be used internally only.

   .. centered:: *Methods*

   
   .. method:: DoBenchmark($test, $db)
   
      Benchmarks the Database Performance. Refer to :meth:`.json_benchmark`
            
      :return: elapsed time [sec] from running the benchmark test 
      
      