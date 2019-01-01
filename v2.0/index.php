<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="main.js"></script>
    </head>
    <body>
        <span id="GEN_C2_OUT"></span><br>
        <button id="GEN_C2_OFF" req='{"commands":["GEN C2:OUTP OFF", "GEN C2:OUTP?"],"values":["GEN_C2_OUT"]}'>C2 OFF</button><br>
        <button id="GEN_C2_ON" req='{"commands":["GEN C2:OUTP ON", "GEN C2:OUTP?"],"values":["GEN_C2_OUT"]}'>C2 ON</button><br>
        <span id="LAS_GMTE_DIODE"></span><br>
        <span id="LAS_GMTE_CRYSTAL"></span><br>
        <span id="LAS_GMTE_ELECTRONICSINK"></span><br>
        <span id="LAS_GMTE_HEATSINK"></span><br>
        <button id="LAS_GMTE" req='{"commands":["LAS GMTE"],"values":["LAS_GMTE_DIODE","LAS_GMTE_CRYSTAL","LAS_GMTE_ELECTRONICSINK","LAS_GMTE_HEATSINK"]}'>Get Temperatures</button>
    </body>
</html>