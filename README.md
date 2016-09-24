# room-booking
A dynamic, server based webpage with PHP as the scripting language.

#Stage 1
The login and registration have been implemented.
  There has been a slight change in the font and its weight. Have to change it.
  Have to implement different redirect for different user i.e., student, faculty, ID, ESD with an if statement.
  Have to implement a database structure for handling room booking logic.
  Database and table info can be found in dblogin.sql file in the root directory.

  
#Stage 2
  No script for room booking yet.
  The database tables for handling room requests has been made.
  The column description can be found in the comments of the file room_booking.sql.


#Stage 3
  Made changes in table structure. Entire database structure can be found in localhost.sql
	Completed the PHP scripting for room booking in room_book.php
  Completed the PHP scripting for retrieval of room requests and display to the user in admin home
  Added user type column to have different data displayed
  Added a tab-styled view to display pending and approved requests
  Yet to add a way to approve requests for ID and faculty.
  Renamed adminhome.php to home.php


#Stage 4
  Made multiple visual changes.
  Added a calendar type view for the available rooms.
  Added functionality for ID to approve or cancel requests.
  Added functionality for ESD to recieve requests when audio-visual equipment is requested.
  Added the extra rooms with all the information about capacity. Updated database can be found in instr_div.sql.
	Yet to add room info to the room booking page, i.e., the capacity, availability of facilities, etc.
  Have to deal with a few UI element problems like, Extra Whitespaces, Text not in line, etc.

#Stage 5
  Made changes to the logic followed in room booking, it is now a two page process.
  The room report generation is still incomplete
  Need to add another table for handling classroom timetable and plan on a db structure to handle repeated events.

	





