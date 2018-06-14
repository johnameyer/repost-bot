Files:
------
- build_kd.sh
	- Responsible for building the kdtree executable
- callback.php
	- Entry point for callbacks from the GroupMe API for the main group
- composer.*
	- Handles dependencies for php
- groupme.php
	- Basic utility functions for cURLing the GroupMe API
- human.php
	- Entry point for callbacks from the GroupMe API for the repost checking group
- init.php
	- Handles initialization for a new GroupMe
- kd_tree.sh
	- Handles interactions with the kdtree executable including startup and timeout
- kd_tree_setup.sh
	- Setups the tmp/ input, output, and store files and starts the kdtree program
- kd_tree_sleep.sh
	- Cleans up the environment at the end of the timeout by killing kdtree and removing files
- main.cpp
	- kdtree main execution code, compiled with the kdtree library
 
Expected Execution Flow:
========================
- callback.php
	- First, a post comes in from the groupme to the ./callback.php endpoint. This request also comes with a json payload of the message.
	- The php file then checks if there is an image attached and if so, execution continues by hashing the image.
	- kd_tree.sh
		- Once hashed, the id and the hash are passed to the ./kd_tree.sh script, which notes that there is no kdtree executable currently running.
		- kd_tree_setup.sh
			- As such, it hands off the current file configuration to the setup script, which setups the pipe and starts the executable with the created files and returns.
		- Once it has done this, it writes out a csv row of id,hash to the input pipe
		- This input is picked up by the kdtree executable, which then writes out the id,similar_id row to the output
		- The kd_tree.sh script waits for the id it was passed to appear in the ouput and then cuts to get the similar id, which is then echo-returned
		- Before exiting, the timeout script (./kd_tree_sleep.sh) is placed into the background
	- Once the most-similar-id is returned, the php file continues by grabbing the message and then hashes that image as well.
	- The distance between the two hashes is then compared, and if entirely similar a message is sent to the groupme.
	- If the distance is not zero, but is less than some predefined threshold, a message is then sent to an admin chat that asks for user input in discerning the two images while also saving the two images' post json.
- human.php
	- Once a user in the admin chat responds, another payload is sent to this endpoint.
	- Upon receiving, the chat does a check to see if it is a bot and then if the message was an affirmativve.
	- If affirmative, the chat then calls out the user in the chat before deleting the images' post json.

- kdtree
	- The executable first loads all the hashes from the hash store csv.
	- Upon receiving an id,hash tuple from the pipe, the program first seaches the constructed kdtree for the nearest hash.
	- Upon finding the closest, it then does a linear search over the mid-execution hashes (as the kdtree does not allow for additions after the tree is constructed.
	- After finishing the search, it appends the id,similar-id tuple to the output file

 Todos
 ====
	- message templates
 	- add support for tesseract ocr comparison
	- check text similarity if image similar enough
	- migrate from current kd tree setup
		- use different Points supporting bitwise nature of data
		- use self balancing kd tree http://jcgt.org/published/0004/01/03/paper.pdf
    - be able to write this tree state out to a file for easy start/stop operations
 	- make some messages nicer
	- figure out thresholds - possibly a dynamic process?
