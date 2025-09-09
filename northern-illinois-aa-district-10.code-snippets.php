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
//causes Media Library to show attachments uploaded via forms
add_action( 'pre_get_posts', 'frm_remove_media_filtering', 1 );
function frm_remove_media_filtering () { 
    remove_action( 'pre_get_posts', 'FrmProFileField::filter_media_library', 99 );
}

/**
 * Upload_Form_Flyer_Title
 */
//form to capture upload metadata, passes to attachment post-processor to update upload metadata
//this version captures flyer title as well as doctype and year-month
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
 * Upload_Form [Backup]
 */
//form to capture upload metadata, passes to attachment post-processor to update upload metadata; this is an older version
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
//form to capture upload metadata, passes to attachment post-processor to update upload metadata
//Used on https://district10nia.org/upload_page/
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
//this function ensures group number is valid 9-digit number
//was used with Formidable Form ID 69 when we were validating group numbers
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
 * Enable Formidable URL Export
 */
//code that allows formidable form entries to be exported
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
//code that allows TSML (Online Meeting list) entries to be exported
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
 * Save PayPal Transaction ID
 */
//code that captures PayPal transaction ID for district contribution transactions
//assumes the transaction was submitted on Formidable Form ID 81
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
// address override for ARC for online meeting guide because of Google geocoder error
// Google geocoder was placing meeting in wrong place (466 E Route 173 instead of W places in Spring Grove instead of Antioch)
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
// adds submitted metadata to uploads submitted on https://district10nia.org/upload_page/
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
 * Flyer Upload Metadata - Flyer Only
 */
// adds metadata to flyer data submitted on Formidable Form ID 88
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
// adds metadata to event and flyer data submitted on Formidable Form ID 87
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

/**
 * Upload Document Metadata
 */
// adds metadata to flyer data submitted on Formidable Form ID 88 (flyer) or 90 (reports and newsletter)
add_action('frm_after_create_entry', 'add_uploaded_doc_metadata', 30, 2);
function add_uploaded_doc_metadata( $entry_id, $form_id ) {
		// Get all uploaded file attachment IDs
		 if ( $form_id == 90 ) { //replace 85 with the id of the form
//change the following line to the current year category at the start of each year, yrcat=1036 is 2025, 1041 is 2026
     $yrcat = 1036;
     $media_ids = $_POST['item_meta'][1121];//Replace 996 with the ID of your file upload field
		foreach ( (array)$media_ids as $id ) {
			if ( ! $id ) {
				continue;
			}
			// Assign title
			$doctype = $_POST['item_meta'][1125]; //Replace 990 with the ID of the document type field
			$title = $_POST['item_meta'][1128]; //Replace 990 with the ID of the month field
			$mydata = array(
  				'ID' => $id,
			    'post_title' => $title
			);
			wp_update_post($mydata);
// switch for title value here		
			switch ($doctype) {
            case 'Minutes':
		$uploadCategory = array($yrcat,974,933,923);
        break;
  case 'Treasurer Report':
		$uploadCategory = array($yrcat,935);
        break;
  case 'Newsletter':
		$uploadCategory = array($yrcat,975,922,937);
		break;		
	default:
    	$uploadCategory = array($yrcat);
		break;
			}
//end switch
			wp_set_object_terms($id,$uploadCategory,'category');
//			echo ('<p>WP Post ID is ' . $id . '</p>' );
//			echo ('<p>Formidable Entry ID is ' . $entry_id . '</p>');
			FrmEntryMeta::add_entry_meta( $entry_id, 1123, "", $id);//change 998 to the ID of the field to store postID
		  }
//end foreach
	    }
//end main
}
add_shortcode('file_meta', 'add_uploaded_doc_metadata');
