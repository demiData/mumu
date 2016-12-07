<?php 
require 'require/sharedVar.php';
require 'require/functions.php';
require 'require/connection.php';
require 'require/error_reporting.php';

      // Check to see if user has posted the information
    if (isset($_POST['update']) && trim($_POST['update']) != '') {
	if (isset($_POST['old_username']) && isset($_POST['old_password']) 
		&& trim($_POST['old_username']) != '' && trim($_POST['old_password']) != '') {

		// Save and check old username and password
		$old_username = escape_quotes($_POST['old_username']);
		$old_password = escape_quotes(hash("sha512", $_POST['old_password']));

		$user = get_all_info("SELECT * FROM users WHERE Username='$old_username'");

		// Get the first instance of the user and store it into an array
		$userArray = $user->fetch_assoc();

         if(count($userArray) <= 0) {
			die("That username doesn't exist! Please type in the correct username. 
				<a href='update.php'>Back</a>");
		}
		if ($userArray['password'] != $old_password) {
			die("Incorrect password! <a href='update.php'>Back</a>");
		}


		$new_name = '';
		if ($_POST['new_name']) {
		
			// Get the existing name if users input the name
			$old_name = $userArray['name'];
			$new_name = escape_quotes(strip_tags($_POST['new_name']));
			insert_or_update_info("UPDATE users SET name='$new_name'
		 		WHERE name='$old_name'");
		 	echo "<h2>Name has been updated. Please <a href='login.php'>log in</a> with your new credentials. </h2><br>";	
		} else {
			echo "<h2>Since no Name was given, Name is still " . $userArray['name'] . "</h2><br>";
		}
	
		// Check new username if user put it
		if (trim($_POST['new_username']) != '' && isset($_POST['new_username']) ) {
			$new_username = escape_quotes(strip_tags($_POST['new_username']));
			$check = get_all_info("SELECT * FROM users WHERE Username='$new_username'");
			// Get the first instance of the user and store it into an array
			$userArray = $check->fetch_assoc();
			if (count($userArray) > 0) {
				die("<h2>That username already exists! Try creating another username. 
					<a href='register.php'>Back</a></h2>");
			}
			if (!ctype_alnum($new_username)) {
				die("<h2>Username contains special characters! Only numbers and letters 
					are permitted. <a href='update.php'>Back</a></h2>" );
			}
			if (strlen($new_username) > 20) {
				die("<h2>Username must contain less than 20 characters. 
					<a href='update.php'>Back</a></h2>" );
			}
			insert_or_update_info("UPDATE users SET Username='$new_username'
		 		WHERE Username='$old_username'");
			echo "<h2>Username has been updated. Please <a href='login.php'>log in</a> with your new credentials. </h2><br>";	
		} else {
			echo "<h2>Since no Username was given, Username is still " . $userArray['Username'] . "</h2><br>";
		}
		
		// Check new password
		if (trim($_POST['new_password']) != '' && isset($_POST['new_password'])) {
			$new_password = escape_quotes(hash("sha512", $_POST['new_password']));
			insert_or_update_info("UPDATE users SET Password='$new_password'
		 		WHERE Password='$old_password'");
			echo "<h2>Password has been updated. Please <a href='login.php'>log in</a> with your new credentials. <h2><br>";	
		} else {
			echo "<h2>Since no Password was given, Password remains the same. </h2><br>";
		}
	}
	else {
		echo "<h2>Please enter a username and password.</h2>";
	}
}



?>
<!doctype html>
<html>
	<head>
		<title>
			Home
			:: <?php $title ?>
			:: <?php $tagLine ?>
		</title>
		<link rel="stylesheet" href="_css/styles.css">
	</head>
	<body>
		<div id="container">
			<?php include "includes/header.php" ?>
			<?php include "nav.php" ?>
		<h1>Update Information</h1>
		<div>
				<?php 
					require_once 'require/cookie_login.php';
					
					if ($logged == true) {
					    echo $userArray['username'] . " is logged in";
					} else {
					    echo "User not logged in";
					}
					?>
			</div>
		<form method="post" action="">
			<ul>
				<li>
					<label for="old_username">Enter Existing Username</label>
					<input id="old_username" type="text" name="old_username" value="" />
				</li>
				<li>
					<label for="old_password">Enter Existing Password</label>
					<input id="old_password" type="password" name="old_password" value="" />
				</li>
				<li>
					<label for="new_username">Enter New Username</label>
					<input id="new_username" type="text" name="new_username" value="" />
				</li>
				<li>
					<label for="new_password">Enter New Password</label>
					<input id="new_password" type="password" name="new_password" value=""/>
				<li>
				<li>
					<label for="new_name">Enter New Name</label>
					<input id="new_name" type="text" name="new_name" value=""/>
				<li>
					<input type="submit" name="update" value="update">
				</li>
			</ul>
		</form>
			<?php include 'includes/footer.php' ?>
		</div>
	</body>
</html>