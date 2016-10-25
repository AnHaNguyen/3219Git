<?php
	require_once("util.php");
	require_once("commit.php");
	//session_start();

	// $list = getHistoryUser("AnHaNguyen");
	// echo(json_encode($list));
	// $list = getHistoryUser("AnHaNguyen", "2015-9-12");
	//echo(json_encode($list));
	// $list = getHistoryFile("source/DesignExtractor.cpp");
	// echo(json_encode($list));
	//$list = getHistoryFile("source/DesignExtractor.cpp", array(10,18));
	//echo(json_encode($list));

	function getHistoryUser($name, $date=null) {	//$date in ISO form: YYYY-MM-DD
		redirect();
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
		redirect();
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
			$command = "git log -L ".$range[0].",".$range[1].":".$file." --date=iso --abbrev-commit";
			$out = array();
			$hash = '';
			$author = '';
			$date = '';
			$list = array();
			exec($command,$out);
			foreach($out as $line) {
				$line = trim_all($line);
				if (strpos($line, 'commit') !== false) {
					$hash = substr($line, strpos($line, 'commit') + 7);
				} else if (strpos($line, 'Author:') !== false) {
					if (strpos($line, '<') !== false) {
						$author = substr($line, strpos($line, 'Author:') + 8, strpos($line, ' <') - strpos($line, 'Author:') - 8);
					} else {
						$author = substr($line, strpos($line, 'Author:') + 8);
					}
				} else if (strpos($line, 'Date:') !== false) {
					$date = substr($line, strpos($line, 'Date:') + 6, 10);
					array_push($list, new Commit($hash, $author, $date));
				}
			}
			return $list;
		}
	}
?>