do_database_benchmark();
do_throughput_benchmark();

function do_database_benchmark() {
    var tests = ['log_in', 'load_file', 'load_daq', 'load_dcs']
    for (var i=0; i<tests.length; i++) {
        var test = tests[i];
        $('#db_bench #' + test).siblings().each(function() {
           var db = $(this).attr('class');
           var td = $(this);
           $.post(
               "json_benchmark/" + test + "/" + db, '',
               function(data) {
                   td.empty().append(
                       '<div class="bench_bar"><span class="benchlabel">'
                       + data['benchmark'] + '</span></div>'
                    );
                    td.find( ".bench_bar" ).progressbar({value: data['benchmark']/3.*100});
               }, "json"
           ); // .post done
        });
    } // for done
}

function do_throughput_benchmark() {
    tests = ['load_diagnostics_list', 'load_pqm_list']
    for (var i=0; i<tests.length; i++) {
        var test = tests[i];
        $('#through_bench #' + test).each(function() {
            var db = 'lbl';
            var td = $(this);
            $.post(
                "json_benchmark/" + test + "/" + db, '',
                function(data) {
                    td.empty().append(
                        '<div class="bench_bar"><span class="benchlabel">'
                        + data['benchmark'] + '</span></div>'
                    );
                    td.find( ".bench_bar" ).progressbar({value: data['benchmark']/3.*100});
                }, "json"
            ); // .post done
        });
    } // for done
}