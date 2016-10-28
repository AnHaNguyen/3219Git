<?php
	class Email {
		var $lastSent;
		var $address;
		function __construct($lastsent, $add) {
			$this->lastSent = $lastsent;
			$this->address = $add;
		}

		function getLastSent() {
			return $this->lastSent;
		}

		function getAddress() {
			return $this->address;
		}
	}
?>