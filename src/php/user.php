<?php
	class User {
		var $name;
		var $commit_num;
		var $totalIns;
		var $totalDel;

		function __construct($persons_name, $commit_num) {		
			$this->name = utf8_encode($persons_name);		//encode username to be able to run json_encode
			$this->name = str_replace(array("'", "\"", "&quot;"), "", $this->name);	//escape quotation so the json can be parsed correctly
			$this->commit_num = $commit_num;		
		}

		function getName(){
			return $this->name;
		}

		function getCommitNum() {
			return $this->commit_num;
		}

		function setTotal($ins,$del) {
			$this->totalIns = $ins;
			$this->totalDel = $del;
		}

		function getIns() {
			return $this->totalIns;
		}

		function getDel() {
			return $this->totalDel;
		}
	}
?>