<?php
	require_once("util.php");
	require_once("commit.php");

	// chdir('../../repos/scrapy/');
	// $list = getHistoryFile('requirements.txt');
	// echo(json_encode($list));

	function getHistoryUser($name, $date=null) {	//$date in ISO form: YYYY-MM-DD
		redirect();
		if ($date === null) {
			$command = "git log --reverse --author=\"".$name."\" --pretty=format:\"%h %ci\"";
		} else {
			$command = "git log --reverse --author=\"".$name."\" --pretty=format:\"%h %ci\" --since=".$date;
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

	function getVersionFile($file) {
		redirect();
		$command = "git blame -c --date=short \"".$file."\"";

		$out = array();
		$list = array();
		exec($command, $out);
		foreach($out as $line) {
			$tokens = explode(" ", trim_all($line));
			$hash = $tokens[0];
			$authorName = substr($tokens[1],1);
			for ($i = 2; $i < sizeof($tokens); $i++) {
				if (DateTime::createFromFormat('Y-m-d', $tokens[$i]) === false) {
					$authorName .= ' '.$tokens[$i];
				} else {
					$date = $tokens[$i];
					break;
				}
			}
			$line = intval(substr($tokens[$i+1], 0, strpos($tokens[$i+1], ")")));
			$isDup = false;
			for ($j = 0; $j < sizeof($list); $j++) {
				$e = $list[$j];
				if (strcmp($e->getHash(), $hash) === 0) {
					$isDup = true;
					$e->addLine($line);
					$list[$j] = $e;
					break;
				}
			}
			if (!$isDup) {
				$commit = new Commit($hash, $authorName, $date);
				$commit->addLine($line);
				array_push($list, $commit);
			}
		}
		usort($list, "sortCommitByDate");
		return $list;
	}

	function getHistoryFile($file, $range=null) {
		redirect();
		if ($range === null) {
			$command = "git log --reverse --pretty=format:\"%h %an %cd\" --stat --date=short \"".$file."\"";
			$out = array();
	  		$list = array();
	  		exec($command, $out);
	  		$hash = '';
	  		$authorName = '';
	  		$date = '';
	  		$c = 0;
	  		foreach($out as $line) {
	  			$line = trim_all($line);
	  			$tokens = explode(" ", $line);
				if (strlen($tokens[0]) === 7) {
					if (strcmp($hash,'') !== 0) {
						$commit = new Commit($hash, $authorName, $date);
						$commit->setTotal(0,0);
						array_push($list, $commit);
					}
					$hash = $tokens[0];
					$authorName = $tokens[1];
					for ($i = 2; $i < sizeof($tokens); $i++) {
						if (DateTime::createFromFormat('Y-m-d', $tokens[$i]) === false) {
							$authorName .= ' '.$tokens[$i];
						} else {
							$date = $tokens[$i];
							break;
						}
					}
				} else if (strpos($line, "file") !== false) {
					//$l = trim_all($out[$i+2]);
					$t = explode(" ", $line);
					$a = 0;
					$d = 0;
					for ($k = 0; $k < sizeof($t); $k++) {
						if (strpos($t[$k], "insertion") !== false) {
							$a = $t[$k-1];
						} else if (strpos($t[$k], "deletion") !== false) {
							$d = $t[$k-1];
						}
					}
					$commit = new Commit($hash, $authorName, $date);
					$commit->setTotal($a, $d);
					// echo($line."<br>");
					// echo($a." ".$d."<br>");
					array_push($list, $commit);
					$hash = '';
				}
			}
					
			// //echo(json_encode($list));
			return $list;
		}
		else {

		}
	}
?>