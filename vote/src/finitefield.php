<?php
	require_once ( 'definitions.php' );
	
	class primeField implements field
	{
	
		private $intmodulus = '';
	
		public function zero()
		{ //returns GroupValue such that A + this.zero = A
	
			return new primeFieldValue( $this, 0 );
		}
	
		public function one()
		{
	
			return new primeFieldValue( $this, 1 );
		}
	
		public function generator()
		{
	
			return new primeFieldValue( $this, 1 );
		}
	
		public function equals( $A, $B )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			if( !( $B instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $B->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->equals( $B );
		}
	
		public function modulus()
		{
	
			return $this->intmodulus;
		}
	
		public function randomMember()
		{
	
			$temp1 = gmp_sub( $this->intmodulus, 1 );
			$temp2 = randGMPVar( 0, $temp1 );
			return new primeFieldValue( $this, $temp2 );
		}
	
		public function randomMemberNOZero()
		{
	
			$temp1 = gmp_sub( $this->intmodulus, 1 );
			$temp2 = randGMPVar( 1, $temp1 );
			return new primeFieldValue( $this, $temp2 );
		}
	
		public function add( $A, $B )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->add( $B );
		}
	
		public function negate( $A )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->negate();
		}
	
		public function subtract( $A, $B )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->subtract( $B );
		}
	
		public function double( $A )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->double();
		}
	
		public function intMultiply( $k, $A, $base = 0 )
		{
	
			return groupMultiply( $k, $A, $base );
		}
	
		public function multiply( $A, $B )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->multiply( $B );
		}
	
		public function invert( $A )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->invert();
		}
	
		public function divide( $A, $B )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->divide( $B );
		}
	
		public function square( $A )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			return $A->square();
		}
	
		public function __construct( $modulus, $base = 0 )
		{
	
			$this->intmodulus = newGMPVar( $modulus, $base );
		}
	}
	
	class primeFieldValue implements fieldValue
	{
	
		public $finiteField;
	
		private $internalValue;
	
		public function asJson()
		{
	
			return json_encode( array( 'type' => 'FiniteFieldValue', 'modulus' => gmp_strval( $this->finiteField->modulus(), 16 ), 'base' => 16, 'internalValue' => gmp_strval( $this->internalValue, 16 ) ) );
		}
	
		public function group()
		{
	
			return $finiteField;
		}
	
		public function field()
		{
	
			return $finiteField();
		}
	
		public function asString( $base = 10 )
		{
	
			return gmp_strval( $this->internalValue, $base );
		}
	
		public function asArrayBigInt()
		{
	
			return array( 0 => $this->internalValue );
		}
	
		public function asBigInt()
		{
	
			return $this->internalValue;
		}
	
		public function isZero()
		{
	
			return $this->equals( $this->finiteField->zero() );
		}
	
		public function isOne()
		{
	
			return $this->equals( $this->finiteField->one() );
		}
	
		public function copy()
		{
	
			return new primeFieldValue( $this->finiteField, $this->internalValue );
		}
	
		public function equals( $B )
		{
	
			return ( gmp_cmp( gmp_mod( $this->internalValue, $this->finiteField->modulus() ), gmp_mod( $B->internalValue, $B->finiteField->modulus() ) ) == 0 ? true : false );
		}
	
		public function add( $A )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this->finiteField ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			$temp = gmp_add( $A->internalValue, $this->internalValue );
			return new primeFieldValue( $this->finiteField, gmp_mod( $temp, $this->finiteField->modulus() ) );
		}
	
		public function negate()
		{
	
			$temp = gmp_neg( $this->internalValue );
			return new primeFieldValue( $this->finiteField, gmp_mod( $temp, $this->finiteField->modulus() ) );
		}
	
		public function subtract( $A )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this->finiteField ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			$temp = gmp_sub( $this->internalValue, $A->internalValue );
			return new primeFieldValue( $this->finiteField, gmp_mod( $temp, $this->finiteField->modulus() ) );
		}
	
		public function double()
		{
	
			$temp = gmp_add( $this->internalValue, $this->internalValue );
			return new primeFieldValue( $this->finiteField, gmp_mod( $temp, $this->finiteField->modulus() ) );
		}
	
		public function intMultiply( $k, $base = 0 )
		{
	
			return groupMultiply( $k, $t, $base );
		}
	
		public function multiply( $A )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this->finiteField ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			$temp = gmp_mul( $A->internalValue, $this->internalValue );
			return new primeFieldValue( $this->finiteField, gmp_mod( $temp, $this->finiteField->modulus() ) );
		}
	
		public function invert()
		{
	
			return new primeFieldValue( $this->finiteField, gmp_invert( $this->internalValue, $this->finiteField->modulus() ) );
		}
	
		public function divide( $A )
		{
	
			if( !( $A instanceof primeFieldValue ) )
			{
				trigger_error( "Not finiteFieldValue", E_USER_ERROR );
			}
			if( !( $A->finiteField == $this->finiteField ) )
			{
				trigger_error( "not elements of the same FiniteField", E_USER_ERROR );
			}
			$temp = gmp_mul( gmp_invert( $A->internalValue, $this->finiteField->modulus() ), $this->internalValue );
			return new primeFieldValue( $this->finiteField, gmp_mod( $temp, $this->finiteField->modulus() ) );
		}
	
		public function square()
		{
	
			$temp = gmp_mul( $this->internalValue, $this->internalValue );
			return new primeFieldValue( $this->finiteField, gmp_mod( $temp, $this->finiteField->modulus() ) );
		}
	
		public function __construct( $finiteField, $internalValue, $base = 0 )
		{
	
			if( $finiteField instanceof primeField )
			{
				$this->finiteField = $finiteField;
			}
			else
			{
				trigger_error( "Not finiteField", E_USER_ERROR );
			}
			$this->internalValue = gmp_mod( newGMPVar( $internalValue, $base ), $finiteField->modulus() );
		
		}
	}
