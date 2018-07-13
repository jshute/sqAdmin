<?php

class options extends admin {
	public $layout = 'admin/layouts/main';

	public function init() {
		parent::init();

		sq::load('/defaults/option');
	}

	public function indexGetAction() {
		$model = sq::model('sq_options')->all();
		$model->layout = sq::view('admin/options/edit');

		sq::widget('breadcrumbs')->add('Options');

        $this->layout->content = $model;
	}

	public function indexPostAction() {
		foreach (sq::request()->post('options') as $id => $val) {
			sq::model('sq_options')->find($id)->save(['value' => $val]);
		}

		sq::response()
			->flash('Options updated', 'success')
			->redirect();
	}
}
