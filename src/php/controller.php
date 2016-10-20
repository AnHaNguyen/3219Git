<?php
	require_once('repo.php');
	require_once('contributors.php');
	require_once('history.php');
	require_once('connects3.php');
	ini_set('max_execution_time', 300);	//give 5 mins to clone a repo
	session_start();
	$command = $_GET["command"];
	switch (strtolower($command)) {
		case 'addrepo':
			if (isset($_GET["repo"])){
				$git_url = $_GET["repo"];	
			} else { //testing
				//$git_url = "https://github.com/AnHaNguyen/SPA";	
				exit("Please include a repo url");
			}
			$repo = new Repo($git_url);
			$repo->initialize();
			sendToCloud($git_url, "repos");
			echo("success");
			break;
		case 'addemail':
			if (isset($_GET["email"])) {
				$email = $_GET["email"];
			} else {	//testing
				exit("Please include an email address");
				//$email = "a0113038@u.nus.edu";
			}
			sendToCloud($email, "emails");
			echo("success");
			break;
		case 'getcontributors':
			$list = getAllUsers();
			echo(json_encode($list));
			break;
		case 'getcommithistory':
			if (isset($_GET["user"])) {
				$user = $_GET["user"];
			} else {	//testing
			//	$user = "AnHaNguyen";
				exit("Please include a username");
			}
			if (isset($_GET["start"])) {
				$date = $_GET["start"];
			} else {
				$date = null;
			}
			$list = getHistoryUser($user, $date);
			echo(json_encode($list));
			break;
		case 'getfilehistory':
			if (isset($_GET["file"])) {
				$file = $_GET["file"];
			} else {	//testing
				exit("Please include a file to view history");
			//	$file = "source/DesignExtractor.cpp";
			}
			if (isset($_GET["s"])) {
				$s = $_GET["s"];
			} else { $s = '';}
			if (isset($_GET["e"])) {
				$e = $_GET["e"];
			} else { $e = '';}
			$list = getHistoryFile($file, [$s,$e]);
			echo(json_encode($list));
			break;
		default:
			exit("Unregconized command");
			break;
	}
?>