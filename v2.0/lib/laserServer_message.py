
# System Imports
import re
import sys

# Local Imports
import laserServer_main as Main
import laserTools       as Tools

# ===============================================================================
# Client message processing =====================================================
# ===============================================================================

def process(input_str, events, queue):
    Tools.verbose("   Received: " + input_str.rstrip())
    device = input_str[:3]
    tosend = input_str[4:]
    if (device == "LAS") :
        # Send to Laser
        queue["laser"].append(tosend)
        events["laser"].set()
        response = "OK - Laser Queued"
    elif (device == "ATT"):
        # Send to attenuator
        queue["atten"].append(tosend)
        events["atten"].set()
        response = "OK - Attenuator Queued"
    elif (device == "GEN"):
        # Send to generator
        queue["gener"].append(tosend)
        events["gener"].set()
        response = "OK - Generator Queued"
    elif (device == "STA"):
        # Get server status
        response = Main.message_handler(tosend, events)
    else:
        response = "KO - Command NOT Queued. Device \"" + device + "\" NOT recognized"
    sys.stdout.flush()
    return response

# ===============================================================================
# Command response parsing ======================================================
# ===============================================================================

def parse(command, response, db):
    Tools.verbose(repr(command), level=2)
    Tools.verbose(repr(response), level=2)
    # Initilizing variables
    try:
        cursor = db.cursor()
    except:
        print("ERROR: Not possible to obtain DB cursor. " + str(sys.exc_info()))
        return None
    queries = []
    # Storing command
    queries.append('INSERT INTO `commands` (`id`, `timestamp`, `command`) VALUES (NULL, CURRENT_TIMESTAMP, \'%s\');' % command)
    # matching regular expressions
    response_match = True
    while True:
        # For empty response
        if (response == ""):
            m = re.match("^GEN C.:BSWV ", command)
            if (m):
                break
            m = re.match("^GEN C.:OUTP ", command)
            if (m):
                break
        # Processing responses
        m = re.match("^GEMT ([0-9]{5}) ([0-9]{2}) ([0-9]{5}) ([0-9]{2})", response)
        if (m):
            # Current
            queries.append(query_current("LAS_GEMT_SUPPLY",  "%d:%d" % (int(m.group(1)), int(m.group(2)))))
            queries.append(query_current("LAS_GEMT_EMITING", "%d:%d" % (int(m.group(3)), int(m.group(4)))))
            # History
            queries.append(query_history("LAS_GEMT_SUPPLY",  "%d:%d" % (int(m.group(1)), int(m.group(2)))))
            queries.append(query_history("LAS_GEMT_EMITING", "%d:%d" % (int(m.group(3)), int(m.group(4)))))
            break
        m = re.match("^GMTE ([0-9]{4}) ([0-9]{4}) ([0-9]{2}) ([0-9]{2})", response)
        if (m):
            # Current
            queries.append(query_current("LAS_GMTE_DIODE",          "%.2f" % (float(m.group(1))/100)))
            queries.append(query_current("LAS_GMTE_CRYSTAL",        "%.2f" % (float(m.group(2))/100)))
            queries.append(query_current("LAS_GMTE_ELECTRONICSINK", "%.2f" % float(m.group(3))))
            queries.append(query_current("LAS_GMTE_HEATSINK",       "%.2f" % float(m.group(4))))
            # History
            queries.append(query_history("LAS_GMTE_DIODE",          "%.2f" % (float(m.group(1))/100)))
            queries.append(query_history("LAS_GMTE_CRYSTAL",        "%.2f" % (float(m.group(2))/100)))
            queries.append(query_history("LAS_GMTE_ELECTRONICSINK", "%.2f" % float(m.group(3))))
            queries.append(query_history("LAS_GMTE_HEATSINK",       "%.2f" % float(m.group(4))))
            # temperatures
            queries.append(query_temperature(m.group(1), m.group(2), m.group(3), m.group(4)))
            break
        m = re.match("^GTCO ([0-9])", response)
        if (m):
            # Current
            queries.append(query_current("LAS_TEC1" , ("ON" if int(m.group(1))&0x1 else "OFF")))
            queries.append(query_current("LAS_TEC2" , ("ON" if int(m.group(1))&0x2 else "OFF")))
            # History
            queries.append(query_history("LAS_GTCO_TEC1" , ("ON" if int(m.group(1))&0x1 else "OFF")))
            queries.append(query_history("LAS_GTCO_TEC2" , ("ON" if int(m.group(1))&0x2 else "OFF")))
            break
        m = re.match("^GSER ([A-F0-9]{2}) ([A-F0-9]{2}) ([A-F0-9]{2}) ([A-F0-9]{2}) ([A-F0-9]{2}) ([A-F0-9]{2})", response)
        if (m):
            # Current
            queries.append(query_current("LAS_GSER_ERROR1", "%d" % int(m.group(1), 16)))
            queries.append(query_current("LAS_GSER_ERROR2", "%d" % int(m.group(2), 16)))
            queries.append(query_current("LAS_GSER_ERROR3", "%d" % int(m.group(3), 16)))
            queries.append(query_current("LAS_GSER_INFO1",  "%d" % int(m.group(4), 16)))
            queries.append(query_current("LAS_GSER_INFO2",  "%d" % int(m.group(5), 16)))
            queries.append(query_current("LAS_GSER_INFO3",  "%d" % int(m.group(6), 16)))
            # History
            queries.append(query_history("LAS_GSER_ERROR1", "%d" % int(m.group(1), 16)))
            queries.append(query_history("LAS_GSER_ERROR2", "%d" % int(m.group(2), 16)))
            queries.append(query_history("LAS_GSER_ERROR3", "%d" % int(m.group(3), 16)))
            queries.append(query_history("LAS_GSER_INFO1",  "%d" % int(m.group(4), 16)))
            queries.append(query_history("LAS_GSER_INFO2",  "%d" % int(m.group(5), 16)))
            queries.append(query_history("LAS_GSER_INFO3",  "%d" % int(m.group(6), 16)))
            break
        m = re.match("^GSSD ([0-1])", response)
        if (m):
            # Current
            queries.append(query_current("LAS_D", ("ON" if int(m.group(1))&0x1 else "OFF")))
            # History
            queries.append(query_history("LAS_GSSD", ("ON" if int(m.group(1))&0x1 else "OFF")))
            break
        m = re.match("^SSSD ([0-1])", response)
        if (m):
            # Current
            queries.append(query_current("LAS_D", ("ON" if int(m.group(1))&0x1 else "OFF")))
            # History
            queries.append(query_history("LAS_SSSD", ("ON" if int(m.group(1))&0x1 else "OFF")))
            break
        m = re.match("^[dD]Pos:([0-9]+)\r\nATTEN:([0-9]+\.[0-9]+)", response)
        if (m):
            # Current
            queries.append(query_current("ATT_POS" , m.group(1)))
            queries.append(query_current("ATT_DB"  , m.group(2)))
            # History
            queries.append(query_history("ATT_POS" , m.group(1)))
            queries.append(query_history("ATT_DB"  , m.group(2)))
            break
        m = re.match("^[aA]([0-9]+(\.[0-9])?)\r\nPos:([0-9]+)", response)
        if (m):
            # Current
            queries.append(query_current("ATT_DB"  , m.group(1)))
            queries.append(query_current("ATT_POS" , m.group(3)))
            # History
            queries.append(query_history("ATT_DB"  , m.group(1)))
            queries.append(query_history("ATT_POS" , m.group(3)))
            break
        m = re.match("^C([12]):OUTP (ON|OFF),LOAD,(HZ|[0-9]+),PLRT,(NOR|INVT)", response)
        if (m):
            # Current
            queries.append(query_current("GEN_C%d_OUT"  % int(m.group(1)), m.group(2)))
            queries.append(query_current("GEN_C%d_LOAD" % int(m.group(1)), m.group(3)))
            queries.append(query_current("GEN_C%d_PLRT" % int(m.group(1)), m.group(4)))
            # History
            queries.append(query_history("GEN_C%d_OUT"  % int(m.group(1)), m.group(2)))
            queries.append(query_history("GEN_C%d_LOAD" % int(m.group(1)), m.group(3)))
            queries.append(query_history("GEN_C%d_PLRT" % int(m.group(1)), m.group(4)))
            break
        m = re.match("^C([12]):BSWV WVTP,([^\W\d]+),FRQ,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)HZ,PERI,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)S,AMP,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)V,AMPVRMS,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)Vrms,OFST,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)V,HLEV,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)V,LLEV,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)V,DUTY,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?),WIDTH,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?),RISE,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)S,FALL,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)S,DLY,((-)?[0-9]+(\.[0-9]+)?(e(-)?[0-9]+)?)", response)
        if (m):
            # Current
            queries.append(query_current("GEN_C%d_WVTP"    % int(m.group(1)), m.group(2)))
            queries.append(query_current("GEN_C%d_FRQ"     % int(m.group(1)), m.group(3)))
            queries.append(query_current("GEN_C%d_PERI"    % int(m.group(1)), m.group(8)))
            queries.append(query_current("GEN_C%d_AMP"     % int(m.group(1)), m.group(13)))
            queries.append(query_current("GEN_C%d_AMPVRMS" % int(m.group(1)), m.group(18)))
            queries.append(query_current("GEN_C%d_OFST"    % int(m.group(1)), m.group(23)))
            queries.append(query_current("GEN_C%d_HLEV"    % int(m.group(1)), m.group(28)))
            queries.append(query_current("GEN_C%d_LLEV"    % int(m.group(1)), m.group(33)))
            queries.append(query_current("GEN_C%d_DUTY"    % int(m.group(1)), m.group(38)))
            queries.append(query_current("GEN_C%d_WIDTH"   % int(m.group(1)), m.group(43)))
            queries.append(query_current("GEN_C%d_RISE"    % int(m.group(1)), m.group(48)))
            queries.append(query_current("GEN_C%d_FALL"    % int(m.group(1)), m.group(53)))
            queries.append(query_current("GEN_C%d_DLY"     % int(m.group(1)), m.group(58)))
            # History
            queries.append(query_history("GEN_C%d_WVTP"    % int(m.group(1)), m.group(2)))
            queries.append(query_history("GEN_C%d_FRQ"     % int(m.group(1)), m.group(3)))
            queries.append(query_history("GEN_C%d_PERI"    % int(m.group(1)), m.group(8)))
            queries.append(query_history("GEN_C%d_AMP"     % int(m.group(1)), m.group(13)))
            queries.append(query_history("GEN_C%d_AMPVRMS" % int(m.group(1)), m.group(18)))
            queries.append(query_history("GEN_C%d_OFST"    % int(m.group(1)), m.group(23)))
            queries.append(query_history("GEN_C%d_HLEV"    % int(m.group(1)), m.group(28)))
            queries.append(query_history("GEN_C%d_LLEV"    % int(m.group(1)), m.group(33)))
            queries.append(query_history("GEN_C%d_DUTY"    % int(m.group(1)), m.group(38)))
            queries.append(query_history("GEN_C%d_WIDTH"   % int(m.group(1)), m.group(43)))
            queries.append(query_history("GEN_C%d_RISE"    % int(m.group(1)), m.group(48)))
            queries.append(query_history("GEN_C%d_FALL"    % int(m.group(1)), m.group(53)))
            queries.append(query_history("GEN_C%d_DLY"     % int(m.group(1)), m.group(58)))
            break
        response_match = False
        break
    # Reporting not recoginized response
    if (not response_match):
        print("ERROR: Command not recognized")
    # Excuting queries
    for query in queries:
        Tools.verbose(query, level=2)
        cursor.execute(query)
    # Clossing db cursor
    db.commit()
    cursor.close()
    return True

def query_current(name, value):
    return ('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'%s\', \'%s\') ON DUPLICATE KEY UPDATE value = \'%s\', timestamp = CURRENT_TIMESTAMP;' % (name, value, value))

def query_history(name, value):
    return ('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'%s\', \'%s\');' % (name, value))

def query_temperature(diode, crystal, electronicsink, heatsink):
    return ('INSERT INTO `temperatures` (`id`, `timestamp`, `diode`, `crystal`, `electronicsink`, `heatsink`) VALUES (NULL, CURRENT_TIMESTAMP, \'%f\', \'%f\', \'%f\', \'%f\');' % (float(diode)/100, float(crystal)/100, float(electronicsink), float(heatsink)))
