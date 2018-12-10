
import socket
import sys
from threading import Thread

# Main server ==================================================================
def start_server():
    # Creating communication socket
    socket = create_socket()
    # Start server threads
    client_management(socket)
    server_management()
    # Closing socket
    socket.close()

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
def client_management(soc):
    # Start listening
    soc.listen(5)
    # Start client loop thread for listening
    try:
        Thread(target=client_thread_loop, args=(soc,)).start()
    except:
        sys.exit("Error creating the client thread loop: " + str(sys.exc_info()))

def client_thread_loop(soc):
    while True:
        # New message arrives
        try:
            connection, address = soc.accept()
        except ConnectionAbortedError:
            return
        print("\n   Reading from socket " + str(address[1]))
        # Create communication thread
        try:
            Thread(target=client_thread, args=(connection, address)).start()
            # Some way to terminate
        except:
            print("  Error creating a communication thread")
            print(str(sys.exc_info()))

def client_thread(connection, address, max_buffer_size = 4096):
    receive_input(connection, max_buffer_size)
    connection.close()

def receive_input(connection, max_buffer_size):
    # Receiving and validating reception
    client_input = connection.recv(max_buffer_size)
    client_input_size = sys.getsizeof(client_input)
    if client_input_size > max_buffer_size:
        sys.exit("Message received larger than expected")
    # Process message
    queue_add(client_input.decode("utf8").rstrip())

def queue_add(input_str):
    print("   Received: " + input_str)
    print("-> ", end ="")
    sys.stdout.flush()

# Server management loop =======================================================
def server_management():
    exit = False
    while not exit:
        message = input("-> ")
        if message == "exit":
            exit = True
        elif message == "":
            pass
        else:
            print("   " + message)

# Main =========================================================================
start_server()

