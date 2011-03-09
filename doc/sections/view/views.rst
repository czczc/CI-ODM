*********************
ODM Views  
*********************

All views are under :file:`application/views/dybdb_view/`

Site-wide Views
---------------

* :file:`header` : The html <header> included by every page.
* :file:`footer` : The footers at the bottom of every page.
* :file:`bookmarks` : The 'QUICK LINKS' tab at the top of every page.
* :file:`breadcrumb` : The logo and menus on every page.


Page Views
----------

* :file:`login` : The login page.
* :file:`findrun` : The 'querying run list' page and the individual run page. that displays DAQ, Diagnostic and PQM information. This view includes:
  
   * :file:`search_runlist_div` : Show run list search options.
   * :file:`diag_div` : Show Diagnostic plots.
   * :file:`pqm_div` : Show PQM plots.

* :file:`channel_view` : Displays the plots for individual channels.
* :file:`search_diagnostics_view` : The 'search diagnostics plots' page.
* :file:`search_pqm_view` : The 'search pqm plots' page.
* :file:`slowmonitor_view` : The slow mointor page.
* :file:`annoucement_view` : The annoucement page.
* :file:`benchmark_view` : The benchmark page.
