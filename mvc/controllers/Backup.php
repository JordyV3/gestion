<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends Admin_Controller {

	protected $_memoryLimit = '1024M';

	function __construct() {
		parent::__construct();
		$language = $this->session->userdata('lang');
        $this->load->model("teacher_m");
        $this->load->model("parents_m");
        $this->load->model("student_m");
        $this->load->model("user_m");
        $this->load->model("book_m");
		$this->lang->load('backup', $language);
	}

	public function index() {
		ini_set('memory_limit', $this->_memoryLimit);
		if ($_POST) {
			if(config_item('demo') == FALSE) {
				$this->load->dbutil();
				$prefs = array(
					 'format'        => 'zip',
	            	'filename'    => 'lesson.sql'
	          	);
				$backup =& $this->dbutil->backup($prefs);
				$this->load->helper('download');
				force_download('lesson.zip', $backup);
				redirect(base_url('backup/index'));
			} else {
				$this->session->set_flashdata('error', 'El módulo de copia de seguridad del DEMO está desactivada');
				redirect(base_url('backup/index'));
			}
		} else {
		     $this->data["subview"] = "backup/index";
		     $this->load->view('_layout_main', $this->data);
		}
	}
}