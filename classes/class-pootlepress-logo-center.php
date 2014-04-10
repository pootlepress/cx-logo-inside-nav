<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function getLogoData()
{

	$settings = woo_get_dynamic_values( array( 'logo' => '' ) );
	if ( ( '' != $settings['logo'] ) ) {

	$site_title = get_bloginfo( 'name' );
	$site_url = home_url( '/' );
	$site_description = get_bloginfo( 'description' );

	$logo_url = $settings['logo'];
	if ( is_ssl() ) $logo_url = str_replace( 'http://', 'https://', $logo_url );

	$size = getimagesize($logo_url);
	$w = $size[0];
	$h = $size[1];

	$nav_mb = ceil($w * 0.7);
	$nav_padding = ceil($w/2);
	$logo_top = ceil($h/2);

	$item_replace = 'style="margin-left:' . $w . 'px;" class="logo_margin ';

	$logo_block = '
	<a href="' . esc_url( $site_url ) . '" title="' . esc_attr( $site_description ) . '">
	<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_title ) . '" />
	</a>' . "\n";

	return array(
		'size'=>$size,
		'w'=>$w,
		'h'=>$h,
		'nav_mb'=>$nav_mb,
		'nav_padding'=>$nav_padding,
		'logo_top'=>$logo_top,
		'item_replace'=>$item_replace,
		'logo_block'=>$logo_block,
		);
	}

	return false;

}

/*Make the array from $nav string then split it (left side always has 1 more item if the sum of them is odd) */
function parseNavitems($nav)
{
  
    $dom = new DOMDocument;  
    $dom -> loadHTML($nav);    
        
    $xpath = new DOMXPath($dom);    
  
    if($xpath -> query('//div/ul/*')->length !== 0)
    {
        $tags = $xpath -> query('//div/ul/*');
        
        }elseif($xpath -> query('//ul[@id="top-nav"]/*')->length !== 0)
        {
            $tags = $xpath -> query('//ul[@id="top-nav"]/*');
            remove_action('woo_header_after', 'woo_nav', 10);
            
            }elseif($xpath -> query('//ul[@id="page-list"]/*')->length !== 0  )
            {
               $tags =  $xpath -> query('//ul[@id="page-list"]/*');    
                }

   
    if($tags){
         foreach($tags as $tag){        
         $arr[] = $tag -> C14N();
         } 
   }
    
	if(count($arr) <= 0) return false;
    
   $navs1 = array_reverse(array_slice($arr, 0, ceil(count($arr)/2)));
   
	$navs2 = array_splice($arr, ceil(count($arr)/2));

	$navs1 = implode('', $navs1);
	$navs2 = implode('', $navs2);
    
      
	return array('nav1'=>$navs1, 'nav2'=>$navs2); 
}

function generateNav($nav)
{
	$logodata = getLogoData();
    $nav = parseNavitems($nav);
    $html = '
<div class="topnav_section">	
  <div class="nav_section first" style="padding: 0 ' . $logodata['nav_padding'] . 'px 0 0" >
	
	<!--<div class="nav_m" style="width:' . $logodata['nav_mb'] . 'px">&nbsp;</div>-->
	<ul class="nav fl">
		' . $nav['nav1'] . '
	</ul>
</div>
<div class="nav_section second" style="
	margin-top:-' .  $logodata['logo_top'] . 'px;
	margin-left:-' .  $logodata['nav_padding'] . 'px;
	width:' .  $logodata['w'] . 'px;">
	' . $logodata['logo_block'] . '
</div>

<div class="nav_section third" style="padding: 0 0 0 ' . $logodata['nav_padding'] . 'px" >
	<!--<div class="nav_m" style="width:' .  $logodata['nav_mb'] . 'px">&nbsp;</div>-->
	<ul class="nav fl">
		' . $nav['nav2'] . '
	</ul>
</div>
</div>';


return $html;
}

function getMainTitle(){
    
    $title = '<div>
<h1 class="site-title"><a href="'.home_url( '/' ).'">'.get_bloginfo( 'name' ).'</a></h1>
<span class="site-description">'.get_bloginfo( 'description' ).'</span>
</div>';

echo $title;
}

/**
 * Pootlepress_Center_logo Class
 *
 * Base class for the Pootlepress Center Logo.
 *
 * @package WordPress
 * @subpackage Pootlepress_Center_logo
 * @category Core
 * @author Pootlepress
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * public $token
 * public $version
 * private $_menu_style
 * 
 * - __construct()
 * - add_theme_options()
 * - load_localisation()
 * - check_plugin()
 * - load_plugin_textdomain()
 * - activation()
 * - register_plugin_version()
 * - load_align_right()
 */
class Pootlepress_Center_logo {
	public $token = 'pootlepress-center-logo';
	public $version;
	private $file;
	private $_menu_style;

	/**
	 * Constructor.
	 * @param string $file The base file of the plugin.
	 * @access public
	 * @since  1.0.0
	 * @return  void
	 */
	public function __construct ( $file ) {
		$this->file = $file;
		$this->load_plugin_textdomain();
		add_action( 'init','check_main_heading', 0 );
		add_action( 'init', array( &$this, 'load_localisation' ), 0 );

		// Run this on activation.
		register_activation_hook( $file, array( &$this, 'activation' ) );

		// Add the custom theme options.
		add_filter( 'option_woo_template', array( &$this, 'add_theme_options' ) );

		// Lood for a method/function for the selected style and load it.
		add_action('init', array( &$this, 'load_center_logo' ) );
		add_action('wp_footer', array(&$this, 'load_scripts'));


	} // End __construct()

	function load_scripts()
	{
		$_rightcenterenabled  = get_option('pootlepress-center-logo-option');
		$_stickyenabled  = get_option('pootlepress-sticky-nav-option');

		if ($_rightcenterenabled == '') $enabled = 'true';
		if ($_rightcenterenabled != 'true')  return null;

		wp_enqueue_style(esc_attr('center_logo'), esc_url(plugins_url('styles/center_logo.css', $this->file)));
	} 

	/**
	 * Add theme options to the WooFramework.
	 * @access public
	 * @since  1.0.0
	 * @param array $o The array of options, as stored in the database.
	 */	
	public function add_theme_options ( $o ) {
		$o[] = array(
				'name' => 'Logo Inside Nav', 
				'type' => 'subheading'
				);
		$o[] = array(
				'name' => 'Primary Menu and Top Menu',
				'desc' => '',
				'id' => 'pootlepress-center-logo-notice',
				'std' => sprintf(("The Logo Inside Nav Canvas Extension will not show Primary Menu when Top Menu checked or both. Find more plugins here <a href=\"%s\" target=\"_blank\">http://www.pootlepress.com/shop</a>" ), "http://www.pootlepress.com/shop" ),
				'type' => 'info'
				);
		$o[] = array(
				'id' => 'pootlepress-center-logo-option', 
				'name' => __( 'Align Logo by Center', 'pootlepress-center-logo' ), 
				'desc' => __( 'Enable Center Logo', 'pootlepress-center-logo' ), 
				'std' => 'true',
				'type' => 'checkbox'
				);
        /*$o[] = array(
				'id' => 'pootlepress-site-title-option', 
				'name' => __( 'Turn on Site Title', 'pootlepress-site-title' ), 
				'desc' => __( 'Turn On Title', 'pootlepress-site-title' ), 
				'std' => 'false',
				'type' => 'checkbox'
				);*/
		return $o;
	} // End add_theme_options()
	
	/**
	 * Load the plugin's localisation file.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_localisation () {
		load_plugin_textdomain( $this->token, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation()
	
	/**
	 * Load the plugin textdomain from the main WordPress "languages" folder.
	 * @access public
	 * @since  1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = $this->token;
	    // The "plugin_locale" filter is also used in load_plugin_textdomain()
	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	 
	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain()

	/**
	 * Run on activation.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * Register the plugin's version.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	private function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( $this->token . '-version', $this->version );
		}
	} // End register_plugin_version()


	/**
	 * Load the right align files
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_center_logo () {
        
        $_isTitleOn = get_option('pootlepress-site-title-option');
		$_rightcenterenabled  = get_option('pootlepress-center-logo-option');
		$_stickyenabled  = get_option('pootlepress-sticky-nav-option');
		if ($_rightcenterenabled == '') $enabled = 'true';
		if ($_rightcenterenabled == 'true') {


			remove_action('woo_header_after','woo_nav', 10);
			remove_action('woo_header_inside','woo_logo', 10);
			remove_action( 'woo_nav_inside','woo_nav_primary', 10 );
			remove_action( 'woo_top','woo_top_navigation', 10 );

            add_action('woo_header_after', 'woo_nav', 10);
           	add_action('woo_nav_inside', 'woo_nav_new');
			add_action('woo_top', 'woo_nav_new_top');
            
            if($_isTitleOn == 'true'){
               add_action('woo_header_inside', 'getMainTitle', 10); 
            } 


			function woo_nav_new_top()
			{

				if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) {
?>
<div id="top">
    <div class="col-full">
      <?php
        echo '<h3 class="top-menu">' . woo_get_menu_name( 'top-menu' ) . '</h3>';
        $getnav = wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav top-navigation fl', 'theme_location' => 'top-menu', 'echo'=>false ) );
        $nav = generateNav($getnav);
        echo $nav;
       ?>
     </div>
</div>
       <?php

				}

			}

			function woo_nav_new()
			{


				if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {

					echo '<h3>' . woo_get_menu_name( 'primary-menu' ) . '</h3>';

					$getnav =  wp_nav_menu(array('theme_location'=>'primary-menu', 'echo'=>false));
					$nav = generateNav($getnav);
					echo $nav;

				} else {

					if ( get_option( 'woo_custom_nav_menu' ) == 'true' ) {

							if ( function_exists( 'woo_custom_navigation_output' ) ) { 
								woo_custom_navigation_output( 'name=Woo Menu 1' );
							}
					} elseif(function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) { 
						// 
						// top navigation active
						// 
					}else{

                        $getnav = wp_list_pages( 'depth=6&title_li=&exclude=&echo=0' );
                        $getnav = '<ul id="page-list">' . $getnav . '</ul>'; 
						$nav = generateNav($getnav);
						echo $nav;

					}
				}

		}

			if ($_stickyenabled == 'true') {
				add_action('wp_footer', 'fixStickyMobile',10);
			}
		}
	} // End load_align_right()
	

} // End Class
