<?php
	require_once("user.php");
	require_once("util.php");
	session_start();

	// $users = getAllUsers();
	// echo(json_encode($users));
	
	function getAllUsers() {
		redirect();
		$out = array();
		exec("git shortlog -sn", $out);
		$tokens = explode(" ", $out[0]);
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
?>