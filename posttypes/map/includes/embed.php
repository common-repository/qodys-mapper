<?php
ob_get_contents();
require_once( dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).'/wp-load.php' );
ob_end_clean();

$map_id = $_GET['m'];

if( $map_id )
{
	$map_data = get_post( $map_id );
	$custom = $qodys_mapper->GetClass('posttype_map')->get_post_custom( $map_id );
	$custom['maps'] = maybe_unserialize( $custom['maps'] );
	
	$map_assets = $qodys_mapper->GetClass('posttype_map')->GetAssets( 'maps' );
	$map_image = $map_assets[ $custom['map_slug'] ]['map']['container_link'].'/'.$map_assets[ $custom['map_slug'] ]['map']['file_name'];
}

$qodys_mapper->EnqueueScript( 'jquery_map_hilight' );
$qodys_mapper->EnqueueScript( 'map_hilight' );
?>


<style>
#map-container-<?php echo $map_id; ?> div.map {
	text-align:center;
	margin:0px auto;
}
</style>

<div id="map-container-<?php echo $map_id; ?>">
	<img class="map" src="<?php echo $map_image; ?>" usemap="#map-<?php echo $custom['map_slug']; ?>">
	<map name="map-<?php echo $custom['map_slug']; ?>">
		
		<?php echo $qodys_mapper->ParseCoordinates( $custom['map_slug'], '', $custom['maps'][ $custom['map_slug'] ] ); ?>
		
	</map>
</div>