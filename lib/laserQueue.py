#!/usr/bin/python3

import laserServer_config as Config

def get_queue():
    # Initilizing variables
    try:
        cursor = Config.db.cursor()
    except:
        print("ERROR: Not possible to obtain DB cursor. " + str(sys.exc_info()))
        return None
    # Retreiving table
    cursor.execute("SET time_zone = 'America/New_York';")
    cursor.execute("SELECT `id`, FROM_UNIXTIME(`timestamp`) as `timestamp`, `device`, `action`, `parameter`, `completed` FROM `queue`;")
    # Iterating over values
    new_table = ''
    while True:
        result = cursor.fetchone()
        if (not result):
            break
        new_table += result_to_row(result)
    # closing cursor
    cursor.close()
    # Adding header
    if (new_table != ''):
        new_table = '<tr>\n    <td>Id</td>\n    <td>Date</td>\n    <td>Action</td>\n</tr>\n' + new_table
    # Returning table
    return new_table

def result_to_row(result):
    completed = True if result[5] == 1 else False
    if(completed):
        row = '<tr class="completed">'
    else:
        row = '<tr>\n'
    row += '    <td class="number">'    + ("%03d" % result[0])    + '</td>\n'
    row += '    <td class="time">'      + str(result[1])          + '</td>\n'
    row += '    <td class="action">'    + str(result[2].decode()) + ':' + str(result[3].decode()) + ':' + str(result[4].decode()) + '</td>\n'
    row += '</tr>\n'
    return(row)

print(get_queue())
