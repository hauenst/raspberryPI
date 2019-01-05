
/////////////////////////////////////////////////////////////////////////////////
// Information management functions /////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

function update_operational(ope) {
    operational = ope;
    $('[id="STA_OPERATIONAL"]').text((ope > 4)?"Done":(ope == 0)?"Ready":(ope < 0)?"Error":ope);
    $('[id^="A_OP_STEP"]').attr('disabled', true);
    $('[id^="O_OP_STEP"]').attr('disabled', true);
    $('[id^="F_OP_STEP"]').attr('disabled', true);
    $('[id^="F_OP_STEP"]').attr('readonly', true);
    $('[id^="A_OP_STEP"]').removeClass('completed');
    $('[id^="O_OP_STEP"]').removeClass('completed');
    $('[id^="F_OP_STEP"]').removeClass('completed');
    $('[id^="F_OP_STEP"]').addClass('uncompleted');
    if (ope == 0) {
        $('[id^="A_OP_STEP0"]').attr('disabled', false);
        $('[id^="F_OP_STEP2"]').val("");
        $('[id^="F_OP_STEP3"]').val("");
    }
    if (ope == 1) {
        $('[id^="A_OP_STEP0"]').attr('disabled', true);
        $('[id^="A_OP_STEP1"]').attr('disabled', false);
    }
    if (ope == 2) {
        $('[id^="A_OP_STEP1"]').attr('disabled', true);
        $('[id^="A_OP_STEP2"]').attr('disabled', false);
        $('[id^="O_OP_STEP2"]').attr('disabled', false);
        $('[id^="F_OP_STEP2"]').attr('disabled', false);
        $('[id^="F_OP_STEP2"]').attr('readonly', false);
        $('[id="F_OP_STEP2_FRQ"]').val($('[id="GEN_C1_FRQ"]').val());
        $('[id="F_OP_STEP2_ATT"]').val($('[id="ATT_DB"]').val());
        $('[id="F_OP_STEP2_PWD"]').val($('[id="GEN_C1_WIDTH"]').val());
    }
    if (ope == 3) {
        $('[id^="A_OP_STEP2"]').attr('disabled', true);
        $('[id^="O_OP_STEP2"]').attr('disabled', true);
        $('[id^="A_OP_STEP3"]').attr('disabled', false);
        $('[id^="O_OP_STEP3"]').attr('disabled', false);
        $('[id^="F_OP_STEP3"]').attr('disabled', false);
        $('[id^="F_OP_STEP3"]').attr('readonly', false);
        $('[id="F_OP_STEP2_FRQ"]').val("");
        $('[id="F_OP_STEP2_ATT"]').val("");
        $('[id="F_OP_STEP2_PWD"]').val("");
        $('[id="F_OP_STEP3_FRQ"]').val($('[id="GEN_C1_FRQ"]').val());
        $('[id="F_OP_STEP3_ATT"]').val($('[id="ATT_DB"]').val());
    }
    if (ope == 4) {
        $('[id^="A_OP_STEP3"]').attr('disabled', true);
        $('[id^="O_OP_STEP3"]').attr('disabled', true);
        $('[id^="A_OP_STEP4"]').attr('disabled', false);
        $('[id="F_OP_STEP3_FRQ"]').val("");
        $('[id="F_OP_STEP3_ATT"]').val("");
    }
    if (ope > 0) {
        $('[id^="A_OP_STEP0"]').addClass('completed');
    }
    if (ope > 1) {
        $('[id^="A_OP_STEP1"]').addClass('completed');
    }
    if (ope > 2) {
        $('[id^="A_OP_STEP2"]').addClass('completed');
        $('[id^="O_OP_STEP2"]').addClass('completed');
        $('[id^="F_OP_STEP2"]').removeClass('uncompleted');
        $('[id^="F_OP_STEP2"]').addClass('completed');
    }
    if (ope > 3) {
        $('[id^="A_OP_STEP3"]').addClass('completed');
        $('[id^="O_OP_STEP3"]').addClass('completed');
        $('[id^="F_OP_STEP3"]').removeClass('uncompleted');
        $('[id^="F_OP_STEP3"]').addClass('completed');
    }
    if (ope > 4) {
        $('[id^="A_OP_STEP4"]').addClass('completed');
    }
    if (ope == 5) {
        $('[id^="A_OP_STEP5"]').attr('disabled', true);
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
                $('[id="' + point["name"] + '"]').text(to_write);
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
        $('[id="X_AT_SET_DB"]').prop('disabled', true);
        $('[id="X_AT_SET_PERCENT"]').prop('disabled', true);
    } else if ($('[id="ATT_LAST"]').val() == "DB") {
        $('[id="ATT_DB"]').prop("readonly", false);
        $('[id="ATT_PERCENT"]').prop("readonly", false);
        $('[id="N_AT_DB"]').prop('disabled', false);
        $('[id="N_AT_PERCENT"]').prop('disabled', false);
    }
    update_diagram();
}

function update_diagram () {
elements = ["DIA_A_B", "DIA_B_C", "DIA_C_D", "DIA_D_E", "DIA_D_F", "DIA_SCRAMBLER", "DIA_SPLITER", "DIA_GENERATOR", "DIA_LASER", "DIA_ATTENUATOR", "DIA_DIODE"];
    // Cleaning previous settings
    elements.forEach(
        function (element) {
            $('[id="' + element +'"]').removeClass("on");
            $('[id="' + element +'"]').removeClass("warning");
            $('[id="' + element +'"]').removeClass("active");
        }
    );
    // Coloring diagram
    laser_on = $('[id="LAS_D"]').val()=="ON"?true:false;
    generator_on = $('[id="GEN_C1_OUT"]').val()=="ON"?true:false;
    if (laser_on && !generator_on) {
        $('[id="DIA_LASER"]').addClass("on");
    } else if (!laser_on && generator_on) {
        $('[id="DIA_GENERATOR"]').addClass("on");
        $('[id="DIA_LASER"]').addClass("warning");
        $('[id="DIA_A_B"]').addClass("warning");
        alert("WARNING: Please check your Laser status, the Laser it's expected ON when the Signal Generator starts emitting signal")
    } else if (laser_on && generator_on) {
        $('[id="DIA_GENERATOR"]').addClass("on");
        $('[id="DIA_LASER"]').addClass("on");
        $('[id="DIA_SCRAMBLER"]').addClass("on");
        $('[id="DIA_SPLITER"]').addClass("on");
        $('[id="DIA_ATTENUATOR"]').addClass("on");
        $('[id="DIA_DIODE"]').addClass("on");
        $('[id="DIA_A_B"]').addClass("active");
        $('[id="DIA_B_C"]').addClass("active");
        $('[id="DIA_C_D"]').addClass("active");
        $('[id="DIA_D_E"]').addClass("active");
        $('[id="DIA_D_F"]').addClass("active");
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
    var ctx = document.getElementById("temperature_plot").getContext('2d');
    temperature_plot = new Chart(ctx, {
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
            $('[id="X_AT_SET_DB"]').prop('disabled', false);
            $('[id="X_AT_SET_PERCENT"]').prop('disabled', false);
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
            $('[id="X_AT_SET_DB"]').prop('disabled', false);
            $('[id="X_AT_SET_PERCENT"]').prop('disabled', false);
        }
    }
}

function value_hook(id) {
    switch (id) {
        case "GEN_C1_DUTY":
            freq = parseFloat($("#GEN_C1_FRQ").val());
            duty = parseFloat($("#GEN_C1_DUTY").val());
            if (duty <= 0 || duty >= 100) {
                alert("Error: The duty should be over 0[%] and under 100[%]")
                return false;
            }
            $("#GEN_C1_WIDTH").val(duty/freq/100 + " [S]");
            break;
        case "GEN_C1_FRQ":
            freq = parseFloat($("#GEN_C1_FRQ").val());
            duty = parseFloat($("#GEN_C1_DUTY").val());
            if (freq <= 0 ) {
                alert("Error: The frequency should be greater than 0[S]")
                return false;
            }
            $("#GEN_C1_WIDTH").val(duty/freq/100 + " [S]");
            break;
        case "GEN_C1_WIDTH":
            freq = parseFloat($("#GEN_C1_FRQ").val());
            width = parseFloat($("#GEN_C1_WIDTH").val());
            duty = width*freq*100;
            if (width <= 0){
                alert("Error: The pulse should be greater than 0[S]")
                return false;
            }
            if (duty>=100) {
                alert("Error: The calculated duty cycle is greater or equal to 100[%]")
                return false;
            }
            $("#GEN_C1_DUTY").val(duty + " [%]");
            break;
        case "GEN_C2_DUTY":
            freq = parseFloat($("#GEN_C2_FRQ").val());
            duty = parseFloat($("#GEN_C2_DUTY").val());
            if (duty <= 0 || duty >= 100) {
                alert("Error: The duty should be over 0[%] and under 100[%]")
                return false;
            }
            $("#GEN_C2_WIDTH").val(duty/freq/100 + " [S]");
            break;
        case "GEN_C2_FRQ":
            freq = parseFloat($("#GEN_C2_FRQ").val());
            duty = parseFloat($("#GEN_C2_DUTY").val());
            if (freq <= 0 ) {
                alert("Error: The frequency should be greater than 0[Hz]")
                return false;
            }
            $("#GEN_C2_WIDTH").val(duty/freq/100 + " [S]");
            break;
        case "GEN_C2_WIDTH":
            freq = parseFloat($("#GEN_C2_FRQ").val());
            width = parseFloat($("#GEN_C2_WIDTH").val());
            duty = width*freq*100;
            if (width <= 0){
                alert("Error: The pulse should be greater than 0[S]")
                return false;
            }
            if (duty>=100) {
                alert("Error: The calculated duty cycle is greater or equal to 100[%]")
                return false;
            }
            $("#GEN_C2_DUTY").val(duty + " [%]");
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
    },
    2: {
        GEN_C2_AMP:   "5 [V]",
        GEN_C2_OFST:  "2.5 [V]",
        GEN_C2_FRQ:   "1000 [Hz]",
        GEN_C2_DUTY:  "10 [%]",
        GEN_C2_WIDTH: "0.0001 [S]",
        GEN_C2_RISE:  "1.68e-08 [S]",
        GEN_C2_FALL:  "1.68e-08 [S]",
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
    input_disable(to_refresh + ',"GEN_C' + channel + '_OUT"');
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh + ',"GEN_C' + channel + '_OUT"');
        }
    );
}

function set_db(value) {
    commands = "";
    if (value == "field") {
        if ($('#ATT_DB').val() == "") {
            alert("Error: Empty dB value");
            return;
        }
        value = $('#ATT_DB').val();
        commands = '"STA OPE 0",'
    }
    commands = commands + '"ATT A' + Math.round(parseFloat(value)*100)/100 + '","ATT D"';
    to_refresh = '"ATT_LAST","ATT_DB","ATT_PERCENT","ATT_POS"'
    input_disable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
        }
    );
}

function set_pos() {
    value = parseInt($('#ATT_POS').val());
    commands = '"STA OPE 0","ATT S' + value + '","ATT D"';
    to_refresh = '"ATT_LAST","ATT_POS"'
    input_disable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh + ',"X_AT_SET_STEP","N_AT_STEP"');
        }
    );
    $('#ATT_DB').val("");
    $('#ATT_PERCENT').val("");
}

function step_db(step) {
    value = parseFloat($('#ATT_DB').val());
    step = parseFloat(step);
    newvalue = parseFloat(Math.round((value + step)+'e2')+'e-2');
    if (newvalue < 0 || newvalue > 40) {
        alert("Error: Applied step gets the dB out of range [0, 40]");
        return;
    }
    commands = '"STA OPE 0","ATT A' + newvalue + '","ATT D"';
    to_refresh = '"ATT_LAST","ATT_POS","ATT_DB","ATT_PERCENT"'
    input_disable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
        }
    );
    $('#ATT_DB').prop('readonly', false);
    $('#ATT_PERCENT').prop('readonly', true);
}

function step_percent(step) {
    value = parseFloat($('#ATT_PERCENT').val());
    step = parseFloat(step);
    newvalue = value + step;
    $('#ATT_PERCENT').val(newvalue);
    if (!value_hook("ATT_PERCENT")){
        $('#ATT_PERCENT').val(value + " [%]");
        return false
    }
    dbvalue = parseFloat($('#ATT_DB').val());
    commands = '"STA OPE 0","ATT A' + dbvalue + '","ATT D"';
    to_refresh = '"ATT_LAST","ATT_POS","ATT_DB","ATT_PERCENT"'
    input_disable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
        }
    );
    $('#ATT_DB').prop('readonly', false);
    $('#ATT_PERCENT').prop('readonly', true);
}

function set_pwd(value) {
    commands = "";
    commands = '"GEN C1:BSWV WIDTH,' + parseFloat(value) + '","GEN C1:OUTP?","GEN C1:BSWV?"';
    to_refresh = '"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'
    input_disable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
        }
    );
}

function step_step(step) {
    value = parseInt($('#ATT_POS').val());
    step = parseInt(step);
    newvalue = value + step;
    commands = '"STA OPE 0","ATT S' + newvalue + '","ATT D"';
    to_refresh = '"ATT_LAST","ATT_POS","ATT_DB","ATT_PERCENT"'
    input_disable(to_refresh + ',"X_AT_SET_STEP","X_AT_SET_DB","X_AT_SET_PERCENT","N_AT_STEP","N_AT_PERCENT","N_AT_DB"');
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh + ',"X_AT_SET_STEP","N_AT_STEP"');
        }
    );
}

function set_frq(value) {
    commands = "";
    commands = '"GEN C1:BSWV FRQ,' + parseInt(value) + '","GEN C1:OUTP?","GEN C1:BSWV?"';
    to_refresh = '"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'
    input_disable(to_refresh);
    laserServer(commands, to_refresh, 0,
        function() {
            input_enable(to_refresh);
        }
    );
}

function input_disable(values) {
    if (values == "" || values == undefined) {
        return;
    }
    values = values.split(",");
    var arrayLength = values.length;
    if (operational == 2) {
        $('[id="O_OP_STEP2_PWD"]').prop('disabled', true);
        $('[id="O_OP_STEP2_FRQ"]').prop('disabled', true);
        $('[id="O_OP_STEP2_ATT"]').prop('disabled', true);
    } else if (operational == 3) {
        $('[id="O_OP_STEP3_FRQ"]').prop('disabled', true);
        $('[id="O_OP_STEP3_ATT"]').prop('disabled', true);
    }
    for (var i = 0; i < arrayLength; i++) {
        if(values[i].substr(0,1)=="X") {
            alert(values[i]);
        }
        $('[id=' + values[i] + ']').prop('disabled', true);
    }
}

function input_enable(values) {
    if (values == "" || values == undefined) {
        return;
    }
    values = values.split(",");
    var arrayLength = values.length;
    if (operational == 2) {
        $('[id="O_OP_STEP2_PWD"]').prop('disabled', false);
        $('[id="O_OP_STEP2_FRQ"]').prop('disabled', false);
        $('[id="O_OP_STEP2_ATT"]').prop('disabled', false);
    } else if (operational == 3) {
        $('[id="O_OP_STEP3_FRQ"]').prop('disabled', false);
        $('[id="O_OP_STEP3_ATT"]').prop('disabled', false);
    }        
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
                if ($(this).attr("req_ope") == undefined){
                    if (commands != undefined) {
                        commands = commands + ',';
                    }
                    commands = commands + '"STA OPE 0"';
                } else {
                    if (to_refresh == undefined) {
                        to_refresh = startup_values;
                    }
                }
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
                update_plot_time(tem[erature_plot], $(this).attr("req_tim")*60*60+60);
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
                step_db($(this).attr('step'));
            }
        );
        $('[id^="N_AT_PERCENT"]').click(
            function() {
                step_percent($(this).attr('step'));
            }
        );
        $('[id^="N_AT_STEP"]').click(
            function() {
                step_step($(this).attr('step'));
            }
        );
        $('[id="O_OP_STEP2_PWD"]').click(
            function() {
                set_pwd($('[id="F_OP_STEP2_PWD"]').val())
            }
        )
        $('[id="O_OP_STEP2_FRQ"]').click(
            function() {
                set_frq($('[id="F_OP_STEP2_FRQ"]').val())
            }
        )
        $('[id="O_OP_STEP3_FRQ"]').click(
            function() {
                set_frq($('[id="F_OP_STEP3_FRQ"]').val())
            }
        )
        $('[id="O_OP_STEP2_ATT"]').click(
            function() {
                set_db($('[id="F_OP_STEP2_ATT"]').val())
            }
        )
        $('[id="O_OP_STEP3_ATT"]').click(
            function() {
                set_db($('[id="F_OP_STEP3_ATT"]').val())
            }
        )
        // Load default values and remove loading page
        laserServer(startup_commands, startup_values, temps_seconds_default,
            function() {
                draw_plot();
                input_enable(startup_values);
                $("#H_advanced").fadeIn(500);
                $("#panel_loading").fadeOut(500);
            }
        );
        system_diagram = $('[id="system_diagram"]');
    }
);