( function( $ )
{
	$.fn.sortableList = function(options)
	{
		// Options par defaut
		var defaults = {};
		
		var options = $.extend( defaults, options );
		this.each( function()
		{
			var obj = $( this );
			
			//Initialisation du composant "sortable"
			$( obj ).sortable(
			{
				containment			: '.storiesList',
				cursor				: 'move',
				handle				: '.item', // Le drag ne peut se faire que sur l'élément .item (le texte)
				distance			: 10, // Le drag ne commence qu'à partir de 10px de distance de l'élément
				//connectWith			: '.storiesList',
				placeholder			: 'placeholder',
				forcePlaceholderSize: true,
				opacity				: 0.7,
				zIndex				: 100,
				// Evenement appelé lorsque l'élément est relaché
				stop				: function( event, ui )
				{
					// Pour chaque item de liste
					/*$( '.ui-sortable-helper' ).parent( '.storiesList' ).each(function( i )
					{
						$( this ).find( '.draggable' ).each( function( i )
						{
							$( this ).find( '.count' ).text( i + 1 );
						});
					} );

					$( this ).trigger( 'click' );*/
				}
			} );
			
		} );
		
		// On continue le chainage JQuery
		return this;
	};
} )( jQuery );

$( document ).ready( function()
{
	var parent	= null;
	var leftDrag	= 0;
	var topDrag	= 0;
	
	//$( '.storiesList' ).sortableList( {} );
	$( '.draggable' ).draggable(
	{
		revert			: 'invalid',
		revertDuration	: 0,
		start			: function( event, ui )
		{
			// On récupère l'ancien parent
			parent = $( '#' + $( this ).attr( 'id' ).substring( 0, $( this ).attr( 'id' ).length - 2 ) );
			leftDrag = $( this ).position().left;
			topDrag = $( this ).position().top;
		}
	} );
	
	$( '.droppable' ).droppable(
	{
		over	: function( event, ui )
		{
			// Si la zone survolée n'est pas celle d'où est parti le draggable
			if( ui.draggable.attr( 'id' ).search( $( this ).attr( 'id' ) ) == -1 )
			{
				var div = document.createElement( 'div' );
				$( div ).addClass( 'placeholder' );
				$( div ).css( 'width', ui.draggable.width() );
				$( div ).css( 'height', ui.draggable.height() );
				$( this ).append( div );
			}
		},
		out		: function( event, ui )
		{
			$( '.placeholder' ).remove();
		},
		drop	: function( event, ui )
		{
			if( $( this ).find( '.placeholder' ).size() != 0 )
			{
				ui.draggable.css( 'left', $( '.placeholder' ).css( 'left' ) );
				ui.draggable.css( 'top', $( '.placeholder' ).css( 'top' )  );
				ui.draggable.attr( 'id', $( this ).attr( 'id' ) + '_' + ( $( this ).size() + 1 ) );
				$( '.placeholder' ).remove();
				
				// on raccroche au nouveau parent
				$( this ).append( ui.draggable );
				
				// On recalcule les id des éléments appartenant toujours à l'ancien parent
				$( parent ).find( '.draggable' ).each( function( i )
				{
					$( this ).attr( 'id', $( parent ).attr( 'id' ) + '_' + ( i + 1 ) );
				});
			}
			else
			{
				ui.draggable.css( 'left', leftDrag );
				ui.draggable.css( 'top', topDrag );
			}
		}
	});
} );