<?php
abstract class Security {
	private $hashCount=1;
	/**
	 * Hash a text with my custom algorithms
	 *
	 * @param      string  $data   Text need to be hash
	 *
	 * @return     string  Hashed text
	 */
	static function hashPassword($hashType=-1) {
		if($hashType < 1 || $hashType > $hashCount) {
			$hashType = $hashCount;
		}

		$hashType = "hashV$hashType";

		return $this->$hashType;
	}

	static function hashV1($data) {
		$token = md5("***REMOVED***");
		$part1 = sha1($data);
		$part2 = $token."42".$part1;
		$part3 = hash('ripemd160', $part2);
		$part4 = hash('whirlpool', $part3."69");

		return $part4;
	}
}
