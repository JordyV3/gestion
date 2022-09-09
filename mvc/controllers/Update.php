<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update extends Admin_Controller {

	protected $_memoryLimit = '1024M';
	protected $_downloadPath = FCPATH.'uploads/update';
	protected $_downloadFileWithPath = '';
	protected $_downloadExtractPath = '';

	function __construct() {
		parent::__construct();
		$this->load->model("update_m");
		$this->load->model("signin_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('update', $language);
		if(config_item('demo')) {
			$this->session->set_flashdata('error', 'El módulo de actualización del DEMO está deshabilitado');
			redirect(base_url('dashboard/index'));
		}
	}

	public function index()
	{
		ini_set('memory_limit', $this->_memoryLimit);
		if(isset($_FILES["file"]['name']) && $_FILES["file"]['name'] != '') {
			$this->htmlDesign('none', false);
			$browseFileUpload = $this->browseFileUpload($_FILES);
			if($browseFileUpload->status) {
				if (file_exists($this->_downloadFileWithPath)) {
					$fileUnZip = $this->fileUnZip();
					if($fileUnZip->status) {
						$manageFile = $this->manageFile($browseFileUpload);
						if($manageFile->status) {
							$databaseUpdate = $this->databaseUpdate();
							if($databaseUpdate->status) {
								if($databaseUpdate->version != 'none') {
					    			$array = [
					    				'version' => $databaseUpdate->version,
					    				'date' => date('Y-m-d H:i:s'),
					    				'userID' =>  $this->session->userdata('loginuserID'),
					    				'usertypeID' => $this->session->userdata('usertypeID'),
					    				'status' => 1,
					    				'log' => $this->updateLog(),
					    			];
			    					$this->update_m->insert_update($array);
									$this->deleteZipAndFile($this->_downloadFileWithPath);
									$this->signin_m->signout();
									redirect(base_url("signin/index"));
								} else {
									$this->deleteZipAndFile($this->_downloadFileWithPath);
									$this->signin_m->signout();
									redirect(base_url("signin/index"));
								}
							} else {
								$this->deleteZipAndFile($this->_downloadFileWithPath);
								$this->signin_m->signout();
								redirect(base_url("signin/index"));
							}
						} else {
							$this->session->set_flashdata('error', 'Falló en la distribución de archivosed');
			    			redirect(base_url('update/index'));
						}
					} else {
						$this->session->set_flashdata('error', 'Falló al extraer archivos');
			    		redirect(base_url('update/index'));
					}
				} else {
					$this->session->set_flashdata('error', 'El archivo de carga no existe');
			    	redirect(base_url('update/index'));
				}
			} else {
				$this->session->set_flashdata('error', $browseFileUpload->message);
				redirect(base_url('update/index'));
			}
		} else {
			$this->data['updates'] = $this->update_m->get_update();
			$this->data["subview"] = "update/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

    public function autoupdate()
    {
    	ini_set('memory_limit', $this->_memoryLimit);
    	if($this->session->userdata('usertypeID') == 1 && $this->session->userdata('loginuserID') == 1) {
    		if($this->session->userdata('updatestatus')) {
    			if(count($postDatas = @$this->postData())) {
					$versionChecking = $this->versionChecking($postDatas);
					if($versionChecking->status) {
						if($versionChecking->version != 'none') {
							$this->htmlDesign($versionChecking);
							$fileDwonload = $this->fileDwonload($versionChecking);
							if(!empty($fileDwonload)) {
								$filePush = $this->filePush($versionChecking, $fileDwonload);
								if($filePush->status) {
									if (file_exists($this->_downloadFileWithPath)) {
										$fileUnZip = $this->fileUnZip();
										if($fileUnZip->status) {
						    				$manageFile = $this->manageFile($versionChecking);
						    				if($manageFile->status) {
								    			$this->databaseUpdate();
								    			$array = [
								    				'version' => $versionChecking->version,
								    				'date' => date('Y-m-d H:i:s'),
								    				'userID' =>  $this->session->userdata('loginuserID'),
								    				'usertypeID' => $this->session->userdata('usertypeID'),
								    				'status' => 1,
								    				'log' => $this->updateLog(),
								    			];
								    			$this->update_m->insert_update($array);
								    			$this->deleteZipAndFile($this->_downloadFileWithPath);
								    			
								    			if(count($postDatas)) {
								    				$postDatas['updateversion'] = $versionChecking->version;
								    				$this->successProvider($postDatas);
								    			}
								    			$this->signin_m->signout();
												redirect(base_url("signin/index"));
								    		} else {
								    			$this->session->set_flashdata('error', 'Falló en la distribución de archivos');
			    								redirect(base_url('dashboard/index'));
								    		}
										} else {
											$this->session->set_flashdata('error', 'Falló al extraer archivos');
			    							redirect(base_url('dashboard/index'));
										}
									} else {
			    						$this->session->set_flashdata('error', 'Descargar, archivo no existe');
			    						redirect(base_url('dashboard/index'));
									}
								} else {
									$this->session->set_flashdata('error', $filePush->message);
		    						redirect(base_url('dashboard/index'));
								}
							} else {
								$this->session->set_flashdata('error', 'Falló la descarga del archivo');
		    					redirect(base_url('dashboard/index'));	
							}
						} else {
							$this->session->set_flashdata('success', 'Estás utilizando la última versión');
		    				redirect(base_url('dashboard/index'));
						}
					} else {
						$this->session->set_flashdata('error', 'Actualización de sincronización fallida');
		    			redirect(base_url('dashboard/index'));
					}
				} else {
		    		$this->session->set_flashdata('error', 'No se encontraron datos de la publicación');
		    		redirect(base_url('dashboard/index'));
				}
    		} else {
	    		$this->session->set_flashdata('error', 'Solo el administrador del sistema principal puede actualizar este sistema.');
	    		redirect(base_url('dashboard/index'));
    		}
    	} else {
    		$this->session->set_flashdata('error', 'Por favor inicie sesión a través del administrador del sistema principal');
    		redirect(base_url('dashboard/index'));
    	}
    }

    public function getloginfo()
    {
    	$text = '';
    	$updateID = $this->input->post('updateID');
    	$update = $this->update_m->get_single_update(array('updateID' => $updateID));
    	if(count($update)) {
    		$text = $update->log;
    	}

    	echo $text;
    }

    private function browseFileUpload($file)
	{
		$returnArray['status'] = false;; 
		$returnArray['version'] = 'none';
		$returnArray['message'] = 'Archivo no encontrado';

		if(isset($file['file'])) {
	      	$fileName 		= $file['file']['name'];
	      	$fileSize 		= $file['file']['size'];
	      	$fileTmp 		= $file['file']['tmp_name'];
	      	$fileType 		= $file['file']['type'];
	     	$endArray 		= explode('.', $file['file']['name']);
	     	$fileExt 		= strtolower(end($endArray));

	      	$extensions 	= array("zip");
	      	$maxFileSize 	= 1073741824;

	      	if(in_array($fileExt, $extensions)) {
		      	if($fileSize <= $maxFileSize) {
		      		move_uploaded_file($fileTmp, $this->_downloadPath.'/'.$fileName);
		      		$this->_downloadFileWithPath = $this->_downloadPath.'/'.$fileName;
		      		$returnArray['status'] = true;
		      		$returnArray['version'] = str_replace('.zip', '', $fileName);
		      		$returnArray['message'] = 'Success';
		      	} else {
	         		$returnArray['message'] = "Su tamaño máximo de archivo es 1 GB";
		      	}
	      	} else {
	         	$returnArray['message'] = "Por favor, elija un archivo zip";
	      	}
	   	} 

	   	return (object) $returnArray;
	}

	private function versionChecking($postDatas) 
	{
		$result = array(
			'status' => false,
			'message' => 'Error',
			'version' => 'none'
		);

		$postDataStrings = json_encode($postDatas);       
		$ch = curl_init($this->_versionCheckingUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");       
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataStrings);                       
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                           
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
			    'Content-Type: application/json',
			    'Content-Length: ' . strlen($postDataStrings)
			)
		);
		
		$result = curl_exec($ch);
		curl_close($ch);
		if(count($result)) {
			$result = json_decode($result, true);
		}
		return (object) $result;
	}

	private function htmlDesign($versionChecking, $versionShow = TRUE)
	{
		$this->load->config('iniconfig');
		echo '<html>';
			echo '<head>';
				echo '<title>'.$this->lang->line('panel_title').'</title>';
				echo '<link rel="SHORTCUT ICON" href="'.base_url('uploads/images/'.$this->data['siteinfos']->photo).'" />';
				echo '<link href="'.base_url('assets/bootstrap/bootstrap.min.css').'" rel="stylesheet">';
				echo '<link href="'.base_url($this->data['backendThemePath'].'/style.css').'" rel="stylesheet">';
				echo '<link href="'.base_url($this->data['backendThemePath'].'/lesson.css').'" rel="stylesheet">';
				echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
				echo '<style type="text/css">.progress { margin: 10px;max-width: 100%; } .content { padding : 20px; }</style>';
			echo '</head>';
			echo '<body>';
				echo '<div class="content">';
					echo '<div class="row">';
						echo '<div class="col-sm-offset-2 col-sm-8">';
							echo '<div class="jumbotron">';
								echo '<center><p style="font-size:20px"><img style="widht:50px;height:50px" src="'.base_url('uploads/images/'.$this->data['siteinfos']->photo).'"></p></center>';
								echo '<center><p style="font-size:20px;color:#1A2229">'.$this->data['siteinfos']->sname.'</p></center>';
								echo '<center><p style="font-size:14px;color:#1A2229">'.$this->data['siteinfos']->address.'</p></center>';
								if($versionShow) {
									echo '<center><p style="font-size:12px" class="text-green">Su sistema se está actualizando '.config_item('ini_version').' to '.$versionChecking->version.'</p></center>';
								}
								echo '<center><p style="font-size:12px">-! Por favor espere unos minutos !-</p></center>';

								echo '<div class="progress">';
			  						echo '<div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">';
			    						echo '<span id="current-progress"></span>';
			  						echo '</div>';
								echo '</div>';

								echo '<p style="font-size:12px;padding-top:10px;padding-left:15px;"> 1. No cierres esta pagina</p>';
								echo '<p style="font-size:12px;padding-left:15px;"> 2. No recargar esta pagina</p>';
								echo '<p style="font-size:12px;padding-left:15px;"> 3. No abra otra pestaña de su sistema</p>';
								echo '<p style="font-size:12px;padding-left:15px;"> 4. Cuando se complete el proceso de actualización, se redirigirá a la página de inicio de sesión</p>';
								echo '<script type="text/javascript">';
									echo '$(function() {
									  	var current_progress = 0;
									  	var interval = setInterval(function() {
									    	current_progress += 1;
									    	$("#dynamic")
									    	.css("width", current_progress + "%")
									    	.attr("aria-valuenow", current_progress)
									    	.text(current_progress + "% Completado");
									    	if (current_progress >= 100)
									        	clearInterval(interval);
									  	}, 18000);
									});';
								echo '</script>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</body>';
		echo '</html>';
	}

	private function fileDwonload($result)
	{
		ini_set('memory_limit', $this->_memoryLimit);
		$this->_updateFileUrl = $this->_updateFileUrl.$result->version.'.zip';
		$curlCh = curl_init();
		curl_setopt($curlCh, CURLOPT_URL, $this->_updateFileUrl);
		curl_setopt($curlCh, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlCh, CURLOPT_SSLVERSION,3);
		$curlData = curl_exec($curlCh);
		curl_close ($curlCh);
		return $curlData;
	}

	private function filePush($result, $curlData)
    {
    	$returnArray['status'] = false;
    	$returnArray['message'] = 'Error';
      	$downloadPath = FCPATH.'uploads/update/'.$result->version.'.zip';
      	$permissionCheckingPath = FCPATH.'uploads/update/index.html';
      	if(file_exists($permissionCheckingPath)) {
      		try {
		      	if($file = @fopen($downloadPath, 'w+')) {
				    fputs($file, $curlData);
					fclose($file);

					$this->_downloadFileWithPath = $downloadPath;
					$returnArray['status'] = true;
		    		$returnArray['message'] = 'Success';
		      	} else {
		      		$returnArray['message'] = 'El permiso para subir carpetas tiene el rechazo';
		      	}
      		} catch(Expetion $e) {
      			$returnArray['message'] = 'El permiso para subir carpetas tiene el rechazo';
      		}
      	} else {
    		$returnArray['message'] = 'El permiso para subir carpetas tiene el rechazo';
      	}

      	return (object) $returnArray;
    }

    private function fileUnZip()
    {
    	$returnArray['status'] = false;
    	$returnArray['message'] = 'Error';
    	$zip = new ZipArchive;
    	if($zip->open($this->_downloadFileWithPath) === TRUE) {
	    	$zip->extractTo($this->_downloadPath);
	    	$zip->close();
	    	$returnArray['status'] = true;
			$returnArray['message'] = 'Success';
		} else {
			$returnArray['message'] = 'La actualización zip no se encuentra';
		}

		return (object) $returnArray;
    }

    private function manageFile($versionChecking)
    {
    	$returnArray['status'] = false;
    	$returnArray['message'] = 'Falló en la distribución de archivos';
    	$destination = FCPATH;
		$destination = rtrim($destination,'/');
		$this->_downloadExtractPath = $this->_downloadPath.'/'.$versionChecking->version.'/';
		if($this->smartCopy($this->_downloadExtractPath, $destination)) {
			$returnArray['status'] = true;
    		$returnArray['message'] = 'Success';
		}

		return (object) $returnArray;
    }

    private function smartCopy($source, $dest, $options=array('folderPermission'=>0777,'filePermission'=>0777)) 
    {
        $result=false;

        if (is_file($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if (!file_exists($dest)) {
                    cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
                }
                $__dest=$dest."/".basename($source);
            } else {
                $__dest=$dest;
            }
            $result=copy($source, $__dest);
            @chmod($__dest,$options['filePermission']);
        } elseif(is_dir($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if ($source[strlen($source)-1]=='/') {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest=$dest.basename($source);
                    @mkdir($dest);
                    @chmod($dest,$options['filePermission']);
                }
            } else {
                if ($source[strlen($source)-1]=='/') {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    @chmod($dest,$options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    @chmod($dest,$options['filePermission']);
                }
            }

            $dirHandle=opendir($source);
            while($file=readdir($dirHandle))
            {
                if($file!="." && $file!="..")
                {
                     if(!is_dir($source."/".$file)) {
                        $__dest=$dest."/".$file;
                    } else {
                        $__dest=$dest."/".$file;
                    }
                    $result=$this->smartCopy($source."/".$file, $__dest, $options);
                }
            }
            closedir($dirHandle);
        } else {
            $result = false;
        }
        return $result;
    }

    private function databaseUpdate()
    {
    	$returnArray['status'] = false; 
    	$returnArray['version'] = 'ninguna'; 
    	$returnArray['message'] = 'Versión desconocida'; 
		return (object) $returnArray;
    }

	private function deleteZipAndFile($filePathAndName)
	{
		$returnArray['status'] = false;
    	$returnArray['message'] = 'Error';

		try {
			
			if(file_exists($filePathAndName)) {
				unlink($filePathAndName);
				$filePathAndName = str_replace(".zip","", $filePathAndName);
				$this->rmdirRecursive($filePathAndName);
				$this->EmptyFolder(APPPATH.'libraries/upgrade/');
			}

			$returnArray['status'] = true;
		    $returnArray['message'] = 'Success';
		} catch(Expetion $e) {
			$returnArray['message'] = 'Problema de permiso de eliminación de archivos';
		}

		return (object) $returnArray;
	}

	private function rmdirRecursive($dir) 
	{
	    if (!file_exists($dir)) {
	        return true;
	    }

	    if (!is_dir($dir)) {
	        return unlink($dir);
	    }

	    foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') {
	            continue;
	        }

	        if (!$this->rmdirRecursive($dir . DIRECTORY_SEPARATOR . $item)) {
	            return false;
	        }
	    }

	    return rmdir($dir);
	}

	private function EmptyFolder($dir)
	{
		foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') {
	            continue;
	        }
	        unlink($dir.$item);
	    }
	    return true;
	}

}
