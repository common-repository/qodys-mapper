<?php 
global $post, $custom, $maps;

if( !$custom )
	$custom = $this->get_post_custom( $post->ID );

$custom['maps'] = maybe_unserialize( $custom['maps'] );
?>

<p>Select a map to edit it's territory settings</p>

<?php
if( $maps )
{
	foreach( $maps as $key => $value )
	{
		$territories = $this->ParseTerritories( $key, $value['coordinates'] );
		
		if( !$territories )
			continue; ?>
<div class="map_content" id="map-content-<?php echo $key; ?>">
	
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th style="width:30%;">Territory Name</th>
				<th>URL to visit when clicked</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach( $territories as $key2 => $value2 )
		{
			$custom_base = $custom['maps'][$key][$value2['slug']];
			
			$title = $custom_base['title'] ? $custom_base['title'] : $value2['title'];
			$link = $custom_base['link'] ? $custom_base['link'] : ($value2['link'] == '#' ? '' : $value2['link']); ?>
			<tr>
				<td>
					<input class="widefat" id="<?php echo $nextItem; ?>" type="text" name="field_maps[<?php echo $key; ?>][<?php echo $value2['slug']; ?>][title]" value="<?php echo $title; ?>"> 
				</td>
				<td>
					<input class="widefat" id="<?php echo $nextItem; ?>" type="text" name="field_maps[<?php echo $key; ?>][<?php echo $value2['slug']; ?>][link]" value="<?php echo $link; ?>"> 
				</td>
			</tr>
		<?php
		} ?>
		
		</tbody>
	</table>
</div>
	<?php
	}
} ?>




