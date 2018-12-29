
# System Imports
import sys

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

def parse(message, db):
    print(repr(message) + "\n-> ", end = "")
