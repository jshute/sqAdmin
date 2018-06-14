<?php

class slots extends admin {
	public $layout = 'admin/layouts/main';

	public function init() {
		parent::init();

		sq::load('/defaults/slot');
	}

	public function indexAction($model = null, $where = null, $value = null) {
		$this->layout->content = sq::model('sq_slots')->all();
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

					sq::model('files', ['variations' => []])
						->upload($_FILES['file'], 'slots/', $slot->id.$ext);
					$content = 'uploads/slots/'.$slot->id.$ext;
				} else {
					$content = $slot->content;
				}

				$slot->alt_text = sq::request()->post('alt_text');
			}

			$slot->content = $content;
			$slot->update();

			sq::response()
				->flash('Slot saved successfully', 'success')
				->redirect(sq::base().'admin/slots');
		} else {
			$this->layout->content = $slot;
		}
	}
}
