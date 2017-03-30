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
		$this->layout->modelName = sq::request()->get('model');
	}
	
	public function indexAction($model = null) {
		if ($model) {
			$this->layout->content = sq::model($model)->paginate()->all();
		} else {
			$this->layout->content = null;
		}
	}
	
	public function createGetAction($model) {
		$this->layout->content = sq::model($model)->schema();
	}
	
	public function createPostAction($model) {
		sq::request()->model($model)->save();
		sq::response()->redirect(sq::base().'admin/'.$model);
	}
	
	public function updatePostAction($model) {
		sq::request()->model($model)->save();
		sq::response()->redirect(sq::base().'admin/'.$model);
	}
	
	public function updateGetAction($model, $id) {
		$this->layout->content = sq::model($model)->find($id);
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
}

?>