#!/usr/bin/python3

import socket
import sys

def client():

    # Set connection parameters
    soc = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    host = '127.0.0.1'
    port = 64845
    client_connect(soc, host, port)
    client_send(soc, sys.argv[1])
    client_disconnect(soc)
    sys.exit()

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
        print("Sending : " + message)
    except:
        sys.exit("Error sending message")
    try:
        server_response = soc.recv(4096).decode("utf8")
        print("Received: " + server_response.rstrip())
    except:
        sys.exit("Error receiving response")

# Main ============================================================================
client()
