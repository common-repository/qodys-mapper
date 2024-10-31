<?php
class qodyPosttype_MapperMap extends QodyPostType
{
	 function __construct()
    {
        $this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		$this->m_priority = 3;
        
        $this->m_show_in_menu = $this->GetPre().'-home.php';
        
       	//$this->m_supports[] = 'title';
        //$this->m_supports[] = 'editor';
        //$this->m_supports[] = 'thumbnail';
        //$this->m_supports[] = 'excerpt';
        $this->m_supports[] = null;
		
		$this->m_rewrite = array( 'slug' => 'map' );
        
        $this->SetMassVariables( 'map', 'maps', true );
		
		$this->m_list_columns['cb'] = '<input type="checkbox" />';
        $this->m_list_columns['title'] = 'Name';
        $this->m_list_columns['shortcode'] = 'Shortcode';
        //$this->m_list_columns['embed_code'] = 'Embed Code';
        $this->m_list_columns['direct_link'] = 'Direct Link';
		
		$this->CreateShortcode();
		
		$this->RegisterScript( 'jquery_map_hilight', plugins_url( 'js/jquery.maphilight.min.js', __FILE__ ), array('jquery') );
		$this->RegisterScript( 'map_hilight', plugins_url( 'js/map_hilight.js', __FILE__ ), array('jquery','jquery_map_hilight') );
		
		$this->EnqueueScript( 'map_hilight', plugins_url( 'js/map_hilight.js', __FILE__ ), array('jquery','jquery_map_hilight') );
        
        parent::__construct();
    }
	
    function WhenViewingPostList()
    {
		if( !parent::WhenViewingPostList() )
			return;
			
		$this->EnqueueStyle( 'nicer-tables' );
    }
	
	function WhenEditing()
	{
		if( !parent::WhenEditing() )
			return;
		
		$this->RemoveAllMetaboxesButMine( null, true );
		
		$this->AddMetabox( 'map', 'Map Selection' );
		$this->AddMetabox( 'fields', 'Territory Settings' );
		
		$this->EnqueueStyle('chosen');
		$this->EnqueueScript('chosen');
	}
	
	function GetMaps()
	{
		$maps = $this->GetAssets( 'maps' );
		
		if( !$maps )
			return;
		
		$data = array();
		foreach( $maps as $key => $value )
		{
			$fields = array();
			$fields['name'] = strtoupper( str_replace( '-', ' ', $key ) );
			$fields['image'] = $value['map']['container_link'].'/'.$value['map']['file_name'];
			$fields['coordinates'] = $value['coordinates']['container_link'].'/'.$value['coordinates']['file_name'];
			
			$data[ $key ] = $fields;
		}
		
		return $data;
	}
	
	function ParseTerritories( $map_slug, $file_url )
	{
		$bits = $this->GetTerritories( $file_url );
		
		if( !$bits )
			return;
		
		$data = array();
		foreach( $bits as $key => $value )
		{
			$attribute = 'slug';
			
			preg_match( '/'.$attribute.'=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is', $value, $match );
			
			$slug = $match[2];
			
			$fields = array();
			$fields['slug'] = $slug;
			$fields['link'] = $this->GetTerritoryLink( $map_slug, $slug );
			$fields['title'] = $this->GetTitleFromSlug( $slug );
			
			$data[] = $fields;
		}
		
		return $data;
	}
	
	function ParseCoordinates( $map_slug, $file_url = '', $custom = array() )
	{
		if( !$file_url )
		{
			$asset = $this->GetAssets( 'maps' );
			$file_url = $asset[$map_slug]['coordinates']['container_link'].'/'.$asset[$map_slug]['coordinates']['file_name'];
		}
		
		$bits = $this->GetTerritories( $file_url );
		
		if( !$bits )
			return;
		
		foreach( $bits as $key => $value )
		{
			$attribute = 'slug';
			
			preg_match( '/'.$attribute.'=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is', $value, $match );
			
			$territory_slug = $match[2];
			
			$value = str_replace( '@@TITLE@@', $this->GetTitleFromSlug( $territory_slug, $custom ), $value );
			$value = str_replace( '@@ALT@@', $this->GetTitleFromSlug( $territory_slug, $custom ), $value );
			$value = str_replace( '@@LINK@@', $this->GetTerritoryLink( $map_slug, $territory_slug, $custom ), $value );
			
			$bits[ $key ] = $value;
		}
		
		$data = implode( "\n", $bits );
		
		return $data;
	}
	
	function GetTerritories( $file_url )
	{
		$result = wp_remote_get( $file_url );
		$data = $result['body'];
		
		if( !$data )
			return;
		
		$data = trim( $data );
		
		$bits = explode( "\n", $data );
		
		if( is_array( $bits ) )
			$data = $bits;
		
		return $data;
	}
	
	function GetTerritoryLink( $map_slug, $territory_slug, $custom = array() )
	{
		$link = $custom[ $territory_slug ]['link'] ? $custom[ $territory_slug ]['link'] : '#';
		
		return $link;
	}
	
	function GetTitleFromSlug( $slug, $custom = array() )
	{
		$data = array();
		$data['wa'] = "Washington";
		$data['or'] = "Oregon";
		$data['ca'] = "California";
		$data['nv'] = "Nevada";
		$data['id'] = "Idaho";
		$data['ne'] = "Nebraska";
		$data['ks'] = "Kansas";
		$data['tx'] = "Texas";
		$data['mn'] = "Minnesota";
		$data['la'] = "Louisiana";
		$data['ia'] = "Iowa";
		$data['mt'] = "Montana";
		$data['nd'] = "North Dakota";
		$data['sd'] = "South Dakota";
		$data['wy'] = "Wyoming";
		$data['ok'] = "Oklahoma";
		$data['co'] = "Colorado";
		$data['ut'] = "Utah";
		$data['az'] = "Arizona";
		$data['nm'] = "New Mexico";
		$data['ar'] = "Arkansas";
		$data['ms'] = "Mississippi";
		$data['al'] = "Alabama";
		$data['fl'] = "Florida";
		$data['ga'] = "Georgia";
		$data['sc'] = "South Carolina";
		$data['nc'] = "North Carolina";
		$data['va'] = "Virginia";
		$data['wv'] = "West Virginia";
		$data['de'] = "Delaware";
		$data['ri'] = "Rhode Island";
		$data['nj'] = "New Jersey";
		$data['ny'] = "New York";
		$data['ct'] = "Connecticut";
		$data['ma'] = "Massachusetts";
		$data['vt'] = "Vermont";
		$data['nh'] = "New Hampshire";
		$data['me'] = "Maine";
		$data['oh'] = "Ohio";
		$data['tn'] = "Tennessee";
		$data['ky'] = "Kentucky";
		$data['in'] = "Indiana";
		$data['mi'] = "Michigan";
		$data['wi'] = "Wisconsin";
		$data['il'] = "Illinois";
		$data['mo'] = "Missouri";
		$data['pa'] = "Pennsylvania";
		$data['md'] = "Maryland";
		
		return $custom[ $slug ]['title'] ? $custom[ $slug ]['title'] : $data[ $slug ];
	}
    
    function CreateShortcode()
	{
		add_shortcode( 'mapper', array( $this, 'Shortcode' ) );
	}
	
	function Shortcode( $atts, $content = null )
	{
		$map_id = $atts['m'];
		
		$data = $this->GetEmbedCode( $map_id, $atts );
		
		return $data;
	}
	
	function GetShortcode( $map_id = '' )
    {
		$data = '[mapper';
		
		if( $map_id )
			$data .= ' m="'.$map_id.'"';
		
		$data .= ']';
		
		return $data;
    }
	
	function GetEmbedCode( $map_id = '', $extras = array() )
    {
		$fields = array();
		$fields['m'] = $map_id;
		
		$fields = wp_parse_args( $fields, $extras );
		
		$result = wp_remote_get( $this->GetAsset( 'includes', 'embed', 'url' ).'?'.http_build_query( $fields ) );
		$data = $result['body'];
		
		return $data;
    }
	
    function DisplayListColumns( $column )
    {
		global $post;
		
		$post_id = $post->ID;
		
		$custom = $this->get_post_custom( $post_id );
        $the_meta = get_post_meta( $post_id, $column, true);
        
        switch( $column )
        {
			case "shortcode":
				
				echo '<input class="embed_input" type="text" onclick="this.select()" readonly="readonly" value=\''.$this->GetShortcode( $post_id ).'\'">';
					
				break;
			
			case "embed_code":
				
				echo '<input class="embed_input" type="text" onclick="this.select()" readonly="readonly" value=\''.$this->GetEmbedCode( $post_id ).'\'">';
					
				break;
			
			case "direct_link":
				
				echo '<input class="embed_input" type="text" onclick="this.select()" readonly="readonly" value=\''.get_permalink( $post_id ).'\'">';
					
				break;
			
			case "list_id":
                
				if( !$the_meta )
					$the_meta = array();
				
				if( $the_meta )
				{
					$iter = 0;
					
					foreach( $the_meta as $key => $value )
					{
						$iter++;
						
						echo '<a href="'.admin_url('post.php?post='.$value ).'&action=edit">'.get_the_title( $value )."</a>";
						
						if( $iter < count( $the_meta ) )
							echo ", ";
					}
				}
                
                break;
			
			case "form_id":
				
				if( $the_meta )
				{
					echo '<a href="'.admin_url('post.php?post='.$the_meta ).'&action=edit">'.get_the_title( $the_meta ).'</a>';
				}
				
				break;
			
            case "date":
            
                $productData = get_post( $the_meta );
                
                echo $productData->post_title;
                
                break;
        }
    }
    
    
}
?>