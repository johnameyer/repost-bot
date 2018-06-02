Files:
------
- build_kd.sh
	- Responsible for building the kdtree executable
- callback.php
	- Main entry point for callbacks from the GroupMe API
- composer.*
	- Handles dependencies for php
- groupme.php
	- Basic utility functions for cURLing the GroupMe API
- human.php
	- Callback point for human repost confirmations
- init.php
	- Handles initialization for a new GroupMe
- kd_tree.sh
	- #
- main.cpp
	- kdtree main execution code, compiled with the kdtree library
 
 Todos
 ====
 	- add support for tesseract ocr comparison
	- check text similarity if image similar enough
	- convert kd tree to long running process, with named pipes
	- migrate from current kd tree setup
		- use different Points supporting bitwise nature of data
		- use self balancing kd tree http://jcgt.org/published/0004/01/03/paper.pdf
    - be able to write this tree state out to a file for easy start/stop operations
 	- make some messages nicer
	- figure out thresholds - possibly a dynamic process?
