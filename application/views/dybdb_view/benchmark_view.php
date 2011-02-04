<?php include 'header.php'; ?>
<?php include 'bookmarks.php'; ?>
<?php include 'breadcrumb.php'; ?>

<div id="content">

    <div class = "subject">
        <h2>
            Database Benchmark
        </h2>
        <table border = "0" cellpadding="0" cellspacing="1", style="width:99%" class="tableborder" id='db_bench'>
            <thead><tr>
                <th>Benchmark Test [sec]</th>
                <th>LBL DB (dayabaydb.lbl.gov)</th>
                <th>IHEP DB (dybdb2.ihep.ac.cn)</th>
                <th>Central DB (dybdb1.ihep.ac.cn)</th>
            </tr></thead>
            <tbody>
                <tr>
                    <td id='log_in' class='th_first'><h6>Database Log In</h6></td>
                    <td class='lbl'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td class='ihep'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td class='dayabay'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                </tr>

                <tr>
                    <td id='load_daq' class='th_first'><h6>Query DAQ Info (Run 5773)</h6></td>
                    <td class='lbl'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td class='ihep'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td class='dayabay'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                </tr>

                <tr>
                    <td id='load_file' class='th_first'><h6>Query File Info (Run 5773)</h6></td>
                    <td class='lbl'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td class='ihep'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td class='dayabay'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                </tr>

                <tr>
                    <td id='load_dcs' class='th_first'><h6>Query DCS Info (Temperature)</h6></td>
                    <td class='lbl'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td class='ihep'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td class='dayabay'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                </tr>

            </tbody>
        </table>
    </div>
    
    <div class = "subject">
        <h2>
            Network Throughput Benchmark
        </h2>
        <table border = "0" cellpadding="0" cellspacing="1", style="width:99%" class="tableborder" id='through_bench'>
            <thead><tr>
                <th>Benchmark Test [sec]</th>
                <th>Diagnostic Plots (NERSC)</th>
                <th>PQM Plots (IHEP)</th>
            </tr></thead>
            <tbody>
                <tr>
                    <td  class='th_first'><h6>Load Available Plots</h6></td>
                    <td id='load_diagnostics_list'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                    <td id='load_pqm_list'><img src=<?php echo(base_url() . "styles/images/loading.gif")?> alt="loading" /></td>
                </tr>

            </tbody>
        </table>
    </div>
    
    <div class = "subject">
        <h2>
            Static Assets Usage
        </h2>
        <img src="<?php echo(base_url() . "dayabay_config/CZ/yslow.png")?>" style="width:99%;">
    
    </div>

</div>


<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-1.4.2.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/jquery-ui-1.8.4.custom.min.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/common.js")?>"></script>
<script type="text/javascript" src="<?php echo(base_url() . "js/benchmark.js")?>"></script>

<?php include 'footer.php'; ?>
