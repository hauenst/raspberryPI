
# System Imports
from threading import Event
from threading import Thread
import sys

# Local Imports
import laserServer_devices_laser as laser
import laserServer_devices_atten as atten
import laserServer_devices_gener as gener

# ===============================================================================
# Devices functions =============================================================
# ===============================================================================

def create_events():
    events = {}
    events["laser"] = Event()
    events["atten"] = Event()
    events["gener"] = Event()
    events["laser_end"] = Event()
    events["gener_end"] = Event()
    events["atten_end"] = Event()
    return events

def create_queues():
    queue = {}
    queue["laser"] = []
    queue["atten"] = []
    queue["gener"] = []
    return queue

def initialize():
    device = {}
    device["laser"] = laser.start()
    device["atten"] = atten.start()
    device["gener"] = gener.start()
    return device

def close(devices):
    devices["laser"].close()
    devices["atten"].close()

def create_threads(devices, queue, triggers, db):
    threads = {}
    try:
        threads["laser"] = Thread(target=laser.queue_handler, args=(devices["laser"], queue["laser"], triggers["laser"],triggers["laser_end"], db))
    except:
        sys.exit("Error starting the Laser device thread. Error: " + str(sys.exc_info()))
    try:
        threads["atten"] = Thread(target=atten.queue_handler, args=(devices["atten"], queue["atten"], triggers["atten"],triggers["atten_end"], db))
    except:
        sys.exit("Error starting the Attenuator device thread. Error: " + str(sys.exc_info()))
    try:
        threads["gener"] = Thread(target=gener.queue_handler, args=(devices["gener"], queue["gener"], triggers["gener"],triggers["gener_end"], db))
    except:
        sys.exit("Error starting the Generator device thread. Error: " + str(sys.exc_info()))
    return threads

def threads_start(devices):
    devices["laser"].start()
    devices["atten"].start()
    devices["gener"].start()

def threads_end(threads):
    for thread in threads:
        thread.join()
        if thread.isAlive():
            sys.exit("Device thread alive, forcing termination")
