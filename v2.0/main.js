
/////////////////////////////////////////////////////////////////////////////////
// Setting environment variables ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

// Default list of commands and variables for startup
var startup_commands = '"LAS GEMT",' +
                       '"LAS GMTE",' +
                       '"LAS GTCO",' +
                       '"LAS GSER",' +
                       '"GEN C1:OUTP?",' +
                       '"GEN C1:BSWV?",' +
                       '"GEN C2:OUTP?",' +
                       '"GEN C2:BSWV?",' +
                       '"ATT D"';
//var startup_commands = ''
var startup_values = '"LAS_GEMT_SUPPLY",' +
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
                     '"LAS_GSER_INFO3",' +
                     '"LAS_D",' +
                     '"GEN_C1_WVTP",' +
                     '"GEN_C1_AMP",' +
                     '"GEN_C1_OFST",' +
                     '"GEN_C1_FRQ",' +
                     '"GEN_C1_DUTY",' +
                     '"GEN_C1_WIDTH",' +
                     '"GEN_C1_RISE",' +
                     '"GEN_C1_FALL",' +
                     '"GEN_C1_OUT",' +
                     '"GEN_C2_WVTP",' +
                     '"GEN_C2_AMP",' +
                     '"GEN_C2_OFST",' +
                     '"GEN_C2_FRQ",' +
                     '"GEN_C2_DUTY",' +
                     '"GEN_C2_WIDTH",' +
                     '"GEN_C2_RISE",' +
                     '"GEN_C2_FALL",' +
                     '"GEN_C2_OUT",' +
                     '"ATT_DB",' +
                     //'"ATT_PERCENT",' +
                     '"ATT_POS"';

// List of units for specific parameters
var units = {
    LAS_GEMT_SUPPLY:         "[H:M]",
    LAS_GEMT_EMITING:        "[H:M]",
    LAS_GMTE_DIODE:          "[°C]",
    LAS_GMTE_CRYSTAL:        "[°C]",
    LAS_GMTE_ELECTRONICSINK: "[°C]",
    LAS_GMTE_HEATSINK:       "[°C]",
    GEN_C1_AMP:              "[V]",
    GEN_C1_OFST:             "[V]",
    GEN_C1_FRQ:              "[Hz]",
    GEN_C1_DUTY:             "[%]",
    GEN_C1_WIDTH:            "[S]",
    GEN_C1_RISE:             "[S]",
    GEN_C1_FALL:             "[S]",
    GEN_C2_AMP:              "[V]",
    GEN_C2_OFST:             "[V]",
    GEN_C2_FRQ:              "[Hz]",
    GEN_C2_DUTY:             "[%]",
    GEN_C2_WIDTH:            "[S]",
    GEN_C2_RISE:             "[S]",
    GEN_C2_FALL:             "[S]",
    ATT_DB:                  "[dB]",
    //ATT_PERCENT:             "[%]",
    ATT_POS:                 "[Step]"
}

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
// Variable to store the advanced view display
var advanced = false;

/////////////////////////////////////////////////////////////////////////////////
// Information management functions /////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

function update_points(points){
    $.each(points,
        function(i, point) {
            var unit = get_unit(point["name"]);
            $('#' + point["name"]).val(point["value"] + unit);
        }
    );
}

function get_unit(point) {
    if (point in units) {
        return " " + units[point];
    } else {
        return "";
    }
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

function laserServer(req_cmd, req_val, req_tim, callback) {
    req = "{";
    if (req_cmd != "") {
        req = req + '"commands":[' + req_cmd + ']';
    }
    if (req_val != "") {
        if (req != "{") {
            req = req + ',';
        }
        req = req + '"values":[' + req_val + ']';
    }
    if (req_tim != 0) {
        if (req != "{") {
            req = req + ',';
        }
        req = req + '"temps":' + req_tim;
    }
    req = req + "}";
    $.post("lib/laserServer.php", {
        random:  Math.random(),
        request: req
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
    laserServer("", "", time,
        function() {
            update_plot(chart);
        }
    );
}

function draw_plot() {
    laserServer("", "", temps_seconds_default,
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
        // Start listening to action buttons
        $('[id^="A_"]').click(
            function() {
                laserServer($(this).attr("req_cmd"), $(this).attr("req_val"), 0);
            }
        );
        // Start listening to plot buttons
        $('[id^="T_"]').click(
            function() {
                update_plot_time(temp_1, $(this).attr("req_tim")*60*60+60);
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
        $('[id="H_C1"').click(
            function() {
                $('#generator_ch2').hide();
                $('#generator_ch1').show();
            }
        )
        $('[id="H_C2"').click(
            function() {
                $('#generator_ch1').hide();
                $('#generator_ch2').show();
            }
        )
        $('[id="H_advanced"').click(
            function() {
                if (advanced) {
                    $('#panel_advanced').fadeOut(420,
                        function() {
                            $('#panel').animate({height: "452px"}, {
                                duration: 420,
                                done: function() {
                                    advanced = false;
                                }
                            });
                        }
                    )
                } else {
                    $('#panel').animate({height: "903px"}, {
                        duration: 420, 
                        done: function() {
                            advanced = true; 
                            $('#panel_advanced').fadeIn(420);
                        }
                    });
                }
            }
        )
        // Load default values and remove loading page
        laserServer(startup_commands, startup_values, 0,
            function() {
                draw_plot(temps_seconds_default);
                $("#H_advanced").fadeIn(500);
                $("#panel_loading").fadeOut(500);
            }
        );
    }
);
