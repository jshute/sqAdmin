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
		$this->layout->modelName = sq::request()->get('model');
	}
	
	public function indexAction($model = null) {
		if (sq::request()->get('model')) {
			$this->layout->content = sq::model($model)
				->read();
		} else {
			$this->layout->content = null;
		}
	}
	
	public function createGetAction() {
		$this->layout->content = sq::model(sq::request()->get('model'))->schema();
	}
	
	public function createPostAction() {
		$model = sq::model(sq::request()->get('model'));
		
		$save = $this->cleanInput(sq::request()->post('save', false));
		
		if (isset($save['id-field'])) {
			$idField = $save['id-field'];
			unset($save['id-field']);
		}
		
		$model->set($save);
		
		if (is_array(sq::request()->post('model'))) {
			foreach (sq::request()->post('model') as $inline) {
				$inlineModel = sq::model($inline)
					->set(sq::request()->post($inline))
					->create();
				
				$model->$idField = $inlineModel->id;
			}
		}
		
		$model->create();
		
		sq::response()->redirect(sq::base().'admin/'.sq::request()->get('model'));
	}
	
	public function updateAction() {
		$model = sq::model(sq::request()->get('model'));
		$model->options['load-relations'] = false;
		$model->where(sq::request()->any('id'));
		
		if (sq::request()->isPost) {
			$save = $this->cleanInput(sq::request()->post('save', false));
			
			if (isset($save['id-field'])) {
				$idField = $save['id-field'];
				unset($save['id-field']);
			}
			
			$model->set($save);
			
			if (is_array(sq::request()->post('model'))) {
				foreach (sq::request()->post('model') as $inline) {
					$inlineModel = sq::model($inline);
					$inlineModel->where(sq::request()->any('id'));
					$inlineModel->set(sq::request()->post($inline));
					$inlineModel->update();
					
					if (isset($inlineModel->id)) {
						$model->$idField = $inlineModel->id;
					}
				}
			}
			
			$model->update();
			sq::response()->redirect(sq::base().'admin/'.sq::request()->get('model'));
		} else {
			$model->read();
			
			$this->layout->content = $model;
		}
	}
	
	public function deleteAction() {
		$model = sq::model(sq::request()->get('model'));
		$model->where(sq::request()->get('id'));
		
		if (sq::request()->isPost) {
			$model->delete();
			
			sq::response()->redirect(sq::base().'admin/'.sq::request()->get('model'));
		} else {
			$model->read();
			
			$this->layout->content = sq::view('admin/confirm', array('model' => $model));
		}
	}
	
	public function sortAction() {
		$model = sq::model(sq::request()->get('model'));
		
		foreach (sq::request()->post('save') as $field => $data) {
			foreach ($data as $id => $value) {
				if ($value && is_numeric($value)) {
					$model->where($id);
					$model->$field = $value;
					$model->update();
				}
			}
		}
		
		sq::response()->redirect(sq::base().'admin/'.sq::request()->get('model'));
	}
	
	private function cleanInput(array $input) {
		foreach (sq::config(sq::request()->get('model').'/fields/form') as $field => $type) {
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