
# System Imports
import sys

# ===============================================================================
# Verbosing management ==========================================================
# ===============================================================================

def verbose(message, terminator="\n"):
    if (False):
        print(message, end = terminator)
        sys.stdout.flush()

def print_interaction(message, response):
    verbose("   Generator command")
    verbose("   " + message)
    verbose("   " + response.rstrip().replace("\r\n", "\r\n   "))
    verbose("-> ", "")
