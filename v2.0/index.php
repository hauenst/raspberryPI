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
                <div id="canvas" class="panel_section">
                    <div class="title"><div class="title_table"><div class="title_table_cell">
                        System diagram and Temperature plots
                    </div></div></div>
                    <div class="content"><div class="content_table"><div class="content_table_cell">
                        <div id="canvas_diagram">
                            <div id="diagram_drawing">
                            </div>
                            <div class="canvas_menu canvas_menu_right">
                                <button id="H_diagram" req="1"  class="button_40">D</button><!---
                            ---><button id="H_plot" req="1" class="button_40 button_end">T</button>
                            </div>
                        </div>
                        <div id="canvas_plot">
                            <div id="plot_drawing">
                                <canvas id="temp_1"></canvas>
                            </div>
                            <div class="canvas_menu">
                                <button id="T_1" req="1" class="button_init">1H</button><!---
                            ---><button id="T_6" req="6">6H</button><!---
                            ---><button id="T_12" req="12">12H</button><!---
                            ---><button id="T_24" req="24">24H</button><!---
                            ---><button id="T_48" req="48">48H</button><!---
                            ---><button id="T_96" req="96">96H</button><!---
                            ---><button id="H_diagram" req="1"  class="button_40">D</button><!---
                            ---><button id="H_plot" req="1" class="button_40 button_end">T</button>
                            </div>
                        </div>
                    </div></div></div>
                </div>
                <div id="operationalcontrol" class="panel_section">
                    <div class="title"><div class="title_table"><div class="title_table_cell">
                        Operational Control
                    </div></div></div>
                    <div class="content"><div class="content_table"><div class="content_table_cell">
                        Content
                    </div></div></div>
                </div>
                <div id="queue" class="panel_section">
                    <div class="title"><div class="title_table"><div class="title_table_cell">
                        Queue
                    </div></div></div>
                    <div class="content"><div class="content_table"><div class="content_table_cell">
                        Content
                    </div></div></div>
                </div>
                <div id="laser" class="panel_section">
                    <div class="title"><div class="title_table"><div class="title_table_cell">
                        Laser
                    </div></div></div>
                    <div class="content"><div class="content_table"><div class="content_table_cell">
                        Content
                    </div></div></div>
                </div>
                <div id="generator" class="panel_section">
                    <div class="title"><div class="title_table"><div class="title_table_cell">
                        Generator
                    </div></div></div>
                    <div class="content"><div class="content_table"><div class="content_table_cell">
                        Content
                    </div></div></div>
                </div>
                <div id="attenuator" class="panel_section">
                    <div class="title"><div class="title_table"><div class="title_table_cell">
                        Attenuator
                    </div></div></div>
                    <div class="content"><div class="content_table"><div class="content_table_cell">
                        Content
                    </div></div></div>
                </div>
            </div>
        </div>
    </body>
</html>



<!---

                        <div id="canvas_diagram">
                            <div id="canvas_diagram_row">
                                <div id="canvas_diagram_selector">
                                    <button id="H_diagram" class="button_1_5">System Diagram</button><button id="H_plot" class="button_1_5">Temperature Plot</button>
                                </div>
                            </div>
                            <div id="canvas_diagram_row">
                                <div id="canvas_diagram_plot">
                                </div>
                            </div>
                            <div id="canvas_diagram_row">
                                <div id="canvas_diagram_menu">
                                    <button id="H_diagram_update">Update</button>
                                </div>
                            </div>
                        </div>
                        <div id="canvas_plot">
                            <div id="canvas_plot_row">
                                <div id="canvas_plot_selector">
                                    <button id="H_diagram" class="button_1_5">System Diagram</button><button id="H_plot" class="button_1_5">Temperature Plot</button>
                                </div>
                            </div>
                            <div id="canvas_plot_row">
                                <div id="canvas_plot_plot">
                                <div style="display: block; position: relative; height:100%; width:100%">
                                    <canvas id="temp_1"></canvas>
                                </div>
                                </div>
                            </div>
                            <div id="canvas_plot_row">
                                <div id="canvas_plot_menu">
                                    <button id="T_1"  req="1" >Last Hour</button><button id="T_6"  req="6" >Last 6 Hours</button><button id="T_12" req="12">Last 12 Hours</button><button id="T_24" req="24">Last Day</button><button id="T_48" req="48">Last 2 Days</button><button id="T_96" req="96">Last 4 Days</button>
                                </div>
                            </div>
                        </div>




                            <div id="content_title">
                                Laser Control and Information
                            </div>
                            <div id="laser_content_group">
                                <div id="laser_content_row">
                                    <div id="laser_content_cell_left">
                                        <ul>
                                            <li><span class="label_title">Times</span></li>
                                            <li>
                                                <ul>
                                                    <li><span class="label">Supply</span><input id="LAS_GEMT_SUPPLY"  class="info center" readonly><span class="unit">[H:M]</span></li>
                                                    <li><span class="label">Emitting</span><input id="LAS_GEMT_EMITING" class="info center" readonly><span class="unit">[H:M]</span></li>
                                                </ul>
                                            </li>
                                            <li><span class="label_title">Temperatures</span></li>
                                            <li>
                                                <ul>
                                                    <li><span class="label">Diode</span><input id="LAS_GMTE_DIODE"  class="info" readonly><span class="unit">[&deg;C]</span></li>
                                                    <li><span class="label">Crystal</span><input id="LAS_GMTE_CRYSTAL" class="info" readonly><span class="unit">[&deg;C]</span></li>
                                                    <li><span class="label">Electronic Sink</span><input id="LAS_GMTE_ELECTRONICSINK" class="info" readonly><span class="unit">[&deg;C]</span></li>
                                                    <li><span class="label">Heat Sink</span><input id="LAS_GMTE_HEATSINK" class="info" readonly><span class="unit">[&deg;C]</span></li>
                                                </ul>
                                            </li>
                                            <li><span class="label_title">Temperature Control</span></li>
                                            <li>
                                                <ul>
                                                    <li><span class="label">Control TEC 1</span><input id="LAS_TEC1"  class="info center" readonly><span class="unit"></span></li>
                                                    <li><span class="label">Control TEC 2</span><input id="LAS_TEC2" class="info center" readonly><span class="unit"></span></li>
                                                </ul>
                                            </li>
                                            <li><span class="label_title">Error and Information Report</span></li>
                                            <li>
                                                <ul>
                                                    <li><span class="label">Error 1</span><input id="LAS_GSER_ERROR1" class="info center" readonly><span class="unit"></span></li>
                                                    <li><span class="label">Error 2</span><input id="LAS_GSER_ERROR2" class="info center" readonly><span class="unit"></span></li>
                                                    <li><span class="label">Error 2</span><input id="LAS_GSER_ERROR3" class="info center" readonly><span class="unit"></span></li>
                                                    <li><span class="label">Error 1</span><input id="LAS_GSER_INFO1" class="info center" readonly><span class="unit"></span></li>
                                                    <li><span class="label">Error 2</span><input id="LAS_GSER_INFO2" class="info center" readonly><span class="unit"></span></li>
                                                    <li><span class="label">Error 2</span><input id="LAS_GSER_INFO3" class="info center" readonly><span class="unit"></span></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="laser_content_cell_right">
                                        <ul>
                                            <li><button id="A_LAS_GENERAL" req='{"commands":["LAS GEMT", "LAS GMTE", "LAS GTCO", "LAS GSER"],"values":["LAS_GEMT_SUPPLY", "LAS_GEMT_EMITING", "LAS_GMTE_DIODE", "LAS_GMTE_CRYSTAL", "LAS_GMTE_ELECTRONICSINK", "LAS_GMTE_HEATSINK", "LAS_TEC1", "LAS_TEC2", "LAS_GSER_ERROR1", "LAS_GSER_ERROR2", "LAS_GSER_ERROR3", "LAS_GSER_INFO1", "LAS_GSER_INFO2", "LAS_GSER_INFO3"]}' class="server_action">Update</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                                    <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <span id="GEN_C2_OUT"></span><br>
        <button id="A_GEN_C2_OFF" req='{"commands":["GEN C2:OUTP OFF", "GEN C2:OUTP?"],"values":["GEN_C2_OUT"]}'>C2 OFF</button><br>
        <button id="A_GEN_C2_ON" req='{"commands":["GEN C2:OUTP ON", "GEN C2:OUTP?"],"values":["GEN_C2_OUT"]}'>C2 ON</button><br>
        <span id="LAS_GMTE_DIODE"></span><br>
        <span id="LAS_GMTE_CRYSTAL"></span><br>
        <span id="LAS_GMTE_ELECTRONICSINK"></span><br>
        <span id="LAS_GMTE_HEATSINK"></span><br>
        <button id="A_LAS_GMTE" req='{"commands":["LAS GMTE"],"values":["LAS_GMTE_DIODE","LAS_GMTE_CRYSTAL","LAS_GMTE_ELECTRONICSINK","LAS_GMTE_HEATSINK"]}'>Get Temperatures</button><br><br><br><br>
        <div style="text-align: center">
            <div style="display: inline-block; position: relative; height:450px; width:600px">
                <canvas id="temp_1"></canvas>
            </div><br>
            <button id="T_1"  req="1" >Last Hour</button>
            <button id="T_6"  req="6" >Last 6 Hours</button>
            <button id="T_12" req="12">Last 12 Hours</button>
            <button id="T_24" req="24">Last Day</button>
            <button id="T_48" req="48">Last 2 Days</button>
            <button id="T_96" req="96">Last 4 Days</button>
        </div>
--->