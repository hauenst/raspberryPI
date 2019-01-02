<html>
    <head>
        <link rel="stylesheet" href="main.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="chart.js"></script>
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
                                <div class="canvas_menu_diagram">
                                    <button id="H_diagram" req="1" class="canvas_menu button_40">D</button><!---
                                ---><button id="H_plot" req="1" class="canvas_menu button_40">T</button>
                                </div>
                            </div>
                            <div id="canvas_plot">
                                <div id="plot_drawing">
                                    <canvas id="temp_1"></canvas>
                                </div>
                                <div class="canvas_menu_plot">
                                    <button id="T_1" req_tim="1" class="canvas_menu button_init">1H</button><!---
                                ---><button id="T_6" req_tim="6" class="canvas_menu">6H</button><!---
                                ---><button id="T_12" req_tim="12" class="canvas_menu">12H</button><!---
                                ---><button id="T_24" req_tim="24" class="canvas_menu">24H</button><!---
                                ---><button id="T_48" req_tim="48" class="canvas_menu">48H</button><!---
                                ---><button id="T_96" req_tim="96" class="canvas_menu">96H</button><!---
                                ---><button id="H_diagram" req_tim="1" class="canvas_menu button_40">D</button><!---
                                ---><button id="H_plot" req_tim="1" class="canvas_menu button_40 button_end">T</button>
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
                                <li class="device_title">
                                    1. Reset system to get a known default status
                                </li>
                                <li>
                                    <span class="label">Reset system</span><!---
                                ---><button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action">Execute</button>
                                </li>
                                <li class="device_title"></li>
                                <li class="device_title">
                                    2. Enable Laser pulsing
                                </li>
                                <li>
                                    <span class="label">Laser ON</span><!---
                                ---><button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action">Execute</button>
                                </li>
                                <li class="device_title"></li>
                                <li class="device_title">
                                    3. Set pulsing frequency
                                </li>
                                <li>
                                    <span class="label">Frequency</span><!---
                                ---><input id="ATT_PERCENT" class="info" readonly>
                                </li>
                                <li>
                                    <span class="label">Set frequency</span><!---
                                ---><button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action">Execute</button>
                                </li>
                                <li class="device_title"></li>
                                <li class="device_title">
                                    4. Turn on the signal generator to start pulsing
                                </li>
                                <li>
                                    <span class="label">Start pulsing</span><!---
                                ---><button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action">Execute</button>
                                </li>
                                <li class="device_title"></li>
                                <li class="device_title">
                                    5. Turn off Signal Generator to stop pulsing
                                </li>
                                <li>
                                    <span class="label">Stop pulsing</span><!---
                                ---><button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action">Execute</button>
                                </li>
                                <li class="device_title"></li>
                                <li class="device_title">
                                    6. Disable Laser pulsing
                                </li>
                                <li>
                                    <span class="label">Laser OFF</span><!---
                                ---><button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action">Execute</button>
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
                                        <td class="icon">D</td>
                                    </tr>
                                    <tr class="script_row">
                                        <td class="number">001</td>
                                        <td class="time">00:00</td>
                                        <td class="device">ATT</td>
                                        <td class="action">Set to 20</td>
                                        <td class="icon">D</td>
                                    </tr>
                                    <tr class="script_row">
                                        <td class="number">002</td>
                                        <td class="time">00:00</td>
                                        <td class="device">GEN</td>
                                        <td class="action">Start signal ch1</td>
                                        <td class="icon">D</td>
                                    </tr>
                                </table>
                            </div>
                            <ul class="device">
                                <li>
                                    <button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action dual_left">Load Script</button><!---
                                ---><button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action dual_right">Add Action</button>
                                </li>
                                <li>
                                    <span id="script_status_reporting" class="label">Status: <span id="script_status">Disabled</span></span><!---
                                ---><button id="A_AT_DB_M01" req_cmd='' req_val='' class="device_action attenuator_action">Enable</button><!---
                                ---><button id="A_AT_DB_P01" req_cmd='' req_val='' class="device_action attenuator_action">Disable</button>
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
                                    <li class="device_title">
                                        Times
                                    </li>
                                    <li>
                                        <span class="label">Supply Time</span><!---
                                    ---><input id="LAS_GEMT_SUPPLY"  class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Emitting</span><!---
                                    ---><input id="LAS_GEMT_EMITING" class="info" readonly>
                                    </li>
                                    <li class="device_title">
                                        Temperatures
                                    </li>
                                    <li>
                                        <span class="label">Diode</span><!---
                                    ---><input id="LAS_GMTE_DIODE"  class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Crystal</span><!---
                                    ---><input id="LAS_GMTE_CRYSTAL" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Electronic Sink</span><!---
                                    ---><input id="LAS_GMTE_ELECTRONICSINK" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Heat Sink</span><!---
                                    ---><input id="LAS_GMTE_HEATSINK" class="info" readonly>
                                    </li>
                                    <li class="device_title">
                                        Temperature Control
                                    </li>
                                    <li>
                                        <span class="label">Control TEC 1</span><!---
                                    ---><input id="LAS_TEC1"  class="info center" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Control TEC 2</span><!---
                                    ---><input id="LAS_TEC2" class="info center" readonly>
                                    </li>
                                    <li class="device_title">
                                        Errors and Informations
                                    </li>
                                    <li>
                                        <span class="label">Error 1</span><!---
                                    ---><input id="LAS_GSER_ERROR1" class="info center" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Error 2</span><!---
                                    ---><input id="LAS_GSER_ERROR2" class="info center" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Error 2</span><!---
                                    ---><input id="LAS_GSER_ERROR3" class="info center" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Info 1</span><!---
                                    ---><input id="LAS_GSER_INFO1" class="info center" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Info 2</span><!---
                                    ---><input id="LAS_GSER_INFO2" class="info center" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Info 3</span><!---
                                    ---><input id="LAS_GSER_INFO3" class="info center" readonly>
                                    </li>
                                </ul>
                            </div>
                            <div id="laser_right">
                                <ul class="device">
                                    <li class="device_title">
                                        Pulsing
                                    </li>
                                    <li>
                                        <span class="label">Status</span><!---
                                    ---><input id="LAS_D" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_LAS_SSSD_1" req_cmd='"LAS SSSD 1","LAS GEMT", "LAS GMTE", "LAS GTCO", "LAS GSER"' req_val='"LAS_GEMT_SUPPLY", "LAS_GEMT_EMITING", "LAS_GMTE_DIODE", "LAS_GMTE_CRYSTAL", "LAS_GMTE_ELECTRONICSINK", "LAS_GMTE_HEATSINK", "LAS_TEC1", "LAS_TEC2", "LAS_GSER_ERROR1", "LAS_GSER_ERROR2", "LAS_GSER_ERROR3", "LAS_GSER_INFO1", "LAS_GSER_INFO2", "LAS_GSER_INFO3", "LAS_D"' class="device_action">Turn ON</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_LAS_SSSD_0" req_cmd='"LAS SSSD 0","LAS GEMT", "LAS GMTE", "LAS GTCO", "LAS GSER"' req_val='"LAS_GEMT_SUPPLY", "LAS_GEMT_EMITING", "LAS_GMTE_DIODE", "LAS_GMTE_CRYSTAL", "LAS_GMTE_ELECTRONICSINK", "LAS_GMTE_HEATSINK", "LAS_TEC1", "LAS_TEC2", "LAS_GSER_ERROR1", "LAS_GSER_ERROR2", "LAS_GSER_ERROR3", "LAS_GSER_INFO1", "LAS_GSER_INFO2", "LAS_GSER_INFO3", "LAS_D"' class="device_action">Turn OFF</button>
                                    </li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li class="device_title"></li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_LAS_UPDATE" class="device_action" req_cmd='"LAS GEMT", "LAS GMTE", "LAS GTCO", "LAS GSER"' req_val='"LAS_GEMT_SUPPLY", "LAS_GEMT_EMITING", "LAS_GMTE_DIODE", "LAS_GMTE_CRYSTAL", "LAS_GMTE_ELECTRONICSINK", "LAS_GMTE_HEATSINK", "LAS_TEC1", "LAS_TEC2", "LAS_GSER_ERROR1", "LAS_GSER_ERROR2", "LAS_GSER_ERROR3", "LAS_GSER_INFO1", "LAS_GSER_INFO2", "LAS_GSER_INFO3", "LAS_D"'>Update</button>
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
                                    <li class="device_title">
                                        Select Channel
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="H_C1" class="device_action">Channel 1</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="H_C2" class="device_action">Channel 2</button>
                                    </li>
                                    <li class="device_title">
                                        Channel 1
                                    </li>
                                    <li>
                                        <span class="label">Wave</span><!---
                                    ---><input id="GEN_C1_WVTP" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Amplitude</span><!---
                                    ---><input id="GEN_C1_AMP" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Offset</span><!---
                                    ---><input id="GEN_C1_OFST" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Frequency</span><!---
                                    ---><input id="GEN_C1_FRQ" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Duty</span><!---
                                    ---><input id="GEN_C1_DUTY" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Pulse Width</span><!---
                                    ---><input id="GEN_C1_WIDTH" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Rise</span><!---
                                    ---><input id="GEN_C1_RISE" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Fall</span><!---
                                    ---><input id="GEN_C1_FALL" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Output</span><!---
                                    ---><input id="GEN_C1_OUT" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_C1_GEN_UPDATE" req_cmd='"GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"' class="device_action">Get Current</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="H_C1_loaddefault" class="device_action">Load Default</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="G_C1_setparameters" class="device_action">Set Parameters</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_C1_ON" req_cmd='"GEN C1:OUTP ON","GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"' class="device_action">Turn ON</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_C1_OFF" req_cmd='"GEN C1:OUTP OFF","GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"' class="device_action">Turn OFF</button>
                                    </li>
                                </ul>
                            </div>
                            <div id="generator_ch2">
                                <ul class="device">
                                    <li class="device_title">
                                        Select Channel
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="H_C1" class="device_action">Channel 1</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="H_C2" class="device_action">Channel 2</button>
                                    </li>
                                    <li class="device_title">
                                        Channel 2
                                    </li>
                                    <li>
                                        <span class="label">Wave</span><!---
                                    ---><input id="GEN_C2_WVTP" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Amplitude</span><!---
                                    ---><input id="GEN_C2_AMP" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Offset</span><!---
                                    ---><input id="GEN_C2_OFST" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Frequency</span><!---
                                    ---><input id="GEN_C2_FRQ" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Duty</span><!---
                                    ---><input id="GEN_C2_DUTY" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Pulse Width</span><!---
                                    ---><input id="GEN_C2_WIDTH" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Rise</span><!---
                                    ---><input id="GEN_C2_RISE" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Fall</span><!---
                                    ---><input id="GEN_C2_FALL" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label">Output</span><!---
                                    ---><input id="GEN_C2_OUT" class="info" readonly>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_C2_GEN_UPDATE" req_cmd='"GEN C2:OUTP?","GEN C2:BSWV?"' req_val='"GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT"' class="device_action">Get Current</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="H_C2_loaddefault" class="device_action">Load Default</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="G_C2_setparameters" class="device_action">Set Parameters</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_C2_ON" req_cmd='"GEN C2:OUTP ON","GEN C2:OUTP?","GEN C2:BSWV?"' req_val='"GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT"' class="device_action">Turn ON</button>
                                    </li>
                                    <li>
                                        <span class="label"></span><!---
                                    ---><button id="A_C2_OFF" req_cmd='"GEN C2:OUTP OFF","GEN C2:OUTP?","GEN C2:BSWV?"' req_val='"GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT"' class="device_action">Turn OFF</button>
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
                                <li class="device_title">
                                    Handling by dB
                                </li>
                                <li>
                                    <span class="label">Current dB</span><!---
                                ---><input id="ATT_DB"  class="info" readonly>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_SET_DB" req_cmd='' req_val='' class="device_action">Set dB</button>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_DB_M01" req_cmd='' req_val='' class="device_action attenuator_action">-0.1db</button><!---
                                ---><button id="A_AT_DB_P01" req_cmd='' req_val='' class="device_action attenuator_action">+0.1db</button>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_DB_M1" req_cmd='' req_val='' class="device_action attenuator_action">-1db</button><!---
                                ---><button id="A_AT_DB_P1" req_cmd='' req_val='' class="device_action attenuator_action">+1db</button>
                                </li>
                                <li class="device_title">
                                    Handling by Attenuation %
                                </li>
                                <li>
                                    <span class="label">Current Attenuation %</span><!---
                                ---><input id="ATT_PERCENT" class="info" readonly>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_SET_PERCENT" req_cmd='' req_val='' class="device_action">Set %</button>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_PERCENT_M1" req_cmd='' req_val='' class="device_action attenuator_action">-1%</button><!---
                                ---><button id="A_AT_PERCENT_P1" req_cmd='' req_val='' class="device_action attenuator_action">+1%</button>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_PERCENT_M10" req_cmd='' req_val='' class="device_action attenuator_action">-10%</button><!---
                                ---><button id="A_AT_PERCENT_P10" req_cmd='' req_val='' class="device_action attenuator_action">+10%</button>
                                </li>
                                <li class="device_title">
                                    Handling by Step
                                </li>
                                <li>
                                    <span class="label">Current Position Step</span><!---
                                ---><input id="ATT_POS"  class="info" readonly>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_SET_STEP" req_cmd='' req_val='' class="device_action">Set Position</button>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_STEP_M1" req_cmd='' req_val='' class="device_action attenuator_action">-1</button><!---
                                ---><button id="A_AT_STEP_P1" req_cmd='' req_val='' class="device_action attenuator_action">+1</button>
                                </li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_STEP_M10" req_cmd='' req_val='' class="device_action attenuator_action">-10</button><!---
                                ---><button id="A_AT_STEP_P10" req_cmd='' req_val='' class="device_action attenuator_action">+10</button>
                                </li>
                                <li class="device_title"></li>
                                <li class="device_title"></li>
                                <li>
                                    <span class="label"></span><!---
                                ---><button id="A_AT_UPDATE" req_cmd='"ATT D"' req_val='"ATT_DB","ATT_POS"' class="device_action">Update</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="underbar">
                <button id="H_advanced">Advanced View</button>
            </div>
        </div>
    </body>
</html>