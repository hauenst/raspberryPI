#!/usr/bin/python3

# System Imports
import socket
import sys

# Local Imports
from lib import laserTools as Tools

# ===============================================================================
# Client functions parameters ===================================================
# ===============================================================================

def client():
    # Creating socket
    soc = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    # Configuring socket
    host = '127.0.0.1'
    port = 64845
    # Connecting to server
    client_connect(soc, host, port)
    # Sending message
    client_send(soc, sys.argv[1])
    # Disconecting socket
    client_disconnect(soc)

def client_connect(soc, host, port):
    try:
        soc.connect((host, port))
    except:
        sys.exit("Error connecting to laserServer")

def client_disconnect(soc):
    try:
        soc.close()
    except:
        sys.exit("Error closing the socket")

def client_send(soc, message):
    try:
        soc.sendall(message.encode("utf8"))
        Tools.verbose("Sent: " + message)
    except:
        sys.exit("Error sending message")
    try:
        server_response = soc.recv(4096).decode("utf8").rstrip()
        Tools.verbose("Resp: " + server_response)
        print(server_response)
    except:
        sys.exit("Error receiving response")

# ===============================================================================
# Client start ==================================================================
# ===============================================================================

client()
