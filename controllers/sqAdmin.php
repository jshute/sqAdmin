<?php

abstract class sqAdmin extends controller {
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
	
	public function defaultAction($action = 'index') {
		if ($action == 'password') {
			$this->layout->content = sq::controller('auth')->action('password');
		}
	}
	
	public function init() {
		$this->layout->modelName = url::get('model');
	}
	
	public function indexAction() {
		if (url::get('model')) {
			$this->layout->content = sq::model(url::get('model'))
				->read();
		} else {
			$this->layout->content = null;
		}
	}
	
	public function createGetAction() {
		$this->layout->content = sq::model(url::get('model'))->schema();
	}
	
	public function createPostAction() {
		$model = sq::model(url::get('model'));
		
		$save = $this->cleanInput(url::post('save', false));
		
		if (isset($save['id-field'])) {
			$idField = $save['id-field'];
			unset($save['id-field']);
		}
		
		$model->set($save);
		
		if (is_array(url::post('model'))) {
			foreach (url::post('model') as $inline) {
				$inlineModel = sq::model($inline)
					->set(url::post($inline))
					->create();
				
				$model->$idField = $inlineModel->id;
			}
		}
		
		$model->create();
		
		sq::redirect(sq::base().'admin/'.url::get('model'));
	}
	
	public function updateAction() {
		$model = sq::model(url::get('model'));
		$model->options['load-relations'] = false;
		$model->where(url::request('id'));
		
		if (url::post()) {
			$save = $this->cleanInput(url::post('save', false));
			
			if (isset($save['id-field'])) {
				$idField = $save['id-field'];
				unset($save['id-field']);
			}
			
			$model->set($save);
			
			if (is_array(url::post('model'))) {
				foreach (url::post('model') as $inline) {
					$inlineModel = sq::model($inline);
					$inlineModel->where(url::request('id'));
					$inlineModel->set(url::post($inline));
					$inlineModel->update();
					
					if (isset($inlineModel->id)) {
						$model->$idField = $inlineModel->id;
					}
				}
			}
			
			$model->update();
			sq::redirect(sq::base().'admin/'.url::get('model'));
		} else {
			$model->read();
			
			$this->layout->content = $model;
		}
	}
	
	public function deleteAction() {
		$model = sq::model(url::get('model'));
		$model->where(url::get('id'));
		
		if (url::post()) {
			$model->delete();
			
			sq::redirect(sq::base().'admin/'.url::get('model'));
		} else {
			$model->read();
			
			$this->layout->content = sq::view('admin/confirm', array('model' => $model));
		}
	}
	
	public function sortAction() {
		$model = sq::model(url::get('model'));
		
		foreach (url::post('save') as $field => $data) {
			foreach ($data as $id => $value) {
				if ($value && is_numeric($value)) {
					$model->where($id);
					$model->$field = $value;
					$model->update();
				}
			}
		}
		
		sq::redirect(sq::base().'admin/'.url::get('model'));
	}
	
	private function cleanInput(array $input) {
		foreach (sq::config(url::get('model').'/fields/form') as $field => $type) {
			foreach ($input as $key => $val) {
				if ($key == $field && $type == 'date') {
					$input[$key] = date('Y-m-d', strtotime($val));
				}
			}
		}
		
		return $input;
	}
}

?>