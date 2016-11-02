<?php
	require_once("user.php");
	require_once("util.php");
	require_once("history.php");
	// chdir('../../repos/SPA/');
	// $list = getLines();
	// echo(json_encode($list));
	
	function getAllUsers() {
		redirect();
		$out = array();
		exec("git shortlog -sn", $out);
		$users = array();

		foreach($out as $line) {
			$line = trim_all($line);
			$pos = strpos($line, " ");
			
			$num = intval(substr($line, 0, $pos));
			$word = substr($line, $pos+1);
			$user = new User($word, $num);
			$output = getInsAndDel($word);	
			$user->setTotal($output[0], $output[1]);
			array_push($users, $user);
		}
		return $users;
	}

	function getInsAndDel($u) {
		$out_ = array();
		exec("git log --author=\"".$u."\" --pretty=tformat: --numstat",$out_);
		$totalIns = 0;
		$totalDel = 0;
		foreach ($out_ as $line) {
			$line = trim_all($line);
			$tokens = explode(" ", $line);
			$totalIns += intval($tokens[0]);
			$totalDel += intval($tokens[1]);
		}
		return array($totalIns, $totalDel);
	}

	function getLines() {
		redirect();
		$out = array();
		$command1 = 'git ls-files';
		exec($command1, $out);
		$list = array();
		foreach($out as $file) {
			$history = getHistoryFile(trim_all($file));
			for ($i = 0; $i < sizeof($history); $i++) {
				$e = $history[$i];	
				$isFound = false;
				for ($j = 0; $j < sizeof($list); $j++) {
					if (strcmp($list[$j]->name,$e->getAuthor()) === 0) {
						$list[$j]->lineNum += sizeof($e->getLines());
						$isFound = true;
						break;
					}
				}
				if (!$isFound) {
					$author = new stdClass();
					$author->name = $e->getAuthor();
					if ($author->name === false) {
						echo(trim_all($file));
					}
					$author->lineNum = sizeof($e->getLines());
					array_push($list, $author);
				}				
			}
		}

		return $list;
	}
?>