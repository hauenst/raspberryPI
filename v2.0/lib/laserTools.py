
# System Imports
import sys

# ===============================================================================
# Verbosing management ==========================================================
# ===============================================================================

verbosity = 2

def verbose(message, terminator="\n", level=1):
    if (level > verbosity):
        print(message, end = terminator)
        sys.stdout.flush()

def print_interaction(message, response):
    verbose("   Generator command")
    verbose("   " + message)
    verbose("   " + response.rstrip().replace("\r\n", "\r\n   "))
    verbose("-> ", "")
