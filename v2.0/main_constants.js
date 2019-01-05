/////////////////////////////////////////////////////////////////////////////////
// Environment variables ////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

// Default list of commands at startup
var startup_commands = '"LAS GEMT",' +
                       '"LAS GMTE",' +
                       '"LAS GTCO",' +
                       '"LAS GSER",' +
                       '"GEN C1:OUTP?",' +
                       '"GEN C1:BSWV?",' +
                       '"GEN C2:OUTP?",' +
                       '"GEN C2:BSWV?",' +
                       '"ATT D"';
var startup_commands = ''

// Default list of values at startup
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
                     '"ATT_PERCENT",' +
                     '"ATT_POS",' + 
                     '"ATT_LAST"';

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
    ATT_PERCENT:             "[%]",
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

// Variable for pushing while editing input
var tmp_value = "";

// Operational state
var operational = 0;