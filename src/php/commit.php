<?php
	class Commit {
		var $hash;
		var $author;
		var $date;
		
		function __construct($hash, $author, $date) {
			$this->hash = $hash;
			$this->author = $author;
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
	}
?>