<?php
class qodyOverseer_Mapper extends QodyOverseer
{
	function __construct()
	{
		$this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		//$this->SetParent( 'home' );
		//$this->m_icon_url = '';
		
		//$this->SetTitle( 'Settings' );
		
		if( $this->PassApiCheck() )
		{
			add_action( 'admin_init', array( $this, 'CreateEditorButton' ) );
		
			$this->CreateShortcode();
		}
		
		$fields['template_redirect'] = 'CheckForLinkInUrl';
		
		$this->LoadActionHooks( $fields );
		
		parent::__construct();
	}
	
	function CheckForLinkInUrl()
	{
		$set_id = $_GET['rlid'];
		
		if( !$set_id || !is_numeric($set_id) )
			return;
		
		$url = $this->GetUrlFromSet( $set_id );
		
		if( $url )
		{
			header( "Location: ".$url );
			exit;
		}
	}
	
	function GetUrlFromSet( $set_id )
	{
		if( !$set_id )
			return;
		
		$set_data = get_post( $set_id );
		$custom = $this->get_post_custom( $set_id );
		
		$links = maybe_unserialize( $custom['link_id'] );
		
		if( !$links )
			return;
		
		$link_count = count( $links );
		
		$nextItem = rand() % $link_count;
		
		$link_id = $links[ $nextItem ];
		
		if( $link_id )
			$url = get_post_meta( $link_id, 'url', true );
		
		return $url ? $url : '';
	}
	
	function CreateShortcode()
	{
		add_shortcode( $this->GetPre().'-link', array( $this, 'DoShortcode' ) );
	}
	
	function DoShortcode( $atts, $content = null )
	{
		$set_id = $atts['set'];
		
		$url = $this->GetUrlFromSet( $set_id );
		
		$data = '<a href="'.$url.'">';
		$data .= $content;
		$data .= '</a>';
		
		return $data;
	}
	
	function GetLinkSets()
	{
		$fields = array();
		$fields['post_type'] = $this->GetClass('posttype_link-set')->m_type_slug;
		$fields['numberposts'] = -1;
		
		$data = get_posts( $fields );
		
		return $data;
	}
	
	function CreateEditorButton()
	{
		if( !current_user_can('edit_posts') && !current_user_can('edit_pages') )
		{
			return;
		}
		
		add_filter( 'mce_external_plugins', array( $this, 'add_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'register_button' ) );
	}
	
	function add_plugin( $plugin_array )
	{
		$plugin_array[$this->GetPre()."buttons"] = $this->GetAsset( 'js', 'shortcode', 'url' );
		//$this->ItemDebug( $plugin_array );
		return $plugin_array;
	}
	
	function register_button( $buttons )
	{
		array_push( $buttons, "|", $this->GetPre()."buttons" );
		//$this->ItemDebug( $buttons );
		return $buttons;
	}
}
?>