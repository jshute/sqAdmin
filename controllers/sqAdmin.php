<?php

abstract class sqAdmin extends controller {
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
		$this->layout->model = sq::model(sq::request()->get('model'));
		$this->layout->modelName = $this->layout->model->options['name'];
	}
	
	public function indexAction($model = null) {
		$this->layout->view = 'admin/layouts/listing';
		$modelName = $model;
		
		$model = sq::model($model);
		if ($model->options['hierarchy']) {
			$model->search(['pages_id' => null])->paginate()->hasMany($modelName);
			
			foreach ($model as $item) {
				$item->{$this->layout->modelName}->hasMany($modelName);
			}
		} else {
			$model->paginate()->all();
		}
		
		$this->layout->model = $model;
		$this->layout->content = $model;
	}
	
	public function createGetAction($model, $type = null) {
		$model = sq::model($model)->columns();
		
		if (!$type) {
			$type = $model->options['default-item-type'];
		}
		
		$this->layout->view = 'admin/layouts/form';
		$this->layout->content = $model->set('type', $type);
	}
	
	public function createPostAction($model) {
		$model = sq::request()->model($model);
		if ($model->validate()) {
			$model->save();
		}
		sq::response()->redirect(sq::base().'admin/'.$model->options['name']);
	}
	
	public function updatePostAction($model) {
		$model = sq::request()->model($model);
		if ($model->validate()) {
			$model->save();
		}
		sq::response()->redirect(sq::base().'admin/'.$model->options['name']);
	}
	
	public function updateGetAction($model, $id, $type = null) {
		$model = sq::model($model)->find($id);
		
		if ($type) {
			$model->set('type', $type);
		}
		
		$this->layout->view = 'admin/layouts/form';
		$this->layout->content = $model;
	}
	
	public function deleteAction($model, $id) {
		$model = sq::model($model)->find($id);
		
		if (sq::request()->isPost) {
			$model->delete();
			
			sq::response()->redirect(sq::base().'admin/'.sq::request()->get('model'));
		} else {
			$model->read();
			
			$this->layout->content = sq::view('admin/confirm', ['model' => $model]);
		}
	}
	
	public function passwordGetAction($id) {
		$users = sq::model('users')->find($id);
		
		$this->layout->content = sq::view('admin/forms/password', [
			'model' => $users
		]);
	}
	
	public function passwordPostAction($id, $password, $confirm, $model) {
		if ($password != $confirm) {
			sq::response()->flash('Passwords do not match.')->review();
		}
		
		$users = sq::model('users')->find($id);
		$users->password = auth::hash($password);
		$users->update();
		
		sq::response()->redirect(sq::base().'admin/'.$model);
	}
	
	public function sortAction($model) {
		$model = sq::model($model, ['debug' => true]);
		
		foreach (sq::request()->post('save') as $field => $data) {
			foreach ($data as $id => $value) {
				if (is_numeric($value)) {
					$model->where($id);
					$model->$field = $value;
					$model->update();
				}
			}
		}
		
		sq::response()->redirect(sq::base().'admin/'.sq::request()->get('model'));
	}
}

?>