$("#status_bar").progressbar({ value: 1 }); //start loading run list
$('.pblabel').text('Loading Run List ...');
$("button").button();

var is_ajax_finished = true; //global variable, ajax status
var is_ajax_aborted = false; //global variable, ajax status
var runs_hash = {};  // hash of runnumber => run_xml_path (all diagnostics)
var runtype_hash = {}; // hash of runnumber => run_type (all runs in db)
var runs = []; // sorted array of run numbers (all diagnostics)
var figures = []; // sorted array of figure names (from the last updated run)
var channel_figures = []; // sorted array of channel figure names (from the last updated run)
var nPlots = 1;
var plot = ''; //selected plot for search
var plots = []; //selected plots for search
var detector_plots = []; //selected detector plots for search
var channel_plots = []; //selected channel plots for search
var runtype = 'All'; //selected run type for search
var sortrun = 'ASC'; //selected sort option
var num_col = 1; //number of columns
var channelname = 'detector'; //channel name 'boardxx-connectorxx'
var width = 400;
var height = 300;
var progressbar_value = 10;

var xhr = null; //the XMLHttpRequest object
var xhr_array = [];

get_runlist_figurelist();
xhr_array.push(xhr);	 	   	
get_runtype();

function get_runlist_figurelist() {
    xhr = $.ajax({
        type: "POST",
        url: "xml_runlist/sim",
        dataType: "xml",
        success: function(xml) {
            var run_count = 0;
            $(xml).find('runnumber').each(function() {
                var run = parseInt($(this).text(), 10);
                runs[run_count] = run;
                run_count++;            
                runs_hash[run] = $(this).next('runindex').text();
            });	
        
            // var last_url = runs_hash[runs[runs.length-1]]; // get last_url before sorting
            var last_url = runs_hash[7000]; // get last_url before sorting
            last_url = last_url.replace(/\//g, '-'); // a hack for codeigniter url
        
            runs.sort();
            progressbar_value += 30;
            $("#status_bar").progressbar({ value: progressbar_value });
            $('.pblabel').text('Loading Figure List ...');
        
            var xhr_2 = $.ajax({
                type: "POST",
                url: "xml_figurelist/" + last_url,
                dataType: "xml",
                success: function(xml) {
                    figure_count = 0;
                    $(xml).find('figname').each(function(){
                        figures[figure_count] = $(this).text();
                        figure_count++;
                    });
                
                    channel_figure_count = 0;
                    $(xml).find('channel_figname').each(function(){
                        channel_figures[channel_figure_count] = $(this).text();
                        channel_figure_count++;
                    });
                },
                complete: function() {
                    figures.sort();
                    channel_figures.sort();
                    fill_figures_name('plotname_1');
                    $('#options_diagnostics').show("slide", { direction: "up" }, "normal");
                    progressbar_value += 30;
                    $("#status_bar").progressbar({ value: progressbar_value });
                    if (progressbar_value == 100) { $('.pblabel').text('Ready for Searching'); }
                    else { $('.pblabel').text('Loading Run Type ...'); }
                }
            }); // .ajax inner (figures) done
            xhr_array.push(xhr_2);	 	   	
        } // success() done
    }); //.ajax outer (runs + figures) done
}

function get_runtype() {
    $.post(
        "json_runtype", '',
        function(data) {
            $('#.diagnostics_table td a').each(function(){
                var run = $(this).html();
                var runtype = data[run];
                runtype_hash[run] = runtype;
                $(this).addClass(runtype);
            });
            progressbar_value += 30;
            $("#status_bar").progressbar({ value: progressbar_value });
            if (progressbar_value == 100) { $('.pblabel').text('Ready for Searching'); }
        }, "json"
    ); // .post done
}

function fill_figures_name(name) {
    str_select_figures = ''
    for(var i=0; i<figures.length; i++) {
    	str_select_figures  += ("\n<option value='" 
    	+ figures[i] + "' class='plot_detector'>" 
    	+ figures[i] + "</option>");
    }
    $("select[name='" + name + "'] .fig_detectors").append(str_select_figures);
    
    str_select_figures = ''
    for(var i=0; i<channel_figures.length; i++) {
    	str_select_figures  += ("\n<option value='" 
    	+ channel_figures[i] + "' class='plot_channel'>" 
    	+ channel_figures[i] + "</option>");
    }
    $("select[name='" + name + "'] .fig_channels").append(str_select_figures);
}

$('#add_button').click(function() {
    nPlots++;
    var newname = 'plotname_' + nPlots;
    $('.plot_selection_tr').last().after( "<tr class='plot_selection_tr'>\n"
        + "<td></td><td>"
        + "<select name='" + newname +"' id='" + newname + "' class='plot_selection'>"  
        + '<optgroup label="Detector Figures" class="fig_detectors"></optgroup>'
        + '<optgroup label="Channel Figures" class= "fig_channels"></optgroup>'
        + "</select>" 
        + "<a class='minus_button' href='#' style='font-size: 20px'>&nbsp;&nbsp;- </a>"
        + "</td></tr>"
    );
    fill_figures_name(newname);
    $('.plot_selection_tr').last().find('.minus_button').click(function() {
        $(this).parent().parent().empty();
        nPlots--;
        if (nPlots == 1) {
            $("input[name='num_col']").removeAttr("disabled");
        }
        return false;
    });

    $("input[name='num_col']").attr("disabled", true);
    return false;
});

function unique(a)
{
   var r = new Array();
   o:for(var i = 0, n = a.length; i < n; i++) {
      for(var x = i + 1 ; x < n; x++) {
         if(a[x]==a[i]) continue o;
      }
      r[r.length] = a[i];
   }
   return r;
}

$("#submit").click( function() {
    plots = []; //empty the array first
    detector_plots = []; //empty the array first
    channel_plots = []; //empty the array first

    $("select[class='plot_selection'] option:selected").each(function() {
        var thisclass = $(this).attr('class');
        if (thisclass == 'plot_detector') { detector_plots.push($(this).val()); }
        else { channel_plots.push($(this).val()); }
        plots.push($(this).val());
    });

    plots = unique(plots);
    detector_plots = unique(detector_plots);
    channel_plots = unique(channel_plots);
    nPlots = plots.length;
    
    plot = $("select[name='plotname_1']").val();
    num_col = $("input[name='num_col']").val();
    channelname = $("#board_select").val() + '_' + $("#connector_select").val();
    runtype = $("#runtype_select").val();
    sortrun = $("#sortrun").val();
    txtlist = $("textarea[name='runlist']").val().split(/\s+/);
          
    runlist = {};
    runlist_keys = [];
    for(var i=0; i<txtlist.length; i++) {
        run = txtlist[i];
        if(run.match('-')) {
            run_min_max = run.split('-');
            for (var j=parseInt(run_min_max[0],10); j<=parseInt(run_min_max[1],10); j++) {
                if(runs_hash[j]) {runlist[j] = runs_hash[j];}
            }
        }
        else {
            run_int = parseInt(run, 10)
            if(runs_hash[run_int]) {runlist[run_int] = runs_hash[run_int];}
        }            
    }
    
    for (var name in runlist) {
        runlist_keys.push(name);
    }
    if (runtype != 'All') {
        runlist_keys = filter_runtype(runlist_keys, runtype);
    }
    if (sortrun == 'ASC') { runlist_keys.sort( function(a,b){return a-b;} ); }
    else if (sortrun == 'DESC') { runlist_keys.sort( function(a,b){return b-a;} ); }
    else {}

    $('.pblabel').text('Loading Figures ... (Double Click to Abort)');
    $("#status_bar").progressbar({ value: 1 });
    
    create_table(runlist_keys, plot, num_col);
    $("#fig_display").show("slide", { direction: "up" }, "normal");
    if (runlist_keys.length == 0) {
        $("#status_bar").progressbar({ value: 100 });
        $('.pblabel').text('No Runs found. Double Click to Clear.');
    }
    
    loaded_run = '';
    loaded_run_count = 0;
    is_ajax_finished = false; //ajax call started
    is_ajax_aborted = false;  
    for (var i=0; i<runlist_keys.length; i++) {
        var run = runlist_keys[i];        
        var url = runlist[run].replace(/\//g, '-'); // a hack for codeigniter url
        for (var j=0; j<nPlots; j++ ) {
            var query_plot = '';
            var query_channel = '';
            if (j<detector_plots.length) {
                query_plot = detector_plots[j];
                query_channel = 'detector';
            }
            else {
                query_plot = channel_plots[j-detector_plots.length];
                query_channel = channelname;
            }

            var xhr_fig = $.ajax({
                type: "POST",
                url: "xml_figureurl/" + run + "/" + query_plot + "/" + query_channel + "/" + url,
                dataType: "xml",
                success: function(xml) {
                    thisrun = parseInt( $(xml).find('runnumber').text() );
                    thisfig = $(xml).find('figname').text();
                    loaded_run = thisrun;
                    loaded_run_count++;

                    var figpath = $(xml).find('path').text(); //prefix already in xml
                    var link = '';
                    if (num_col>1) {
                        link = '<br /><a href="findrun/' + thisrun + '/sim">' + thisrun + "</a>"
                    }
                    var thisid = thisrun;
                    if(nPlots>1) {
                        thisid += thisfig; 
                        link = '';
                        var window_width = $(window).width() * 0.85;
                        width = (window_width - 50) / nPlots;
                        height = width * 3 / 4;
                    }
                    $("#"+thisid).html(
                        '<img class="img_db" src="' 
                        + figpath + '"' 
                        + ' width=' + width 
                        + ' height=' + height + ' />'
                        + link
                    );
                    return false;
                },
                complete: function() {
                    $('.pblabel').text('Run ' + loaded_run + ' Loaded (Double Click to Abort)');
                    percent = loaded_run_count*100 / runlist_keys.length / nPlots
                    $("#status_bar").progressbar({ value: percent });
                    if (percent == 100 && !is_ajax_aborted) {
                        $('.pblabel').text('All Figures Loaded. Double Click to Clear.');
                        is_ajax_finished = true;
                        is_ajax_aborted = false;
                        width=400; height=300; //reset to default
                        //set image dbclickable (after the img is set)
                        $(".img_db").dblclick(function() {
                            window.location = $(this).attr("src");
                            return false;
                        });
                    }
                },
                error: function(XMLHttpRequest, textStatus) {
                    // console.log(textStatus);
                }
            });
        xhr_array.push(xhr_fig);	 	   	
        }
                                
    }
    $(this).blur();
    return false;
});

function filter_runtype(runlist, runtype) {
    var filted_list = [];
    for (var i=0; i<runlist.length; i++) {
        var thisrun = sprintf("%05d",runlist[i]);
        var thisruntype = runtype_hash[thisrun];
        if (thisruntype == runtype) {
            filted_list.push(runlist[i]);
        }        
    }    
    return filted_list;
}

function create_table(runlist, plot, num_col) {
    var fig_display = $('#fig_display');
    fig_display.empty();
     
    fig_display.append('<table id="fig_table"' 
        + ' border = "0" cellpadding="0" cellspacing="1"' 
        + ' style="width:99%" class="tableborder">'
        + '</table>'
    );
    
    var fig_table = fig_display.find('#fig_table');
    
    if (nPlots > 1) {
        var str = "\n<thead>\n<tr><th>Run No.</th>";
        for (var i=0; i<nPlots; i++) {
            str += ("<th>" + plots[i] + "</th>");
        }
        str += " </tr>\n</thead>\n";
        fig_table.append(str);
        
        fig_table.append("<tbody>\n");
        for (var i=0; i<runlist.length; i++ ) {
            var str = "<tr><td>" + '<a href="findrun/' 
            + runlist[i] + '/sim">' + runlist[i] 
            + "</a></td>";
            
            for (var j=0; j<nPlots; j++) {
                var this_id = runlist[i] + plots[j];
                str += ('<td id="' + this_id + '">loading ...</td>');
            }
            str += "</tr>\n";
            fig_table.append(str);
        }
        fig_table.append("</tbody>");
        return false;
    }
    
    if (num_col == 1) {
        fig_table.append("\n<thead>\n<tr>"
            + "<th>Run No.</th>"
            + "<th>" + plot + "</th>" 
            + " </tr>\n</thead>\n"
        );

        fig_table.append("<tbody>\n");
        for (var i=0; i<runlist.length; i++ ) {
            fig_table.append("<tr>"
                + "<td>" + '<a href="findrun/' + runlist[i] 
                + '/sim">' + runlist[i] + "</a></td>"
                + '<td id="' + runlist[i] + '">loading ...</td>'
                + "</tr>\n");
        }
        fig_table.append("</tbody>");       
    }
    else if (num_col > 1) {
        var nRuns = 0;
        fig_table.append("<tbody>\n");
        
        while (nRuns < runlist.length) {
            var str_row = "<tr>";
            for (var i=0; i<num_col; i++) {
                if (nRuns < runlist.length) {
                    str_row += ('<td id="' + runlist[nRuns] + '">'
                    + runlist[nRuns] + '<br />loading ...</td>');
                }
                else {
                    str_row += ('<td></td>'); 
                }
                nRuns++;
            }
            str_row += "</tr>\n";
            fig_table.append(str_row);
        }
        fig_table.append("</tbody>");
        
        window_width = $(window).width() * 0.85;
        width = window_width / num_col;
        height = width * 3 / 4;
    }
    else {}
}

$("#status_bar").dblclick(function() {
    if(is_ajax_finished) {
        $("#fig_display").empty();
        $('.pblabel').text('Ready for Searching');
        is_ajax_finished = true;
        is_ajax_aborted = false;
    }
    else {
        //stop all ajax calls, do not empty tables;
        for (var i=0; i<xhr_array.length; i++) {
            xhr_array[i].abort();
        }
        $('.pblabel').text('Loading Aborted. Double Click to Clear');
        is_ajax_aborted = true;
        is_ajax_finished = true;
    }
    $("#status_bar").progressbar({ value: 100 });
});

$('#print_results').click(function() {    
    var width = $(window).width() * 0.9;
    
    var print =  window.open('print_results',
        'printWindow',
        'width=' + width 
        + ',scrollbars=yes,menubar=yes,toolbar=yes,location=yes,resizable=yes');
    var html = $('#fig_display').html();
    print.document.open();
    var header = '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'
        + '<head><title>Print Results</title>'
        + '<style>table{ text-align: center;font-size: 12px;}</style>'
        + '</head><body>';
    print.document.write(header);
    print.document.write(html);
    var footer = '</body></html>';
    print.document.write(footer);
    print.document.close();
    
    return false;
});

$('a.collapse_one').click(function() {
    $(this).parent().parent().parent().next('tbody').toggle("blind", "", "fast");
    if ($(this).html() == "[-]") { $(this).html("[+]"); } 
    else { $(this).html("[-]"); } 
    return false;
});

$('#collapse_all').click(function() {
    if ($(this).html() == "[-]") { 
        $('.diagnostics_table tbody').hide("blind", "", "fast");
        $(this).html("[+]"); 
        $('a.collapse_one').html('[+]'); 
    } 
    else { 
        $('.diagnostics_table tbody').show("blind", "", "fast");
        $(this).html("[-]"); 
        $('a.collapse_one').html('[-]'); 
    } 
    return false;
});

function collapse_all() {
    $('.diagnostics_table tbody').hide("blind", "", "fast");
    $('#collapse_all').html("[+]");
    $('a.collapse_one').html('[+]');
}
