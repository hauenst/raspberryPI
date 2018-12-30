
# System Imports
import sys

# Local Imports
from lib import laserServer_config as Config

# ===============================================================================
# Verbosing management ==========================================================
# ===============================================================================

def verbose(message, terminator="\n", level=1):
    if (level > Config.verbosity):
        print(message, end = terminator)
        sys.stdout.flush()

def print_interaction(message, response):
    verbose("   Generator command")
    verbose("   " + message)
    verbose("   " + response.rstrip().replace("\r\n", "\r\n   "))
    verbose("-> ", "")
