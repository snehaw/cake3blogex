<?php
namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Behavior;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Core\Exception\Exception;
use Cake\Routing\Router;
use Cake\Utility\Hash;

class UploadBehavior extends Behavior{

	public $_defaultConfig = [
		'allowedFileSize' 	  => '8', // allowed max File size default 8
		'allowedFileSizeUnit' => 'MB', // file size unit that is allowed MB or KB
		'allowedFileType'  	  => '*', // * or array of file mime types allowed.
		'allowedExt'  	  	  => '*', // * or an array of file extensions allowed.
		'fileDataName'		  => 'image', // key name in entity that contains file array
		'uploadDirPath'		  => 'img/uploads', // upload directory path relative to webroot
		'renameNewFile'		  => true, // default:true Allow rename of new Uploaded File by adding 'key' to the end of the filename.
		'key'				  => 'id', // default:id key to be added at the end of filename if renameNewFile == true
		'deleteExistingFile'  => true, // default:true (works only if renameNewFile == true)delete file that has the same key as that of the new uploaded file.(allow to have one file per entity)
		'deepFolderPath'	  => true, // default:true Allow to have deep folder path for the uploads (eg. 2015/03/filename.ext)
	];


	public function uploadFile(Entity $entity)
	{
		// if fileRename is set true
		$renameNewFile 	= $this->_config['renameNewFile'];
		$fileDataName  	= $this->_config['fileDataName'];
		$uploadDirPath 	= $this->_config['uploadDirPath'];
		$deepFolderPath = $this->_config['deepFolderPath'];
		$fileInfo 	   	= $entity->get($fileDataName);
		$dir 		   	= new Folder(WWW_ROOT . $uploadDirPath, true, 0755);
		$year = date('Y');
		$month = date('m');

		if ($deepFolderPath) {

			// Check whether the folder exists
			if ( !empty($dir->read(true))  && in_array($year, $dir->read(true)[0])) {
				$dir->cd($year);
			} else {
				$dir->create($year, 0777);
				$dir->cd($year);
			}

			if (!empty($dir->read(true))  && in_array($month, $dir->read(true)[0])) {
				$dir->cd($month);
			} else {
				$dir->create($month, 0777);
				$dir->cd($month);
			}
		}

		if ($renameNewFile) {
			$key = $this->_config['key'];			

			// find files for particular type and delete
			$deleteExistingFile = $this->_config['deleteExistingFile'];
			if ($deleteExistingFile) {
				$uploadedFileList = $dir->find("^.*(?=-".$entity->get($key).".).*$", true);
				foreach ($uploadedFileList as $file) {
				    $file = new File($dir->pwd() . DS . $file);
				    $file->delete(); 
				    $file->close();
				}				
			}

			// copy the Uploaded file from tmp directory
			$fileUrl = false;
			$filepath = new File($fileInfo['tmp_name']);
			$arr = explode('.', $fileInfo['name']);
			$cnt = count($arr);
			$arr[$cnt-2]  = $arr[$cnt-2] . '-' . $entity->get($key);
			$newFileName = implode('.', $arr);
			// echo $entity->get('first_name') .'_'. $entity->get('last_name') .'-' . $entity->get($key) . '.' .$arr[$cnt-1];
			if ($filepath->copy($dir->pwd() . DS . $newFileName  , true)) {
				if ($deepFolderPath) {
					$fileUrl = Router::url('/' . $uploadDirPath . DS . $year . DS . $month . DS . $newFileName , true);
				} else {
					$fileUrl = Router::url('/' . $uploadDirPath . DS . $newFileName , true);				
				}
			}
			$filepath->close();
		} else {

			$fileUrl     = false;
			$filepath    = new File($fileInfo['tmp_name']);
			$newFileName = $fileInfo['name'];

			if ($filepath->copy($dir->pwd() . DS . $newFileName  , true)) {
				if ($deepFolderPath) {
					$fileUrl = Router::url('/' . $uploadDirPath . DS . $year . DS . $month . DS . $newFileName , true);
				} else {
					$fileUrl = Router::url('/' . $uploadDirPath . DS . $newFileName , true);
					$fileUrl = Router::url('/' . $uploadDirPath . DS . $newFileName , true);				
				}
			}
			$filepath->close();
		}

		return $fileUrl;
		// return false;
	}

	public function beforeSave(Event $event, Entity $entity)
	{
		$fileInfo = $entity->get($this->_config['fileDataName']);
		$tableName =  $this->_table->registryAlias();

		echo "<pre>";
		// print_r($event);
		print_r($entity->toArray());
		echo "</pre>";

		// Check Upload errors
		$error = $this->checkError($fileInfo);
		if( $error ){
			throw new Exception($error);
			// $event->stopPropagation();
			// return;
		}

		// Check file size
		$filesize = $this->checkFileSize($fileInfo);
		if ( $filesize ) {
			throw new Exception(__('File size error.'));
		}

		// Check File Type
		$mimeType = $this->checkMimeType($fileInfo);
		if ($mimeType) {
			throw new Exception(__('File type error.'));	
		}

		$filepath =  $this->uploadFile($entity);
		if (!$filepath) {
			$entity->set('image', '');
			throw new Exception(__('File was not uploaded some error occured.'));
		} else {
			$entity->set('image', $filepath);
		}
	}

	public function checkError($fileInfo)
	{
		$error = $fileInfo['error'];
		$message = false;
		if ($error > 0) {
			switch ($error) { 
	            case UPLOAD_ERR_INI_SIZE: 
	                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
	                break; 
	            case UPLOAD_ERR_FORM_SIZE: 
	                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
	                break; 
	            case UPLOAD_ERR_PARTIAL: 
	                $message = "The uploaded file was only partially uploaded"; 
	                break; 
	            case UPLOAD_ERR_NO_FILE: 
	                $message = "No file was uploaded"; 
	                break; 
	            case UPLOAD_ERR_NO_TMP_DIR: 
	                $message = "Missing a temporary folder"; 
	                break; 
	            case UPLOAD_ERR_CANT_WRITE: 
	                $message = "Failed to write file to disk"; 
	                break; 
	            case UPLOAD_ERR_EXTENSION: 
	                $message = "File upload stopped by extension"; 
	                break;
	        } 
		}
        return $message; 
	}


	public function checkFileSize($fileInfo)
	{
		$factor = (1028 * 1028);
		if($this->_config['allowedFileSizeUnit'] === 'KB'){
			$factor = 1028;
		} else {
			$factor = 1028 * 1028;
		}
		
		if(($factor * (int)$this->_config['allowedFileSize']) < $fileInfo['size']) {
			return true;
		}
		return false;
	}


	public function checkMimeType($fileInfo)
	{
		echo $fileInfo['type'];
		$config = $this->_config['allowedFileType'];
		if (!is_array($config) || $config == '*') {
			return false;
		}

		if (in_array($fileInfo['type'], $config)) {
			return false;
		} elseif ($allowedMime == $fieldData[$fileField]['type']) {
            return false;
        }        
		return true;
	}


}


