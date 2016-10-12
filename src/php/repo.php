<?php
	//require_once("connects3.php");
	session_start();
	define('DIR',"../../repos/");
	
	if (!is_dir(DIR)) {
		mkdir(DIR);		
	}

	if (isset($_REQUIRE["url"])){
		$git_url = $_REQUIRE["url"];	
	} else { //testing
		$git_url = "https://github.com/AnHaNguyen/CS3219.git";	
	}
	if (strpos($git_url, '.git') === false) {
		$git_url = $git_url.".git";
	}

	$repo_name = getRepoName($git_url);
	

	chdir(DIR);
	doClone($git_url, $repo_name);
	chdir($repo_name);
	$_SESSION['git_url'] = $git_url;
	$_SESSION['repo_name'] = $repo_name;

	function doClone($git_url, $repo_name) {
		if (!is_dir($repo_name)) {
			exec("git clone ".$git_url);	
			if (!is_dir($repo_name)) {
				exit("Repository does not exist!");
			}
		}	 
	}

	function getRepoName($git_url) {
		$tokens = explode("/", $git_url);
		$repo_name = $tokens[sizeof($tokens)-1];
		$repo_name = substr($repo_name, 0, sizeof($repo_name)-5);
		return $repo_name;
	}

?>