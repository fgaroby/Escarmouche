$( document ).ready( function()
{
	$( '#username' ).focus( function()
	{
		if( $( this ).val() == 'Username' )
			$( this ).val( '' );
	} );
	
	$( '#username' ).blur( function()
	{
		if( $( this ).val() == '' )
			$( this ).val( 'Username' );
	} );
} );