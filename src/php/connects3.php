<?php
	foreach(glob('aws/*.php') as $file) {
    	include_once $file;
	}
	require_once("credential.php");
	use Aws\S3\S3Client;
	use Aws\Credentials\Credentials;
	
	 sendToCloud("https://github.com/AnHaNguyen/CS3219.git", "repos");
	 sendToCloud("a0113038@u.nus.edu", "emails");
	
	function sendToCloud($string, $type) {
		$bucket = '3219';
		$keyname = $type.'.txt';
		$filepath = '../../data/'.$type.'.txt';
		$fo = fopen($filepath,"a+");
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
		//echo ($result['ObjectURL']."<br>");
	}

?>