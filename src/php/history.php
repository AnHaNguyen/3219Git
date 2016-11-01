<?php
	require_once("util.php");
	require_once("commit.php");
	//git ls-files
	//'git blame --line-porcelain $file | sed -n \'s/^author //p\' | sort | uniq -c | sort -rn'
	// chdir('../../repos/SPA/');
	// $list = getHistoryFile('source/DesignExtractor.cpp',array(10,18));
	// echo(json_encode($list));

	function getHistoryUser($name, $date=null) {	//$date in ISO form: YYYY-MM-DD
		//redirect();
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

	function getHistoryFile($file, $range=null) {
		//redirect();
		if ($range === null) {
			//$command = "git log --reverse --pretty=format:\"%h %an %ci\" ".$file;
			//$command = "git log --reverse --date=iso --abbrev-commit --stat ".$file;
			$command = "git blame -c --date=short \"".$file."\"";
		} else {
			$command = "git blame -c --date=short -L ".$range[0].",".$range[1]." \"".$file."\"";
		}
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
			// foreach ($out as $line) {
			// 	$tokens = explode(" ", trim_all($line));
			// 	$hash = $tokens[0];				
			// 	$authorName = '';
			// 	$date = '';
			// 	for ($i = 1; $i < sizeof($tokens); $i++) {
			// 		if (DateTime::createFromFormat('Y-m-d', $tokens[$i]) === false) {
			// 			$authorName .= $tokens[$i].' ';
			// 		} else {
			// 			$date = $tokens[$i];
			// 			$authorName = trim($authorName);
			// 			break;
			// 		}
			// 	}
			// 	array_push($list, new Commit($hash, $authorName, $date));
			// }
			// return $list;
		// } else {
		// 	$command = "git log --reverse -L ".$range[0].",".$range[1].":".$file." --date=iso --abbrev-commit";
		// 	$out = array();
		// 	$hash = '';
		// 	$author = '';
		// 	$date = '';
		// 	$list = array();
		// 	exec($command,$out);
		// 	foreach($out as $line) {
		// 		$line = trim_all($line);
		// 		if (strpos($line, 'commit') !== false) {
		// 			$hash = substr($line, strpos($line, 'commit') + 7);
		// 		} else if (strpos($line, 'Author:') !== false) {
		// 			if (strpos($line, '<') !== false) {
		// 				$author = substr($line, strpos($line, 'Author:') + 8, strpos($line, ' <') - strpos($line, 'Author:') - 8);
		// 			} else {
		// 				$author = substr($line, strpos($line, 'Author:') + 8);
		// 			}
		// 		} else if (strpos($line, 'Date:') !== false) {
		// 			$date = substr($line, strpos($line, 'Date:') + 6, 10);
		// 			array_push($list, new Commit($hash, $author, $date));
		// 		}
		// 	}
		// 	return $list;
		// }
	}
?>