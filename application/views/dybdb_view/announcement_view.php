<?php include 'header.php'; ?>
<?php include 'bookmarks.php'; ?>
<?php include 'breadcrumb.php'; ?>

<div id="content">
    <div class = "subject">
        <h2>
            Updates
            <div class="inline_right">01/22/2011</div>
        </h2>
        <div class="annoucement">
            <ul>
                <li>
                    Add multiple sites/detectors support. 
                    Each live detector will have a green button, clicking on which loads the corresponding plots.
                </li>
                <li>
                    Move most of the queries to background. 
                    This will especially increase the apparent page response in China.  
                </li>
                <li>
                    Fix the PQM links (help from Miao).
                </li>
                <li>
                    Add FEE/PMT mapping for all sites/detectors (loaded from offline DB).
                </li>
                <li>
                    Add PMT flasher plots (by Dan).
                </li>
                <li>
                    Add run time display in Beijing time.
                </li>
                <li>
                    Double clicking on a plot brings a modal window of original size. 
                </li>
                <li>
                    The 'Current Run' page auto-reloads every minute.
                </li>
                <li>
                    Add 'Auto-reload' check box to all 'live' runs (i.e. runs that are not in the offline DB).
                </li>
            </ul>
            - CZ
        </div>
    </div>
    
    <div class = "subject">
        <h2>
            Updates
            <div class="inline_right">12/03/2010</div>
        </h2>
        <div class="annoucement">
            <ul>
                <li>
                    Add PMT information viewer, can be located above the Diagnostic plots of each run.
                </li>
                <li>
                    Switch to Ajax loader for the PQM and Diagnotics list query on the front page, to increase the apparent loading speed. 
                </li>
                <li>
                    Tip for Firefox users: If you install the <a href="http://getfirebug.com/">Firebug</a> plugin, you can find out exactly what data are being requested and the querying time.
                </li>
                <li>
                    Change the homepage to list "ALL" runs instead of "Last Week" runs.
                    Clean up the urls.
                </li>
            </ul>
            - CZ
        </div>
    </div>
    
    <div class = "subject">
        <h2>
            Site Mirror
            <div class="inline_right">10/28/2010</div>
        </h2>
        <div class="annoucement">
            <p>
                The ODM site is now mirrored at IHEP as well: http://dayabay.ihep.ac.cn/ODM/index.php/dybdb
            </p>
            - CZ
        </div>
    </div>
    
    <div class = "subject">
        <h2>
            Source Code and Tutorial
            <div class="inline_right">10/10/2010</div>
        </h2>
        <div class="annoucement">
            <p>
                The source code of the ODM site is now available at DYBSVNROOT:///people/zhang 
            </p>
            <p>
                You might be interested in this 
                <a href="<?php echo(base_url() . "dayabay_config/CZ/DiveIntoODM.pdf")?>">tutorial</a>
                as well.
            </p>
            - CZ
        </div>
    </div>
    
    <div class = "subject">
        <h2>
            New Design of Menus
            <div class="inline_right">09/28/2010</div>
        </h2>
        <div class="annoucement">
            <p>
                I designed a set of new menus for the ODM site. Let me know what do you think.
            </p>
            - CZ
        </div>
    </div>
    
    <div class = "subject">
        <h2>
            New Features
            <div class="inline_right">09/27/2010</div>
        </h2>
        <div class="annoucement">
            <ul>
                <li>
                    Database Choice (Thanks to Craig): At log in, one can choose which database to use. For best performance, one should use 'LBL'. In case 'LBL' is down or not updated for some reason, one can choose other databases such as 'IHEP' or 'Central Database'.
                </li>
                <li> Integration with PQM (Thanks to Miao):
                    <ul> 
                        <li style='margin-left:20px;'>All PQM plots are available now on ODM.</li>
                        <li style='margin-left:20px;'>One can search for PQM plots at http://portal.nersc.gov/project/dayabay/dybruns/dybdb/search_pqm</li>
                        <li style='margin-left:20px;'>One can monitor the PQM plots for the current run in real time at http://portal.nersc.gov/project/dayabay/dybruns/dybdb/currentrun 
                            (link is at the left most corner). 
                            The plots are reloaded every minutes 
                            (a replica of the PQM monitor for convenience)</li>
                    </ul>
                </li>
                <li> 
                    Slow Monitor (Thanks to Wei and Liang): Right now only the AD temperature trends are viewable (data taken from offline database), will add more later. (Warning: 'slow' monitor page might render 'slow' in IE, depending on your computer specs. For best performance, please consider switching to other browsers).
                </li>
            </ul>
            - CZ
        </div>
    </div>
    
    <div class = "subject">
        <h2>
            New Features
            <div class="inline_right">09/20/2010</div>
        </h2>
        <div class="annoucement">
            <ul>
                <li>
                    The run description fields from dry-run-list.xls file are imported. (Since they are not in the offline database yet, I will update them manually once I see new dry-run-list posted on docdb)
                </li>
                
                <li>
                    One can customize the fields by click the 'customize fields...' link next to the list header
                </li>

                <li> 
                    DAQ settings added to the single run display page (thanks to Liang).
                </li>
                
                <li> 
                    Now shows meaningful calibration settings (instead of cryptic variables from calibration table. Thanks to Raymond).
                </li>
            </ul>
            - CZ
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-1.4.2.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-ui-1.8.4.custom.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/common.js")?>"></script>

<?php include 'footer.php'; ?>
