
# System Imports
from threading import Thread
import socket
import sys

# Local Imports
import laserTools          as Tools
import laserServer_message as Message

# ===============================================================================
# Socket management =============================================================
# ===============================================================================

def create_socket():
    # Socket parameters
    host = '127.0.0.1'
    port = 64845
    # Socket creation
    soc = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    soc.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    # Bind Socket
    try:
        soc.bind((host, port))
    except:
        sys.exit("Bind failed. Error: " + str(sys.exc_info()))
    # Return Socket
    return soc

def terminate_socket(soc):
    soc.shutdown(socket.SHUT_RDWR)
    soc.close()

# ===============================================================================
# Server management =============================================================
# ===============================================================================

def server_management(events):
    exit = False
    while not exit:
        message = input("-> ")
        if message == "":
            pass
        elif message == "exit":
            events["laser_end"].set()
            events["gener_end"].set()
            events["atten_end"].set()
            events["laser"].set()
            events["atten"].set()
            events["gener"].set()
            exit = True
        else:
            print("   " + message)

# ===============================================================================
# Client management =============================================================
# ===============================================================================

def client_management(soc, events, queue):
    # Start listening
    soc.listen(5)
    # Start client loop thread for listening
    try:
        thread = Thread(target=client_listening_loop, args=(soc, events, queue))
        thread.start()
    except:
        sys.exit("Error creating the client thread loop: " + str(sys.exc_info()))
    return thread

def client_listening_loop(soc, events, queue):
    while True:
        # New message arrives
        try:
            connection, address = soc.accept()
        except:
            return
        # Printing socket receiving message
        Tools.verbose("\n   Reading from socket " + str(address[1]))
        # Processing result
        receive_input(connection, events, queue)
        connection.close()

def receive_input(connection, events, queue, max_buffer_size = 4096):
    # Receiving and validating reception
    client_input = connection.recv(max_buffer_size)
    # Process message
    message = Message.process(client_input.decode("utf8").rstrip(), events, queue)
    connection.sendall(message.encode("utf8"))
