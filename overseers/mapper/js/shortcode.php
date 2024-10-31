<?php
ob_get_contents();
require_once( dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).'/wp-load.php' );
ob_end_clean();

$slug = $qodys_rotator->GetPre().'buttons';
?>
<?php ob_start("qody_javascriptCompress"); ?>
<?php header("Content-type:text/javascript"); ?>
(function() {
    tinymce.create('tinymce.plugins.<?php echo $slug; ?>',
	{
        init : function( ed, url )
		{
			ed.addCommand('<?php echo $slug; ?>_cmd', function()
			{
				ed.windowManager.open(
				{
					file : '<?php echo $qodys_rotator->GetOverseer()->GetAsset( 'includes', 'button_selection', 'url' ); ?>',
					width : 800,
					height : 500,
					inline : 1
				},
				{
					plugin_url : url
				} );
			} );
			
            ed.addButton('<?php echo $slug; ?>',
			{
                title : 'Qodys Rotator',
                image : '<?php echo $qodys_rotator->GetOverseer()->GetAsset( 'images', 'shortcode_icon', 'url' ); ?>',
				cmd : '<?php echo $slug; ?>_cmd'
            } );
			
			ed.onNodeChange.add( function(ed, cm, n) {
				//cm.setActive('<?php echo $slug; ?>', n.nodeName == 'IMG');
			} );
        },
		getInfo : function() {
			return {
				longname : "Qody's Rotator",
				author : 'Qody LLC',
				authorurl : 'http://qody.co',
				infourl : 'http://rotator.qody.co',
				version : "1.0"
			};
		}		
    });
    tinymce.PluginManager.add('<?php echo $slug; ?>', tinymce.plugins.<?php echo $slug; ?>);
})();
