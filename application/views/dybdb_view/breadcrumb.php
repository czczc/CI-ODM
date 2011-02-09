<div id="top-bar">
    <img src=<?php echo(base_url() . "styles/images/logo.png")?> alt="Daya Bay Run Summary" class="floatleft" />
    <div id="right-side">
        <?php echo anchor('dybdb/runtype/All', 'Home&ensp;', array('class' => 'first')); ?>
        <?php echo anchor('dybdb/currentrun', 'Current Run&ensp;'); ?>
        <?php echo anchor('dybdb/logout', 'Logout&ensp;'); ?>

        <?php echo form_open('dybdb/findrun', array('id' => 'main-search'));?>
        <label for="search-field" id="search-field-label">Search</label>
        <input type="text" tabindex="1" maxlength="255" name="findrun" id="search-field"/>
        <input type="image" alt="Find" value="Find" src=<?php echo(base_url() . "styles/images/search.png")?> id="search-button"/>  
        <?php echo form_close();?>
    </div>
</div>

<div id="zone-bar">
    <ul>            
        <li>
            <a href="#" class="top_menu"><span>
                Find Runs&ensp;
                <img src=<?php echo(base_url() . "styles/images/zonebar-downarrow.png")?> alt="dropdown" />
            </span></a>
            <ul class="sub_menu">
                <li><?php echo anchor('dybdb/currentrun', 'Current Run'); ?></li>
                <li><?php echo anchor('dybdb/lastweek_runs', 'Last Week'); ?></li>
                <li class="menu_separator"></li>
                <li><?php echo anchor('dybdb/runtype/All', 'All'); ?></li>
                <li><?php echo anchor('dybdb/runtype/Physics', 'Physics'); ?></li>
                <li><?php echo anchor('dybdb/runtype/ADCalib', 'ADCalib'); ?></li>
                <li><?php echo anchor('dybdb/runtype/Pedestal', 'Pedestal'); ?></li>
                <li><?php echo anchor('dybdb/runtype/FEEDiag', 'FEEDiag'); ?></li>
            </ul>
        </li>

        <li>
            <a href="#" class="top_menu"><span>
                Find Plots&ensp;
                <img src=<?php echo(base_url() . "styles/images/zonebar-downarrow.png")?> alt="dropdown" />
            </span></a>
            <ul class="sub_menu">
                <li><?php echo anchor('dybdb/search_diagnostics', 'Diagnostic Plots'); ?></li>
                <li><?php echo anchor('dybdb/search_pqm', 'PQM Plots'); ?></li>
            </ul>
        </li>

        <li>
            <a href="#" class="top_menu"><span>
                Slow Monitor&ensp;
                <img src=<?php echo(base_url() . "styles/images/zonebar-downarrow.png")?> alt="dropdown" />
            </span></a>
            <ul class="sub_menu">
                <li><?php echo anchor('dybdb/slowmonitor#spade', 'Spade Status'); ?></li>
                <li><?php echo anchor('dybdb/slowmonitor#temperature', 'AD Temperature'); ?></li>
            </ul>
        </li>

        <li>
            <a href="#" class="top_menu"><span>
                System&ensp;
                <img src=<?php echo(base_url() . "styles/images/zonebar-downarrow.png")?> alt="dropdown" />
            </span></a>
            <ul class="sub_menu">
                <li><?php echo anchor('dybdb/announcement', 'Announcement'); ?></li>
                <li><?php echo anchor('dybdb/benchmark', 'Benchmark'); ?></li>
                <li><?php echo anchor('doc/html', 'Documentation'); ?></li>
                <li class="menu_separator"></li>
                <li><?php echo anchor('dybdb/logout', 'Logout'); ?></li>
            </ul>
        </li>

    </ul>
</div>
