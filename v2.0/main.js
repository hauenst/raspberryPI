
/////////////////////////////////////////////////////////////////////////////////
// Information management functions /////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

function update_operational(ope) {
    $('[id^="A_OP_STEP"]').attr('disabled', true);
    $('[id^="A_OP_STEP"]').removeClass('completed');
    if (ope == 0) {
        $('[id="A_OP_STEP0"]').attr('disabled', false);
    }
    if (ope == 1) {
        $('[id="A_OP_STEP0"]').attr('disabled', true);
        $('[id="A_OP_STEP1"]').attr('disabled', false);
    }
    if (ope == 2) {
        $('[id="A_OP_STEP1"]').attr('disabled', true);
        $('[id="A_OP_STEP2"]').attr('disabled', false);
    }
    if (ope == 3) {
        $('[id="A_OP_STEP2"]').attr('disabled', true);
        $('[id="A_OP_STEP3"]').attr('disabled', false);
    }
    if (ope == 4) {
        $('[id="A_OP_STEP3"]').attr('disabled', true);
        $('[id="A_OP_STEP4"]').attr('disabled', false);
    }
    if (ope == 5) {
        $('[id="A_OP_STEP5"]').attr('disabled', true);
    }
    if (ope > 0) {
        $('[id="A_OP_STEP0"]').addClass('completed');
    }
    if (ope > 1) {
        $('[id="A_OP_STEP1"]').addClass('completed');
    }
    if (ope > 2) {
        $('[id="A_OP_STEP2"]').addClass('completed');
    }
    if (ope > 3) {
        $('[id="A_OP_STEP3"]').addClass('completed');
    }
    if (ope > 4) {
        $('[id="A_OP_STEP4"]').addClass('completed');
    }
}

function get_unit(point) {
    if (point in units) {
        return " " + units[point];
    } else {
        return "";
    }
}

function update_points(points){
    $.each(points,
        function(i, point) {
            if (point["value"] != "" && point["value"] != undefined) {
                var unit = get_unit(point["name"]);
                var to_write = point["value"] + unit;
                $('[id="' + point["name"] + '"]').val(to_write);
                if (to_write == "ON") {
                    $('[id="' + point["name"] + '"]').removeClass("info_off");
                    $('[id="' + point["name"] + '"]').addClass("info_on");
                } else if ((to_write == "OFF")) {
                    $('[id="' + point["name"] + '"]').removeClass("info_on");
                    $('[id="' + point["name"] + '"]').addClass("info_off");
                }
            }
        }
    );
    if (points[0]["name"] == "STA_OPERATIONAL") {
        update_operational(parseInt(points[0]["value"]));
    }
    // Managing attenuator special blockings
    if ($('[id="ATT_LAST"]').val() == "STEP") {
        $('[id="ATT_DB"]').val("");
        $('[id="ATT_DB"]').prop("readonly", true);
        $('[id="ATT_PERCENT"]').val("");
        $('[id="ATT_PERCENT"]').prop("readonly", true);
        $('[id="N_AT_DB"]').prop('disabled', true);
        $('[id="N_AT_PERCENT"]').prop('disabled', true);
        $('[id="X_ATT_SET_DB"]').prop('disabled', true);
        $('[id="X_ATT_SET_PERCENT"]').prop('disabled', true);
    } else if ($('[id="ATT_LAST"]').val() == "DB") {
        $('[id="ATT_DB"]').prop("readonly", false);
        $('[id="ATT_PERCENT"]').prop("readonly", false);
        $('[id="N_AT_DB"]').prop('disabled', false);
        $('[id="N_AT_PERCENT"]').prop('disabled', false);
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
    data = data.split("\n");
    response = JSON.parse(data[data.length - 1]);
    update_points(response["values"]);
    update_temperatures(response["temperatures"]);
}

function laserServer(req_cmd, req_val, req_tim, callback) {
    req = "{";
    req = req + '"values":["STA_OPERATIONAL"'
    if (req_val != "" && req_val != undefined) {
        req = req + ',' + req_val;
    }
    req = req + ']';
    if (req_cmd != "" && req_cmd != undefined) {
        req = req + ',"commands":[' + req_cmd + ']';
    }
    if (req_tim != 0 && req_tim != undefined) {
        req = req + ',"temps":' + req_tim;
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

/////////////////////////////////////////////////////////////////////////////////
// Form management //////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////


function tmp_push(object) {
    tmp_value = object.value;
    object.value = "";
}

function tmp_pop(object) {
    if (object.value == "") {
        object.value = tmp_value;
    } else {
        object.value = object.value + tmp_value.substr(tmp_value.lastIndexOf(" "));
        if (!value_hook(object.id)) {
            object.value = tmp_value;
        }
    }
    if (object.id == "ATT_DB") {
        if (object.value == "") {
            $('[id="ATT_DB"]').prop('readonly', true);
        } else {
            if (tmp_value == "") {
                object.value = object.value + " [dB]";
            }
            $('[id="ATT_DB"]').prop('readonly', false);
            $('[id="ATT_PERCENT"]').prop('readonly', false);
            $('[id="X_ATT_SET_DB"]').prop('disabled', false);
            $('[id="X_ATT_SET_PERCENT"]').prop('disabled', false);
        }
    } else if (object.id == "ATT_PERCENT") {
        if (object.value == "") {
            $('[id="ATT_PERCENT"]').prop('readonly', true);
        } else {
            if (tmp_value == "") {
                object.value = object.value;
            }
            $('[id="ATT_DB"]').prop('readonly', false);
            $('[id="ATT_PERCENT"]').prop('readonly', false);
            $('[id="X_ATT_SET_DB"]').prop('disabled', false);
            $('[id="X_ATT_SET_PERCENT"]').prop('disabled', false);
        }
    }
}

function value_hook(id) {
    switch (id) {
        case "GEN_C1_DUTY":
            freq = parseFloat($("#GEN_C1_FRQ").val());
            duty = parseFloat($("#GEN_C1_DUTY").val());
            $("#GEN_C1_WIDTH").val(duty/freq/100 + " [S]");
            break;
        case "GEN_C1_FRQ":
            freq = parseFloat($("#GEN_C1_FRQ").val());
            duty = parseFloat($("#GEN_C1_DUTY").val());
            $("#GEN_C1_WIDTH").val(duty/freq/100 + " [S]");
            break;
        case "GEN_C1_WIDTH":
            freq = parseFloat($("#GEN_C1_FRQ").val());
            width = parseFloat($("#GEN_C1_WIDTH").val());
            duty = 100*width/freq;
            if (duty>=100) {
                alert("Error: The calculated duty cycle is greater or equal to 100%. Returnint to original pulse width value")
                return false;
            }
            $("#GEN_C1_DUTY").val(100*width/freq + " [S]");
            break;
        case "GEN_C2_DUTY":
            freq = parseFloat($("#GEN_C2_FRQ").val());
            duty = parseFloat($("#GEN_C2_DUTY").val());
            $("#GEN_C2_WIDTH").val(duty/freq/100 + " [S]");
            break;
        case "GEN_C2_FRQ":
            freq = parseFloat($("#GEN_C2_FRQ").val());
            duty = parseFloat($("#GEN_C2_DUTY").val());
            $("#GEN_C2_WIDTH").val(duty/freq/100 + " [S]");
            break;
        case "GEN_C2_WIDTH":
            freq = parseFloat($("#GEN_C2_FRQ").val());
            width = parseFloat($("#GEN_C2_WIDTH").val());
            duty = 100*width/freq;
            if (duty >= 100) {
                alert("Error: The calculated duty cycle is greater or equal to 100%. Returnint to original pulse width value")
                return false;
            }
            $("#GEN_C2_DUTY").val(100*width/freq + " [%]");
            break;
        case "ATT_DB":
            db = parseFloat($("#ATT_DB").val());
            if (db < 0 || db > 40) {
                alert("Error: Attenuation out of range [0, 40]")
                return false
            }
            db = Math.round(db*100)/100
            $("#ATT_PERCENT").val(Math.round(Math.exp(-db/4.3425121307373)*100*10000)/10000 + " [%]")
            break;
        case "ATT_PERCENT":
            percent = parseFloat($("#ATT_PERCENT").val());
            if (percent < 0.01 || percent > 100) {
                alert("Error: Transference out of range [100, 0.01]")
                return false
            }
            percent = Math.round(percent*10000)/10000
            $("#ATT_DB").val(-Math.round(Math.log(percent/100)*4.3425121307373*100)/100 + " [dB]")
            // Back to DB
            db = Math.round(parseFloat($("#ATT_DB").val())*100)/100
            $("#ATT_PERCENT").val(Math.round(Math.exp(-db/4.3425121307373)*100*10000)/10000 + " [%]")
            // Back to %
            //percent = Math.round(percent*10000)/10000
            $("#ATT_DB").val(-Math.round(Math.log(parseFloat($("#ATT_PERCENT").val())/100)*4.3425121307373*100)/100 + " [dB]")
            break;
        default:
            break;
    }
    return true
}

var channel_defaults = {
    1: {
        GEN_C1_AMP:   "5 [V]",
        GEN_C1_OFST:  "2.5 [V]",
        GEN_C1_FRQ:   "1000 [Hz]",
        GEN_C1_DUTY:  "10 [%]",
        GEN_C1_WIDTH: "0.0001 [S]",
        GEN_C1_RISE:  "1.68e-08 [S]",
        GEN_C1_FALL:  "1.68e-08 [S]",
        GEN_C1_OUT:   "" 
    },
    2: {
        GEN_C2_AMP:   "5 [V]",
        GEN_C2_OFST:  "2.5 [V]",
        GEN_C2_FRQ:   "1000 [Hz]",
        GEN_C2_DUTY:  "10 [%]",
        GEN_C2_WIDTH: "0.0001 [S]",
        GEN_C2_RISE:  "1.68e-08 [S]",
        GEN_C2_FALL:  "1.68e-08 [S]",
        GEN_C2_OUT:   "" 
    }
};

function load_default(channel) {
    values = channel_defaults[channel];
    for (var key in values) {
        $('#' + key).val(values[key]);
    }
}

function set_parameters(channel) {
    values = channel_defaults[channel];
    to_refresh = "";
    commands = "";
    for (var key in values) {
        value = $('#' + key).val();
        value = value.substr(0, value.lastIndexOf(" "));
        if (to_refresh != "") {
            to_refresh = to_refresh + ",";
            commands = commands + ",";
        }
        to_refresh = to_refresh + "\"" + key + '"';
        commands = commands + "\"GEN C" + channel + ":BSWV " + key.substr(key.lastIndexOf("_")+1) + "," + value + "\"";
    }
    commands = commands + ',"GEN C' + channel + ':BSWV?"';
    commands = commands + ',"GEN C' + channel + ':OUTP?"';
    input_disable(to_refresh);
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh);
        }
    );
}

function set_db() {
    if ($('#ATT_DB').val() == "" || $('#ATT_DB').val() != undefined) {
        alert("Error: Empty dB value");
        return;
    }
    value = parseFloat($('#ATT_DB').val());
    commands = '"ATT A' + Math.round(value*100)/100 + '","ATT D"';
    to_refresh = '"ATT_LAST","ATT_DB","ATT_PERCENT","ATT_POS"'
    input_disable(to_refresh);
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh);
        }
    );
}

function set_pos() {
    value = parseInt($('#ATT_POS').val());
    commands = '"ATT S' + value + '","ATT D"';
    to_refresh = '"ATT_LAST","ATT_POS"'
    input_disable(to_refresh);
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh);
        }
    );
    $('#ATT_DB').val("");
    $('#ATT_PERCENT').val("");
    $('#ATT_DB').prop('readonly', true);
    $('#ATT_PERCENT').prop('readonly', true);
}

function step_db(step) {
    value = parseFloat($('#ATT_DB').val());
    step = parseFloat(step);
    newvalue = parseFloat(Math.round((value + step)+'e2')+'e-2');
    if (newvalue < 0 || newvalue > 40) {
        alert("Error: Applied step gets the dB out of range [0, 40]");
        return;
    }
    commands = '"ATT A' + newvalue + '","ATT D"';

    to_refresh = '"ATT_LAST","ATT_POS","ATT_DB","ATT_PERCENT"'
    input_disable(to_refresh);
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh);
        }
    );
    $('#ATT_DB').prop('readonly', false);
    $('#ATT_PERCENT').prop('readonly', true);
}

function input_disable(values) {
    if (values == "" || values == undefined) {
        return;
    }
    values = values.split(",");
    var arrayLength = values.length;
    for (var i = 0; i < arrayLength; i++) {
        $('[id=' + values[i] + ']').prop('disabled', true);
    }
}

function input_enable(values) {
    if (values == "" || values == undefined) {
        return;
    }
    values = values.split(",");
    var arrayLength = values.length;
    for (var i = 0; i < arrayLength; i++) {
        $('[id=' + values[i] + ']').prop('disabled', false);
    }
}

function input_readable(values) {
    if (values == "" || values == undefined) {
        return;
    }
    values = values.split(",");
    var arrayLength = values.length;
    for (var i = 0; i < arrayLength; i++) {
        if ($('[id=' + values[i] + ']').val() != "") {
            $('[id=' + values[i] + ']').prop('readonly', false);
        }
    }
}

/////////////////////////////////////////////////////////////////////////////////
// Running on load //////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

$(document).ready(
    function() {
        // Start listening to action buttons
        $('[id^="A_"]').click(
            function() {
                commands = $(this).attr("req_cmd");
                to_refresh = $(this).attr("req_val");
                input_disable(to_refresh);
                laserServer(commands, to_refresh, 0,
                    function() {
                        input_enable(to_refresh);
                        input_readable(to_refresh);
                    }
                );
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
                $('#canvas_diagram').find('[id="H_diagram"]').focus();
            }
        )
        $('[id="H_plot"').click(
            function() {
                $('#canvas_diagram').hide();
                $('#canvas_plot').show();
                $('#canvas_plot').find('[id="H_plot"]').focus();
            }
        )
        $('[id="H_C1"]').click(
            function() {
                $('#generator_ch2').hide();
                $('#generator_ch1').show();
                $('#generator_ch1').find('[id="H_C1"]').focus();
            }
        )
        $('[id="H_C2"]').click(
            function() {
                $('#generator_ch1').hide();
                $('#generator_ch2').show();
                $('#generator_ch2').find('[id="H_C2"]').focus();
            }
        )
        $('input, select').keypress(
            function(key) {
                if (key.which == 13) {
                    $(this).blur();
                }
            }
        );
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
        $('[id="ATT_DB"], [id="ATT_PERCENT"]').focus(
            function() {
                if ($(this).val() == "") {
                    $(this).prop('readonly', false);
                }
            }
        );
        $('[id^="N_AT_DB"]').click(
            function() {
                step_db($(this).attr('step'))
            }
        );
        $('[id^="N_AT_PERCENT"]').click(
            function() {
                step_percent($(this).attr('step'))
            }
        );
        $('[id^="N_AT_STEP"]').click(
            function() {
                step_step($(this).attr('step'))
            }
        );
        // Load default values and remove loading page
        laserServer(startup_commands, startup_values, temps_seconds_default,
            function() {
                draw_plot();
                input_enable(startup_values)
                $("#H_advanced").fadeIn(500);
                $("#panel_loading").fadeOut(500);
            }
        );
    }
);
