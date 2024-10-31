<?php
ob_get_contents();
require_once( dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).'/wp-load.php' );
ob_end_clean();

$preset_id = $_GET['p'];

if( $preset_id )
{
	$preset_data = get_post( $preset_id );
	$preset_custom = $qodys_optiner->GetClass('posttype_form-preset')->get_post_custom( $preset_id );
	$list_ids = maybe_unserialize( $preset_custom['list_id'] );
}

$form_id = $_GET['f'] ? $_GET['f'] : $preset_custom['form_id'];

if( $form_id )
{
	$form_data = get_post( $form_id );
	$form_custom = $qodys_optiner->GetClass('posttype_form')->get_post_custom( $form_id );
}

$iframe_asset = $qodys_optiner->GetClass('posttype_form')->GetAsset( 'includes', 'optin_iframe' );

$fields = array();
$fields['id'] = 'optiner-optin';
$fields['width'] = $form_custom['frame_width'] + 25;
$fields['height'] = $form_custom['frame_height'] + 25;
$fields['style'] = 'overflow:hidden; padding:0px; margin:0px; border:none;';
$fields['scrolling'] = 'no';
$fields['src'] = $qodys_optiner->GetClass('posttype_form')->GetAsset( 'includes', 'optin_iframe', 'url' ).'?'.http_build_query( $_GET );

$frame_string = '<iframe';

foreach( $fields as $key => $value )
{
	$frame_string .= ' '.$key.'="'.$value.'"';
}

$frame_string .= '></iframe>';
?>
<?php ob_start("qody_javascriptCompress"); ?>
<?php header("Content-type:text/javascript"); ?>

document.write( '<?php echo $frame_string; ?>' );