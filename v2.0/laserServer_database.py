
# System Imports
import sys
import mysql.connector
from mysql.connector import errorcode

# Local Imports
import laserServer_config as Config

# ===============================================================================
# Database interactions =========================================================
# ===============================================================================

def connect():
    try:
        db = mysql.connector.connect(**Config.db)
    except mysql.connector.Error as err:
        if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
            sys.exit("Something is wrong with your user name or password")
        elif err.errno == errorcode.ER_BAD_DB_ERROR:
            sys.exit("Database does not exist")
        else:
            sys.exit(err)                        
    return db
