
/////////////////////////////////////////////////////////////////////////////////
// Setting environment variables ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

// Default list of variables to load at ready
var json_request_values = '{"values" : [' +
    //'"GEN_C2_OUT",' +
    '"LAS_GEMT_SUPPLY",' +
    '"LAS_GEMT_EMITING",' +
    '"LAS_GMTE_DIODE",' +
    '"LAS_GMTE_CRYSTAL",' +
    '"LAS_GMTE_ELECTRONICSINK",' +
    '"LAS_GMTE_HEATSINK",' +
    '"LAS_TEC1",' +
    '"LAS_TEC2",' +
    '"LAS_GSER_ERROR1",' +
    '"LAS_GSER_ERROR2",' +
    '"LAS_GSER_ERROR3",' +
    '"LAS_GSER_INFO1",' +
    '"LAS_GSER_INFO2",' +
    '"LAS_GSER_INFO3"' +
    ']}';

// Variable to reference the temperature plot
var temp_1;
// Default amount of seconds for the temperature plot tim range
var temps_seconds_default = 1*60*60+60;
// Arrays to share the temperature informarion
var temp_diode          = [];
var temp_crystal        = [];
var temp_electronicsink = [];
var temp_heatsink       = [];
var temp_times          = [];

/////////////////////////////////////////////////////////////////////////////////
// Information management functions /////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

function update_points(points){
    $.each(points,
        function(i, point) {
            $('#' + point["name"]).val(point["value"]);
        }
    );
}

function update_temperatures(data) {
    temp_diode          = [];
    temp_crystal        = [];
    temp_electronicsink = [];
    temp_heatsink       = [];
    var i;
    for (i = 0; i < data["times"].length; i++) { 
        temp_diode.push({x: data["times"][i], y: data["diode"][i]});
        temp_crystal.push({x: data["times"][i], y: data["crystal"][i]});
        temp_electronicsink.push({x: data["times"][i], y: data["electronicsink"][i]});
        temp_heatsink.push({x: data["times"][i], y: data["heatsink"][i]});
    }
    temp_times = data["times"];
}

function update_info(data, status){
    var response = JSON.parse(data);
    update_points(response["values"]);
    update_temperatures(response["temperatures"])
}

function laserServer(request_parameter, callback) {
    $.post("lib/laserServer.php", {
        random:  Math.random(),
        request: request_parameter
    },
        function(data, status) {
            update_info(data, status);
            if (callback) {
                callback();
            }
        }
    );
}

/////////////////////////////////////////////////////////////////////////////////
// Plotting functions ///////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

function update_plot(chart) {
    chart.data.datasets[0].data = temp_diode;
    chart.data.datasets[1].data = temp_crystal;
    chart.data.datasets[2].data = temp_electronicsink;
    chart.data.datasets[3].data = temp_heatsink;
    minX = Math.ceil(Math.min(...temp_times)/60/60)*60*60;
    chart.options.scales.xAxes[0].ticks.max = 0;
    chart.options.scales.xAxes[0].ticks.min = minX;
    chart.options.scales.xAxes[0].ticks.stepSize = Math.abs(minX)/6;
    chart.update();
}

function update_plot_time(chart, time){
    laserServer('{"temps": ' + time + '}',
        function() {
            update_plot(chart);
        }
    );
}

function draw_plot() {
    laserServer('{"temps": ' + temps_seconds_default + '}',
        function() {
            let minX = Math.ceil(Math.min(...temp_times)/60/60)*60*60;
            var ctx = document.getElementById("temp_1").getContext('2d');
            temp_1 = new Chart(ctx, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Diode',
                        fill: false,
                        data: temp_diode,
                        borderColor: 'rgba(0, 173, 159, 1)',
                        backgroundColor: 'rgba(0, 173, 159, 1)',
                        borderWidth: 2,
                        pointRadius: 0,
                        showLine: true
                    },
                    {
                        label: 'Crystal',
                        fill: false,
                        data: temp_crystal,
                        borderColor: 'rgba(36, 96, 167, 1)',
                        backgroundColor: 'rgba(36, 96, 167, 1)',
                        borderWidth: 2,
                        pointRadius: 0,
                        showLine: true
                    },
                    {
                        label: 'Electronic Sink',
                        fill: false,
                        data: temp_electronicsink,
                        borderColor: 'rgba(246, 82, 117, 1)',
                        backgroundColor: 'rgba(246, 82, 117, 1)',
                        borderWidth: 2,
                        pointRadius: 0,
                        showLine: true
                    },
                    {
                        label: 'Heat Sink',
                        fill: false,
                        data: temp_heatsink,
                        borderColor: 'rgba(252, 76, 2, 1)',
                        backgroundColor: 'rgba(252, 76, 2, 1)',
                        borderWidth: 2,
                        pointRadius: 0,
                        showLine: true
                    }]
                },
                options: {
                    responsive:true,
                    maintainAspectRatio: false,
                    animation: false,
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Time [hh:mm]'
                            },
                            ticks: {
                                max: 0,
                                min: minX,
                                stepSize: Math.abs(minX)/6,
                                callback: function(value, index, values) {
                                    value = Math.abs(value);
                                    sec = value % 60;
                                    min = ((value - sec)/60) % 60;
                                    hou = (value - min*60 - sec)/60/60
                                    if (min<10) {
                                        min = '0' + min;
                                    }
                                    if (hou < 10) {
                                        hou = '0' + hou;
                                    }
                                    return '-' + hou + ':' + min;
                                }
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: "Temperature [°C]"
                            }
                        }]
                    }
                }
            });
        }
    )
}

/////////////////////////////////////////////////////////////////////////////////
// Running on load //////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

$(document).ready(
    function() {
        // Load default values
        laserServer(json_request_values);
        // Load the temperature plot
        draw_plot(temps_seconds_default);
        // Start listening to action buttons
        $('[id^="A_"]').click(
            function() {
                laserServer($(this).attr("req"));
            }
        );
        // Start listening to plot buttons
        $('[id^="T_"]').click(
            function() {
                update_plot_time(temp_1, $(this).attr("req")*60*60+60);
            }
        );
        $('[id="H_diagram"]').click(
            function() {
                $('#canvas_plot').hide();
                $('#canvas_diagram').show();
            }
        )
        $('[id="H_plot"').click(
            function() {
                $('#canvas_diagram').hide();
                $('#canvas_plot').show();
            }
        )
    }
);
