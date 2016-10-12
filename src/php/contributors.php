<?php
	require_once("user.php");
	session_start();
	if (isset($_SESSION['git_url'])){
		$url = $_SESSION['git_url'];
		$repo = $_SESSION['repo_name'];
	} else {//testing
		$url = "https://github.com/AnHaNguyen/SPA.git";
		$repo = "SPA";
	}
	chdir("../../repos/".$repo);

	$users = getAllUsers();
	echo(json_encode($users));
	
	function getAllUsers() {
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

	function trim_all( $str , $what = NULL , $with = ' ' ){
    	if( $what === NULL ) {
        //  Character      Decimal      Use
        //  "\0"            0           Null Character
        //  "\t"            9           Tab
        //  "\n"           10           New line
        //  "\x0B"         11           Vertical Tab
        //  "\r"           13           New Line in Mac
        //  " "            32           Space
       
    	    $what   = "\\x00-\\x20";    //all white-spaces and control chars
    	}
   
    	return trim(preg_replace( "/[".$what."]+/" , $with , $str));
	}
?>