<html>
    <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="main.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="chart.js"></script>
        <script src="main_constants.js"></script>
        <script src="main.js"></script>
    </head>
    <body>
        <div id="container">
            <div id="panel">
                <div id="panel_loading">
                    <div id="loading_table">
                        <div id="loading_table_cell">
                            <p>
                                <img src="images/henlab.png" width="210px">
                            </p>
                            <p>
                                <img src="images/progress.svg">
                            </p>
                        </div>
                    </div>
                </div>
                <div id="panel_operation">
                    <div id="canvas" class="panel_section">
                        <div class="title"><div class="title_table"><div class="title_table_cell">
                            System diagram and Temperature plots
                        </div></div></div>
                        <div class="content">
                            <div id="canvas_diagram">
                                <div id="diagram_drawing">
                                </div>
                                <div class="canvas_menu">
                                    <button id="H_diagram" class="canvas_menu canvas_single">D</button><!---
                                ---><button id="H_plot" class="canvas_menu canvas_single">T</button>
                                </div>
                            </div>
                            <div id="canvas_plot">
                                <div id="plot_drawing">
                                    <canvas id="temp_1"></canvas>
                                </div>
                                <div class="canvas_menu">
                                    <button id="T_1" req_tim="1" class="canvas_menu button_init">1H</button><!---
                                ---><button id="T_6" req_tim="6" class="canvas_menu">6H</button><!---
                                ---><button id="T_12" req_tim="12" class="canvas_menu">12H</button><!---
                                ---><button id="T_24" req_tim="24" class="canvas_menu">24H</button><!---
                                ---><button id="T_48" req_tim="48" class="canvas_menu">48H</button><!---
                                ---><button id="T_96" req_tim="96" class="canvas_menu">96H</button><!---
                                ---><button id="H_diagram" req_tim="1" class="canvas_menu canvas_single">D</button><!---
                                ---><button id="H_plot" req_tim="1" class="canvas_menu canvas_single">T</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="operationalcontrol" class="panel_section">
                        <div class="title"><div class="title_table"><div class="title_table_cell">
                            Operational Control
                        </div></div></div>
                        <div class="content">
                            <ul class="device">
                                <!--<li>
                                    <span>Operational Stage</span>
                                <input disabled id="STA_OPERATIONAL" readonly>
                                </li>-->
                                <li class="title">
                                    1. Reset system to get a known default status
                                </li>
                                <li>
                                    <button id="A_OP_STEP0" req_cmd='"STA OPE 1"' class="full">Set Default</button>
                                    <!---<button id="A_OP_STEP1" req_cmd='"LAS SSSD 0","GEN C1:OUTP OFF","GEN C1:OUTP OFF","ATT A0","GEN C1:BSWV AMP,5","GEN C1:BSWV OFST,2.5","GEN C1:BSWV FRQ,1000","GEN C1:BSWV DUTY,10","GEN C1:BSWV WIDTH,0.0001","GEN C1:BSWV RISE,1.68e-08","GEN C1:BSWV FALL,1.68e-08","GEN C1:BSWV OUT,","GEN C1:BSWV?","GEN C1:OUTP?"' req_ope="1">Set Default</button>--->
                                </li>
                                <li class="title">
                                    2. Enable Laser pulsing
                                </li>
                                <li>
                                    <button id="A_OP_STEP1" class="full" req_cmd='"STA OPE 2"'>Enable Pulsing</button>
                                </li>
                                <li class="title">
                                    3. Set frequency, attenuation and pulse width
                                </li>
                                <li>
                                    <input disabled id="otherfreq" class="info dual_left" readonly><!---
                                ---><button id="A_OP_STEP2">Set Frequency</button>
                                </li>
                                <li>
                                    <input disabled id="otherfreq" readonly><!---
                                ---><button id="A_OP_STEP2">Set Attenuation</button>
                                </li>
                                <li>
                                    <input disabled id="otherfreq" readonly><!---
                                ---><button id="A_OP_STEP2">Set Pulse Width</button>
                                </li>
                                <li class="title">
                                    4. Turn on the signal generator to start pulsing
                                </li>
                                <li>
                                    <button id="A_OP_STEP2" req_cmd='"STA OPE 3"' class="full">Turn ON Signal Generator</button>
                                </li>
                                <li class="title">
                                    5. Adjust the pulsing frequency and attenuation
                                </li>
                                <li>
                                    <input disabled id="otherfreq" readonly><!---
                                ---><button id="A_OP_STEP3" >Adjust Frequency</button>
                                </li>
                                <li>
                                    <input disabled id="otherfreq" readonly><!---
                                ---><button id="A_OP_STEP3">Adjust Attenuation</button>
                                </li>
                                <li class="title">
                                    6. Turn off Signal Generator to stop pulsing
                                </li>
                                <li>
                                    <button id="A_OP_STEP3" req_cmd='"STA OPE 4"' class="full">Turn OFF Signal Generator</button>
                                </li>
                                <li class="title">
                                    7. Disable Laser pulsing
                                </li>
                                <li>
                                    <button id="A_OP_STEP4" req_cmd='"STA OPE 5"' class="full">Disable Laser Pulsing</button>
                                </li>
                                <li>
                                    <button id="A_OP_0STEP" class="align-bottom" req_cmd='"STA OPE 0"'>Restart Operation</button><!---
                                ---><button id="MA_OP_STEP" class="align-bottom"></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="queue" class="panel_section">
                        <div class="title"><div class="title_table"><div class="title_table_cell">
                            Queue
                        </div></div></div>
                        <div class="content">
                            <div id="script_container">
                                <table id="script" cellpadding="0px" cellspacing="2px">
                                    <tr class="script_row">
                                        <td class="number">000</td>
                                        <td class="time">00:00</td>
                                        <td class="device">LAS</td>
                                        <td class="action">Start pulsing</td>
                                        <td class="icon">E</td>
                                        <td class="icon">D</td>
                                    </tr>
                                    <tr class="script_row">
                                        <td class="number">001</td>
                                        <td class="time">00:00</td>
                                        <td class="device">ATT</td>
                                        <td class="action">Set to 20</td>
                                        <td class="icon">E</td>
                                        <td class="icon">D</td>
                                    </tr>
                                    <tr class="script_row">
                                        <td class="number">002</td>
                                        <td class="time">00:00</td>
                                        <td class="device">GEN</td>
                                        <td class="action">Start signal ch1</td>
                                        <td class="icon">E</td>
                                        <td class="icon">D</td>
                                    </tr>
                                </table>
                            </div>
                            <ul class="device">
                                <li>
                                    <button id="A_AT_SET_DB">Load Script</button><!---
                                ---><button id="A_AT_SET_DB">Add Action</button>
                                </li>
                                <li>
                                    <button id="A_AT_DB_M01" class="align-top">Download</button><!---
                                ---><button id="A_AT_DB_M01" class="align-top quarter">Enable</button><!---
                                ---><button id="A_AT_DB_P01" class="align-top quarter">Disable</button>
                                </li>
                        </div>
                    </div>
                </div>
                <div id="panel_advanced">
                    <div id="laser" class="panel_section">
                        <div class="title"><div class="title_table"><div class="title_table_cell">
                            Laser
                        </div></div></div>
                        <div class="content">
                            <div id="laser_left">
                                <ul class="device">
                                    <li class="title">
                                        Times
                                    </li>
                                    <li>
                                        <span>Supply Time</span><!---
                                    ---><input disabled id="LAS_GEMT_SUPPLY"  readonly>
                                    </li>
                                    <li>
                                        <span>Emitting</span><!---
                                    ---><input disabled id="LAS_GEMT_EMITING" readonly>
                                    </li>
                                    <li class="title">
                                        Temperatures
                                    </li>
                                    <li>
                                        <span>Diode</span><!---
                                    ---><input disabled id="LAS_GMTE_DIODE"  readonly>
                                    </li>
                                    <li>
                                        <span>Crystal</span><!---
                                    ---><input disabled id="LAS_GMTE_CRYSTAL" readonly>
                                    </li>
                                    <li>
                                        <span>Electronic Sink</span><!---
                                    ---><input disabled id="LAS_GMTE_ELECTRONICSINK" readonly>
                                    </li>
                                    <li>
                                        <span>Heat Sink</span><!---
                                    ---><input disabled id="LAS_GMTE_HEATSINK" readonly>
                                    </li>
                                    <li class="title">
                                        Temperature Control
                                    </li>
                                    <li>
                                        <span>Control TEC 1</span><!---
                                    ---><input disabled id="LAS_TEC1"  readonly>
                                    </li>
                                    <li>
                                        <span>Control TEC 2</span><!---
                                    ---><input disabled id="LAS_TEC2" readonly>
                                    </li>
                                    <li class="title">
                                        Errors and Informations
                                    </li>
                                    <li>
                                        <span>Error 1</span><!---
                                    ---><input disabled id="LAS_GSER_ERROR1" readonly>
                                    </li>
                                    <li>
                                        <span>Error 2</span><!---
                                    ---><input disabled id="LAS_GSER_ERROR2" readonly>
                                    </li>
                                    <li>
                                        <span>Error 2</span><!---
                                    ---><input disabled id="LAS_GSER_ERROR3" readonly>
                                    </li>
                                    <li>
                                        <span>Info 1</span><!---
                                    ---><input disabled id="LAS_GSER_INFO1" readonly>
                                    </li>
                                    <li>
                                        <span>Info 2</span><!---
                                    ---><input disabled id="LAS_GSER_INFO2" readonly>
                                    </li>
                                    <li>
                                        <span>Info 3</span><!---
                                    ---><input disabled id="LAS_GSER_INFO3" readonly>
                                    </li>
                                </ul>
                            </div>
                            <div id="laser_right">
                                <ul class="device">
                                    <li class="title">
                                        Pulsing
                                    </li>
                                    <li>
                                        <span>Status</span><!---
                                    ---><input disabled id="LAS_D" readonly>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_LAS_SSSD_1" class="align-top" req_cmd='"LAS SSSD 1","LAS GEMT", "LAS GMTE", "LAS GTCO", "LAS GSER"' req_val='"LAS_GEMT_SUPPLY", "LAS_GEMT_EMITING", "LAS_GMTE_DIODE", "LAS_GMTE_CRYSTAL", "LAS_GMTE_ELECTRONICSINK", "LAS_GMTE_HEATSINK", "LAS_TEC1", "LAS_TEC2", "LAS_GSER_ERROR1", "LAS_GSER_ERROR2", "LAS_GSER_ERROR3", "LAS_GSER_INFO1", "LAS_GSER_INFO2", "LAS_GSER_INFO3", "LAS_D"'>Turn ON</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_LAS_SSSD_0" class="align-top" req_cmd='"LAS SSSD 0","LAS GEMT", "LAS GMTE", "LAS GTCO", "LAS GSER"' req_val='"LAS_GEMT_SUPPLY", "LAS_GEMT_EMITING", "LAS_GMTE_DIODE", "LAS_GMTE_CRYSTAL", "LAS_GMTE_ELECTRONICSINK", "LAS_GMTE_HEATSINK", "LAS_TEC1", "LAS_TEC2", "LAS_GSER_ERROR1", "LAS_GSER_ERROR2", "LAS_GSER_ERROR3", "LAS_GSER_INFO1", "LAS_GSER_INFO2", "LAS_GSER_INFO3", "LAS_D"'>Turn OFF</button>
                                    </li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_LAS_UPDATE" class="align-top" req_cmd='"LAS GEMT", "LAS GMTE", "LAS GTCO", "LAS GSER"' req_val='"LAS_GEMT_SUPPLY", "LAS_GEMT_EMITING", "LAS_GMTE_DIODE", "LAS_GMTE_CRYSTAL", "LAS_GMTE_ELECTRONICSINK", "LAS_GMTE_HEATSINK", "LAS_TEC1", "LAS_TEC2", "LAS_GSER_ERROR1", "LAS_GSER_ERROR2", "LAS_GSER_ERROR3", "LAS_GSER_INFO1", "LAS_GSER_INFO2", "LAS_GSER_INFO3", "LAS_D"'>Update</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="generator" class="panel_section">
                        <div class="title"><div class="title_table"><div class="title_table_cell">
                            Generator
                        </div></div></div>
                        <div class="content">
                            <div id="generator_ch1">
                                <ul class="device">
                                    <li class="title">
                                        Select Channel
                                    </li>
                                    <li>
                                        <button id="H_C1" class="highlight">Channel 1</button><!---
                                    ---><button id="H_C2">Channel 2</button>
                                    </li>
                                    <li class="title">
                                        Channel 1
                                    </li>
                                    <li>
                                        <span>Wave</span><!---
                                    ---><select id="GEN_C1_WVTP">
                                            <option>PULSE</option>
                                        </select>
                                    </li>
                                    <li>
                                        <span>Amplitude</span><!---
                                    ---><input disabled id="GEN_C1_AMP" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Offset</span><!---
                                    ---><input disabled id="GEN_C1_OFST" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Frequency</span><!---
                                    ---><input disabled id="GEN_C1_FRQ" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Duty</span><!---
                                    ---><input disabled id="GEN_C1_DUTY" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Pulse Width</span><!---
                                    ---><input disabled id="GEN_C1_WIDTH" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Rise</span><!---
                                    ---><input disabled id="GEN_C1_RISE" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Fall</span><!---
                                    ---><input disabled id="GEN_C1_FALL" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Output</span><!---
                                    ---><input disabled id="GEN_C1_OUT" readonly>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_C1_GEN_UPDATE" class="align-top" req_cmd='"GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'>Get Current</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="H_C1_loaddefault" class="align-top" onclick="load_default(1)">Load Default</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="G_C1_setparameters" class="align-top" onclick="set_parameters(1)">Set Parameters</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_C1_ON" class="align-top" req_cmd='"GEN C1:OUTP ON","GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'>Turn ON</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_C1_OFF" class="align-top" req_cmd='"GEN C1:OUTP OFF","GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'>Turn OFF</button>
                                    </li>
                                </ul>
                            </div>
                            <div id="generator_ch2">
                                <ul class="device">
                                    <li class="title">
                                        Select Channel
                                    </li>
                                    <li>
                                        <button id="H_C1">Channel 1</button><!---
                                    ---><button id="H_C2" class="highlight">Channel 2</button>
                                    </li>
                                    <li class="title">
                                        Channel 2
                                    </li>
                                    <li>
                                        <span>Wave</span><!---
                                    ---><select id="GEN_C2_WVTP">
                                            <option>PULSE</option>
                                        </select>
                                    </li>
                                    <li>
                                        <span>Amplitude</span><!---
                                    ---><input disabled id="GEN_C2_AMP"  onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Offset</span><!---
                                    ---><input disabled id="GEN_C2_OFST"  onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Frequency</span><!---
                                    ---><input disabled id="GEN_C2_FRQ"  onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Duty</span><!---
                                    ---><input disabled id="GEN_C2_DUTY"  onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Pulse Width</span><!---
                                    ---><input disabled id="GEN_C2_WIDTH"  onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Rise</span><!---
                                    ---><input disabled id="GEN_C2_RISE"  onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Fall</span><!---
                                    ---><input disabled id="GEN_C2_FALL"  onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span>Output</span><!---
                                    ---><input disabled id="GEN_C2_OUT" readonly>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_C2_GEN_UPDATE" class="align-top" req_cmd='"GEN C2:OUTP?","GEN C2:BSWV?"' req_val='"GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT"'>Get Current</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="H_C2_loaddefault" class="align-top" onclick="load_default(2)">Load Default</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="G_C2_setparameters" class="align-top" onclick="set_parameters(2)">Set Parameters</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_C2_ON" class="align-top" req_cmd='"GEN C2:OUTP ON","GEN C2:OUTP?","GEN C2:BSWV?"' req_val='"GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT"'>Turn ON</button>
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_C2_OFF" class="align-top" req_cmd='"GEN C2:OUTP OFF","GEN C2:OUTP?","GEN C2:BSWV?"' req_val='"GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT"'>Turn OFF</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="attenuator" class="panel_section">
                        <div class="title"><div class="title_table"><div class="title_table_cell">
                            Attenuator
                        </div></div></div>
                        <div class="content">
                            <ul class="device">
                                <li class="title">
                                    Handling by dB
                                </li>
                                <li>
                                    <span>Current dB</span><!---
                                ---><input disabled id="ATT_DB" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="X_ATT_SET_DB" class="align-top" onclick="set_db()">Set dB</button>
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="N_AT_DB" class="align-top quarter" step='-0.1'>-0.1[dB]</button><!---
                                ---><button id="N_AT_DB" class="align-top quarter" step='+0.1'>+0.1[dB]</button>
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="N_AT_DB" class="align-top quarter" step='-1'>-1[dB]</button><!---
                                ---><button id="N_AT_DB" class="align-top quarter" step='+1'>+1[dB]</button>
                                </li>
                                <li class="title">
                                    Handling by Transference (OUT/IN)%
                                </li>
                                <li>
                                    <span>Current Transference %</span><!---
                                ---><input disabled id="ATT_PERCENT"   onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="X_ATT_SET_PERCENT" class="align-top" onclick="set_db()">Set %</button>
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="N_AT_PERCENT" class="align-top quarter" step="-1">-1[%]</button><!---
                                ---><button id="N_AT_PERCENT" class="align-top quarter" step="+1">+1[%]</button>
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="N_AT_PERCENT" class="align-top quarter" step="-10">-10[%]</button><!---
                                ---><button id="N_AT_PERCENT" class="align-top quarter" step="+10">+10[%]</button>
                                </li>
                                <li class="title">
                                    Handling by Step
                                </li>
                                <li>
                                    <span>Current Position Step</span><!---
                                ---><input disabled id="ATT_POS"  onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="N_AT_SET_STEP" class="align-top" onclick="set_pos()">Set Position</button>
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="N_AT_STEP" class="align-top quarter" step="-1">-1</button><!---
                                ---><button id="N_AT_STEP" class="align-top quarter" step="+1">+1</button>
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="N_AT_STEP" class="align-top quarter" step="-10">-10</button><!---
                                ---><button id="N_AT_STEP" class="align-top quarter" step="+10">+10</button>
                                </li>
                                <li></li>
                                <li>
                                    <span>Last Command</span><!---
                                ---><input disabled id="ATT_LAST"  readonly>
                                </li>
                                <li>
                                    <span class="align-bottom"></span><!---
                                ---><button id="A_AT_UPDATE" class="align-top" req_cmd='"ATT D"' req_val='"ATT_LAST","ATT_DB","ATT_POS","ATT_PERCENT"'>Update</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="underbar">
                <button id="H_advanced" class="advanced">Advanced Options</button>
            </div>
        </div>
    </body>
</html>