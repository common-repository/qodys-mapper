<?php
ob_get_contents();
require_once( dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).'/wp-load.php' );
ob_end_clean();

$qodys_rotator->EnqueueStyle( 'bootstrap' );

$link_sets = $qodys_rotator->GetOverseer()->GetLinkSets();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>
</title>
	<title>Qody's Buttoner</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<script src="<?php echo get_bloginfo('url').'/'.WPINC; ?>/js/tinymce/tiny_mce_popup.js"></script>
	<script src="<?php echo get_bloginfo('url').'/'.WPINC; ?>/js/tinymce/utils/mctabs.js"></script>
	<script src="<?php echo get_bloginfo('url').'/'.WPINC; ?>/js/tinymce/utils/form_utils.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
	
	<script src="<?php echo $qodys_rotator->GetRegisteredSrc( 'bootstrap-tab', 'script' ); ?>"></script>
	<script src="<?php echo $qodys_rotator->GetRegisteredSrc( 'bootstrap-dropdown', 'script' ); ?>"></script>
	<script src="<?php echo $qodys_rotator->GetRegisteredSrc( 'chosen', 'script' ); ?>"></script>
	
	<link href="<?php echo $qodys_rotator->GetRegisteredSrc( 'bootstrap' ); ?>" rel="stylesheet">
	<link href="<?php echo $qodys_rotator->GetRegisteredSrc( 'chosen' ); ?>" rel="stylesheet">
	<link href="<?php echo $qodys_rotator->GetRegisteredSrc( 'jquery-ui' ); ?>" rel="stylesheet">
	<link href="<?php echo $qodys_rotator->GetOverseer()->GetAsset( 'css', 'button_picker', 'url' ); ?>" rel="stylesheet">
	
	<script language="javascript" type="text/javascript">
	function GenerateShortcode()
	{
		var link_set = jQuery('#link_set :selected').val();
		
		var the_shortcode = '[<?php echo $qodys_rotator->GetPre(); ?>-link';
		the_shortcode += ' set="' + link_set + '"';
		the_shortcode += ']';
		the_shortcode += ' insert your link text or image here ';
		the_shortcode += '[/<?php echo $qodys_rotator->GetPre(); ?>-link]';
		
		if( window.tinyMCE )
		{
			window.tinyMCE.execInstanceCommand( 'content', 'mceInsertContent', false, the_shortcode );
			
			tinyMCEPopup.editor.execCommand( 'mceRepaint' );
			tinyMCEPopup.close();
		}
	}
	</script>
    
</head>
<body>
		
	<div class="row-fluid" style="margin-top:20px;">
		
		<div class="span12">
			<form class="form-horizontal" onSubmit="return false;">
				<fieldset>
					<legend>Button settings</legend>
					
					<div class="control-group">
						<label class="control-label" for="input01">Link Set </label>
						<div class="controls">
							
							<select id="link_set" name="link_set" data-placeholder="Choose a link set" class="chzn-select">
								<option></option>
							
							<?php
							if( $link_sets )
							{
								foreach( $link_sets as $key => $value )
								{ ?>									
								<option value="<?php echo $value->ID; ?>"><?php echo $value->post_title; ?></option>
								<?php
								}
							} ?>
							</select>
							
							<p class="help-block">Choose which link set to use</p>
						</div>
					</div>
					
					<div class="form-actions">
						<a class="btn btn-success" onclick="GenerateShortcode();" href="javascript:null(0);"><i class="icon-ok icon-white"></i> Insert to Content</a>
						<a class="btn" onclick="tinyMCEPopup.close();" href="javascript:null(0);"><i class="icon-remove-circle"></i> Cancel</a>
					</div>
					
				</fieldset>	
			</form>
			
		</div>
	</div>
	
	<script>
	jQuery( function()
	{
		jQuery('.chzn-select').chosen();
	} );
	</script>
		
</body>
</html>