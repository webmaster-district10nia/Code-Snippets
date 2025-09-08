<?php

/**
 * Make upload filenames lowercase
 *
 * Makes sure that image and file uploads have lowercase filenames.
 * 
 * This is a sample snippet. Feel free to use it, edit it, or remove it.
 */
add_filter( 'sanitize_file_name', 'mb_strtolower' );

/**
 * Disable admin bar
 *
 * Turns off the WordPress admin bar for everyone except administrators.
 * 
 * This is a sample snippet. Feel free to use it, edit it, or remove it.
 */
add_action( 'wp', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		show_admin_bar( false );
	}
} );

/**
 * Allow smilies
 *
 * Allows smiley conversion in obscure places.
 * 
 * This is a sample snippet. Feel free to use it, edit it, or remove it.
 */
add_filter( 'widget_text', 'convert_smilies' );
add_filter( 'the_title', 'convert_smilies' );
add_filter( 'wp_title', 'convert_smilies' );
add_filter( 'get_bloginfo', 'convert_smilies' );

/**
 * Current year
 *
 * Shortcode for inserting the current year into a post or page..
 * 
 * This is a sample snippet. Feel free to use it, edit it, or remove it.
 */
add_shortcode( 'code_snippets_export_4', function () {
	ob_start();
	?>

	<?php echo date('Y-m' ); ?>

	<?php
	return ob_get_clean();
} );

/**
 * Show_Form_Uploads
 */
add_action( 'pre_get_posts', 'frm_remove_media_filtering', 1 );
function frm_remove_media_filtering () { 
    remove_action( 'pre_get_posts', 'FrmProFileField::filter_media_library', 99 );
}

/**
 * Get_PostID
 */
add_action('frm_after_create_entry', 'save_attachment_id', 60, 2);
function save_attachment_id( $entry_id, $form_id ) {
        if ( $form_id == 68 ) {// Replace 68 with the ID of your form
		$entry = FrmEntry::getOne( $entry_id );

		if ( ! $entry->attachment_id ) {
			return;
		}

		FrmEntryMeta::add_entry_meta( $entry_id, 723, "", $entry->attachment_id); // Replace 723 with the ID of the field where the ID should be saved
	}
}

/**
 * Test_setCategory
 */
function test_category_update() {
	$post_id = 15728;
$uploadCategory = array(8 );
	 $post_id['post_category'] = $uploadCategory;
  wp_update_post( $post_id);
 }

/**
 * Add_Attachment_Test
 */
add_action( "add_attachment", "execute_on_add_attachment_event" , 10, 1);
function execute_on_add_attachment_event($attachment_id){
$uploadCategory = array(976);
wp_set_object_terms( $attachment_id, $uploadCategory, 'category' );}

/**
 * Attachment_Postprocessing
 */
// add_action( "add_attachment", "execute_on_add_attachment_event" , 10, 1);
function execute_on_add_attachment_event($attachment_id, $doctype, $title) {
//	echo "Attachment is " . $attachment_id . "<br>";
//	echo "Doctype is " . $doctype . "<br>";
//	$title = date('Y-m' );
	$dotitle = false;
switch ($doctype) {
  case 'Minutes':
		$uploadCategory = array(1034,974,933,923);
		$dotitle = true;
        break;
  case 'Treasurer Report':
		$uploadCategory = array(1034,935);
		$dotitle = true;
        break;
  case 'Newsletter':
		$uploadCategory = array(1034,975,922,937);
		$dotitle = true;
		break;
  case 'Flyer':
		$uploadCategory = array(1034,976);
		break;
  case 'Graphic/Image':
		$uploadCategory = array(1034,977);
		break;
  default:
    	$uploadCategory = array(1034);
		break;
}
wp_set_object_terms( $attachment_id, $uploadCategory, 'category' );
  if ($dotitle) {
  // place the current post and $new_title into array
  $post_update = array(
    'ID'         => $attachment_id,
    'post_title' => $title
  );
	
	 $updatedtitle = wp_update_post( $post_update );
	        if (is_wp_error($attachment_id)) {
                // There was an error
                echo "Error updating title";
            } else {
                // Updated successfully!
                echo "Title updated successfully with ID: " . $attachment_id . "<br>";
	        }
  }
}

/**
 * Upload_Form_Flyer_Title
 */
function myFileUploader() {
	if (isset($_POST['submit'])) {
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
//	$title_year = date('Y' );
//	$title_month = date('m');
   	$doctype = $_POST['doctype'];
	$title = $_POST['doctitle'];
	$docdesc = $_POST['docdesc'];
      $attachment_id = media_handle_upload("fileToUpload", 0); 
            if (is_wp_error($attachment_id)) {
                // There was an error uploading the file.
                echo "Error adding file";
            } else {
                // The image was uploaded successfully!
                echo "File added successfully with ID: " . $attachment_id . "<br>";
//				    echo "AttachmentID  is " . $attachment_id  . "<br>";
// if this snippet is activated, the docdesc parameter needs to be added to the execute_on_attachment
				execute_on_add_attachment_event($attachment_id, $doctype, $title,$docdesc);

  }
  } 
	echo '
     <form action="" method="post" enctype="multipart/form-data">
     
	  <input type="file" name="fileToUpload" id="fileToUpload" required></input>
	  <br>
      <label for="doctype">Choose a document type:</label>
      <select id="doctype" name="doctype" required>
	    <option value="default" disabled selected="selected" >Select from List </option>
        <option value="Newsletter">newsletter</option>
        <option value="Minutes">minutes</option>
        <option value="Treasurer Report">treasurer</option>
		<option value="Flyer">flyer</option>
        <option value="Graphic/Image">image</option>
      </select>
	  <br>
	  <br>
	  <label for="title">Month and year for title (non-graphics only):</label>
	  <input type="text" name="doctitle" id = "doctitle" ></input>
	  <br>
	  <br>
	  <label for="title">Flyer title:</label>
	  <input type="text" name="docdesc" id = "docdesc" style="width: 300px;"</input>
      <script>
	    var d = new Date();
        yyyy = d.getFullYear();
	    mm = d.getMonth() + 1;
	    if (mm < 10) {
	      mm = "0" + mm;
	    }
	    var datetitle = yyyy + "-" + mm;
		document.getElementById("doctitle").style.maxWidth = "65px";
		document.getElementById("doctitle").value = datetitle;
	  </script>
	  <br>
	  <br>
      <input type="submit" value="Upload File" name="submit"/>
	
    </form>
	
  ';
}

function myFileUploaderRenderer() {
  ob_start();
  myFileUploader();
  return ob_get_clean();
}
	
add_shortcode('custom_file_uploader', 'myFileUploaderRenderer');

/**
 * Form_Processing
 */
add_action( "processUpload", "uploadFiles" , 10, 1);
function uploadFiles() {
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    $files = $_FILES["my_file_upload"];
            $file = array(
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            );
            $_FILES = array("upload_file" => $file);
            $attachment_id = media_handle_upload("upload_file", 0);

            if (is_wp_error($attachment_id)) {
                // There was an error uploading the image.
                echo "Error adding file";
            } else {
                // The image was uploaded successfully!
                echo "File added successfully with ID: " . $attachment_id . "<br>";
                echo wp_get_attachment_image($attachment_id, array(800, 600)) . "<br>"; //Display the uploaded image with a size you wish. In this case it is 800x600
            }
} 
}

/**
 * Update_Title
 */
function update_title($attachment_ID, $new_title) {
    echo "Attachment is " . $attachment_id . "<br>";
    echo "Title is " . $doctype . "<br>";

// if new_title isn't defined, return
  if ( empty ( $new_title ) ) {
      echo "Title is empty";
	  return;
  }    

  // ensure title case of $new_title
  $new_title = mb_convert_case( $new_title, MB_CASE_TITLE, "UTF-8" );

  // if $new_title is defined, but it matches the current title, return
  if ( $attachmentID->post_title === $new_title ) {
	  echo "No change in title";
      return;
  }

  // place the current post and $new_title into array
  $post_update = array(
    'ID'         => $attachment_ID->ID,
    'post_title' => $new_title
  );
	
	 $updatedtitle = wp_update_post( $post_update );
	        if (is_wp_error($attachment_id)) {
                // There was an error uploading the image.
                echo "Error updating title";
            } else {
                // The image was uploaded successfully!
                echo "Title updated successfully with ID: " . $attachment_id . "<br>";
	   }
}
add_shortcode('test_titleUpdate', 'update_title');

/**
 * Upload_Form [Backup]
 */
function myFileUploader() {
	if (isset($_POST['submit'])) {
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
//	$title_year = date('Y' );
//	$title_month = date('m');
	$doctype = $_POST['doctype'];
      $attachment_id = media_handle_upload("fileToUpload", 0); 
            if (is_wp_error($attachment_id)) {
                // There was an error uploading the image.
                echo "Error adding file";
            } else {
                // The image was uploaded successfully!
                echo "File added successfully with ID: " . $attachment_id . "<br>";
//				    echo "AttachmentID  is " . $attachment_id  . "<br>";
				execute_on_add_attachment_event($attachment_id, $doctype);

  }
  }
  echo '
    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="fileToUpload" id="fileToUpload" required>
	  <br>
      <label for="doctype">Choose a document type:</label>
      <select id="doctype" name="doctype" required>
	    <option value="default" disabled selected="selected" >Select from List </option>
        <option value="Newsletter">newsletter</option>
        <option value="Minutes">minutes</option>
        <option value="Treasurer Report">treasurer</option>
		<option value="Flyer">flyer</option>
        <option value="Graphic/Image">image</option>
      </select>
	  <br>
<!--
      <label for="title_year">Title:</label>
      <label for="title_year_month">Month and year for title:</label>
      <input type="month" id="title_year_month" name="title_year_month">
	  
      <input type="number" min="2000" max="2099" step="1" value= "2023" /> 
	  <input type="number" min="01" max = "12" step ="1" value = "03"size="20" /> -->
	  <br>
	  <br>
      <input type="submit" value="Upload File" name="submit"/>
    </form>
  ';
}

function myFileUploaderRenderer() {
  ob_start();
  myFileUploader();
  return ob_get_clean();
}
	
add_shortcode('custom_file_uploader', 'myFileUploaderRenderer');

/**
 * Upload_Form_no_Flyer_Desc
 */
function myFileUploader() {
	if (isset($_POST['submit'])) {
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
//	$title_year = date('Y' );
//	$title_month = date('m');
   	$doctype = $_POST['doctype'];
	$title = $_POST['doctitle'];	
      $attachment_id = media_handle_upload("fileToUpload", 0); 
            if (is_wp_error($attachment_id)) {
                // There was an error uploading the file.
                echo "Error adding file";
            } else {
                // The image was uploaded successfully!
                echo "File added successfully with ID: " . $attachment_id . "<br>";
//				    echo "AttachmentID  is " . $attachment_id  . "<br>";
				execute_on_add_attachment_event($attachment_id, $doctype, $title);

  }
  } 
	echo '
     <form action="" method="post" enctype="multipart/form-data">
     
	  <input type="file" name="fileToUpload" id="fileToUpload" required></input>
	  <br>
      <label for="doctype">Choose a document type:</label>
      <select id="doctype" name="doctype" required>
	    <option value="default" disabled selected="selected" >Select from List </option>
        <option value="Newsletter">newsletter</option>
        <option value="Minutes">minutes</option>
        <option value="Treasurer Report">treasurer</option>
		<option value="Flyer">flyer</option>
        <option value="Graphic/Image">image</option>
      </select>
	  <br>
	  <br>
	  <label for="title">Month and year for title (non-graphics only):</label>
	  <input type="text" name="doctitle" id = "doctitle" ></input>
      <script>
	     var d = new Date();
        yyyy = d.getFullYear();
	    mm = d.getMonth() + 1;
	    if (mm < 10) {
	      mm = "0" + mm;
	    }
	    var datetitle = yyyy + "-" + mm;
		document.getElementById("doctitle").style.maxWidth = "65px";
		document.getElementById("doctitle").value = datetitle;
		var foobar = document.getElementById("doctype").value
		window.alert(foobar)
	  </script>
	  <br>
	  <br>
      <input type="submit" value="Upload File" name="submit"/>
	
    </form>
	
  ';
}

function myFileUploaderRenderer() {
  ob_start();
  myFileUploader();
  return ob_get_clean();
}
	
add_shortcode('custom_file_uploader', 'myFileUploaderRenderer');

/**
 * Formidable Validate Group # Entry
 */
add_filter('frm_validate_field_entry', 'my_custom_validation', 10, 3);
function my_custom_validation($errors, $posted_field, $posted_value){
//	 echo("Validating field".$posted_field->id." ".$posted_value);
  if ( $posted_field->id == 747){ //change 25 to the ID of the field to validate
	//echo("Validating New Group # field");
    //check the $posted_value here
    //$words = explode(' ', $posted_value); //separate at each space
    //$count = count($words); //count each word
     $count = strlen($posted_value); //uncomment this line to count characters instead of words
     //echo("Count is ".$count);
    //uncomment the next two lines create a minimum value and error message
    if($count < 9 && $count > 3) { //change "100" to fit your minimum limit 
		$errors['field'. $posted_field->id] = 'That group number is too short.';
	}
	if($count > 9) {
		$errors['field'. $posted_field->id] = 'That group number is too long.';
    }
		//comment the next two lines if you only want a minimum value and error message
  //  if($count > 10) { //change "300" to fit your maximum limit
  //     $errors['field'. $posted_field->id] = 'That group number is too long.';
  //  uncomment both pairs above to create a range
  }
	 if ( $posted_field->id == 750 && $posted_value != '' ){ 
     $count = strlen($posted_value); //uncomment this line to count characters instead of words
     //echo("Count is ".$count);
    //uncomment the next two lines create a minimum value and error message
    if($count < 6)  //change "100" to fit your minimum limit 
		$errors['field'. $posted_field->id] = 'This group number is too short.';
	if($count > 6)
		$errors['field'. $posted_field->id] = 'This group number is too long.';
	  }
  return $errors;
}

/**
 * CV Pro Link to Full Image
 */
// Content Views Pro - thumbnail link to its full image
add_filter( 'pt_cv_field_href', 'cvp_theme_thumbnail_link_itself', 100, 2 );
function cvp_theme_thumbnail_link_itself( $href, $post ) {
	$trace = debug_backtrace();	
	if ( !empty($trace[5]['function']) && $trace[5]['function'] === '_field_thumbnail' ) {
		$full_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		if ( !empty( $full_img[0] ) ) {
				$href = $full_img[0];
		}
	}

	return $href;
}

/**
 * CV Pro Direct Media Link
 */
// Content Views Pro - Link Media to direct file, instead of media page
add_filter( 'pt_cv_field_href', 'cvp_theme_link_media_file_directly', 100, 2 );
function cvp_theme_link_media_file_directly( $args, $post ) {
	if ( get_post_type( $post->ID ) === 'attachment' ) {
		$url = wp_get_attachment_url( $post->ID );
		if ( $url ) {
			$args = $url;
		}
	}
	return $args;
}

/**
 * Test Formidable API
 */
import district10nia.org

api = district10nia.authorize('IO6T-ELZD-QF1A-T0N3')
api.district10nia.get()

/**
 * Enable Formidable URL Export
 */
add_action(
	'wp_ajax_nopriv_frm_entries_csv',
	function() {
		$allowed_form_id = 20;
		$form_id = FrmAppHelper::get_param( 'form', '', 'get', 'absint' );
		if ( $form_id !== $allowed_form_id ) {
			return;
		}		

		add_filter(
			'user_has_cap',
			function( $caps ) {
				$caps['frm_view_entries'] = true;
				return $caps;
			}
		);
	},
	9
);

/**
 * TSML CSV Download
 */
add_action('wp_ajax_nopriv_csv', function () {
    //going to need this later
    global $tsml_days, $tsml_programs, $tsml_program, $tsml_sharing, $tsml_export_columns, $tsml_custom_meeting_fields;

    //get data source
    $meetings = tsml_get_meetings([], false, true);

    //helper vars
    $delimiter = ',';
    $escape = '"';

    // allow user-defined fields to be exported
    if (!empty($tsml_custom_meeting_fields)) {
        $tsml_export_columns = array_merge($tsml_export_columns, $tsml_custom_meeting_fields);
    }

    //do header
    $return = implode($delimiter, array_values($tsml_export_columns)) . PHP_EOL;

    //get the preferred time format setting
    $time_format = get_option('time_format');

    //append meetings
    foreach ($meetings as $meeting) {
        $line = [];
        foreach ($tsml_export_columns as $column => $value) {
            if (in_array($column, ['time', 'end_time'])) {
                $line[] = empty($meeting[$column]) ? null : date($time_format, strtotime($meeting[$column]));
            } elseif ($column == 'day') {
                $line[] = $tsml_days[$meeting[$column]];
            } elseif ($column == 'types') {
                $types = !empty($meeting[$column]) ? $meeting[$column] : [];
                if (!is_array($types)) $types = [];
                foreach ($types as &$type) {
                    $type = $tsml_programs[$tsml_program]['types'][trim($type)];
                }
                sort($types);
                $line[] = $escape . implode(', ', $types) . $escape;
            } elseif (strstr($column, 'notes')) {
                $line[] = $escape . strip_tags(str_replace($escape, str_repeat($escape, 2), !empty($meeting[$column]) ? $meeting[$column] : '')) . $escape;
            } elseif (array_key_exists($column, $meeting)) {
                $line[] = $escape . str_replace($escape, '', $meeting[$column]) . $escape;
            } else {
                $line[] = '';
            }
        }
        $return .= implode($delimiter, $line) . PHP_EOL;
    }

    //headers to trigger file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="meetings.csv"');

    //output
    wp_die($return);
});

/**
 * Create_Page
 */
function create_page($title_of_the_page,$content,$parent_id = NULL ) 
{
    $objPage = get_page_by_title($title_of_the_page, 'OBJECT', 'page');
    if( ! empty( $objPage ) )
    {
        echo "Page already exists:" . $title_of_the_page . "<br/>";
        return $objPage->ID;
    }
    
    $page_id = wp_insert_post(
            array(
            'comment_status' => 'close',
            'ping_status'    => 'close',
            'post_author'    => 1,
            'post_title'     => ucwords($title_of_the_page),
            'post_name'      => sanitize_title('title_of_the_page'), 
            'post_status'    => 'publish',
            'post_content'   => $content,
            'post_type'      => 'page',
            'post_parent'    =>  $parent_id //'id_of_the_parent_page_if_it_available'
            )
        );
    echo "Created page_id=". $page_id." for page '".$title_of_the_page. "'<br/>";
    return $page_id;
}

create_page( 'How it works', 'This is how it works');
// create_page( 'Contact Us', 'The contact us page');
// create_page( 'About Us', 'The about us page');
// create_page( 'Team', 'The team page');
$pid = create_page( 'Sample Page', 'This is sample page');
create_page( 'Sample SubPage 1', 'This is sample SubPage 1',$pid);
create_page( 'Sample SubPage 2', 'This is sample SubPage 2',$pid);

/**
 * Save PayPal Transaction ID
 */
add_action('frm_payment_paypal_ipn', 'save_frmpaypal_transaction_id');
function save_frmpaypal_transaction_id( $vars ){
    if ( ! $vars['pay_vars']['completed'] ) {
        return; //don't change value if the payment was not completed
    }
    if ( $vars['entry']->form_id == 81 ) {
        $field_id = 941;
        $transaction_id = $vars['pay_vars']['receipt_id'];
        if ( $transaction_id ) {
            FrmEntryMeta::add_entry_meta( $vars['entry']->id, $field_id, null, $transaction_id );
        }
    }
}

/**
 * Disable XML-RPC
 */
// disable xmlrpc
function remove_xmlrpc_methods( $methods ) {
  return array();
}
add_filter( 'xmlrpc_methods', 'remove_xmlrpc_methods' );

/**
 * ARC Address Override
 */
if (function_exists('tsml_custom_addresses')) {
    tsml_custom_addresses(array(
        '466 W Illinois Route 173' => array(
            'formatted_address' => '466 W Route 173, Antioch, IL',
            'city' => 'Antioch',
            'latitude' => 42.4740932,
            'longitude' => -88.1033171,
            'approximate' => 'no',
        ),
    ));
}

/**
 * Post-Upload_Attachment_Processing
 */
// add_action( "add_attachment", "execute_on_add_attachment_event" , 10, 1);
function execute_on_add_attachment_event($attachment_id, $doctype, $title) {
//	echo "Attachment is " . $attachment_id . "<br>";
//	echo "Doctype is " . $doctype . "<br>";
//	$title = date('Y-m' );
//change the following line to the current year category at the start of each year, yrcat=1036 is 2025, 1041 is 2026
    $yrcat = 1036;
	$dotitle = false;
switch ($doctype) {
  case 'Minutes':
		$uploadCategory = array($yrcat,974,933,923);
		$dotitle = true;
        break;
  case 'Treasurer Report':
		$uploadCategory = array($yrcat,935);
		$dotitle = true;
        break;
  case 'Newsletter':
		$uploadCategory = array($yrcat,975,922,937);
		$dotitle = true;
		break;
  case 'Flyer':
		$uploadCategory = array($yrcat,976);
		break;
  case 'Graphic/Image':
		$uploadCategory = array($yrcat,977);
		break;
  default:
    	$uploadCategory = array($yrcat);
		break;
}
wp_set_object_terms( $attachment_id, $uploadCategory, 'category' );
  if ($dotitle) {
  // place the current post and $new_title into array
  $post_update = array(
    'ID'         => $attachment_id,
    'post_title' => $title
  );
	
	 $updatedtitle = wp_update_post( $post_update );
	        if (is_wp_error($attachment_id)) {
                // There was an error
                echo "Error updating title";
            } else {
                // Updated successfully!
                echo "Title updated successfully with ID: " . $attachment_id . "<br>";
	        }
  }
}

/**
 * Embed PDF on CV Pro Page
 */
// Content Views Pro - Show PDF media as embed object
add_filter( 'pt_cv_attachment_thumbnail', 'cvp_theme_pdf_preview', 100, 3 );
function cvp_theme_pdf_preview( $attachment, $post, $dimensions ) {
	$pdf = wp_get_attachment_url( $post->ID );
	if ( strpos( $pdf, '.pdf' ) !== false ) {
		$attachment = sprintf( '<object data="%1$s" type="application/pdf" width="100%%" height="100%%"><p>This browser does not support PDFs. Please <a href="%1$s">Download file</a>.</p></object>', esc_url( $pdf ) );
	}

	return $attachment;
}

/**
 * File Upload Handler
 */
add_action( "processUpload", "my_custom_file_upload_action" , 10, 1);
function my_custom_file_upload_action( $attachment_id ) {
    // Get attachment metadata
    $message = "Running code now!";
    echo "<script type='text/javascript'>alert('$message');</script>";
    $attachment = get_post( $attachment_id );
    $file_path = get_attached_file( $attachment_id );
    $file_mime_type = get_post_mime_type( $attachment_id );
     wp_mail( 'webmaster@district10nia.org', 'New File Upload', 'A new Formidable Form file has been uploaded: ' . $file_path );
    // Perform actions based on the uploaded file
    // Example: send an email notification about the upload
   if ($str_contains($file_path,'formidable')) {
	    wp_mail( 'webmaster@district10nia.org', 'New File Upload', 'A new Formidable Form file has been uploaded: ' . $file_path );
		}
    //    wp_mail( 'admin@example.com', 'New File Upload', 'A new file has been uploaded: ' . $file_path );

    // Example: add custom metadata to the attachment
    //   update_post_meta( $attachment_id, 'custom_meta_field', 'some_value' );

    // Example: resize the image (for image uploads)
    //   if ( strpos( $file_mime_type, 'image/' ) !== false ) {
    // ... (image resizing code)
    }
}
add_action( 'add_attachment', 'my_custom_file_upload_action' );

/**
 * Modify Metadata after Upload
 */
add_action('frm_after_create_entry', 'modify_uploaded_file_metadata', 10, 2);
function modify_uploaded_file_metadata($entry_id, $form_id) {
    // Replace 'YOUR_FILE_UPLOAD_FIELD_ID' with the actual ID of your file upload field
    $file_field_id = 1076; 

    // Get the uploaded file's attachment ID from the entry
    $attachment_id = FrmEntryMeta::get_entry_meta_by_field($entry_id, $file_field_id);

    if ($attachment_id) {
        // Get existing metadata
        $metadata = wp_get_attachment_metadata($attachment_id);

        // Modify or add custom metadata
        $metadata['description'] = 'Foo E. Barr';
        // You can add other standard or custom metadata fields here

        // Update the attachment metadata in the database
        wp_update_attachment_metadata($attachment_id, $metadata);
    }
}

/**
 * Add Metadata to Upload
 */
add_action('frm_after_create_entry', 'add_uploaded_file_metadata', 30, 2);
function add_uploaded_file_alt( $entry_id, $form_id ) {
        if ( $form_id == 88 ) { //replace 88 with the id of the form
		// Get all uploaded file attachment IDs
            $media_ids = $_POST['item_meta'][1076]; //Replace 1076 with the ID of your file upload field
		foreach ( (array)$media_ids as $id ) {
			if ( ! $id ) {
				continue;
			}
			// Assign title
			$title = $_POST['item_meta'][1065]; //Field 1065 has the event name for title
			$mydata = array(
  				'ID' => $id,
			    'post_title' => $title
			);
			wp_update_post($mydata);
		  }
      }
}

/**
 * Get Flyer IDs
 */
function getflyerids() {
$args = array(
        'type' => 'attachment',
//        'category_name' => 'Flyer'
        );
    $attachments = get_posts($args);
    echo <p>$attachments</p>;
}
add_shortcode('getIDs', 'getflyerids');

/**
 * New After Upload Metadata
 */
add_action('frm_after_create_entry', 'add_uploaded_file_alt', 30, 2);
function add_uploaded_file_alt( $entry_id, $form_id ) {
        if ( $form_id == 88 ) { //replace 5 with the id of the form
		// Get all uploaded file attachment IDs
            $media_ids = $_POST['item_meta'][1076];//Replace 519 with the ID of your file upload field
		foreach ( (array)$media_ids as $id ) {
			if ( ! $id ) {
				continue;
			}
			// Assign title
			$title = $_POST['item_meta'][1065];
			$mydata = array(
  				'ID' => $id,
			    'post_title' => $title
			);
			wp_update_post($mydata);
		  }
	    }
}

/**
 * Flyer Upload Metadata - Flyer Only
 */
add_action('frm_after_create_entry', 'add_uploaded_file_alt', 30, 2);
function add_uploaded_file_alt( $entry_id, $form_id ) {
        if ( $form_id == 88 ) { //replace 85 with the id of the form
		// Get all uploaded file attachment IDs
            $media_ids = $_POST['item_meta'][1076];//Replace 996 with the ID of your file upload field
		foreach ( (array)$media_ids as $id ) {
			if ( ! $id ) {
				continue;
			}
			// Assign title
			$title = $_POST['item_meta'][1065]; //Replace 990 with the ID of the event name field
			$meta_key_1 = 'expiry_check';
         	$meta_value_1 = true;
			$meta_key_2 = 'expiry_date';
			$email = $_POST['item_meta'][1063]; //Replace with the ID of the email field
			$emailwrite = 'Submitted by: '.$email;
			$mydata = array(
  				'ID' => $id,
			    'post_title' => $title
//				'post_content' => $emailwrite
			);
			wp_update_post($mydata);
			wp_set_object_terms($id,976,'category');
//			echo ('<p>WP Post ID is ' . $id . '</p>' );
//			echo ('<p>Formidable Entry ID is ' . $entry_id . '</p>');
			FrmEntryMeta::add_entry_meta( $entry_id, 1092, "", $id);//change 998 to the ID of the field to store postID
			$expdate = $_POST['item_meta'][1090]; //Replace 997 with the ID of the expiration date field
			if (!empty($expdate)) {
		    $date = str_replace('/', '-', $expdate);
            $meta_value_2 = date('Y-m-d', strtotime($expdate));
			add_post_meta( $id, $meta_key_1, $meta_value_1 );
			add_post_meta( $id, $meta_key_2, $meta_value_2 );
//			echo ('<p> Date is ' . $meta_value_2 . '</p>');
		    	} else {
			echo ('No expiration date was specified!');
			}
		  }
	    }
}
add_shortcode('file_meta', 'add_uploaded_file_alt');

/**
 * Flyer Upload Metadata - Event
 */
add_action('frm_after_create_entry', 'add_uploaded_file_meta_event', 30, 2);
function add_uploaded_file_meta_event( $entry_id, $form_id ) {
        if ( $form_id == 87 ) { //replace 85 with the id of the form
		// Get all uploaded file attachment IDs
            $media_ids = $_POST['item_meta'][1051];//Replace 996 with the ID of your file upload field
		foreach ( (array)$media_ids as $id ) {
			if ( ! $id ) {
				continue;
			}
			// Assign title
			$title = $_POST['item_meta'][1056]; //Replace 990 with the ID of the event name field
			$meta_key_1 = 'expiry_check';
         	$meta_value_1 = true;
			$meta_key_2 = 'expiry_date';
			$email = $_POST['item_meta'][1060]; //Replace with the ID of the email field
			$emailwrite = 'Submitted by: '.$email;
			$mydata = array(
  				'ID' => $id,
			    'post_title' => $title
//				'post_content' => $emailwrite
			);
			wp_update_post($mydata);
			wp_set_object_terms($id,976,'category');
			echo ('<p>WP Post ID is ' . $id . '</p>' );
			echo ('<p>Formidable Entry ID is ' . $entry_id . '</p>');
			FrmEntryMeta::add_entry_meta( $entry_id, 1094, "", $id);//change 998 to the ID of the field to store postID
			$expdate = $_POST['item_meta'][1095]; //Replace 997 with the ID of the expiration date field
			if (!empty($expdate)) {
		    $date = str_replace('/', '-', $expdate);
            $meta_value_2 = date('Y-m-d', strtotime($expdate));
			add_post_meta( $id, $meta_key_1, $meta_value_1 );
			add_post_meta( $id, $meta_key_2, $meta_value_2 );
			echo ('<p> Date is ' . $meta_value_2 . '</p>');
		    	} else {
			echo ('No expiration date was specified!');
			}
		  }
	    }
}
add_shortcode('file_meta', 'add_uploaded_event_file_alt');

/**
 * Clear Formidable Form Entries
 */
// Replace 123 with the actual ID of your Formidable Form
$form_id = 89; 

// Get all entries for the specified form
$entries = FrmEntry::getAll( array( 'form_id' => $form_id ) );

// Loop through each entry and delete it
if ( $entries ) {
    foreach ( $entries as $entry ) {
        FrmEntry::destroy( $entry->id );
    }
    echo "All entries for Form ID " . $form_id . " have been deleted.";
} else {
    echo "No entries found for Form ID " . $form_id . ".";
}
