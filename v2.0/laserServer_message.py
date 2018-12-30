
# System Imports
import sys
import re

# Local Imports
import laserTools as Tools

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
        response = "LAS: Laser command queued"
        events["laser"].set()
    elif (device == "ATT"):
        # Send to attenuator
        response = "ATT: Attenuator command queued"
        queue["atten"].append(tosend)
        events["atten"].set()
    elif (device == "GEN"):
        # Send to generator
        response = "GEN: Generator command queued"
        queue["gener"].append(tosend)
        events["gener"].set()
    else:
        response = "Command NOT Queued. Device \"" + device + "\" NOT recognized"
    sys.stdout.flush()
    return response

def parse(command, response, db):
    print(repr(response) + "\n-> ", end = "")
    # Initilizing variables
    cursor = db.cursor()
    queries = []
    # Storing command
    queries.append('INSERT INTO `commands` (`id`, `timestamp`, `command`) VALUES (NULL, CURRENT_TIMESTAMP, \'%s\');' % command)
    # matching regular expressions
    while True:
        m = re.match("^GEMT ([0-9]{5}) ([0-9]{2}) ([0-9]{5}) ([0-9]{2})", response)
        if (m):
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GEMT_SUPPLY\', \'%d:%d\') ON DUPLICATE KEY UPDATE value = \'%d:%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(1)), int(m.group(2)), int(m.group(1)), int(m.group(2))))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GEMT_EMITING\', \'%d:%d\') ON DUPLICATE KEY UPDATE value = \'%d:%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(3)), int(m.group(4)), int(m.group(3)), int(m.group(4)))) 
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GEMT_SUPPLY\', \'%d:%d\');' % (int(m.group(1)), int(m.group(2))))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GEMT_EMITTING\', \'%d:%d\');' % (int(m.group(3)), int(m.group(4))))
            break
        m = re.match("^GMTE ([0-9]{4}) ([0-9]{4}) ([0-9]{2}) ([0-9]{2})", response)
        if (m):
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GMTE_DIODE\', \'%.2f\') ON DUPLICATE KEY UPDATE value = \'%.2f\', timestamp = CURRENT_TIMESTAMP;' % (float(m.group(1))/100, float(m.group(1))/100))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GMTE_CRYSTAL\', \'%.2f\') ON DUPLICATE KEY UPDATE value = \'%.2f\', timestamp = CURRENT_TIMESTAMP;' % (float(m.group(2))/100, float(m.group(2))/100))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GMTE_ELECTRONICSINK\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(3)), int(m.group(3))))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GMTE_HEATSINK\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(4)), int(m.group(4))))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GMTE_DIODE\', \'%.2f\');' % (float(m.group(1))/100))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GMTE_CRYSTAL\', \'%.2f\');' % (float(m.group(2))/100))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GMTE_ELECTRONICSINK\', \'%d\');' % (int(m.group(3))))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GMTE_HEATSINK\', \'%d\');' % (int(m.group(4))))
            break
        m = re.match("^GTCO ([0-9])", response)
        if (m):
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_TEC1\', \'%s\') ON DUPLICATE KEY UPDATE value = \'%s\', timestamp = CURRENT_TIMESTAMP;' % (("ON" if int(m.group(1))&0x1 else "OFF"), ("ON" if int(m.group(1))&0x1 else "OFF")))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_TEC2\', \'%s\') ON DUPLICATE KEY UPDATE value = \'%s\', timestamp = CURRENT_TIMESTAMP;' % (("ON" if int(m.group(1))&0x1 else "OFF"), ("ON" if int(m.group(1))&0x2 else "OFF")))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GTCO_TEC1\', \'%s\');' % ("ON" if int(m.group(1))&0x1 else "OFF"))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GTCO_TEC2\', \'%s\');' % ("ON" if int(m.group(1))&0x2 else "OFF"))
            break
        m = re.match("^GSER ([A-F0-9]{2}) ([A-F0-9]{2}) ([A-F0-9]{2}) ([A-F0-9]{2}) ([A-F0-9]{2}) ([A-F0-9]{2})", response)
        if (m):
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GSER_ERROR1\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(1), 16), int(m.group(1), 16)))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GSER_ERROR2\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(2), 16), int(m.group(2), 16)))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GSER_ERROR3\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(3), 16), int(m.group(3), 16)))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GSER_INFO1\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(4), 16), int(m.group(4), 16)))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GSER_INFO2\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(5), 16), int(m.group(5), 16)))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_GSER_INFO3\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(6), 16), int(m.group(6), 16)))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GSER_ERROR1\', \'%d\');' % (int(m.group(1), 16)))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GSER_ERROR2\', \'%d\');' % (int(m.group(2), 16)))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GSER_ERROR3\', \'%d\');' % (int(m.group(3), 16)))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GSER_INFO1\', \'%d\');' % (int(m.group(4), 16)))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GSER_INFO2\', \'%d\');' % (int(m.group(5), 16)))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GSER_INFO3\', \'%d\');' % (int(m.group(6), 16)))
            break
        m = re.match("^GSSD ([0-1])", response)
        if (m):
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_D\', \'%s\') ON DUPLICATE KEY UPDATE value = \'%s\', timestamp = CURRENT_TIMESTAMP;' % (("ON" if int(m.group(1))&0x1 else "OFF"), ("ON" if int(m.group(1))&0x1 else "OFF")))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_GSSD\', \'%s\');' % ("ON" if int(m.group(1))&0x1 else "OFF"))
            break
        m = re.match("^SSSD ([0-1])", response)
        if (m):
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'LAS_D\', \'%s\') ON DUPLICATE KEY UPDATE value = \'%s\', timestamp = CURRENT_TIMESTAMP;' % (("ON" if int(m.group(1))&0x1 else "OFF"), ("ON" if int(m.group(1))&0x1 else "OFF")))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'LAS_SSSD\', \'%s\');' % ("ON" if int(m.group(1))&0x1 else "OFF"))
            break
        m = re.match("^[dD]Pos:([0-9]+)\r\nATTEN:([0-9]+\.[0-9]+)", response)
        if (m):
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'ATT_POS\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(1)), int(m.group(1))))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'ATT_DB\', \'%.2f\') ON DUPLICATE KEY UPDATE value = \'%.2f\', timestamp = CURRENT_TIMESTAMP;' % (float(m.group(2)), float(m.group(2))))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'ATT_D_POS\', \'%d\');' % (int(m.group(1))))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'ATT_D_DB\', \'%.2f\');' % (float(m.group(2))))
            break
        m = re.match("^A([0-9]+(\.[0-9])?)\r\nPos:([0-9]+)", response)
        if (m):
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'ATT_DB\', \'%.2f\') ON DUPLICATE KEY UPDATE value = \'%.2f\', timestamp = CURRENT_TIMESTAMP;' % (float(m.group(1)), float(m.group(1))))
            queries.append('INSERT INTO `current` (`timestamp`, `name`, `value`) VALUES (CURRENT_TIMESTAMP, \'ATT_POS\', \'%d\') ON DUPLICATE KEY UPDATE value = \'%d\', timestamp = CURRENT_TIMESTAMP;' % (int(m.group(3)), int(m.group(3))))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'ATT_D_DB\', \'%.2f\');' % (float(m.group(1))))
            queries.append('INSERT INTO `history` (`id`, `timestamp`, `name`, `value`) VALUES (NULL, CURRENT_TIMESTAMP, \'ATT_D_POS\', \'%d\');' % (int(m.group(3))))
            break
        break
    # Updating database
    if (len(queries) < 2):
        print("ERROR: Command not recognized")
    else:
        for query in queries:
            cursor.execute(query)
        db.commit()
    # Cleaning
    cursor.close()
