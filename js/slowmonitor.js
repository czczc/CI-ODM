$("#date_from").datepicker({ defaultDate: +0 });
$("#date_to").datepicker({ defaultDate: +0 });

// temperature monitor functions
var Temp_DataSet = {
    "Temp_PT1" : {label: "1: AD Bottom", color: 2, data: []},
    "Temp_PT2" : {label: "2: AD Middle", color: 6, data: []},
    "Temp_PT3" : {label: "3: AD Middle", color: 4, data: []},
    "Temp_PT4" : {label: "4: AD Top", color: 5, data: []},
    "Temp_PT5" : {label: "5: Clean Room", color: 3, data: []}
};
var Temp_Display = {
    "Temp_All" :  []
};
var temperature_options = {};

var empty_data = [ { label: "loading ...", data: [] } ];
var empty_options = [ {} ];

$('.holder').each(function() {
    $.plot($(this), empty_data, empty_options);
});

$("#show_temperature_options").click(function() {
    $("#temperature_options").slideToggle("fast");
    return false;
});

post_temperature_data({});
function post_temperature_data(options) {
    $.post(
        'json_temperature', options,
        function(data) {
            //empty data first
            $.each(Temp_DataSet, function(key, val) {
               val.data = [];
            });
            Temp_Display["Temp_All"] = [];
            
            for (var key_time in data) { 
                $.each(Temp_DataSet, function(key, val) {
                   val.data.push([key_time * 1000, data[key_time][key]]);
                });
            }      
                       
            //repopulate global variables
            $("#temperature_options input:checked").each(function() {
               var key = $(this).attr("name");
                Temp_Display["Temp_All"].push(Temp_DataSet[key]);
            });
            
            temperature_options = {
                series: {
                    lines: { show: false },
                    points: { show: true, fill: true, radius: 1 }
                },
                legend: {noColumns: 5, position: "nw"},
                xaxis: { mode: 'time' },
                yaxis: {min: 20.5, max: 23.5},
                selection: { mode: "xy"}
            };
            $.plot($("#Temp_All"), Temp_Display["Temp_All"], temperature_options);
        }, "json"
    ); // .post done
}

function submit_temperature() {
    $('.holder').each(function() {
        $.plot($(this), empty_data, empty_options);
    });
    var options = {
        'date_from': $("#date_from").val(),
        'date_to': $("#date_to").val(),
        'points' : $("#points").val()
    };
    post_temperature_data(options);
}

$("#temperature_submit").click(function() {
    submit_temperature();
    return false;
});

$("#temperature_reset").click( function() {
    post_temperature_data({});
    $("#date_from").value('');
    $("#date_to").value('');
    return false;
});

$(".holder").bind("plotselected", function (event, ranges) {
    var data = Temp_Display[$(this).attr('id')];
    $.plot( $(this), data, 
            $.extend(true, {}, temperature_options, {
                xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to },
                yaxis: { min: ranges.yaxis.from, max: ranges.yaxis.to }
            }));
});


//spade monitor functions
getSpadeRSS();
function getSpadeRSS() {
    $.getFeed({
        url: 'spaderss',
        success: function(feed) {
            $('#spaderss').empty();
            $('#spaderss').append('<h3 style="margin-top:5px;">'
            + '<a href="http://dayabay.lbl.gov/sentry-status/dybdmz_dbay/recentSyphoning.jsp">'
            + feed.title + '</a>' + '</h3>');

            var html = '<ul>';
            for(var i = 0; i < feed.items.length && i < 5; i++) {
                var item = feed.items[i];
                html += '<li><h6>' 
                + item.title + '</h6>'
                + '&ensp;&ensp;&ensp;&ensp;' + item.description
                + '&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;[' + item.updated
                + ']</li>';
            }
            html += '</ul>';
            $('#spaderss').append(html);
        } // success done    
    });
}
