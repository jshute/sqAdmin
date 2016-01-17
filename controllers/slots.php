<?php

class slots extends controller {
	public $layout = 'admin/layouts/main';
	
	public function filter($action) {
		if ($action == 'login' || $action == 'logout') {
			sq::controller('auth')->action($action);
		}
		
		if (!$this->options['require-login'] || sq::auth()->level == 'admin') {
			return true;
		} else {
			return sq::view('admin/login');
		}
	}
	
	public function init() {
		$this->layout->modelName = 'slots';
	}
	
	public function indexAction() {
		sq::load('/defaults/slot');
		$this->layout->content = sq::model('sq_slots')->read();
	}
	
	public function updateAction($id) {
		$slot = sq::model('sq_slots')->find($id);
		$slot->layout = sq::view('admin/slots/edit');
		
		if (sq::request()->isPost) {
			$content = sq::request()->post('content');
			
			if ($slot->type == 'image') {
				if (isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
					$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
					$ext = '.'.strtolower($ext);
					
					sq::model('files', array('variations' => array()))
						->upload($_FILES['file'], 'slots/', $slot->id.$ext);
					$content = 'uploads/slots/'.$slot->id.$ext;
				} else {
					$content = $slot->content;
				}
				
				$slot->alt_text = sq::request()->post('alt_text');
			}
			
			$slot->content = $content;
			$slot->update();
			
			sq::response()->redirect(sq::base().'admin/slots');
		} else {
			$this->layout->content = $slot;
		}
	}
}

?>
