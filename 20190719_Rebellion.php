<!DOCTYPE html>
<html>
<head>
<title>Rebellion Challenge!</title>
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
			$i = 3;

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
			while ((sizeof($nextP) <= $count) and ($number <= 100000)) {

				if (isPrime($number + 1)) {
					$nextP[] = $number + 1;
				}
				$number++;
			}
			return $nextP;
		}


		function simpleEncrypt($textToEncrypt, $encyptionKey, $primes, $minAllowedASCII = 32, $maxAllowedASCII = 126) {
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

				$n = $i + ord(substr($encyptionKey, $i % strlen($encyptionKey), 1));
				
				$n = $primes[$n] + $i + ord($textToEncrypt[$i]);

				$n = ($n % ($maxAllowedASCII - 1)) + $minAllowedASCII;

				$encryptedText = $encryptedText . chr($n);
			}

			return $encryptedText;
		};
	?>
	<div class="encrypted_text"><?php
	$text = "Hello, my name is John. How are you today?";
	$key = 'SUPERMAN';
	echo 'Text: '.$text;
	echo "<br>";
	echo 'Key: '.$key;
	echo "<br>";
	echo 'Encrypted Text: '.simpleEncrypt(
		$text,
		 $key,
		 nextPrimes(1)
	); 
	echo "<br>";
echo chr(32);
	?></div>
</body>
</html>