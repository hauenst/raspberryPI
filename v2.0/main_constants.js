
///////////////////////////////////////////////////////////////////////////////////
// Startup parameters /////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

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
                     '"GE1_WVTP",' +
                     '"GE1_AMP",' +
                     '"GE1_OFST",' +
                     '"GE1_FRQ",' +
                     '"GE1_DUTY",' +
                     '"GE1_WIDTH",' +
                     '"GE1_RISE",' +
                     '"GE1_FALL",' +
                     '"GE1_OUT",' +
                     '"GE2_WVTP",' +
                     '"GE2_AMP",' +
                     '"GE2_OFST",' +
                     '"GE2_FRQ",' +
                     '"GE2_DUTY",' +
                     '"GE2_WIDTH",' +
                     '"GE2_RISE",' +
                     '"GE2_FALL",' +
                     '"GE2_OUT",' +
                     '"ATT_DB",' +
                     '"ATT_PERCENT",' +
                     '"ATT_POS",' + 
                     '"ATT_LAST"';

// Default amount of seconds for the temperature plot tim range
var startup_seconds = 1*60*60+60;

///////////////////////////////////////////////////////////////////////////////////
// Default values /////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

var channel_defaults = {
    1: {
        GE1_AMP:   "5 [V]",
        GE1_OFST:  "2.5 [V]",
        GE1_FRQ:   "1000 [Hz]",
        GE1_DUTY:  "10 [%]",
        GE1_WIDTH: "0.0001 [S]",
        GE1_RISE:  "1.68e-08 [S]",
        GE1_FALL:  "1.68e-08 [S]",
    },
    2: {
        GE2_AMP:   "5 [V]",
        GE2_OFST:  "2.5 [V]",
        GE2_FRQ:   "1000 [Hz]",
        GE2_DUTY:  "10 [%]",
        GE2_WIDTH: "0.0001 [S]",
        GE2_RISE:  "1.68e-08 [S]",
        GE2_FALL:  "1.68e-08 [S]",
    }
};

// List of units for specific parameters
var parameter_units = {
    LAS_GEMT_SUPPLY:         "[H:M]",
    LAS_GEMT_EMITING:        "[H:M]",
    LAS_GMTE_DIODE:          "[°C]",
    LAS_GMTE_CRYSTAL:        "[°C]",
    LAS_GMTE_ELECTRONICSINK: "[°C]",
    LAS_GMTE_HEATSINK:       "[°C]",
    GE1_AMP:                 "[V]",
    GE1_OFST:                "[V]",
    GE1_FRQ:                 "[Hz]",
    GE1_DUTY:                "[%]",
    GE1_WIDTH:               "[S]",
    GE1_RISE:                "[S]",
    GE1_FALL:                "[S]",
    GE2_AMP:                 "[V]",
    GE2_OFST:                "[V]",
    GE2_FRQ:                 "[Hz]",
    GE2_DUTY:                "[%]",
    GE2_WIDTH:               "[S]",
    GE2_RISE:                "[S]",
    GE2_FALL:                "[S]",
    ATT_DB:                  "[dB]",
    ATT_PERCENT:             "[%]",
    ATT_POS:                 "[Step]"
}

///////////////////////////////////////////////////////////////////////////////////
// Graphical Elements /////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

// Variable to reference the temperature plot
var temperature_plot;

// Arrays to store the temperature informarion
var temp_diode          = [];
var temp_crystal        = [];
var temp_electronicsink = [];
var temp_heatsink       = [];
var temp_times          = [];

// Variable to reference the diagram plot
var system_diagram;

// Variable to store the advanced view display
var advanced = false;

///////////////////////////////////////////////////////////////////////////////////
// Handy sets /////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

// Laser //////////////////////////////////////////////////////////////////////////

var laser_buttons = '"B_LAS_ON",' +
                    '"B_LAS_OFF",' +
                    '"B_LAS_UPDATE"';

// Generator //////////////////////////////////////////////////////////////////////

var generator_ch1_buttons = '"B_GE1_UPDATE",' +
                            '"B_GE1_DEFAULT",' +
                            '"B_GE1_SET",' +
                            '"B_GE1_ON",' +
                            '"B_GE1_OFF"'

var generator_ch2_buttons = '"B_GE2_UPDATE",' +
                            '"B_GE2_DEFAULT",' +
                            '"B_GE2_SET",' +
                            '"B_GE2_ON",' +
                            '"B_GE2_OFF"'                            

var generator_buttons = generator_ch1_buttons + ',' +
                        generator_ch2_buttons

var generator_ch1_parameters =  '"GE1_AMP",' +
                                '"GE1_OFST",' +
                                '"GE1_FRQ",' +
                                '"GE1_DUTY",' +
                                '"GE1_WIDTH",' +
                                '"GE1_RISE",' +
                                '"GE1_FALL",' +
                                '"GE1_OUT"'

var generator_ch2_parameters = '"GE2_AMP",' +
                               '"GE2_OFST",' +
                               '"GE2_FRQ",' +
                               '"GE2_DUTY",' +
                               '"GE2_WIDTH",' +
                               '"GE2_RISE",' +
                               '"GE2_FALL",' +
                               '"GE2_OUT"'

var generator_parameters = generator_ch1_parameters + "," +
                           generator_ch2_parameters

// Attenuator /////////////////////////////////////////////////////////////////////

var attenuator_db_buttons = '"B_ATT_DB",' +
                            '"B_ATT_DB_M01",' +
                            '"B_ATT_DB_P01",' +
                            '"B_ATT_DB_M10",' +
                            '"B_ATT_DB_P10"'

var attenuator_percent_buttons = '"B_ATT_PERCENT",' +
                                 '"B_ATT_PERCENT_M01",' +
                                 '"B_ATT_PERCENT_P01",' +
                                 '"B_ATT_PERCENT_M10",' +
                                 '"B_ATT_PERCENT_P10"'

var attenuator_step_buttons = '"B_ATT_POS",' +
                              '"B_ATT_POS_M01",' +
                              '"B_ATT_POS_P01",' +
                              '"B_ATT_POS_M10",' +
                              '"B_ATT_POS_P10"'

var attenuator_buttons = attenuator_db_buttons + ',' + 
                         attenuator_percent_buttons + ',' + 
                         attenuator_step_buttons + ',' +
                         '"B_ATT_UPDATE"';

var attenuator_fields = '"I_ATT_DB","I_ATT_PERCENT","I_ATT_POS"';

// Buffering and state ////////////////////////////////////////////////////////////

// Variable for pushing while editing input
var tmp_value = "";

// Operational
var operational = 0;
