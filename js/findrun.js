var this_url = window.location.href;
var root_url = this_url.substring(0,this_url.indexOf('dybdb')) + '/';
var base_url = this_url.substring(0,this_url.indexOf('dybdb')) + 'dybdb/';
var loading_img = root_url + "styles/images/loading.gif";

var link_loading_img = '<img style="display:inline" src="' 
                     + loading_img
                     + '" alt="loading" />'

var runNo = $('#runno').html();
if( !runNo >0 ) {runNo = $('#run_no').html();}

var is_run_exist = true;
if ($('#run_not_exists').html()) {
    is_run_exist = false;
    // console.log(is_run_exist);
}

var diagnostics_detector_list = [];
var diagnostics_figure_list = {};
var diagnostics_rootPath = '#';
var pqm_detector_list = [];
var pqm_figure_list = {};

$("button").button();
$("#date_from").datepicker({ defaultDate: +0 });
$("#date_to").datepicker({ defaultDate: +0 });

$("#show_options").click(function() {
    $("#options").toggle("blind", "", "fast");
    return false;
});

$("#show_fields input[type=checkbox]").click(function() {
    var val = $(this).val();
    var is_checked = $(this).attr('checked');
    if (is_checked) { $('.' + val).show(); }
    else { $('.' + val).hide(); }
})

$("#csv_link").click(function() {
    $('#list_of_runs').table2CSV();
    return false;
});


if (window.location.href.indexOf('findrun') <= 0 
    && window.location.href.indexOf('currentrun') <= 0) {
    load_pqm_list();
    load_diagnostics_list();
}
else {
    load_daq();
    load_diagnostics();
    load_pqm();
}

function load_pqm_list() {
    var url = base_url + 'pqm_json_runlist';
    $.post(
        url, '',
        function(data) {
            $("td.PQMPlots").each(function() {
               var run = $(this).attr('id').substr(4);
               // var oldhtml = $(this).html();
               if (!data[run]) { $(this).html('N/A'); }
            });
        }, "json"
    ); // .post done
}

function load_diagnostics_list() {
    var url = base_url + 'diagnostics_json_runlist';
    $.post(
        url, '',
        function(data) {
            $("td.DiagnosticPlots").each(function() {
               var run = $(this).attr('id').substr(5);
               // var oldhtml = $(this).html();
               if (!data[run]) { $(this).html('N/A'); }
            });
        }, "json"
    ); // .post done
}

function load_daq() {
    //.getJSON use get method, which is not good for codeIgniter
    var url = base_url + 'json_daq/' + runNo;
    $.post(url,
        function(data) {
            var active_detector = data['active'];
            var LTB_triggerSource = $('#LTB_triggerSource');
            if (LTB_triggerSource.html() == 'loading ...') {LTB_triggerSource.html(data['LTB-'+active_detector]['LTB_triggerSource']);}
            $('#HSUM_trigger_threshold').html(data['LTB-' + active_detector]['HSUM_trigger_threshold']);
            $('#DAC_total_value').html(data['LTB-' + active_detector]['DAC_total_value']);
            $('#FEEBoardVersion').html(data[active_detector]['FEEBoardVersion']);
            $('#FEECPLDVersion').html(data[active_detector]['FEECPLDVersion']);
            $('#LTBfirmwareVersion').html(data[active_detector]['LTBfirmwareVersion']);

            var FEE_prefix = data[active_detector]['FEE_prefix'];          
            var boards = ['5','6','7','8','9','10','11','12','13','14','15','16','17'];
            for (var i=0; i<boards.length; i++) {
                var id = '#_' + boards[i] + '_DACUniformVal';
                $(id).html(data[ FEE_prefix + boards[i] ]['DACUniformVal']);

                id = '#_' + boards[i] + '_MaxHitPerTrigger';
                $(id).html(data[ FEE_prefix + boards[i] ]['MaxHitPerTrigger']);
            }
            $('#daq_table td.loading').removeClass('loading').addClass('value');   
        }, "json"
    ); // .post done
}

function load_diagnostics() {
    //.getJSON use get method, which is not good for codeIgniter
    var url = base_url + 'diagnostics_json_figurelist/' + runNo;
    $.post(
        url, '',
        function(data) {
            diagnostics_rootPath = data['rootPath'];
            $("#diagnostics_rootfile").click( function() {
               window.location =  diagnostics_rootPath;
            });
            for (var detector in data['detectors']) {
                diagnostics_detector_list.push(detector);
            }                
            diagnostics_detector_list.sort();
            diagnostics_figure_list = data['detectors'];
            if (diagnostics_detector_list.length>0) { 
                // enable live detectors
                for (var i=0; i<diagnostics_detector_list.length; i++) {
                    var site_detector = parse_detname(diagnostics_detector_list[i]);
                    var site = site_detector[0];
                    var detector = site_detector[1];    
                    var det_cell = $("#diagnostics_site_det tr." + site + " td." + detector + " a" );
                    det_cell.removeClass("det").addClass("live_det").attr("href", "#");
                }
                build_diagnostics(diagnostics_detector_list[0]); 
                build_pmtmap(diagnostics_detector_list[0]);
                
                // enable live detectors click
                $("#diagnostics_site_det a.live_det").click( function(){
                    var td = $(this).parent();
                    var tr = td.parent();
                    var site = tr.attr("class");
                    var detector = td.attr("class");
                    build_diagnostics(site+detector);
                    build_pmtmap(site+detector);  
                    return false; 
                });
            }
            $("#diagnostics_loading").remove();
        }, "json"
    ); // .post done
}

function build_diagnostics(detname) {
    $("#diagnostics_detector").html(detname);
    var date_now = new Date();
    var timestamp = date_now.getTime();
    
    // load figures
    var figure_list = diagnostics_figure_list[detname];
    var table_diagnostics_plots = $('#diagnostics_plots');
    table_diagnostics_plots.empty();
    var column_index = 0;
    var html = '';
    for (var i=0; i<figure_list.length; i++) {
        if (column_index == 0) {html += '<tr>';}
        html += '<td><img class="img_db" style="margin-bottom:5px" src="' 
             + str_force_reload(figure_list[i]['path'], timestamp)
             + '" width=300 height=225/><h6>'
             + figure_list[i]['figname'] 
             + '</h6><br /></td>';
        column_index++; 
        
        if (column_index == 3) {html += "</tr>\n"; column_index=0;}
    }
    table_diagnostics_plots.append(html);
    
    // enable image double click to origninal size
    $(".img_db").dblclick(function() {
        $.modal('<div><img src="' 
            + $(this).attr("src")
            + '" /></div>',
            {
                'overlayClose' : true,
            }
        );
        
        return false;
    });
    

    
}

function load_pqm() {
    var url = base_url + 'pqm_json_figurelist/' + runNo;
    $.post(
        url, '',
        function(data) {
            for (var detector in data) {
                pqm_detector_list.push(detector);
            }
            pqm_detector_list.sort();
            pqm_figure_list = data;
            if (pqm_detector_list.length>0) { 
                // enable live detectors
                for (var i=0; i<pqm_detector_list.length; i++) {
                    var site_detector = parse_detname(pqm_detector_list[i]);
                    var site = site_detector[0];
                    var detector = site_detector[1];    
                    var det_cell = $("#pqm_site_det tr." + site + " td." + detector + " a" );
                    det_cell.removeClass("det").addClass("live_det").attr("href", "#");
                }
                build_pqm(pqm_detector_list[0]); 
                
                // enable live detectors click
                $("#pqm_site_det a.live_det").click( function(){
                    var td = $(this).parent();
                    var tr = td.parent();
                    var site = tr.attr("class");
                    var detector = td.attr("class");
                    build_pqm(site+detector);
                    return false; 
                });
            }
            $("#pqm_loading").remove();
        }, "json"
    ); // .post done
}

function build_pqm(detname) {
    
    $("#pqm_detector").html(detname);
    var date_now = new Date();
    var timestamp = date_now.getTime();
    
    // load figures
    var figure_list = pqm_figure_list[detname];
    var table_pqm_plots = $('#pqm_plots');
    table_pqm_plots.empty();
    var column_index = 0;
    var html = '';
    for (var i=0; i<figure_list.length; i++) {
        if (column_index == 0) {html += '<tr>';}
        html += '<td><img class="img_db" style="margin-bottom:5px" src="' 
             + str_force_reload(figure_list[i]['path'], timestamp)
             + '" width=300 height=225/><h6>'
             + figure_list[i]['figname'] 
             + '</h6><br /></td>';
        column_index++; 
        
        if (column_index == 3) {html += "</tr>\n"; column_index=0;}
    }
    table_pqm_plots.append(html);
    
    // enable image double click
    $(".img_db").dblclick(function() {
        $.modal('<div><img src="' 
            + $(this).attr("src")
            + '" /></div>',
            {
                'overlayClose' : true,
            }
        );
        return false;
    });
    
}

function parse_detname(detname) {
    var sites = ['DayaBay', "LingAo", "Far", "SAB"];
    var site = "";
    var detector = "";
    var found = true;
    for (var i=0; i<sites.length; i++) {
        site = sites[i];
        detector = detname.replace(site, "");
        if (detector.length < detname.length) {
            found = true;
            break;
        }
    }
    if (found) {
        return [site, detector];
    }
    else {
        return ["", ""];
    }
}

function build_pmtmap(detname) {
    var site_detector = parse_detname(detname);
    var site = site_detector[0];
    var detector = site_detector[1];
    
    var feemap_table = $("#feemap_table");
    feemap_table.empty();
    var html = '';
    html += "<tr><td>connector</td>";
    for (var i=1; i<=16; i++) {
        html += "<td>" + sprintf("%02d", i) + "</td>";
    }
    html += "</tr>";    
    for (var board=5; board<=17; board++) {
        str_board = sprintf("%02d", board);
        html += "<tr board="
              + '"' + str_board + '"'
              + "><td>board " + str_board + "</td>";
        for (var connector=1; connector<=16; connector++) {
            str_connector = sprintf("%02d", connector);
            html += '<td connector="' + str_connector + '">';
            link = '<a href="' + base_url + 'channel/' + runNo + '/' + detname + '/' + str_board + '/' + str_connector + '">O</a>';
            html += link;
            html += '</td>';
        }
        html += "</tr>";
    }
    feemap_table.append(html);
    
    var pmtmap_table = $("#pmtmap_table");
    pmtmap_table.empty();
    if (detector.indexOf('AD') != -1) {
        html = "<tr><td>column</td>";
        for (var i=1; i<=24; i++) {
            html += "<td>" + sprintf("%02d", i) + "</td>";
        }
        html += "</tr>";    
        for (var ring=8; ring>=0; ring--) {
            str_ring = sprintf("%02d", ring);
            html += "<tr ring="
                  + '"' + str_ring + '"'
                  + "><td>ring " + str_ring + "</td>";
            for (var column=1; column<=24; column++) {
                str_column = sprintf("%02d", column);
                html += '<td column="' + str_column + '">';
                html += "</td>";
            }
            html += "</tr>";
        }
        pmtmap_table.append(html);
    }
    else if (detector.indexOf('WS') != -1) {
        html = "<tr><td>spot</td>";
        for (var i=1; i<=29; i++) {
            html += "<td>" + sprintf("%02d", i) + "</td>";
        }
        for (var ring=1; ring<=9; ring++) {
            str_ring = sprintf("%02d", ring);
            html += "<tr ring="
                  + '"' + str_ring + '"'
                  + "><td>" + str_ring + "</td>";
            for (var column=1; column<=29; column++) {
                str_column = sprintf("%02d", column);
                html += '<td column="' + str_column + '">';
                html += "</td>";
            }
            html += "</tr>";
        }
        pmtmap_table.append(html);
        $("#td_ring").prev().html('<h6>Wall</h6>');
        $("#td_column").prev().html('<h6>Spot</h6>');
    }
    
    $('#th_pmtinfo').html('Loading ...');
    load_channels(runNo, detname);
}

var channel_info = {};
function load_channels(runno, detname) {
    var url = base_url + 'json_channels/' + runNo + '/' + detname;
    $.post(url, 
        function(data) {
            channel_info = data;
            
            $('#feemap_table td').each(function(){
                var connector = $(this).attr("connector");
                var board = $(this).parent().attr("board");
                var channelname = 'board' + board + '_connector' + connector;
                if (board && connector && !data[channelname]) {
                    $(this).empty();
                }
            });
            
            load_pmtinfo(detname);
            $('#th_pmtinfo').html('PMT Information');
            
        }, "json"
    ); // .post done
}

function load_pmtinfo(detname) {
    var url = base_url + 'json_pmtinfo/' + detname;
    $.post(url, 
        { 'run_start_time': $.trim($('#run_start_time').html()) },
        function(data) {
            setup_pmtinfo(detname, data);
        }, "json"
    ); // .post done   
}


var td_ring = $("#td_ring");
var td_column = $("#td_column");
var td_board = $("#td_board");
var td_connector = $("#td_connector");
function setup_pmtinfo(detname, pmtinfo) {
    
    var feemap_tds = $('#feemap_table td');
    var pmtmap_tds = $('#pmtmap_table td');
    // var pmt_td;
    feemap_tds.each(function() {
        var connector = $(this).attr("connector");
        var board = $(this).parent().attr("board");
        
        if (board && connector) {
            var pmt = pmtinfo[detname + '-board' + board + '-connector' + connector];
            var channelname = 'board' + board + '_connector' + connector;
            var ring = '';
            var column = '';
            if (pmt) {
                ring = pmt['ring'];
                column = pmt['column'];
            }
            var selector = 'tr[ring="' + ring + '"] td[column="' +column + '"]';
            var pmt_td = $(selector);
            
            if (channel_info[channelname]) {
                $(selector).html($(this).html());
            }
            
            // setup fee table cell mouse over
            $(this).bind('mouseover', function(){
                td_board.html(board);
                td_connector.html(connector);
                
                if (pmt) {
                    var in_out = '';
                    if (pmt['in_out'] == 'in') { in_out = ' &rarr;'; }
                    if (pmt['in_out'] == 'out') { in_out = ' &larr;'; }

                    td_ring.html(pmt['ring']);
                    td_column.html(pmt['column'] + in_out);
                }
                else {
                    td_ring.html('--');
                    td_column.html('--');
                }

                $(this).css('background-color', '#5f0'); 
                
                if (pmt_td) {
                    pmt_td.css('background-color', '#5f0');
                }
            });
            
            $(this).bind('mouseout', function(){
                $(this).css('background-color', 'white');
                if (pmt_td) {
                    pmt_td.css('background-color', 'white');
                }
            });
            
            // setup pmt table cell mouse over
            var fee_td = $(this);
            pmt_td.bind('mouseover', function(){
                td_board.html(board);
                td_connector.html(connector);
                
                var in_out = '';
                if (pmt['in_out'] == 'in') { in_out = ' &rarr;'; }
                if (pmt['in_out'] == 'out') { in_out = ' &larr;'; }

                td_ring.html(pmt['ring']);
                td_column.html(pmt['column'] + in_out);


                $(this).css('background-color', '#5f0'); 
                fee_td.css('background-color', '#5f0');
            });
            
            pmt_td.bind('mouseout', function(){
                $(this).css('background-color', 'white');
                fee_td.css('background-color', 'white');
            });
        }
    }); // feemap_tds.each() done.
    
}

$('#show_pmtmap').click(function() {
    $("#feemap_table").parent().slideToggle('fast');
    $("#pmtmap_table").parent().slideToggle('fast');
    $("#pmt_info").slideToggle('fast');
});

function nowToBeijingTime() {
    var now = new Date();
    now.setMinutes(now.getMinutes() + now.getTimezoneOffset()); // UTC
    now.setHours(now.getHours() + 8); //Beijing
    return now.toString().replace(/ GMT.*/, ' [Beijing]');
}

var currentrun_update = $('.currentrun_update');
if (!is_run_exist) {
    $('#div_autoreload').removeClass('hidden');
    $("#chk_autoreload").attr('checked', false);
}

var timeinterval_id = new Object();
$("#chk_autoreload").click(function() {
    var val = $(this).val();
    var is_checked = $(this).attr('checked');
    if (is_checked) { 
        currentrun_update.html('last update: ' + nowToBeijingTime());
        timeinterval_id = setInterval(function() {
            build_diagnostics(diagnostics_detector_list[0]); 
            build_pmtmap(diagnostics_detector_list[0]);
            build_pqm(pqm_detector_list[0]);
            currentrun_update.html('last update: ' + nowToBeijingTime());
            // console.log('updated: ' + nowToBeijingTime() );
        }, 60000);
    }
    else {
        currentrun_update.html('');
        clearTimeout(timeinterval_id);  
    }
})

if (window.location.href.indexOf('currentrun') > 0) {
    var currentrun_update = $('.currentrun_update');
    currentrun_update.html('last update: ' + nowToBeijingTime());
    setInterval(function() {
        build_diagnostics(diagnostics_detector_list[0]); 
        build_pmtmap(diagnostics_detector_list[0]);
        build_pqm(pqm_detector_list[0]);
        currentrun_update.html('last update: ' + nowToBeijingTime());
        // console.log('updated: ' + nowToBeijingTime() );
    }, 60000);
}

function str_force_reload(str, timestamp) {
    // var pos = str.indexOf('?');
    // if (pos >= 0) { str = str.substr(0, pos);}       
    str = str + '?v=' + timestamp;
    // console.log(str);
    return str;
}

