<?php
	class Repo {
		var $url;
		var $repoName;	
		function __construct($url) {
			$this->url = $url;
			$this->repoName = $this->getRepoName();
		}
	
		function initialize() {
			chdir(__DIR__);		//need to go back to initial directory to get correct absolute path
			$dir = "../../repos/";
			if (!is_dir($dir)) {
				mkdir($dir);		
			}

			chdir($dir);
			$this->doClone();
			chdir($this->repoName);
			$_SESSION['dir'] = getcwd();	
			
			$_SESSION['git_url'] = $this->url;
			$_SESSION['repo_name'] = $this->repoName;
		}

		function doClone() {
			$repo_name = $this->repoName;
			$git_url = $this->url;
			if (!is_dir($repo_name)) {
				exec("git clone ".$git_url);	
				if (!is_dir($repo_name)) {
					exit("Repository does not exist!");
				}
			}	 
		}

		function getRepoName() {
			$git_url = $this->url;
			if (strpos($git_url, '.git') === false) {
				$git_url = $git_url.".git";
				$this->url = $git_url;
			}
			$tokens = explode("/", $git_url);
			$repo_name = $tokens[sizeof($tokens)-1];
			$repo_name = substr($repo_name, 0, sizeof($repo_name)-5);
			return $repo_name;
		}
	}
?>