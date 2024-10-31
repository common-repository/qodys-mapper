<?php 
global $post, $custom, $maps;

if( !$custom )
	$custom = $this->get_post_custom( $post->ID );

$maps = $this->GetMaps();
?>

<input type="hidden" name="content" value='<?php echo $this->GetShortcode( $post->ID ); ?>' />
	
<table class="form-table">
	<tbody>
		<tr>
			<?php $nextItem = 'post_title'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Name</label>
			</th>
			<td>
				<input class="widefat the_title" id="<?php echo $nextItem; ?>" type="text" name="<?php echo $nextItem; ?>" value="<?php echo $post->post_title; ?>">
				<span class="howto">Pick a name so you can recognize this apart from any others; internal use only</span>
			</td>
		</tr>
		<tr>
			<?php $nextItem = 'map_slug'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Map</label>
			</th>
			<td>
				<select id="map_selection" data-placeholder="Choose a map" name="field_<?php echo $nextItem; ?>" class="chzn-select">
					<option value=""></option> 
					
					<?php
					if( $maps )
					{
						foreach( $maps as $key => $value )
						{
							if( $custom[ $nextItem ] == $key )
								$selected = 'selected="selected"';
							else
								$selected = ''; ?>
					<option <?php echo $selected; ?> rel="<?php echo $value['image']; ?>" value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
						<?php
						}
					} ?>
				<span class="howto">Choose the Optin Form to use as a visible capture method</span>
			</td>
		</tr>
	</tbody>
</table>

<div id="map_preview_image"></div>

<script type="text/javascript" src="http://toprvlistings.com/wp-content/themes/TopRvListings/js/jquery.maphilight.min.js"></script>
<script type="text/javascript">
jQuery(function() {
	jQuery('.map').maphilight();
});
</script>

<?php
if( $maps )
{
	foreach( $maps as $key => $value )
	{
		$coordinates = $this->ParseCoordinates( $key, $value['coordinates'] ); ?>
<map name="map-<?php echo $key; ?>"><?php echo $coordinates; ?></map>
	<?php		
	}
} ?>

<script>
jQuery( function()
{
	jQuery('.chzn-select').chosen();
	
	jQuery('#map_selection').change( function(e) {
		
		var the_slug = jQuery(this).find( ':selected' ).val();
		var the_image = jQuery(this).find( ':selected' ).attr( 'rel' );
		
		LoadMapImage( the_slug, the_image );
		LoadMapCoordinates( the_slug );
	} );
} );
</script>
