<?php
	require_once ( 'group.php' );
	
	class StandardCurve extends elipticCurve
	{
	
		private $identifier = '';
	
		function __construct( $curve )
		{
			switch( $curve )
			{
				case "P256":
					$p = newGMPVar( 'ffffffff00000001000000000000000000000000ffffffffffffffffffffffff', 16 );
					$a = newGMPVar( 'ffffffff00000001000000000000000000000000fffffffffffffffffffffffc', 16 );
					$b = newGMPVar( '5ac635d8aa3a93e7b3ebbd55769886bc651d06b0cc53b0f63bce3c3e27d2604b', 16 );
					$Gx = newGMPVar( '6b17d1f2e12c4247f8bce6e563a440f277037d812deb33a0f4a13945d898c296', 16 );
					$Gy = newGMPVar( '4fe342e2fe1a7f9b8ee7eb4a7c0f9e162bce33576b315ececbb6406837bf51f5', 16 );
					$n = newGMPVar( 'ffffffff00000000ffffffffffffffffbce6faada7179e84f3b9cac2fc632551', 16 );
					$h = '1';
					$this->identifier = "P256";
					break;
			}
			
			parent::__construct( $p, $a, $b, $Gx, $Gy, $n, $h, 16 );
			return;
		}
	
		public function asJSONArray( $base = 0 )
		{
	
			return array( 'name' => $this->identifier );
		}
	
		public function asJSON()
		{
	
			return json_encode( $this->asJSONArray() );
		}
	}