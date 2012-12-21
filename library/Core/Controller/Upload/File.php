<?php

class Core_Controller_Upload_File extends Core_Controller_Action {
	
	protected $options;
	protected $_scriptUrl;
	protected $_uploadDir;
	protected $_uploadUrl;
	protected $_paramName = 'files';
	protected $_maxFileSize = NULL;
	protected $_minFileSize = 1;
	protected $_acceptFileTypes = '/.+$/i';
	protected $_maxNumberOfFiles = Null;
	protected $_thumbnailUploadDir;
	protected $_thumbnailUploadUrl;
	protected $_thumbnailMaxWidth = 190;
	protected $_thumbnailMaxHeight = 190;
	
	public function initializeAttributes() {
		$id = $this->_getParam ( "id" );
		$this->_scriptUrl = $this->view->baseUrl () . '/product/file/index/id/' . $id;
		$this->_uploadUrl = $this->view->baseUrl () . '/survey/images/';
		$this->_uploadDir = APPLICATION_PATH . '/../public/survey/images/';
		$this->_thumbnailUploadDir = APPLICATION_PATH . '/../public/survey/images/thumbnails/';
		$this->_thumbnailUploadUrl = $this->view->baseUrl () . '/survey/images/thumbnails/';
	
	}
	
	public function preInsert() {
	
	}
	
	public function postInsert($info = array()) {
	
	}
	
	public function postDelete($filename = "") {
	
	}
	
	public function preDelete($filename) {
	}
	
	public function getImages() {
		$id = $this->_getParam ( 'id', 0 );
		$images = array ();
		return $images;
	}
	
	public function init($options = null) {
		$this->_helper->viewRenderer->setNoRender ( true );
		$this->initializeAttributes ();
		$this->options = array ('script_url' => $this->_scriptUrl, 'upload_dir' => $this->_uploadDir, 'upload_url' => $this->_uploadUrl, 'param_name' => $this->_paramName, 'max_file_size' => $this->_maxFileSize, 'min_file_size' => $this->_minFileSize, 'accept_file_types' => $this->_acceptFileTypes, 'max_number_of_files' => $this->_maxNumberOfFiles, 'discard_aborted_uploads' => true, 'image_versions' => array (

		'thumbnail' => array ('upload_dir' => $this->_thumbnailUploadDir, 'upload_url' => $this->_thumbnailUploadUrl, 'max_width' => $this->_thumbnailMaxWidth, 'max_height' => $this->_thumbnailMaxHeight ) ) );
		
		if ($options) {
			$this->options = array_replace_recursive ( $this->options, $options );
		}
	}
	
	public function indexAction() {
		
		if ($this->_request->isPost ()) {
			$this->post ();
		}
		if ($this->_request->isGet ()) {
			$this->get ();
		}
		if ($this->_request->isDelete () || $_SERVER ['REQUEST_METHOD'] == 'DELETE') {
			$this->delete ();
		}
		exit ();
	}
	
	public function get() {
		
		$file_name = isset ( $_REQUEST ['file'] ) ? basename ( stripslashes ( $_REQUEST ['file'] ) ) : null;
		if ($file_name) {
			$info = $this->get_file_object ( $file_name );
		} else {
			$info = $this->get_file_objects ();
		}
		header ( 'Content-type: application/json' );
		echo json_encode ( $info );
	
	}
	
	public function post() {
		$this->preInsert ();
		$upload = isset ( $_FILES [$this->options ['param_name']] ) ? $_FILES [$this->options ['param_name']] : null;
		$info = array ();
		$names = array ();
		if ($upload && is_array ( $upload ['tmp_name'] )) {
			foreach ( $upload ['tmp_name'] as $index => $value ) {
				
				$info1 = pathinfo ( $upload ['name'] [$index] );
				$file_name = basename ( $upload ['name'] [$index], '.' . $info1 ['extension'] );
				$name = $file_name . '_' . time () . '.' . $info1 ['extension'];
				$names [] = $name;
				$info [] = $this->handle_file_upload ( $upload ['tmp_name'] [$index], $name, isset ( $_SERVER ['HTTP_X_FILE_SIZE'] ) ? $_SERVER ['HTTP_X_FILE_SIZE'] : $upload ['size'] [$index], isset ( $_SERVER ['HTTP_X_FILE_TYPE'] ) ? $_SERVER ['HTTP_X_FILE_TYPE'] : $upload ['type'] [$index], $upload ['error'] [$index] );
			}
		} elseif ($upload) {
			$name = time () . uniqid ();
			$names [] = $name;
			$info [] = $this->handle_file_upload ( $upload ['tmp_name'], $name, isset ( $_SERVER ['HTTP_X_FILE_SIZE'] ) ? $_SERVER ['HTTP_X_FILE_SIZE'] : $upload ['size'], isset ( $_SERVER ['HTTP_X_FILE_TYPE'] ) ? $_SERVER ['HTTP_X_FILE_TYPE'] : $upload ['type'], $upload ['error'] );
		}
		
		$json = json_encode ( $info );
		$this->postInsert ( $info, $names );
		if (isset ( $_SERVER ['HTTP_ACCEPT'] ) && (strpos ( $_SERVER ['HTTP_ACCEPT'], 'application/json' ) !== false)) {
			header ( 'Content-type: application/json' );
		} else {
			header ( 'Content-type: text/plain' );
		}
		echo $json;
	}
	
	public function delete() {
		$file_name = isset ( $_REQUEST ['file'] ) ? basename ( stripslashes ( $_REQUEST ['file'] ) ) : null;
		$file_path = $this->options ['upload_dir'] . $file_name;
		$this->preDelete ( $file_name );
		$success = is_file ( $file_path ) && $file_name [0] !== '.' && unlink ( $file_path );
		if ($success) {
			foreach ( $this->options ['image_versions'] as $version => $options ) {
				$file = $options ['upload_dir'] . $file_name;
				if (is_file ( $file )) {
					
					unlink ( $file );
				
				}
			
			}
		
		}
		$this->postDelete ( $file_name );
		
		header ( 'Content-type: application/json' );
		echo json_encode ( $success );
	
	}
	
	// core functions
	/**
	 *
	 * @param type $file_name
	 * @return stdClass 
	 * 
	 */
	public function get_file_object($file_name) {
		$file_path = $this->options ['upload_dir'] . $file_name;
		if (is_file ( $file_path ) && $file_name [0] !== '.') {
			$file = new stdClass ();
			$file->name = $file_name;
			$file->size = filesize ( $file_path );
			$file->url = $this->options ['upload_url'] . rawurlencode ( $file->name );
			foreach ( $this->options ['image_versions'] as $version => $options ) {
				if (is_file ( $options ['upload_dir'] . $file_name )) {
					$file->{$version . '_url'} = $options ['upload_url'] . rawurlencode ( $file->name );
				}
			}
			$file->delete_url = $this->options ['script_url'] . '?file=' . rawurlencode ( $file->name );
			$file->delete_type = 'DELETE';
			return $file;
		}
		return null;
	}
	
	public function get_file_objects() {
		
		$images = $this->getImages ();
		return array_values ( array_filter ( array_map ( array ($this, 'get_file_object' ), $images ) ) );
	}
	
	public function create_scaled_image($file_name, $options) {
		$file_path = $this->options ['upload_dir'] . $file_name;
		$new_file_path = $options ['upload_dir'] . $file_name;
		list ( $img_width, $img_height ) = @getimagesize ( $file_path );
		if (! $img_width || ! $img_height) {
			return false;
		}
		$scale = min ( $options ['max_width'] / $img_width, $options ['max_height'] / $img_height );
		if ($scale > 1) {
			$scale = 1;
		}
		$new_width = $img_width * $scale;
		$new_height = $img_height * $scale;
		$new_img = @imagecreatetruecolor ( $new_width, $new_height );
		switch (strtolower ( substr ( strrchr ( $file_name, '.' ), 1 ) )) {
			case 'jpg' :
			case 'jpeg' :
				$src_img = @imagecreatefromjpeg ( $file_path );
				$write_image = 'imagejpeg';
				break;
			case 'gif' :
				@imagecolortransparent ( $new_img, @imagecolorallocate ( $new_img, 0, 0, 0 ) );
				$src_img = @imagecreatefromgif ( $file_path );
				$write_image = 'imagegif';
				break;
			case 'png' :
				@imagecolortransparent ( $new_img, @imagecolorallocate ( $new_img, 0, 0, 0 ) );
				@imagealphablending ( $new_img, false );
				@imagesavealpha ( $new_img, true );
				$src_img = @imagecreatefrompng ( $file_path );
				$write_image = 'imagepng';
				break;
			default :
				$src_img = $image_method = null;
		}
		$success = $src_img && @imagecopyresampled ( $new_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height ) && $write_image ( $new_img, $new_file_path );
		// Free up memory (imagedestroy does not delete files):
		@imagedestroy ( $src_img );
		@imagedestroy ( $new_img );
		return $success;
	}
	
	public function has_error($uploaded_file, $file, $error) {
		if ($error) {
			return $error;
		}
		if (! preg_match ( $this->options ['accept_file_types'], $file->name )) {
			return 'acceptFileTypes';
		}
		if ($uploaded_file && is_uploaded_file ( $uploaded_file )) {
			$file_size = filesize ( $uploaded_file );
		} else {
			$file_size = $_SERVER ['CONTENT_LENGTH'];
		}
		if ($this->options ['max_file_size'] && ($file_size > $this->options ['max_file_size'] || $file->size > $this->options ['max_file_size'])) {
			return 'maxFileSize';
		}
		if ($this->options ['min_file_size'] && $file_size < $this->options ['min_file_size']) {
			return 'minFileSize';
		}
		if (is_int ( $this->options ['max_number_of_files'] ) && (count ( $this->get_file_objects () ) >= $this->options ['max_number_of_files'])) {
			return 'maxNumberOfFiles';
		}
		return $error;
	}
	
	public function trim_file_name($name, $type) {
		// Remove path information and dots around the filename, to prevent uploading
		// into different directories or replacing hidden system files.
		// Also remove control characters and spaces (\x00..\x20) around the filename:
		$file_name = trim ( basename ( stripslashes ( $name ) ), ".\x00..\x20" );
		// Add missing file extension for known image types:
		if (strpos ( $file_name, '.' ) === false && preg_match ( '/^image\/(gif|jpe?g|png|pjpeg|x-png)/', $type, $matches )) {
			if ($matches [1] == 'x-png') {
				$matches [1] = 'png';
			}
			if ($matches [1] == 'pjpeg') {
				$matches [1] = 'jpeg';
			}
			$file_name .= '.' . $matches [1];
		}
		return $file_name;
	}
	
	public function handle_file_upload($uploaded_file, $name, $size, $type, $error) {
		$file = new stdClass ();
		$file->name = $this->trim_file_name ( $name, $type );
		$file->size = intval ( $size );
		$file->type = $type;
		$error = $this->has_error ( $uploaded_file, $file, $error );
		if (! $error && $file->name) {
			$file_path = $this->options ['upload_dir'] . $file->name;
			$append_file = ! $this->options ['discard_aborted_uploads'] && is_file ( $file_path ) && $file->size > filesize ( $file_path );
			clearstatcache ();
			if ($uploaded_file && is_uploaded_file ( $uploaded_file )) {
				if ($append_file) {
					file_put_contents ( $file_path, fopen ( $uploaded_file, 'r' ), FILE_APPEND );
				} else {
					move_uploaded_file ( $uploaded_file, $file_path );
				}
			} else {
				file_put_contents ( $file_path, fopen ( 'php://input', 'r' ), $append_file ? FILE_APPEND : 0 );
			}
			$file_size = filesize ( $file_path );
			if ($file_size === $file->size) {
				$file->url = $this->options ['upload_url'] . rawurlencode ( $file->name );
				foreach ( $this->options ['image_versions'] as $version => $options ) {
					if ($this->create_scaled_image ( $file->name, $options )) {
						$file->{$version . '_url'} = $options ['upload_url'] . rawurlencode ( $file->name );
					}
				}
			} else if ($this->options ['discard_aborted_uploads']) {
				unlink ( $file_path );
				$file->error = 'abort';
			}
			$file->size = $file_size;
			$file->delete_url = $this->options ['script_url'] . '?file=' . rawurlencode ( $file->name );
			$file->delete_type = 'DELETE';
		} else {
			$file->error = $error;
		}
		return $file;
	}

}

