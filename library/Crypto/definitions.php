<?php
	interface group
	{
		public function zero();				//returns GroupValue such that A + this.zero = A
		public function generator();
		
		public function add( $A, $B );		//returns A+B
		public function negate( $A );		//returns -A such that A + (-A) = this.zero
		public function subtract( $A, $B );	//returns A + (-B)
		public function double( $A );		//returns A + A
		
		public function equals( $A, $B );
		public function intMultiply( $k , $A, $base = 0 );	//returns kA
		public function randomMember(); //returns a random member
		public function randomMemberNOZero(); //returns a random member of the Multiplicitave Group
	}
	
	interface field extends group
	{
		public function one();
		
		public function multiply( $A, $B );	//returns  A x B
		public function invert( $A );		//returns  1/A such that A x 1/A = this.one()
		public function divide( $A , $B );	//returns  A x 1/B 
		public function square( $A );		//returns  A x A
	}
	
	interface groupValue
	{	
		public function add( $A );			//returns A+B
		public function negate();			//returns -A such that A + (-A) = this.zero
		public function subtract( $A );		//returns A + (-B)
		public function double();			//returns A + A
		public function intMultiply( $k, $base = 0 );	//returns kA
		
		public function group();
		public function copy();
		
		public function equals( $A );
		
		public function isZero();
		
		public function asString( $base = 0 );
		public function asArrayBigInt();
		public function asJson();
	}
	
	interface fieldValue extends groupValue
	{
		public function isOne();
		public function field();
		
		public function multiply( $A );		//returns  A x B
		public function invert();			//returns  1/A such that A x 1/A = this.one()
		public function divide( $A );		//returns  A x 1/B 
		public function square();			//returns  A x A
	}
	
	function newGMPVar( $a, $base = 0 )
	{
		if( is_resource( $a ) && get_resource_type( $a ) == 'GMP integer' )
		{
			return $a;
		}
		else
		{
			return gmp_init( $a , $base );
		}
	}
	
	// returns a new random GMPVar such that $a<=GMPVar<=$b
	function randGMPVar( $a, $b, $base = 0, $cstrong = true )
	{
	    $bytes = openssl_random_pseudo_bytes( 256, $cstrong );
		$hex   = bin2hex( $bytes );
		$GMPnum = newGMPVar( $hex, 16 );
		$GMPa = newGMPVar( $a, $base );
		$GMPb = newGMPVar( $b, $base );
		$modulus = gmp_add(gmp_sub( $GMPb , $GMPa ), 1 );
		$temp = gmp_mod( $GMPnum, $modulus );
		return gmp_add( $temp, $GMPa ); 
	}
	
	function int2bin( $num )
	{
	    $result = '';
	    do
	    {
	        $result .= chr( gmp_intval( gmp_mod( $num, 256 ) ) );
	        $num = gmp_div( $num, 256 );
	    }
	    while ( gmp_cmp( $num, 0 ) );
	    
	    return $result;
	}
	
	function bitLen( $num )
	{
		$tmp = int2bin( $num );
		$bit_len = strlen( $tmp ) * 8;
		$tmp = ord( $tmp{strlen( $tmp ) - 1} );
        if( !$tmp )
        {
			$bit_len -= 8;
		}
		else
		{
			while( !( $tmp & 0x80 ) )
			{
				$bit_len--;
				$tmp <<= 1;
			}
		}
		return $bit_len;
	}
	
	function groupMultiply( $k, $A, $base = 0 )
	{
		$k = newGMPVar( $k, $base );
		$P = $A->copy();
		$Q = $A->group()->zero()->copy();
		$len_k = bitLen( $k );
		
		for( $t = 0; $t <= $len_k - 1; $t = $t + 1 )
		{
			if( gmp_testbit( $k, $t ) )
			{
				$Q = $P->add( $Q );
			}
			$P = $P->double();
		}
		return $Q->copy();
	}
