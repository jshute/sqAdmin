<?php

use Michelf\MarkdownExtra;

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
		sq::widget('breadcrumbs')->add('Admin', sq::route()->to(['module' => 'admin']));

		if (sq::request()->get('model')) {
			$this->layout->model = sq::model(sq::request()->get('model'));
			$this->layout->modelName = $this->layout->model->getTitle();
		}
	}

	public function indexAction() {
		$modelName = sq::request()->get('model');
		$type = sq::request()->get('type');

		$this->layout->view = 'admin/layouts/listing';

		$model = sq::model($modelName);

		if ($model->options['hierarchy'] && !$type) {
			$model->search(['pages_id' => null])->hasMany($modelName);

			foreach ($model as $item) {
				$item->{$modelName}->hasMany($modelName);
			}
		} else {
			if ($type) {
				$model->order('name', 'ASC')->search(['type' => $type]);
			} else {
				$model->all();
			}

			// @TODO Fix this hack
			if ($type == 'event') {
				$model->options['fields']['list'] = [
					'name' => 'text',
					'begins' => 'date',
					'ends' => 'date'
				];
			} else if ($type == 'page') {
				$model->options['fields']['list'] = [
					'name' => 'text',
					'description' => 'blurb'
				];
			}
		}

		$model->paginate();

		$modelURL = $this->getModelURL($model, $type);
		sq::widget('breadcrumbs')
			->add($model->getTitle('plural', $type), $modelURL);

		$this->layout->model = $model;
		$this->layout->content = $model;
	}

	public function createGetAction($model, $type = null) {
		$model = sq::model($model)->columns();

		if (!$type) {
			$type = $model->options['default-item-type'];
		}

		$modelURL = $this->getModelURL($model, $type);
		sq::widget('breadcrumbs')
			->add($model->getTitle('plural', $type), $modelURL)
			->add('Create');

		$this->layout->view = 'admin/layouts/form';
		$this->layout->content = $model->set('type', $type);
	}

	public function createPostAction($model, $type = null) {
		$model = sq::request()->model($model);
		if ($model->validate()) {
			$model->save();
		}

		sq::response()
			->flash($this->getFlashMessage($model, 'created', $type), 'success')
			->redirect($this->getModelURL($model, $type));
	}

	public function updateGetAction($model, $id = null, $type = null) {
		if ($id) {
			$model = sq::model($model)->find($id);
		} else {
			$model = sq::model($model)->find(['type' => $type]);
		}

		if ($type) {
			$model->set('type', $type);
		}

		$modelURL = $this->getModelURL($model, $type);
		sq::widget('breadcrumbs')
			->add($model->getTitle('plural', $type), $modelURL)
			->add('Update');

		$this->layout->view = 'admin/layouts/form';
		$this->layout->content = $model;
	}

	public function updatePostAction($model, $type = null) {
		$model = sq::request()->model($model);

		if ($model->validate()) {
			$model->save();
		}

		// @TODO I need a better way to handle unique actions
		if (isset($_FILES['upload'])) {
			sq::model('files', ['path' => $model->path])
				->upload('upload');
		}

		if (sq::request()->post('sort-keys')) {
			foreach (explode(',', sq::request()->post('sort-keys')) as $sort => $id) {
				sq::model('sq_files')->update(['sort' => $sort], $id);
			}
		}

		sq::response()
			->flash($this->getFlashMessage($model, 'saved', $type), 'success')
			->redirect();
	}

	public function deleteAction($model, $id, $type = null) {
		$model = sq::model($model)->find($id);
		$modelURL = $this->getModelURL($model, $type);

		if (sq::request()->isPost) {
			$flashMessage = $this->getFlashMessage($model, 'deleted', $type);

			// @TODO make this not a hardcoded hack
			if (sq::request()->get('model') == 'files' && !array_key_exists('path', $_GET)) {
				$id = sq::model('galleries')->find(['path' => dirname($id)])->id;
				$modelURL = sq::route()->to([
					'model' => 'galleries',
					'action' => 'update',
					'id' => $id
				]);
			}

			$model->delete();

			sq::response()
				->flash($flashMessage, 'success')
				->redirect($modelURL);
		} else {
			$model->read();
			sq::widget('breadcrumbs')
				->add($model->getTitle('plural', $type), $modelURL)
				->add('Delete');

			$this->layout->content = sq::view('admin/confirm', ['model' => $model]);
		}
	}

	public function passwordGetAction($id) {
		$users = sq::model('users')->find($id);

		sq::widget('breadcrumbs')
			->add('Users', sq::route()->to(['model' => 'users']))
			->add('Change Password');

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

	/**
	 * Loads the specified markdown file in a help page
	 *
	 * @param string $file The name of the markdown file to load from the
	 * admin/help directory.
	 */
	public function helpAction($file = null) {
		sq::widget('breadcrumbs')->add('Help', sq::route()->to([
			'action' => 'help']));

		$parser = new MarkdownExtra;
		$parser->url_filter_func = function($url) {
			return sq::base().'modules/admin/help/src/'.$url;
		};

		if ($file) {
			sq::widget('breadcrumbs')->add(ucwords(pathinfo($file, PATHINFO_FILENAME)));
		}

		$file = $file ?: 'index.md';
		$this->layout->content = sq::view('admin/help', [
			'content' => $parser->transform(
				file_get_contents(sq::root().'modules/admin/help/'.$file))
		]);
	}

	private function getModelURL($model, $type = null) {
		$modelURL = sq::route()->to(['model' => $model->options['name']]);

		if ($type) {
			$modelURL->append(['type' => $type]);
		}

		return $modelURL;
	}

	private function getFlashMessage($model, $verb, $type = null) {
		if (!empty($model->name)) {
			return $model->getTitle('singular', $type).' \''.$model->name.'\' '.$verb;
		}

		return $model->getTitle('singular', $type).' '.$verb;
	}
}
