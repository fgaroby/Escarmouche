<?php
class Escarmouche_View extends Zend_View
{
	public function escape( $var )
	{
		if( empty( $var ) )
			return '&nbsp;';
		
		return nl2br( parent::escape( $var ) );
	}
}