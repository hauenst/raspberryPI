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
            <div id="container_cell">
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
                            <div class="title">
                                <div class="title_table">
                                    <div class="title_table_cell">
                                        System diagram and Temperature plots
                                    </div>
                                </div>
                            </div>
                            <div class="content">
                                <div id="canvas_diagram">
                                    <div id="diagram_drawing">
                                        <svg id="system_diagram">
                                            <!--- Connection Lines -->
                                            <g style="stroke-width:5px;stroke:#000;stroke-linecap:square;">
                                                <!--- Generator to Laser --->
                                                <line x1="10%" y1="75%" x2="30%" y2="75%" id="DIA_A_B"/>
                                                <!--- Laser to Scrambler --->
                                                <line x1="30%" y1="75%" x2="50%" y2="75%" id="DIA_B_C"/>
                                                <!--- Scrambler to Spliter --->
                                                <line x1="50%" y1="75%" x2="70%" y2="75%" id="DIA_C_D"/>
                                                <!--- Spliter to Attenuator --->
                                                <line x1="70%" y1="75%" x2="90%" y2="75%" id="DIA_D_E"/>
                                                <!--- Spliter to Photodiode --->
                                                <line x1="70%" y1="25%" x2="90%" y2="25%" id="DIA_D_F"/>
                                                <line x1="70%" y1="25%" x2="70%" y2="75%" id="DIA_D_F"/>
                                            </g>
                                            <!--- Base Elements --->
                                            <g style="fill:#DDD;">
                                                <circle cx="50%" cy="75%" r="8%"                   id="DIA_SCRAMBLER"/>
                                                <circle cx="70%" cy="75%" r="8%"                   id="DIA_SPLITER"/>
                                                <rect    x="2%"   y="55%" width="16%" height="40%" id="DIA_GENERATOR"/>
                                                <rect    x="22%"  y="55%" width="16%" height="40%" id="DIA_LASER"/>
                                                <rect    x="82%"  y="55%" width="16%" height="40%" id="DIA_ATTENUATOR"/>
                                                <rect    x="82%"  y="5%"  width="16%" height="40%" id="DIA_DIODE"/>
                                            <g>
                                            <!--- Title Boxes --->
                                            <g style="fill:#AAA;">
                                                <rect x="2%"  y="55%" width="16%" height="10%"/>
                                                <rect x="22%" y="55%" width="16%" height="10%"/>
                                                <rect x="82%" y="55%" width="16%" height="10%"/>
                                                <rect x="82%" y="5%"  width="16%" height="10%"/>
                                            </g>
                                            <!--- Texts --->
                                            <g style="text-anchor:middle;dominant-baseline:middle;fill:black;">
                                                <text x="10%" y="60%"                  >Generator</text>
                                                <text x="10%" y="70%" id="GEN_C1_WIDTH"></text>
                                                <text x="10%" y="80%" id="GEN_C1_FRQ"  ></text>
                                                <text x="10%" y="90%" id="GEN_C1_OUT"  ></text>
                                                <text x="30%" y="60%"                  >Laser</text>    
                                                <text x="30%" y="80%" id="LAS_D"       ></text>
                                                <text x="50%" y="75%"                  >Scrambler</text>
                                                <text x="70%" y="75%"                  >Spliter</text>
                                                <text x="90%" y="60%"                  >Attenuator</text>
                                                <text x="90%" y="75%" id="ATT_DB"      ></text>
                                                <text x="90%" y="85%" id="ATT_PERCENT" ></text>
                                                <text x="90%" y="10%"                  >Photodiode</text>
                                                <text x="90%" y="30%" id="LAS_D"       ></text>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="canvas_menu">
                                        <button id="H_diagram" class="canvas_menu canvas_single">D</button><!---
                                    ---><button id="H_plot" class="canvas_menu canvas_single">T</button>
                                    </div>
                                </div>
                                <div id="canvas_plot">
                                    <div id="plot_drawing">
                                        <canvas id="temperature_plot"></canvas>
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
                            <div class="title">
                                <div class="title_table">
                                    <div class="title_table_cell">
                                        Operational Control
                                    </div>
                                </div>
                            </div>
                            <div class="content">
                                <ul class="device">
                                    <li class="title">
                                        Reset system to get a known default status
                                    </li>
                                    <li>
                                        <button id="A_OP_STEP0" class="full" req_ope="set" req_cmd='"STA OPE 1","GEN SSSD 0","GEN C1:OUTP OFF","GEN C1:BSWV AMP,5","GEN C1:BSWV OFST,2.5","GEN C1:BSWV DUTY,10","GEN C1:BSWV WIDTH,0.0001","GEN C1:BSWV RISE,2.68e-08","GEN C1:BSWV FALL,1.68e-08","GEN C1:BSWV OUT,","GEN C1:BSWV?","GEN C1:OUTP?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'>Set Default</button>
                                    </li>
                                    <li class="title">
                                        1. Enable Laser pulsing
                                    </li>
                                    <li>
                                        <button id="A_OP_STEP1" class="full" req_ope="set" req_cmd='"STA OPE 2","LAS SSSD 1"' req_val='"LAS_D"'>Enable Pulsing</button>
                                    </li>
                                    <li class="title">
                                        2. Set frequency, attenuation and pulse width
                                    </li>
                                    <li>
                                        <input disabled id="F_OP_STEP2_PWD" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)"><!---
                                    ---><button id="O_OP_STEP2_PWD">Set Pulse Width</button>
                                    </li>
                                    <li>
                                        <input disabled id="F_OP_STEP2_FRQ" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)"><!---
                                    ---><button id="O_OP_STEP2_FRQ">Set Frequency</button>
                                    </li>
                                    <li>
                                        <input disabled id="F_OP_STEP2_ATT" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)"><!---
                                    ---><button id="O_OP_STEP2_ATT">Set Attenuation</button>
                                    </li>
                                    <li class="title">
                                        Turn on the signal generator to start pulsing
                                    </li>
                                    <li>
                                        <button id="A_OP_STEP2" class="full" req_ope="set" req_cmd='"STA OPE 3","GEN C1:OUTP ON","GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'>Turn ON Signal Generator</button>
                                    </li>
                                    <li class="title">
                                        3. Adjust the pulsing frequency and attenuation
                                    </li>
                                    <li>
                                        <input disabled id="F_OP_STEP3_FRQ" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)"><!---
                                    ---><button id="O_OP_STEP3_FRQ" req_ope="set">Adjust Frequency</button>
                                    </li>
                                    <li>
                                        <input disabled id="F_OP_STEP3_ATT" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)"><!---
                                    ---><button id="O_OP_STEP3_ATT" req_ope="set">Adjust Attenuation</button>
                                    </li>
                                    <li class="title">
                                        Turn off Signal Generator to stop pulsing
                                    </li>
                                    <li>
                                        <button id="A_OP_STEP3" class="full" req_ope="set" req_cmd='"STA OPE 4","GEN C1:OUTP OFF","GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'>Turn OFF Signal Generator</button>
                                    </li>
                                    <li class="title">
                                        4. Disable Laser pulsing
                                    </li>
                                    <li>
                                        <button id="A_OP_STEP4" class="full" req_ope="set" req_cmd='"STA OPE 5","LAS SSSD 0"' req_val='"LAS_D"'>Disable Laser Pulsing</button>
                                    </li>
                                    <li>
                                        <span class="center">Current Status: <span id="STA_OPERATIONAL">?</span></span><!---
                                    ---><button id="A_OP_0STEP" class="align-bottom" req_cmd='"LAS GEMT","LAS GMTE","LAS GTCO","LAS GSER","GEN C1:OUTP?","GEN C1:BSWV?","GEN C2:OUTP?","GEN C2:BSWV?","ATT D"' req_val='"LAS_GEMT_SUPPLY","LAS_GEMT_EMITING","LAS_GMTE_DIODE","LAS_GMTE_CRYSTAL","LAS_GMTE_ELECTRONICSINK","LAS_GMTE_HEATSINK","LAS_TEC1","LAS_TEC2","LAS_GSER_ERROR1","LAS_GSER_ERROR2","LAS_GSER_ERROR3","LAS_GSER_INFO1","LAS_GSER_INFO2","LAS_GSER_INFO3","LAS_D","GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT","GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT","ATT_DB","ATT_PERCENT","ATT_POS","ATT_LAST"'>Restart Operation</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="queue" class="panel_section">
                            <div class="title">
                                <div class="title_table">
                                    <div class="title_table_cell">
                                        Queue
                                    </div>
                                </div>
                            </div>
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
                            <div class="title">
                                <div class="title_table">
                                    <div class="title_table_cell">
                                        Laser
                                    </div>
                                </div>
                            </div>
                            <div class="content">
                                <div id="laser_left">
                                    <ul class="device">
                                        <li class="title">
                                            Times
                                        </li>
                                        <li>
                                            <span>Supply Time</span><!---
                                        ---><input disabled readonly id="LAS_GEMT_SUPPLY">
                                        </li>
                                        <li>
                                            <span>Emitting</span><!---
                                        ---><input disabled readonly id="LAS_GEMT_EMITING">
                                        </li>
                                        <li class="title">
                                            Temperatures
                                        </li>
                                        <li>
                                            <span>Diode</span><!---
                                        ---><input disabled readonly id="LAS_GMTE_DIODE" >
                                        </li>
                                        <li>
                                            <span>Crystal</span><!---
                                        ---><input disabled readonly id="LAS_GMTE_CRYSTAL">
                                        </li>
                                        <li>
                                            <span>Electronic Sink</span><!---
                                        ---><input disabled readonly id="LAS_GMTE_ELECTRONICSINK">
                                        </li>
                                        <li>
                                            <span>Heat Sink</span><!---
                                        ---><input disabled readonly id="LAS_GMTE_HEATSINK">
                                        </li>
                                        <li class="title">
                                            Temperature Control
                                        </li>
                                        <li>
                                            <span>Control TEC 1</span><!---
                                        ---><input disabled readonly id="LAS_TEC1" >
                                        </li>
                                        <li>
                                            <span>Control TEC 2</span><!---
                                        ---><input disabled readonly id="LAS_TEC2">
                                        </li>
                                        <li class="title">
                                            Errors and Informations
                                        </li>
                                        <li>
                                            <span>Error 1</span><!---
                                        ---><input disabled readonly id="LAS_GSER_ERROR1">
                                        </li>
                                        <li>
                                            <span>Error 2</span><!---
                                        ---><input disabled readonly id="LAS_GSER_ERROR2">
                                        </li>
                                        <li>
                                            <span>Error 2</span><!---
                                        ---><input disabled readonly id="LAS_GSER_ERROR3">
                                        </li>
                                        <li>
                                            <span>Info 1</span><!---
                                        ---><input disabled readonly id="LAS_GSER_INFO1">
                                        </li>
                                        <li>
                                            <span>Info 2</span><!---
                                        ---><input disabled readonly id="LAS_GSER_INFO2">
                                        </li>
                                        <li>
                                            <span>Info 3</span><!---
                                        ---><input disabled readonly id="LAS_GSER_INFO3">
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
                                        ---><input disabled readonly id="LAS_D">
                                        </li>
                                        <li>
                                            <span class="align-bottom"></span><!---
                                        ---><button id="A_LAS_SSSD_1" class="align-top" req_cmd='"LAS SSSD 1","LAS GEMT","LAS GMTE","LAS GTCO","LAS GSER"' req_val='"LAS_GEMT_SUPPLY","LAS_GEMT_EMITING","LAS_GMTE_DIODE","LAS_GMTE_CRYSTAL","LAS_GMTE_ELECTRONICSINK","LAS_GMTE_HEATSINK","LAS_TEC1","LAS_TEC2","LAS_GSER_ERROR1","LAS_GSER_ERROR2","LAS_GSER_ERROR3","LAS_GSER_INFO1","LAS_GSER_INFO2","LAS_GSER_INFO3","LAS_D"'>Turn ON</button>
                                        </li>
                                        <li>
                                            <span class="align-bottom"></span><!---
                                        ---><button id="A_LAS_SSSD_0" class="align-top" req_cmd='"LAS SSSD 0","LAS GEMT","LAS GMTE","LAS GTCO","LAS GSER"' req_val='"LAS_GEMT_SUPPLY","LAS_GEMT_EMITING","LAS_GMTE_DIODE","LAS_GMTE_CRYSTAL","LAS_GMTE_ELECTRONICSINK","LAS_GMTE_HEATSINK","LAS_TEC1","LAS_TEC2","LAS_GSER_ERROR1","LAS_GSER_ERROR2","LAS_GSER_ERROR3","LAS_GSER_INFO1","LAS_GSER_INFO2","LAS_GSER_INFO3","LAS_D"'>Turn OFF</button>
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
                                        ---><button id="A_LAS_UPDATE" class="align-top" req_ope="set" req_cmd='"LAS GEMT","LAS GMTE","LAS GTCO","LAS GSER"' req_val='"LAS_GEMT_SUPPLY","LAS_GEMT_EMITING","LAS_GMTE_DIODE","LAS_GMTE_CRYSTAL","LAS_GMTE_ELECTRONICSINK","LAS_GMTE_HEATSINK","LAS_TEC1","LAS_TEC2","LAS_GSER_ERROR1","LAS_GSER_ERROR2","LAS_GSER_ERROR3","LAS_GSER_INFO1","LAS_GSER_INFO2","LAS_GSER_INFO3","LAS_D"'>Update</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="generator" class="panel_section">
                            <div class="title">
                                <div class="title_table">
                                    <div class="title_table_cell">
                                        Generator
                                    </div>
                                </div>
                            </div>
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
                                        ---><input disabled readonly id="GEN_C1_OUT">
                                        </li>
                                        <li>
                                            <span class="align-bottom"></span><!---
                                        ---><button id="A_C1_GEN_UPDATE" class="align-top" req_ope="set" req_cmd='"GEN C1:OUTP?","GEN C1:BSWV?"' req_val='"GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT"'>Get Current</button>
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
                                        ---><input disabled id="GEN_C2_AMP" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                        </li>
                                        <li>
                                            <span>Offset</span><!---
                                        ---><input disabled id="GEN_C2_OFST" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                        </li>
                                        <li>
                                            <span>Frequency</span><!---
                                        ---><input disabled id="GEN_C2_FRQ" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                        </li>
                                        <li>
                                            <span>Duty</span><!---
                                        ---><input disabled id="GEN_C2_DUTY" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                        </li>
                                        <li>
                                            <span>Pulse Width</span><!---
                                        ---><input disabled id="GEN_C2_WIDTH" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                        </li>
                                        <li>
                                            <span>Rise</span><!---
                                        ---><input disabled id="GEN_C2_RISE" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                        </li>
                                        <li>
                                            <span>Fall</span><!---
                                        ---><input disabled id="GEN_C2_FALL" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                        </li>
                                        <li>
                                            <span>Output</span><!---
                                        ---><input disabled readonly id="GEN_C2_OUT">
                                        </li>
                                        <li>
                                            <span class="align-bottom"></span><!---
                                        ---><button id="A_C2_GEN_UPDATE" class="align-top" req_ope="set" req_cmd='"GEN C2:OUTP?","GEN C2:BSWV?"' req_val='"GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT"'>Get Current</button>
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
                            <div class="title">
                                <div class="title_table">
                                    <div class="title_table_cell">
                                        Attenuator
                                    </div>
                                </div>
                            </div>
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
                                    ---><button id="X_AT_SET_DB" class="align-top" onclick="set_db('field')">Set dB</button>
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
                                    ---><input disabled id="ATT_PERCENT" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="X_AT_SET_PERCENT" class="align-top" onclick="set_db('field')">Set %</button>
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
                                    ---><input disabled id="ATT_POS" onfocusin="tmp_push(this)" onfocusout="tmp_pop(this)">
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="X_AT_SET_STEP" class="align-top" onclick="set_pos()">Set Position</button>
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
                                    ---><input disabled readonly id="ATT_LAST" >
                                    </li>
                                    <li>
                                        <span class="align-bottom"></span><!---
                                    ---><button id="A_AT_UPDATE" class="align-top" req_ope="set" req_cmd='"ATT D"' req_val='"ATT_LAST","ATT_DB","ATT_POS","ATT_PERCENT"'>Update</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="underbar">
                    <button id="A_REFRESH_ALL" class="advanced" req_ope='set' req_cmd='"LAS GEMT","LAS GMTE","LAS GTCO","LAS GSER","GEN C1:OUTP?","GEN C1:BSWV?","GEN C2:OUTP?","GEN C2:BSWV?","ATT D"' req_val='"LAS_GEMT_SUPPLY","LAS_GEMT_EMITING","LAS_GMTE_DIODE","LAS_GMTE_CRYSTAL","LAS_GMTE_ELECTRONICSINK","LAS_GMTE_HEATSINK","LAS_TEC1","LAS_TEC2","LAS_GSER_ERROR1","LAS_GSER_ERROR2","LAS_GSER_ERROR3","LAS_GSER_INFO1","LAS_GSER_INFO2","LAS_GSER_INFO3","LAS_D","GEN_C1_WVTP","GEN_C1_AMP","GEN_C1_OFST","GEN_C1_FRQ","GEN_C1_DUTY","GEN_C1_WIDTH","GEN_C1_RISE","GEN_C1_FALL","GEN_C1_OUT","GEN_C2_WVTP","GEN_C2_AMP","GEN_C2_OFST","GEN_C2_FRQ","GEN_C2_DUTY","GEN_C2_WIDTH","GEN_C2_RISE","GEN_C2_FALL","GEN_C2_OUT","ATT_DB","ATT_PERCENT","ATT_POS","ATT_LAST"'>Refresh All</button><!---
                ---><button id="H_advanced" class="advanced">Advanced Options</button>
                </div>
            </div>
        </div>
    </body>
</html>