<?php
/*
Plugin Name: All-In-One Slideshow
Plugin URI: http://www.sramekdesign.com/wordpress/plugins/all-in-one-slideshow/
Description: All-In-One Slideshow plugin implements jCycle, Easing and Cufon scripts into the highly customizable slideshow gallery. You can combine 27 transition and 8 easing effects! The plugin comes with package of 8 cufon fonts. You can easlily add new fonts. Choose between arrow and numbered navigation. Or use both at once. Try out how easy it is to customize this slideshow gallery without knowledge of programming.
Version: 1.3.1
Author: Tom Sramek
Author URI: http://www.sramekdesign.com/
*/

define( 'AIOS_DIR', WP_PLUGIN_DIR . '/all-in-one-slideshow' );
define( 'AIOS_CUFON', AIOS_DIR . '/cufon' );
define( 'AIOS_FONTS', AIOS_CUFON . '/fonts' );

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'aioslideshow_load_widgets' );

/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below
 *
 */     
function aioslideshow_load_widgets() {
	register_widget( 'Aioslideshow_Widget' );
}

/**
 * aioslideshow Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class Aioslideshow_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Aioslideshow_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'aioslideshow', 'description' => __('All-In-One Slideshow widget', 'aioslideshow') );

		/* Create the widget. */
		$this->WP_Widget( 'aioslideshow-widget', __('All-In-One Slideshow widget', 'aioslideshow'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
	

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		aio_slideshow();

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
	
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('All-In-One Slideshow', 'aioslideshow'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

<?php } }

/*
This plugin inherits the GPL license from it's parent system, WordPress.
*/

/*
///////////////////////////////////////////////
This section defines the variables that
will be used throughout the plugin
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/
//	define our defaults (filterable)
$aio_slideshow_defaults = apply_filters('aio_slideshow_defaults', array(
    'on_posts' => '',
    'on_pages' => '',
    'on_archive' => 1,
	'rotate' => 1,
	'effect' => 'fade',
    'easing' => 'none',
	'delay' => 3,
	'duration' => 1,
	'img_width' => 500,
	'img_height' => 300,
	'div' => 'aio-slideshow',
    'fontFamily' => 'geo-sans-ligh.font.js',
    'arrnav' => 'allthetime',
    'numnav_position' => 'dont_display',
    'text_position' => 'bottom_left',
    'textbg_margin' => 5,
    'text_align' => 'left',
    'text_size' => 40,
    'textColorsCSS' => 0,
    'text_shadow_enabled' => 1,
    'text_bg' => '000',
    'text_color1' => 'FFF',
    'text_color2' => 'C0C0C0',
    'text_shadow' => '000',
    'your_css' => ''    
));

//	pull the settings from the db
$aio_slideshow_settings = get_option('aio_slideshow_settings');
$aio_slideshow_images = get_option('aio_slideshow_images');

//	fallback
$aio_slideshow_settings = wp_parse_args($aio_slideshow_settings, $aio_slideshow_defaults);


/*
///////////////////////////////////////////////
This section hooks the proper functions
to the proper actions in WordPress
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/

//	this function registers our settings in the db
add_action('admin_init', 'aio_slideshow_register_settings');
function aio_slideshow_register_settings() {
	register_setting('aio_slideshow_images', 'aio_slideshow_images', 'aio_slideshow_images_validate');
	register_setting('aio_slideshow_settings', 'aio_slideshow_settings', 'aio_slideshow_settings_validate');
}

add_action('admin_print_scripts', 'aio_slideshow_colorpicker');
function aio_slideshow_colorpicker() {?>

<link rel="stylesheet" media="screen" type="text/css" href="<?php echo WP_CONTENT_URL.'/plugins/all-in-one-slideshow/colorpicker/css/colorpicker.css'; ?>" />

<script type="text/javascript" src="<?php echo WP_CONTENT_URL.'/plugins/all-in-one-slideshow/colorpicker/js/jquery.js'; ?>"></script>

<script type="text/javascript" src="<?php echo WP_CONTENT_URL.'/plugins/all-in-one-slideshow/colorpicker/js/colorpicker.js'; ?>"></script>

<script type="text/javascript" src="<?php echo WP_CONTENT_URL.'/plugins/all-in-one-slideshow/colorpicker/js/eye.js'; ?>"></script>
<script type="text/javascript" src="<?php echo WP_CONTENT_URL.'/plugins/all-in-one-slideshow/colorpicker/js/utils.js'; ?>"></script>

<script type="text/javascript">

$(document).ready(function() {
 $('p.aio-toggle-msg').hide();   
 // toggles the slickbox on clicking the noted link
 $('a.aio-toggle-link').click(function() {
 $('p.aio-toggle-msg').toggle(400);
 return false;
  });
});
$(document).ready(function() {

<?php for ($i=0; $i<=3; $i++) { ?>

$('#colorpickerField<?php echo $i; ?>').ColorPicker({
       
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
        $(this).css('backgroundColor', '#' + hex);
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	},
    onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);         
		return false;
	},   
    onChange: function(hsb, hex, rgb, el) {	       
        $('#colorpickerField<?php echo $i; ?>').css('backgroundColor', '#' + hex);        
	} 
}) 
.bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
}); 

<?php } ?>

});

</script>

<?php }

//	this function adds the settings page to the Appearance tab
add_action('admin_menu', 'add_aio_slideshow_menu');
function add_aio_slideshow_menu() {
	add_submenu_page('options-general.php', 'All-In-One Slideshow Settings', 'All-In-One Slideshow', 'upload_files', 'aio-slideshow', 'aio_slideshow_admin_page');
}

//	add "Settings" link to plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'aio_slideshow_plugin_action_links');
function aio_slideshow_plugin_action_links($links) {
	$aio_slideshow_settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=aio-slideshow' ), __('Settings') );
	array_unshift($links, $aio_slideshow_settings_link);
	return $links;
}


/*
///////////////////////////////////////////////
this function is the code that gets loaded when the
settings page gets loaded by the browser.  It calls 
functions that handle image uploads and image settings
changes, as well as producing the visible page output.
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/
function aio_slideshow_admin_page() {
	echo '<div class="wrap">';
	
		//	handle image upload, if necessary
		if($_REQUEST['action'] == 'wp_handle_upload')
			aio_slideshow_handle_upload();
		
		//	delete an image, if necessary
		if(isset($_REQUEST['delete']))
			aio_slideshow_delete_upload($_REQUEST['delete']);
		
		//	the image management form
		aio_slideshow_images_admin();
		
		//	the settings management form
		aio_slideshow_settings_admin();

	echo '</div>';
}


/*
///////////////////////////////////////////////
this section handles uploading images, adding
the image data to the database, deleting images,
and deleting image data from the database.
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/
//	this function handles the file upload,
//	resize/crop, and adds the image data to the db
function aio_slideshow_handle_upload() {
	global $aio_slideshow_settings, $aio_slideshow_images;
	
	//	upload the image
	$upload = wp_handle_upload($_FILES['aio_slideshow'], 0);
	
	//	extract the $upload array
	extract($upload);
	
	//	the URL of the directory the file was loaded in
	$upload_dir_url = str_replace(basename($file), '', $url);
	
	//	get the image dimensions
	list($width, $height) = getimagesize($file);
	
	//	if the uploaded file is NOT an image
	if(strpos($type, 'image') === FALSE) {
		unlink($file); // delete the file
		echo '<div class="error" id="message"><p>Sorry, but the file you uploaded does not seem to be a valid image. Please try again.</p></div>';
		return;
	}
	
	//	if the image doesn't meet the minimum width/height requirements ...
	if($width < $aio_slideshow_settings['img_width'] || $height < $aio_slideshow_settings['img_height']) {
		unlink($file); // delete the image
		echo '<div class="error" id="message"><p>Sorry, but this image does not meet the minimum height/width requirements. Please upload another image</p></div>';
		return;
	}
    /*
    if (isset($upload['error'])) {
        $aio_ErrorMessage = $upload['error'];
        echo '<div class="error" id="message">' . $aio_ErrorMessage . '</div>';
        return;
    }*/
    
	
	//	if the image is larger than the width/height requirements, then scale it down.
	if($width > $aio_slideshow_settings['img_width'] || $height > $aio_slideshow_settings['img_height']) {
		//	resize the image
		$resized = image_resize($file, $aio_slideshow_settings['img_width'], $aio_slideshow_settings['img_height'], true, 'resized');
		$resized_url = $upload_dir_url . basename($resized);
		//	delete the original
		unlink($file);
		$file = $resized;
		$url = $resized_url;
	}
	
	//	make the thumbnail
	$thumb_height = round((100 * $aio_slideshow_settings['img_height']) / $aio_slideshow_settings['img_width']);
	if(isset($upload['file'])) {
		$thumbnail = image_resize($file, 100, $thumb_height, true, 'thumb');
		$thumbnail_url = $upload_dir_url . basename($thumbnail);
	}
	
	//	use the timestamp as the array key and id
	$time = date('YmdHis');
	
	//	add the image data to the array
	$aio_slideshow_images[$time] = array(
		'id' => $time,
		'file' => $file,
		'file_url' => $url,
		'thumbnail' => $thumbnail,
		'thumbnail_url' => $thumbnail_url,
		'image_links_to' => ''
	);
    
	
	//	add the image information to the database
	$aio_slideshow_images['update'] = 'Added';
	update_option('aio_slideshow_images', $aio_slideshow_images);
}

//	this function deletes the image,
//	and removes the image data from the db
function aio_slideshow_delete_upload($id) {
	global $aio_slideshow_images;
	
	//	if the ID passed to this function is invalid,
	//	halt the process, and don't try to delete.
	if(!isset($aio_slideshow_images[$id])) return;
	
	//	delete the image and thumbnail
	unlink($aio_slideshow_images[$id]['file']);
	unlink($aio_slideshow_images[$id]['thumbnail']);
	
	//	indicate that the image was deleted
	$aio_slideshow_images['update'] = 'Deleted';
	
	//	remove the image data from the db
	unset($aio_slideshow_images[$id]);
	update_option('aio_slideshow_images', $aio_slideshow_images);
}


/*
///////////////////////////////////////////////
these two functions check to see if an update
to the data just occurred. if it did, then they
will display a notice, and reset the update option.
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/
//	this function checks to see if we just updated the settings
//	if so, it displays the "updated" message.
function aio_slideshow_settings_update_check() {
	global $aio_slideshow_settings;
	if(isset($aio_slideshow_settings['update'])) {
		echo '<div class="updated fade" id="message"><p>All-In-One Slideshow Settings <strong>'.$aio_slideshow_settings['update'].'</strong></p></div>';
		unset($aio_slideshow_settings['update']);
		update_option('aio_slideshow_settings', $aio_slideshow_settings);
	}
}
//	this function checks to see if we just added a new image
//	if so, it displays the "updated" message.
function aio_slideshow_images_update_check() {
	global $aio_slideshow_images;
	if($aio_slideshow_images['update'] == 'Added' || $aio_slideshow_images['update'] == 'Deleted' || $aio_slideshow_images['update'] == 'Updated') {
		echo '<div class="updated fade" id="message"><p>Image(s) '.$aio_slideshow_images['update'].' Successfully</p></div>';
		unset($aio_slideshow_images['update']);
		update_option('aio_slideshow_images', $aio_slideshow_images);
	}
}


/*
///////////////////////////////////////////////
these two functions display the front-end code
on the admin page. it's mostly form markup.
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/
//	display the images administration code
function aio_slideshow_images_admin() { ?>
	<?php global $aio_slideshow_images; ?>
	<?php aio_slideshow_images_update_check(); ?>
    <div id="icon-themes" class="icon32"></div>    
	<h2><?php _e('Images', 'aio_slideshow'); ?></h2>
    <a href="#aio-msg" class="aio-toggle-link">Please, <span style="color: #A60101; font-weight: bold;">click and read</span> before you start uploading images.</a>
	<p class="aio-toggle-msg">1. The first you have to set dimensions of the images [General / Image Dimensions].<br />
    2. Dimensions of the uploaded image should <span style="color: #AE0101; font-weight: bold;">NOT</span> be smaller than the dimensions you set below.<br />
    3. Check if you have correct file permissions set for your uploads directory (CHMOD).<br />
    4. Upload folder should be "wp-content/uploads" (check in Settings / Media / Store uploads in this folder). It should never start with a slash "/".<br />
    5. If you are still having a problem, let me know on <a href="http://www.sramekdesign.com/wordpress/plugins/all-in-one-slideshow/">my site</a> please. 
    </p>
	<table class="form-table">    
		<tr valign="top">        
        <th scope="row">Upload New Image</th>
			<td>
			<form enctype="multipart/form-data" method="post" action="?page=aio-slideshow">
				<input type="hidden" name="post_id" id="post_id" value="0" />
				<input type="hidden" name="action" id="action" value="wp_handle_upload" />
				
				<label for="aio_slideshow">Select a File: </label>
				<input type="file" name="aio_slideshow" id="aio_slideshow" />
				<input type="submit" class="button-primary" name="html-upload" value="Upload" />
			</form>
			</td>
		</tr>
	</table><br />
	
	<?php if(!empty($aio_slideshow_images)) : ?>
	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" class="column-slug">Image</th>
				<th scope="col">Image Links To</th>
                <th scope="col">Title</th>
				<th scope="col" class="column-slug">Actions</th>
			</tr>
		</thead>
		
		<tfoot>
			<tr>
				<th scope="col" class="column-slug">Image</th>
				<th scope="col">Image Links To</th>
                <th scope="col">Title</th>
				<th scope="col" class="column-slug">Actions</th>
			</tr>
		</tfoot>
		
		<tbody>
		
		<form method="post" action="options.php">
		<?php settings_fields('aio_slideshow_images'); ?>
		<?php foreach((array)$aio_slideshow_images as $image => $data) : ?>
			<tr>
				<input type="hidden" name="aio_slideshow_images[<?php echo $image; ?>][id]" value="<?php echo $data['id']; ?>" />
				<input type="hidden" name="aio_slideshow_images[<?php echo $image; ?>][file]" value="<?php echo $data['file']; ?>" />
				<input type="hidden" name="aio_slideshow_images[<?php echo $image; ?>][file_url]" value="<?php echo $data['file_url']; ?>" />
                <input type="hidden" name="aio_slideshow_images[<?php echo $image; ?>][title]" value="<?php echo $data['title']; ?>" />
				<input type="hidden" name="aio_slideshow_images[<?php echo $image; ?>][thumbnail]" value="<?php echo $data['thumbnail']; ?>" />
				<input type="hidden" name="aio_slideshow_images[<?php echo $image; ?>][thumbnail_url]" value="<?php echo $data['thumbnail_url']; ?>" />
				<th scope="row" class="column-slug"><img src="<?php echo $data['thumbnail_url']; ?>" /></th>
				<td><input type="text" name="aio_slideshow_images[<?php echo $image; ?>][image_links_to]" value="<?php echo $data['image_links_to']; ?>" size="35" /></td>
                <td><input type="text" name="aio_slideshow_images[<?php echo $image; ?>][title]" value="<?php echo $data['title']; ?>" size="35" /></td>
				<td class="column-slug"><input type="submit" class="button-primary" value="Update" /> <a href="?page=aio-slideshow&amp;delete=<?php echo $image; ?>" class="button">Delete</a></td>
			</tr>
		<?php endforeach; ?>
		<input type="hidden" name="aio_slideshow_images[update]" value="Updated" />
		</form>
		
		</tbody>
	</table>
	<?php endif; ?>

<?php
}

//	display the settings administration code
function aio_slideshow_settings_admin() { ?>

	<?php aio_slideshow_settings_update_check(); ?>
	
	<form method="post" action="options.php">
	<?php settings_fields('aio_slideshow_settings'); ?>
	<?php global $aio_slideshow_settings; $options = $aio_slideshow_settings; ?>
    
    <div class="wrap">
     <div id="icon-plugins" class="icon32"></div>
      <h2><?php _e('All-In-One Slideshow Settings', 'aio-slideshow'); ?></h2>
       <div id="poststuff" class="metabox-holder has-right-sidebar">      
          <div id="side-info-column" class="inner-sidebar">
           <div id="side-sortables" class="meta-box-sortables ui-sortable">
             <div id="submitdiv" class="postbox ">
                <!--
                <div class="handlediv" title="Click to toggle"><br /></div>
                -->
                <h3 class='hndle'><span>Save</span></h3>
                <div class="inside">          
               
                 <div id="minor-publishing" style="text-align: center;">
                 <p>For help, please visit <a href="http://www.sramekdesign.com/wordpress/plugins/all-in-one-slideshow">official plugin's site</a>.</p>
                  <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" /></p>
                 </div><!-- /#minor-publishing -->             
               
                </div><!-- /.inside -->
              </div><!-- /#submitdiv --> 
              
              
              <div id="submitdiv" class="postbox ">
                <!--
                <div class="handlediv" title="Click to toggle"><br /></div>
                -->
                <h3 class='hndle'><span>Please read:</span></h3>
                <div class="inside">          
               
                 <div class="misc-pub-section">
                 <p>Except writing plugins I also convert<br />
                 <a href="http://www.sramekdesign.com">PSD (or existing site) to Wordpress</a>.</p>
                 <p style="text-align: center;">
                 <a href="http://www.sramekdesign.com"><img src="http://www.tomsramek.com/uploads/psd-to-wpconversion.jpg" /></a></p>
                 <p>Developing and maintenance of the plugins takes a lot of time. If you find this plugin useful, please consider donation via<br />
                  <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GK36ZXQZB5CUS">Paypal</a><br />
                  <a href="http://www.sramekdesign.com/wordpress/plugins/all-in-one-slideshow">Retweet it</a><br />
                  or spread a good news about this slideshow on your site.<br />
                  If you have any problems, please contact me via <a href="http://www.sramekdesign.com/wordpress/plugins/all-in-one-slideshow">official release page</a>.<br />If you are satisfied with the plugin, please give us 5 stars feedback on Wordpress.                 
                  </ul>Thank you!<br /><br /><em>Tom Sramek</em></p> 
                 
                 </div>
                 
                </div><!-- /.inside -->
              </div><!-- /#submitdiv --> 
              
              
            </div><!-- /#side-sortables -->            
          </div><!-- /#side-info-column -->
       
    
    <div id="post-body">
    
    <div id="post-body-content">
    
    <!-- general settings /////////////////////////////////////// -->
    <div class="postbox">
    <h3 class="handle"><span>General</span></h3>
    
    <div class="inside">
	<table class="form-table">
    <tr><th scope="row">Show slideshow on these single posts:</th>
		<td> 1. Leave this field empty if you want to load the slideshow's scripts / write CSS styles into the header on all single posts.<br />
        2. Type in "1,2,3" if you want to load slidesow on single posts with IDs 1,2 and 3.<br />
        
			<input type="text" name="aio_slideshow_settings[on_posts]" value="<?php echo $options['on_posts'] ?>" />
		</td></tr>
        <tr><th scope="row">Show slideshow on these pages:</th>
		<td> 1. Leave this field empty if you want to load the slideshow's scripts / write CSS styles into the header on all pages.<br />
        2. Type in "1,2,3" if you want to load slideshow on pages with IDs 1,2 and 3.<br />
        
			<input type="text" name="aio_slideshow_settings[on_pages]" value="<?php echo $options['on_pages'] ?>" />
		</td></tr>
        
        	<tr valign="top"><th scope="row">Show on archive pages</th>
		<td><input name="aio_slideshow_settings[on_archive]" type="checkbox" value="1" <?php checked('1', $options['on_archive']); ?> /> <label for="aio_slideshow_settings[on_archive]">Check this box if you want to show your slideshow on archive pages ( is_home, is_archive(), is_author(), is_category() )</td>
		</tr>
        
		<tr valign="top"><th scope="row">Transition Enabled</th>
		<td><input name="aio_slideshow_settings[rotate]" type="checkbox" value="1" <?php checked('1', $options['rotate']); ?> /> <label for="aio_slideshow_settings[rotate]">Check this box if you want to enable the transition effects</td>
		</tr>
		
		<tr><th scope="row">Transition Effect</th>
		<td>Please select the effect you would like to use when your images rotate (if applicable):<br />
			<select name="aio_slideshow_settings[effect]">
                
                <option value="none" <?php selected('none', $options['effect']); ?>>none</option>
				<option value="blindX" <?php selected('blindX', $options['effect']); ?>>blindX</option>
                <option value="blindY" <?php selected('blindY', $options['effect']); ?>>blindY</option>
                <option value="cover" <?php selected('cover', $options['effect']); ?>>cover</option>
				<option value="curtainX" <?php selected('curtainX', $options['effect']); ?>>curtainX</option>
                <option value="curtainY" <?php selected('curtainY', $options['effect']); ?>>curtainY</option>
                <option value="fade" <?php selected('fade', $options['effect']); ?>>fade</option>                
				<option value="fadeZoom" <?php selected('fadeZoom', $options['effect']); ?>>fadeZoom</option>
                <option value="growX" <?php selected('growX', $options['effect']); ?>>growX</option>
				<option value="growY" <?php selected('growY', $options['effect']); ?>>growY</option>
                <option style="background-color: #FF99CC;" value="random" <?php selected('random', $options['effect']); ?>>Random</option>
                <option value="scrollUp" <?php selected('scrollUp', $options['effect']); ?>>scrollUp</option>
				<option value="scrollDown" <?php selected('scrollDown', $options['effect']); ?>>scrollDown</option>
				<option value="scrollLeft" <?php selected('scrollLeft', $options['effect']); ?>>scrollLeft</option>
				<option value="scrollRight" <?php selected('scrollRight', $options['effect']); ?>>scrollRight</option>
                
            
				<option value="scrollHorz" <?php selected('scrollHorz', $options['effect']); ?>>scrollHorz</option>
				<option value="scrollVert" <?php selected('scrollVert', $options['effect']); ?>>scrollVert</option>
                <option value="shuffle" <?php selected('shuffle', $options['effect']); ?>>shuffle</option>
				<option value="slideX" <?php selected('slideX', $options['effect']); ?>>slideX</option>                
                <option value="toss" <?php selected('toss', $options['effect']); ?>>toss</option>
				<option value="turnUp" <?php selected('turnUp', $options['effect']); ?>>turnUp</option>
                <option value="turnDown" <?php selected('turnDown', $options['effect']); ?>>turnDown</option>
				<option value="turnLeft" <?php selected('turnLeft', $options['effect']); ?>>turnLeft</option>
                <option value="turnRight" <?php selected('turnRight', $options['effect']); ?>>turnRight</option>
				<option value="uncover" <?php selected('uncover', $options['effect']); ?>>uncover</option>
                <option value="wipe" <?php selected('wipe', $options['effect']); ?>>wipe</option>
                <option value="zoom" <?php selected('zoom', $options['effect']); ?>>zoom</option>
			</select>
		</td></tr>
        <tr><th scope="row">Easing Effect</th>
		<td>Please select the  easing effect:<br />
			<select name="aio_slideshow_settings[easing]">
				<option value="none" <?php selected('none', $options['easing']); ?>>none</option>				
                <option value="easeOutQuad" <?php selected('easeOutQuad', $options['easing']); ?>>easeOutQuad</option>          
                <option value="easeOutQuart" <?php selected('easeOutQuart', $options['easing']); ?>>easeOutQuart</option>       
                <option value="easeInElastic" <?php selected('easeInElastic', $options['easing']); ?>>easeInElastic</option>
                <option value="easeInOutElastic" <?php selected('easeInOutElastic', $options['easing']); ?>>easeInOutElastic</option>
                <option value="easeInBack" <?php selected('easeInBack', $options['easing']); ?>>easeInBack</option>
                <option value="easeInSine" <?php selected('easeInSine', $options['easing']); ?>>easeInSine</option>
                <option value="easeInBounce" <?php selected('easeInBounce', $options['easing']); ?>>easeInBounce</option>
                <option value="easeOutBounce" <?php selected('easeOutBounce', $options['easing']); ?>>easeOutBounce</option>
                <option value="easeInOutBounce" <?php selected('easeInOutBounce', $options['easing']); ?>>easeInOutBounce</option>
			</select>
		</td></tr>
		
		<tr><th scope="row">Transition Delay</th>
		<td>Length of time (in seconds) you would like each image to be visible (insert 0 to turn automatic transitions off):<br />
			<input type="text" name="aio_slideshow_settings[delay]" value="<?php echo $options['delay'] ?>" size="4" />
			<label for="aio_slideshow_settings[delay]">second(s)</label>
		</td></tr>
		
		<tr><th scope="row">Transition Length</th>
		<td>Length of time (in seconds) you would like the transition length to be:<br />
			<input type="text" name="aio_slideshow_settings[duration]" value="<?php echo $options['duration'] ?>" size="4" />
			<label for="aio_slideshow_settings[duration]">second(s)</label>
		</td></tr>

		<tr><th scope="row">Image Dimensions</th>
		<td>Please input the width of the image rotator:<br />
			<input type="text" name="aio_slideshow_settings[img_width]" value="<?php echo $options['img_width'] ?>" size="4" />
			<label for="aio_slideshow_settings[img_width]">px</label>
			<br /><br />
			Please input the height of the image rotator:<br />
			<input type="text" name="aio_slideshow_settings[img_height]" value="<?php echo $options['img_height'] ?>" size="4" />
			<label for="aio_slideshow_settings[img_height]">px</label>
		</td></tr>
		
		<tr><th scope="row">Rotator DIV ID</th>
		<td>Please indicate what you would like the rotator DIV ID to be:<br />
			<input type="text" name="aio_slideshow_settings[div]" value="<?php echo $options['div'] ?>" />
		</td></tr>
        
        <?php $count = 0; ?>
         
         <!-- body of the table starts here -->
        <tr><th scope="row">Font</th>
        <td>Select the font:<br /> 
        <select name="aio_slideshow_settings[fontFamily]">
        <?php  foreach (glob( AIOS_FONTS . "/*") as $path_to_files) { ?>
        
        
        <?php // let's get some info within the loop
             global $font_name;
             $count++;
             $file_name = basename($path_to_files);
             $file_content = file_get_contents($path_to_files); //open file and read
             $delimeterLeft = 'font-family":"';
             $delimeterRight = '"';
             $font_name = $file_name; //aio_slideshow_font_name($file_content, $delimeterLeft, $delimeterRight, $debug = false);
             $fontFamily = aio_slideshow_font_name($file_content, $delimeterLeft, $delimeterRight, $debug = false); ?>
             
            <option value="<?php echo $font_name; ?>" <?php selected( $font_name, $options['fontFamily']); ?>><?php echo $font_name; ?></option>             
         
        <?php } ?>
        </select>
        </td></tr>
        
        </table>
        
        </div><!-- /.inside -->
    
        </div><!-- /.postbox -->
        
        <!-- navigation settings /////////////////////////////////////// -->
        <div class="postbox">
        <h3 class="handle"><span>Navigation</span></h3>
    
        <div class="inside">
        
        <table class="form-table">
        
        <tr><th scope="row">Arrows navigation</th>
		<td>Select the behavior of the arrows navigation in the gallery:<br />
			<select name="aio_slideshow_settings[arrnav]">
                <option value="dont_display" <?php selected('dont_display', $options['arrnav']); ?>>Don't display arrows navigation</option>
				<option value="allthetime" <?php selected('allthetime', $options['arrnav']); ?>>Keep displayed all the time</option>
				<!--
<option value="onrollover" <?php selected('onrollover', $options['arrnav']); ?>>Display only on rollover</option>
-->
			</select>            
		</td></tr>
        
        <tr><th scope="row">Position of numbered navigation</th>
		<td>Select the position of the numbered navigation in the gallery:<br />
			<select name="aio_slideshow_settings[numnav_position]">
                <option value="dont_display" <?php selected('dont_display', $options['numnav_position']); ?>>Don't display numbered navigation</option>
				<option value="bottom_left" <?php selected('bottom_left', $options['numnav_position']); ?>>Bottom Left</option>
				<option value="bottom_right" <?php selected('bottom_right', $options['numnav_position']); ?>>Bottom Right</option>
				<option value="top_left" <?php selected('top_left', $options['numnav_position']); ?>>Top Left</option>
				<option value="top_right" <?php selected('top_right', $options['numnav_position']); ?>>Top Right</option>			
			</select>            
		</td></tr>
        </table>
        
        </div><!-- /.inside -->
    
        </div><!-- /.postbox -->
        
        <!-- text settings /////////////////////////////////////// -->
        <div class="postbox">
        <h3 class="handle"><span>Text settings</span></h3>
    
        <div class="inside">
        
        <table class="form-table">
        <tr><th scope="row">Text position</th>
		<td>Select the position of the text in the gallery:<br />
			<select name="aio_slideshow_settings[text_position]">
                <option value="dont_display" <?php selected('dont_display', $options['text_position']); ?>>Don't display text</option>
				<option value="bottom_left" <?php selected('bottom_left', $options['text_position']); ?>>Bottom Left</option>
				<option value="bottom_right" <?php selected('bottom_right', $options['text_position']); ?>>Bottom Right</option>
				<option value="top_left" <?php selected('top_left', $options['text_position']); ?>>Top Left</option>
				<option value="top_right" <?php selected('top_right', $options['text_position']); ?>>Top Right</option>			
			</select>
            <br /><br />
			Please input the distance(vertical) from the gallery margin:<br />
			<input type="text" name="aio_slideshow_settings[textbg_margin]" value="<?php echo $options['textbg_margin'] ?>" size="4" />
			<label for="aio_slideshow_settings[textbg_margin]">%</label>
		</td></tr>
        
        <tr><th scope="row">Text align</th>
		<td>Text align:<br />
			<select name="aio_slideshow_settings[text_align]">
				<option value="left" <?php selected('left', $options['text_align']); ?>>Left</option>
				<option value="right" <?php selected('right', $options['text_align']); ?>>Right</option>
                <option value="center" <?php selected('center', $options['text_align']); ?>>Center</option>	
			</select>
		</td></tr>
        
        <tr><th scope="row">Text size</th>
		<td>Please input the size of the text:<br />
			<input type="text" name="aio_slideshow_settings[text_size]" value="<?php echo $options['text_size'] ?>" size="4" />
			<label for="aio_slideshow_settings[text_size]">px</label>			
		</td></tr>
        
        <tr valign="top"><th scope="row">Use CSS for colors</th>
		<td><input name="aio_slideshow_settings[textColorsCSS]" type="checkbox" value="1" <?php checked('1', $options['textColorsCSS']); ?> /> <label for="aio_slideshow_settings[textColorsCSS]">Check this box if you want to use CSS for colors</td>
		</tr>
        
        <tr valign="top"><th scope="row">Text shadow Enabled</th>
		<td><input name="aio_slideshow_settings[text_shadow_enabled]" type="checkbox" value="1" <?php checked('1', $options['text_shadow_enabled']); ?> /> <label for="aio_slideshow_settings[text_shadow_enabled]">Check this box if you want to enable the text shadow</td>
		</tr>
        
        <tr><th scope="row">Text background</th>
		<td>Color of the background.<br />
			<input id="colorpickerField0" type="text" name="aio_slideshow_settings[text_bg]" value="<?php echo $options['text_bg'] ?>" style="background-color: #<?php echo $options['text_bg'] ?>;" />
		</td></tr>
        
        <tr><th scope="row">Text color #1</th>
		<td>Color of the Text #1.<br />
			<input id="colorpickerField1" type="text" name="aio_slideshow_settings[text_color1]" value="<?php echo $options['text_color1'] ?>" style="background-color: #<?php echo $options['text_color1'] ?>;" />
		</td></tr>
        
        <tr><th scope="row">Text color #2</th>
		<td>Color of the Text #2.<br />
			<input id="colorpickerField2" type="text" name="aio_slideshow_settings[text_color2]" value="<?php echo $options['text_color2'] ?>" style="background-color: #<?php echo $options['text_color2'] ?>;" />
		</td></tr>     		
		<input type="hidden" name="aio_slideshow_settings[update]" value="UPDATED" />
        
        <tr><th scope="row">Text shadow</th>
		<td>Text shadow.<br />
			<input id="colorpickerField3" type="text" name="aio_slideshow_settings[text_shadow]" value="<?php echo $options['text_shadow'] ?>" style="background-color: #<?php echo $options['text_shadow'] ?>;" />
		</td></tr>   
        
        </table>
        
        </div><!-- /.inside -->
    
        </div><!-- /.postbox -->
        
        <!-- custom code /////////////////////////////////////// -->
        <div class="postbox">
        <h3 class="handle"><span>Custom code</span></h3>
    
        <div class="inside">
        
        <table class="form-table">
          		
		<input type="hidden" name="aio_slideshow_settings[update]" value="UPDATED" />
        
        <tr><th scope="row">Your CSS</th>
		<td>Input your CSS:<br />
        <textarea name="aio_slideshow_settings[your_css]" style="width: 400px; height: 200px;"><?php echo $options['your_css'] ?></textarea>		
			<label for="aio_slideshow_settings[your_css]"></label>			
		</td></tr>
	
	</table>
    
    </div><!-- /.inside -->
    
    </div><!-- /.postbox -->
    
    </div><!-- /#post-body-content -->
    
    </div><!-- /#post-body -->
    
    </div><!-- /#poststuff --> 
    
    <div style="clear: both;"></div>
    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" /></p>   
          
    </div><!-- /.wrap -->
	
	</form>


	<div class="clear"></div>
	<!-- The Reset Option -->
	<form method="post" action="options.php" style="margin: -48px 0 0 150px;" >
	<?php settings_fields('aio_slideshow_settings'); ?>
	<?php global $aio_slideshow_defaults; // use the defaults ?>
	<?php foreach((array)$aio_slideshow_defaults as $key => $value) : ?>
	<input type="hidden" name="aio_slideshow_settings[<?php echo $key; ?>]" value="<?php echo $value; ?>" />
	<?php endforeach; ?>
	<input type="hidden" name="aio_slideshow_settings[update]" value="RESET" />
	<input type="submit" class="button" style="color: #fff; background: #8B1111; text-shadow: none; font-weight: bold;" value="<?php _e('Reset Settings') ?>" />
	</form>
	<!-- End Reset Option -->
	</p>

<?php }

/*
///////////////////////////////////////////////
these two functions sanitize the data before it
gets stored in the database via options.php
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/
//	this function sanitizes our settings data for storage
function aio_slideshow_settings_validate($input) {
    $input['on_archive'] = ($input['on_archive'] == 1 ? 1 : 0);
	$input['rotate'] = ($input['rotate'] == 1 ? 1 : 0);
	$input['effect'] = wp_filter_nohtml_kses($input['effect']);
    $input['easing'] = wp_filter_nohtml_kses($input['easing']);
	$input['img_width'] = intval($input['img_width']);
	$input['img_height'] = intval($input['img_height']);
    $input['textbg_margin'] = intval($input['textbg_margin']);
	$input['div'] = wp_filter_nohtml_kses($input['div']);
    $input['title'] = ($input['title']);
    $input['text_size'] = intval($input['text_size']);
    $input['textColorsCSS'] = ($input['textColorsCSS'] == 1 ? 1 : 0);
    $input['text_shadow_enabled'] = ($input['text_shadow_enabled'] == 1 ? 1 : 0);
    $input['your_css'] = wp_filter_nohtml_kses($input['your_css']);
	
	return $input;
}
//	this function sanitizes our image data for storage
function aio_slideshow_images_validate($input) {
	foreach((array)$input as $key => $value) {
		if($key != 'update') {
			$input[$key]['file_url'] = clean_url($value['file_url']);
			$input[$key]['thumbnail_url'] = clean_url($value['thumbnail_url']);
			
			if($value['image_links_to'])
			$input[$key]['image_links_to'] = clean_url($value['image_links_to']);
            if($value['title'])
			$input[$key]['title'] = ($value['title']);
		}
	}
	return $input;
}

/*
///////////////////////////////////////////////
this final section generates all the code that
is displayed on the front-end of the WP Theme
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/
function aio_slideshow($args = array(), $content = null) {
	global $aio_slideshow_settings, $aio_slideshow_images, $value;
	 
	// possible future use
	$args = wp_parse_args($args, $aio_slideshow_settings);
	
	$newline = "\n"; // line break
	
    echo '<div id="'.$aio_slideshow_settings['div'].'">'.$newline;
    
	echo '<ul>'.$newline;
	
	foreach((array)$aio_slideshow_images as $image => $data) {
	   
        echo '<li>';
       
		if($data['image_links_to'])
		echo '<a href="'.$data['image_links_to'].'">';
		
		echo '<img src="'.$data['file_url'].'" width="'.$aio_slideshow_settings['img_width'].'" height="'.$aio_slideshow_settings['img_height'].'" class="'.$data['id'].'" alt="" />';
        
        if(($aio_slideshow_settings['text_position'] !== "dont_display") && ($data['title']) )  {
        echo '<div class="text-bg">';
		echo '<h2>';        
        echo $data['title'];     
		echo '</h2>';
        echo '</div>';
        }
		
		if($data['image_links_to'])
		echo '</a>';
		
        echo '</li>';
        
		echo $newline;
	}
	
	echo '</ul>'.$newline;
    echo '<div id="prevnext"><a id="prev" href="#prevslide">Prev</a> <a id="next" href="#nextslide">Next</a></div>';
    echo '</div>'.$newline;    
}

//	create the shortcode [aio_slideshow]
add_shortcode('aio_slideshow', 'aio_slideshow_shortcode');
function aio_slideshow_shortcode($atts) {
	
	// Temp solution, output buffer the echo function.
	ob_start();
	aio_slideshow();
	$output = ob_get_clean();
	
	return $output;
	
}


function aio_slideshow_scripts() {
    global $aio_slideshow_settings;
        
    $font_name = $aio_slideshow_settings['fontFamily'];   
    
	if(!is_admin())
	wp_enqueue_script('cycle', WP_CONTENT_URL.'/plugins/all-in-one-slideshow/jquery.cycle.all.min.js', array('jquery'), '', true);
    wp_enqueue_script('easing', WP_CONTENT_URL.'/plugins/all-in-one-slideshow/jquery.easing.1.3.js', array('jquery'), '', true);
    wp_enqueue_script('cufon-yui', WP_CONTENT_URL.'/plugins/all-in-one-slideshow/cufon/cufon-yui.js', '', '', true);
    wp_enqueue_script('cufon-font', WP_CONTENT_URL.'/plugins/all-in-one-slideshow/cufon/fonts/' . $font_name, '', '', true);
}


function aio_slideshow_args() {
	global $aio_slideshow_settings; ?>

<?php if($aio_slideshow_settings['rotate']) : ?>
<script type="text/javascript">

Cufon.replace('#<?php echo $aio_slideshow_settings['div']; ?> h2,#<?php echo $aio_slideshow_settings['div']; ?> #aio-nav', {    
    <?php  foreach (glob(AIOS_FONTS . "/*") as $path_to_files) { ?>        
        <?php
             // let's get some info within the loop
             global $font_name;
             
             $count++;
             $file_name = basename($path_to_files);
             $file_content = file_get_contents($path_to_files); //open file and read
             $delimeterLeft = 'font-family":"';
             $delimeterRight = '"';
             $font_name = $file_name; //aio_slideshow_font_name($file_content, $delimeterLeft, $delimeterRight, $debug = false);
             $fontFamily = aio_slideshow_font_name($file_content, $delimeterLeft, $delimeterRight, $debug = false);
             //echo "\n fontFamily:" . $fontFamily; "\n";
        if($font_name == $aio_slideshow_settings['fontFamily']) { ?> 
                
         fontFamily: '<?php echo $fontFamily; ?>', 
         
        <?php } } ?>   
        <?php if($aio_slideshow_settings['textColorsCSS'] == 0) { ?>
		color: '-linear-gradient(#<?php echo $aio_slideshow_settings['text_color1']; ?>, #<?php echo $aio_slideshow_settings['text_color2']; ?>)',<?php } ?>
        <?php if($aio_slideshow_settings['text_shadow_enabled']) { ?>textShadow: '2px 2px #<?php echo $aio_slideshow_settings['text_shadow']; ?>', <?php } ?>		
	});   


jQuery(document).ready(function($) {
    
	$("#<?php echo $aio_slideshow_settings['div']; ?> ul")
    <?php if($aio_slideshow_settings['numnav_position'] !== "dont_display") { ?>
    .before('<div id=aio-nav>')
    <?php } ?>
    .cycle({ 
        
        <?php if($aio_slideshow_settings['effect'] == 'random') { ?>
        fx: 'scrollUp,turnLeft,turnRight,scrollHorz,scrollVert',
        <?php } else { ?>
	    fx: '<?php echo $aio_slideshow_settings['effect']; ?>',
        <?php } ?>
        <?php if($aio_slideshow_settings['easing'] != 'none') { ?>
        easing: '<?php echo $aio_slideshow_settings['easing']; ?>',
        <?php } ?>
	    timeout: <?php echo ($aio_slideshow_settings['delay'] * 1000); ?>,
	    speed: <?php echo ($aio_slideshow_settings['duration'] * 1000); ?>,
	    pause: 1,
	    fit: 1,
        next:   '#next', 
        prev:   '#prev',
        pager:  '#aio-nav'
        
	});
});

/*prev/next animation*/
<?php if($aio_slideshow_settings['arrnav'] == "onrollover") { ?> 
$(document).ready(function() {
    //$('#<?php // echo $aio_slideshow_settings['div']; ?> #prevnext').stop().addClass('hidden');
	$('#<?php echo $aio_slideshow_settings['div']; ?>').hover(function() { //mouse in	     
        $('#<?php echo $aio_slideshow_settings['div']; ?> #prevnext').fadeIn(10);        
	}, function() { //mouse out
        $('#<?php echo $aio_slideshow_settings['div']; ?> #prevnext').fadeOut(10);
	});
});
<?php } ?>

</script>

<?php endif; ?>

<?php } // end of aio_slideshow_args()

function aio_slideshow_style() {     
    
	global $aio_slideshow_settings; ?>
	
<style type="text/css" media="screen">
    .cufon-loading  #<?php echo $aio_slideshow_settings['div']; ?> h2 {
	    visibility: hidden !important;  
        display: none !important;      
   } 
   
    /*slider*/
    #<?php echo $aio_slideshow_settings['div']; ?> {
		position: relative;
		width: <?php echo $aio_slideshow_settings['img_width']; ?>px;
		height: <?php echo $aio_slideshow_settings['img_height']?>px;
		margin: 0; padding: 0;
		overflow: hidden;
	}
    
    #<?php echo $aio_slideshow_settings['div']; ?> ul {	
		margin: 0 !important; padding: 0 !important;		
	}
    
	#<?php echo $aio_slideshow_settings['div']; ?> ul li {
		position: relative;
		width: <?php echo $aio_slideshow_settings['img_width']; ?>px;
		height: <?php echo $aio_slideshow_settings['img_height']?>px;
		margin: 0; padding: 0;
		overflow: hidden;
	}
    
    #<?php echo $aio_slideshow_settings['div']; ?> ul li .text-bg {
        position: absolute;
        min-width: 80%;
        min-height: 20px;
        <?php if($aio_slideshow_settings['text_position'] == "bottom_left") { ?>
        left: 0;
        bottom: <?php echo $aio_slideshow_settings['textbg_margin']; ?>%;
        <?php } else  if($aio_slideshow_settings['text_position'] == "bottom_right") { ?>
        right: 0;
        bottom: <?php echo $aio_slideshow_settings['textbg_margin']; ?>%;
        <?php } else  if($aio_slideshow_settings['text_position'] == "top_left") { ?>
        left: 0;
        top: <?php echo $aio_slideshow_settings['textbg_margin']; ?>%;
        <?php } else  if($aio_slideshow_settings['text_position'] == "top_right") { ?>
        right: 0;
        top: <?php echo $aio_slideshow_settings['textbg_margin']; ?>%;
        <?php } ?>
        padding: 5px 10px 5px 10px;
        text-align: <?php echo $aio_slideshow_settings['text_align']; ?>;
        background: #<?php echo $aio_slideshow_settings['text_bg']; ?>;
        opacity: 0.7;
    }
    
    #<?php echo $aio_slideshow_settings['div']; ?> ul li .text-bg h2 {
		position: relative;        
        margin: 0 0 0 0px;        
        font-size: <?php echo $aio_slideshow_settings['text_size']?>px;
        line-height: 1;
        color: #fff;
        opacity: 1.0 !important;
	}   
    
    #<?php echo $aio_slideshow_settings['div']; ?> ul li .text-bg h2 .cufon.cufon-canvas cufontext {
       opacity: 1.0 !important;
    }
    
    /*previous/next*/
   #<?php echo $aio_slideshow_settings['div']; ?> #prevnext {
        position: absolute;
        width: <?php echo $aio_slideshow_settings['img_width']; ?>px;
        height: 48px;
        top: <?php echo ($aio_slideshow_settings['img_height']/2-24); ?>px;
        text-indent: -9999px;
        left: 0px;
        z-index: 9999999;  
        <?php if($aio_slideshow_settings['arrnav'] !== "allthetime") { ?>  
        display: none; 
        <?php } ?>
    }
    
    #<?php echo $aio_slideshow_settings['div']; ?> #prevnext.hidden {
        z-index: -999999;
        opacity: 1 !important;
    }
    
    #<?php echo $aio_slideshow_settings['div']; ?> #prevnext a { position: absolute; display: block; width: 38px; height: 48px; 
        outline: none; border: none;  }

    #<?php echo $aio_slideshow_settings['div']; ?> #prevnext a#prev { left: 0px; background: url(<?php echo WP_CONTENT_URL ?>/plugins/all-in-one-slideshow/left.png) no-repeat; }
    
    #<?php echo $aio_slideshow_settings['div']; ?> #prevnext a#next { right: 0px; background: url(<?php echo WP_CONTENT_URL ?>/plugins/all-in-one-slideshow/right.png) no-repeat; }
    
    /*numbered nav*/
    #aio-nav { 
        position: absolute;  
        <?php if($aio_slideshow_settings['numnav_position'] == "bottom_left") { ?>
        left: 10px;
        bottom: 20px;
        <?php } else  if($aio_slideshow_settings['numnav_position'] == "bottom_right") { ?>
        right: 10px;
        bottom: 20px;
        <?php } else  if($aio_slideshow_settings['numnav_position'] == "top_left") { ?>
        left: 10px;
        top: 20px;
        <?php } else  if($aio_slideshow_settings['numnav_position'] == "top_right") { ?>
        right: 10px;
        top: 20px;
        <?php } ?>  
        z-index: 99999; 
        outline: none;   
        border: none;    
    }
    #aio-nav a { color: #fff; border: none; background: url(<?php echo WP_CONTENT_URL ?>/plugins/all-in-one-slideshow/pixel.png) repeat; text-decoration: none; margin: 0 5px; padding: 3px 5px; font-weight: bold; }   
    #aio-nav a.activeSlide { background: #474747; }
    #aio-nav a:focus { outline: none; }
    
    <?php echo $aio_slideshow_settings['your_css']; ?>
    
</style>

<?php } // end of aio_slideshow_style()

/**
 * next 3 functions check whethe to load slideshow scripts (since 1.3))
 */

//decide when you want to write styles into the header
add_action( 'wp_head', 'aio_slideshow_when_styles' );
function aio_slideshow_when_styles() {
   
  global $aio_slideshow_settings, $post;   
  
  //POSTS
  //if posts field is empty
  if( is_single() && $aio_slideshow_settings['on_posts'] == '' ) {   
     
     aio_slideshow_style(); 
     
  }
  
  //if posts field is not empty  
  if($aio_slideshow_settings['on_posts'] !== '') {
    
  $on_posts_list = $aio_slideshow_settings['on_posts'];
  $on_posts = explode(',', $on_posts_list);  
  if( is_single($on_posts)) {   
     
     aio_slideshow_style(); 
     
   }
   
  }
  
  //PAGES
  //if pages field is empty
  if( is_page() && $aio_slideshow_settings['on_pages'] == '' ) {   
     
     aio_slideshow_style(); 
     
  }
  
  //if pages field is not empty  
  if($aio_slideshow_settings['on_pages'] !== '') {
    
  $on_pages_list = $aio_slideshow_settings['on_pages'];
  $on_pages = explode(',', $on_pages_list);  
  if( is_page($on_pages)) {   
     
     aio_slideshow_style(); 
     
   }
   
  }
  
  //ARCHIVE
  if(($aio_slideshow_settings['on_archive']) && ( is_home() || is_archive() || is_category() || is_author() ) ) {
    
    aio_slideshow_style();
    
  }
    
}

//decide when you want hook scripts into the wp_print_scripts
add_action('wp_print_scripts', 'aio_slideshow_when_scripts');
function aio_slideshow_when_scripts() {
   
  global $aio_slideshow_settings, $post;   
  
  //POSTS
  //if posts field is empty
  if( is_single() && $aio_slideshow_settings['on_posts'] == '' ) {   
     
     aio_slideshow_scripts(); 
     
  }
  
  //if posts field is not empty  
  if($aio_slideshow_settings['on_posts'] !== '') {
    
  $on_posts_list = $aio_slideshow_settings['on_posts'];
  $on_posts = explode(',', $on_posts_list);  
  if( is_single($on_posts)) {   
     
     aio_slideshow_scripts(); 
     
   }
   
  }
  
  //PAGES
  //if pages field is empty
  if( is_page() && $aio_slideshow_settings['on_pages'] == '' ) {   
     
     aio_slideshow_scripts();  
     
  }
  
  //if pages field is not empty  
  if($aio_slideshow_settings['on_pages'] !== '') {
    
  $on_pages_list = $aio_slideshow_settings['on_pages'];
  $on_pages = explode(',', $on_pages_list);  
  if( is_page($on_pages)) {   
     
     aio_slideshow_scripts();  
     
   }
   
  }
  
  //ARCHIVE
  if(($aio_slideshow_settings['on_archive']) && ( is_home() || is_archive() || is_category() || is_author() ) ) {
    
    aio_slideshow_scripts(); 
    
  }
    
}

//decide when you want load / write scripts into the footer
add_action('wp_footer', 'aio_slideshow_when_args', 15);

function aio_slideshow_when_args() {
   
  global $aio_slideshow_settings, $post; 
  
  //POSTS
  //if posts field is empty
  if( is_single() && $aio_slideshow_settings['on_posts'] == '' ) {   
     
     aio_slideshow_args(); 
     
  }
  
  //if posts field is not empty  
  if($aio_slideshow_settings['on_posts'] !== '') {
    
  $on_posts_list = $aio_slideshow_settings['on_posts'];
  $on_posts = explode(',', $on_posts_list);  
  if( is_single($on_posts)) {   
     
     aio_slideshow_args(); 
     
   }
   
  }
  
  //PAGES
  //if pages field is empty
  if( is_page() && $aio_slideshow_settings['on_pages'] == '' ) {   
     
     aio_slideshow_args();  
     
  }
  
  //if pages field is not empty  
  if($aio_slideshow_settings['on_pages'] !== '') {
    
  $on_pages_list = $aio_slideshow_settings['on_pages'];
  $on_pages = explode(',', $on_pages_list);  
  if( is_page($on_pages)) {   
     
     aio_slideshow_args(); 
     
   }
   
  }
    

  
  //ARCHIVE
  if(($aio_slideshow_settings['on_archive']) && ( is_home() || is_archive() || is_category() || is_author() ) ) {
    
    aio_slideshow_args(); 
    
  }
    
}

/**
  * extract name of the font
  *
  */
 function aio_slideshow_font_name($inputStr, $delimeterLeft, $delimeterRight, $debug = false)
 {
     $posLeft = strpos($inputStr, $delimeterLeft);
     if ($posLeft === false)
     {
         if ($debug)
         {
             echo "Warning: left delimiter '{$delimeterLeft}' not found";
         }
         return false;
     }
     $posLeft += strlen($delimeterLeft);
     $posRight = strpos($inputStr, $delimeterRight, $posLeft);
     if ($posRight === false)
     {
         if ($debug)
         {
             echo "Warning: right delimiter '{$delimeterRight}' not found";
         }
         return false;
     }
     return substr($inputStr, $posLeft, $posRight - $posLeft);
 }
?>