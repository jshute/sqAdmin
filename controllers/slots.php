<?php

class slots extends controller {
	public $layout = 'admin/layouts/main';
	
	public function filter($action) {
		if ($action == 'login' || $action == 'logout') {
			sq::controller('auth')->action($action);
		}
		
		if (!$this->options['require-login'] || auth::check('admin')) {
			return true;
		} else {
			return sq::view('admin/login');
		}
	}
	
	public function indexAction() {
		$this->layout->content = sq::model('sq_slots')
			->read();
	}
	
	public function updateAction($id) {
		$slot = sq::model('sq_slots', array('layout' => 'admin/slots/edit'))
			->find($id);
		
		if (sq::request()->isPostRequest) {
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
			
			sq::redirect(sq::base().'admin/slots');
		} else {
			$this->layout->content = $slot;
		}
	}
}

?>