<?php
	foreach(glob('php/aws/*.php') as $file) {
    	include_once $file;
	}
	require_once("credential.php");
	require_once("email.php");
	use Aws\S3\S3Client;
	use Aws\Credentials\Credentials;
	
	 // sendToCloud("https://github.com/AnHaNguyen/CS3219.git", "repos");
	 // sendToCloud("a0113038@u.nus.edu", "emails");
	
	function sendToCloud($string, $type) {
		$bucket = '3219';
		
		if (!is_dir('../../data/')) {
			mkdir('../../data/');
		}
		switch($type) {
			case 'repos':
				$keyname = 'repos.txt';
				$filepath = '../../data/repos.txt';
				$fo = fopen($filepath, "a+");
				$dup = false;
				while(($line = fgets($fo)) !== false) {
					if (strcmp(rtrim($line, "\r\n"), $string) == 0) {
					$dup = true;
					}
				}
				if (!$dup) {
					fwrite($fo, $string."\n");
				}
				fclose($fo);
				break;
			case 'emails':
				$keyname = 'emails.json';
				$filepath = '../../data/emails.json';
				$time = date("d-M-Y H:i");
				$email = new Email($time, $string);
				if (($dataStr = file_get_contents($filepath)) !== false) {
					$data = json_decode($dataStr, true);
					for ($i = 0; $i < sizeof($data); $i++) {
						$cur = $data[$i];
						if (strcasecmp($cur["address"], $string) === 0) {
							array_splice($data, $i, 1);
							break;
						}
					}
				} else {
					$data = array();
				}
				array_push($data, $email);
				$fo = fopen($filepath, "w");
				fwrite($fo, json_encode($data));
				fclose($fo);
				break;
			default:
				exit("Unrecognized send-to-Cloud type!");	
		}
		
		$s3 = S3Client::factory([
			'version'		=> 'latest',
			'region'		=> 'ap-southeast-1',
			'credentials' => new Credentials(KEY, SECRET),
		]);
		$result = $s3->putObject(array(
			'Bucket' 		=> $bucket,
			'Key' 			=> $keyname,
			'SourceFile' 	=> $filepath,
			'ContentType' 	=> 'text/plain',
			'ACL' 			=> 'public-read',
		));
	}

?>