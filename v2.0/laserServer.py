#!/usr/bin/python3

# Local Imports
import laserServer_devices  as Devices
import laserServer_main     as Main
import laserServer_database as Database

# ===========================================================================
# Setting up database communication =========================================
# ===========================================================================
db = Database.connect()

# ==========================================================================
# Setting up devices =======================================================
# ==========================================================================
# Initialize devices communication
devices = Devices.initialize()
# Creating threads
events = Devices.create_events()
queues = Devices.create_queues()
devices_threads = Devices.create_threads(devices, queues, events, db)
# Devices threads start
Devices.threads_start(devices_threads)

# ===========================================================================
# Setting up server communication ===========================================
# ===========================================================================
soc = Main.create_socket()

# ===========================================================================
# Setting up client management ==============================================
# ===========================================================================
client_management = Main.client_management(soc, events, queues)

# ===========================================================================
# Setting up server management ==============================================
# ===========================================================================
Main.server_management(events)

# ===========================================================================
# Termination procedures ====================================================
# ===========================================================================
# Closing socket
Main.terminate_socket(soc)
# Closing devices
Devices.close(devices)

# ===========================================================================
# Termination verbosing= ====================================================
# ===========================================================================
print("   Good Bye!")
