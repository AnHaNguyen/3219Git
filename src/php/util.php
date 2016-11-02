<?php
    require_once('commit.php');
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

    function redirect() {
        if (isset($_SESSION['git_url'])){
            $url = $_SESSION['git_url'];
            $repo = $_SESSION['repo_name'];
        } else {//testing
            exit("Session does not exist");
        }
        if (isset($_SESSION['dir'])) {
            chdir($_SESSION['dir']);
        }
    }

    function sortCommitByDate($commit1, $commit2) {
        $date1 = new DateTime($commit1->getDate());
        $date2 = new DateTime($commit2->getDate());
        if ($date1 < $date2) {
            return -1;
        } else if ($date1 > $date2) {
            return 1;
        } else {
            return 0;
        }
    }
?>