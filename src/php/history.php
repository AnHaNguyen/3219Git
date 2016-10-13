<?php
	require_once("util.php");
	require_once("commit.php");
	session_start();
	if (isset($_SESSION['git_url'])){
		$url = $_SESSION['git_url'];
		$repo = $_SESSION['repo_name'];
	} else {//testing
		$url = "https://github.com/AnHaNguyen/SPA.git";
		$repo = "SPA";
	}
	chdir("../../repos/".$repo);

	// $list = getHistoryUser("AnHaNguyen");
	// echo(json_encode($list));
	// $list = getHistoryUser("AnHaNguyen", "2015-9-12");
	//echo(json_encode($list));
	// $list = getHistoryFile("source/DesignExtractor.cpp");
	// echo(json_encode($list));

	function getHistoryUser($name, $date=null) {	//$date in ISO form: YYYY-MM-DD
		if ($date === null) {
			$command = "git log --author=\"".$name."\" --pretty=format:\"%h %ci\"";
		} else {
			$command = "git log --author=\"".$name."\" --pretty=format:\"%h %ci\" --since=".$date;
		}
		$out = array();
		$list = array();
		exec($command,$out);
		foreach ($out as $line) {
			$tokens = explode(" ", trim_all($line));
			$hash = $tokens[0];
			$date = $tokens[1];
			array_push($list, new Commit($hash, $name, $date));	
		}
		return $list;
	}

	function getHistoryFile($file, $range=null) {
		if ($range === null) {
			$command = "git log --pretty=format:\"%h %an %ci\" ".$file;
			$out = array();
			$list = array();
			exec($command, $out);
			foreach ($out as $line) {
				$tokens = explode(" ", trim_all($line));
				$hash = $tokens[0];				
				$authorName = '';
				$date = '';
				for ($i = 1; $i < sizeof($tokens); $i++) {
					if (DateTime::createFromFormat('Y-m-d', $tokens[$i]) === false) {
						$authorName .= $tokens[$i].' ';
					} else {
						$date = $tokens[$i];
						$authorName = trim($authorName);
						break;
					}
				}
				array_push($list, new Commit($hash, $authorName, $date));
			}
			return $list;
		} else {
			//TODO: show commit history for a chunk of lines in a file
		}
	}
?>