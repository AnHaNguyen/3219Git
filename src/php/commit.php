<?php
	class Commit {
		var $hash;
		var $author;
		var $date;
		var $lines = array();
		var $totalIns;
		var $totalDel;

		function __construct($hash, $author, $date) {
			$this->hash = $hash;
			$this->author = utf8_encode($author); //encode username to be able to run json_encode
			$this->author = str_replace(array("'", "\"", "&quot;"), "", $this->author);	//escape quotation so the json can be parsed correctly
			$this->date = $date;
		}

		function getHash() {
			return $this->hash;
		}

		function getAuthor() {
			return $this->author;
		}

		function getDate() {
			return $this->date;
		}

		function addLine($line) {
			array_push($this->lines, $line);
		}

		function getLines() {
			return $this->lines;
		}

		function setTotal($totalIns, $totalDel) {
			$this->totalIns = $totalIns;
			$this->totalDel = $totalDel;
		}

		function getTotalIns() {
			return $this->totalIns;
		}
		
		function getTotalDel() {
			return $this->totalDel;
		}
	}
?>