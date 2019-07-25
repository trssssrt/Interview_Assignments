<!DOCTYPE html>
<html>
<head>
<title>Hello, World! Page</title>
</head>
<body>
	<?php
		// A better approach to calulating the next prime would be to use gmp_nextprime
		// Instead, we calculate the primes and then use them
		function isPrime($number) {
			//1 is not prime.
			if($number == 1) {
				return false;
			}
			//2 is prime (the only even number that is prime)
			if($number == 2) {
				return true;
			}

			/**
			 * if the number is divisible by two, then it's not prime and it's no longer
			 * needed to check other even numbers
			 */
			if($number % 2 == 0) {
				return false;
			}

			$n = abs($number);
			$i = 2;

			/**
			 * Elementary abstract algebra says that sqrt($num)
			 *  is the highest value we have to check. Since we already
			 *  are checking even, we can skip even numbers.
			 */
			while ($i <= sqrt($n))
			{
				if ($n % $i == 0) {
					return false;
				}
				$i += 2;
			}
			return true;
		}
		
		function nextPrimes($number, $count = 126) {
			$nextP = array();
			//Keep looping until we have an array of 10 prime numbers.
			while ((sizeof($nextP) < $count) and ($number <= 100000)) {

				if (isPrime($number + 1)) {
					$nextP[] = $number + 1;
				}
				$number++;
			}
			return $nextP;
		}


		function simpleEncrypt($textToEncrypt, $encyptionKey, $primes, $maxAllowedASCII = 126) {
			// Check if encyptionKey is empty
			if (!$encyptionKey) {
				throw new Exception('No Encyption Key');
			}
			
			// Check if input contains valid characters (ASCII 32-126)
			if(
				preg_match('/[^\x20-\x7f]/', $textToEncrypt)
				||
				preg_match('/[^\x20-\x7f]/', $encyptionKey)
			) {
				throw new Exception('Invalid Characters In Input');
			}

			$encryptedText = '';
			for ($i = 0; $i < strlen($textToEncrypt); $i++){

				$n = $i + ord(strval(substr($encyptionKey, $i % strlen($encyptionKey), 1)));

				$n = $primes[$n] + $i + ord($encryptedText[$i]);
				$n = $n % $maxAllowedASCII;
				
				$encryptedText = $encryptedText . $n;
			}

			return $encryptedText;
		};
	?>
	<div class="encrypted_text"><?php
	$text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";
	$key = 'What is Lorem Ipsum?';
	echo 'Text: '.$text;
	echo "<br>";
	echo 'Key: '.$key;
	echo "<br>";
	echo 'Encrypted Text: '.simpleEncrypt(
		$text,
		 $key,
		 nextPrimes(1)
	); 
	
	?></div>
</body>
</html>