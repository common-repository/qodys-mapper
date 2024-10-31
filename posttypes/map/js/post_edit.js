function LoadMapImage( map_slug, map_image )
{
	var the_image = jQuery('<img />');
	
	the_image.addClass('map');
	the_image.attr( 'src', map_image );
	the_image.attr( 'usemap', '#map-' + map_slug );
	//the_image.addClass('defaultText');
	
	jQuery('#map_preview_image').html( the_image );
}

function LoadMapCoordinates( map_slug )
{
	jQuery( '.map_content' ).hide();
	jQuery( '#map-content-' + map_slug ).show();
}


	