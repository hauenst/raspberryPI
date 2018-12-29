
# System Imports
import sys
import mysql.connector

# Local Imports
import laserServer_config as Config

# ===============================================================================
# Database interactions =========================================================
# ===============================================================================

def connect():
    try:
        db = mysql.connector.connect(user     = Config.user,
                                     password = Config.password,
                                     host     = Config.host,
                                     database = Config.database)
    except:
        sys.exit("Error connecting to Database. Error: " + str(sys.exc_info()))                           
    return db
