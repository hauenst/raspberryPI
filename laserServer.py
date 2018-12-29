
import socket
import sys
from threading import Thread, Event

# Main server ==================================================================
def start_server():
    # Creating communication events to run commands
    events = device_events()
    # Start device threads
    thread_devices = start_devices(events)
    # Creating communication socket
    soc = create_socket()
    # Start server threads
    thread_management = client_management(soc, events)
    server_management(events)
    # Closing socket
    soc.shutdown(socket.SHUT_RDWR)
    soc.close()
    # Joining device threads
    for thread in thread_devices:
        thread.join()
        if thread.isAlive():
            print("Device thread alive!!")
    # Joining management thread
    thread_management.join()
    if thread_management.isAlive():
        print("Management thread alive!!")
    print("   Good Bye!")

# Create socket for connection =================================================
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
        sys.exit("Bind failed. Error : " + str(sys.exc_info()))
    # Return Socket
    return soc

# Client thread management =====================================================
def client_management(soc, events):
    # Start listening
    soc.listen(5)
    # Start client loop thread for listening
    try:
        thread = Thread(target=client_thread_loop, args=(soc, events))
        thread.start()
    except:
        sys.exit("Error creating the client thread loop: " + str(sys.exc_info()))
    return thread

def client_thread_loop(soc, events):
    while True:
        # New message arrives
        try:
            connection, address = soc.accept()
        except:
            return
        # Printing socket receiving message
        print("\n   Reading from socket " + str(address[1]))
        # Create communication thread
        try:
            thread = Thread(target=client_thread, args=(connection, address, events))
            thread.start()
            thread.join()
            # Some way to terminate
        except:
            print("  Error creating a communication thread")
            print(str(sys.exc_info()))

def client_thread(connection, address, events, max_buffer_size = 4096):
    receive_input(connection, max_buffer_size, events)
    connection.close()

def receive_input(connection, max_buffer_size, events):
    # Receiving and validating reception
    client_input = connection.recv(max_buffer_size)
    # Process message
    message = queue_add(client_input.decode("utf8").rstrip(), events)
    connection.sendall(message.encode("utf8"))

def queue_add(input_str, events):
    print("   Received: " + input_str)
    print("-> ", end = "")
    sys.stdout.flush()
    return "Command Queued"

# Server management loop =======================================================
def server_management(events):
    exit = False
    while not exit:
        message = input("-> ")
        if message == "exit":
            events["laser_end"].set()
            events["generator_end"].set()
            events["attenuator_end"].set()
            events["laser"].set()
            events["attenuator"].set()
            events["generator"].set()
            exit = True
        elif message == "":
            pass
        else:
            print("   " + message)

# Devices threads configuration =================================================
def device_events():
    events = {}
    events["laser"]          = Event()
    events["attenuator"]     = Event()
    events["generator"]      = Event()
    events["laser_end"]      = Event()
    events["generator_end"]  = Event()
    events["attenuator_end"] = Event()
    return events
    
def start_devices(triggers):
    threads = []
    try:
        threads.append(Thread(target=device_laser, args=(triggers["laser"],triggers["laser_end"])))
        threads[-1].start()
    except:
        print("Error starting the Laser device thread")
        print(str(sys.exc_info()))
    try:
        threads.append(Thread(target=device_atten, args=(triggers["attenuator"],triggers["attenuator_end"])))
        threads[-1].start()
    except:
        print("Error starting the Attenuator device thread")
        print(str(sys.exc_info()))
    try:
        threads.append(Thread(target=device_gener, args=(triggers["generator"],triggers["generator_end"])))
        threads[-1].start()
    except:
        print("Error starting the Signal generator device thread")
        print(str(sys.exc_info()))
    return threads

# Devices threads ==============================================================
def device_laser(trigger, end):
    trigger.wait()
    while not end.is_set():
        print("Laser command")
        trigger.clear()
        trigger.wait()

def device_atten(trigger, end):
    trigger.wait()
    while not end.is_set():
        print("Attenuator command")
        trigger.clear()
        trigger.wait()

def device_gener(trigger, end):
    trigger.wait()
    while not end.is_set():
        print("Generator command")
        trigger.clear()
        trigger.wait()

# Main =========================================================================
start_server()
