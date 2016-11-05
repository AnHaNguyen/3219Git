<?php
	require_once('repo.php');
	require_once('contributors.php');
	require_once('history.php');
	//require_once('connects3.php');
	ini_set('max_execution_time', 300);	//give 5 mins to clone a repo

	function execute($command, $repo=null, $email=null,$user=null, $start=null,$file=null,$s='', $e ='') {
		switch (strtolower($command)) {
			case 'addrepo':
				if ($repo===null){
					exit("Please include a repo url");
				}
				$repoObj = new Repo($repo);

				$repoObj->initialize();
				//sendToCloud($repo, "repos");
				return("success");
				break;
			case 'addemail':
				if ($email === null) {
					exit("Please include an email address");
				}
				//sendToCloud($email, "emails");
				return("success");
				break;
			case 'getcontributors':
				$list = getAllUsers();
				return($list);
				break;
			case 'getcommithistory':
				if ($user === null) {	
					exit("Please include a username");
				}
				$list = getHistoryUser($user, $start);
				return($list);
				break;
			case 'getfilehistory':
				if ($file===null) {
					exit("Please include a file to view history");
				}
				$list = getHistoryFile($file, [$s,$e]);
				return($list);
				break;
			case 'getlines':
				return getLines();
				break;
			default:
				exit("Unregconized command");
				break;
		}
	}
?>