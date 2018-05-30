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
			$this->layout->modelName = $this->layout->model->options['name'];
		}
	}

	public function indexAction($model = null, $where = null, $value = null) {
		$this->layout->view = 'admin/layouts/listing';
		$modelName = $model;

		$model = sq::model($model);
		if ($model->options['hierarchy'] && !$where) {
			$model->search(['pages_id' => null])->paginate()->hasMany($modelName);

			foreach ($model as $item) {
				$item->{$this->layout->modelName}->hasMany($modelName);
			}

			sq::widget('breadcrumbs')->add(ucwords($this->layout->modelName), sq::route()->to([
				'module' => 'admin',
				'model' => $this->layout->modelName
			]));
		} else {
			if ($where) {
				if ($value == 'event') {
					$model->options['fields']['list'] = [
						'name' => 'text',
						'begins' => 'date',
						'ends' => 'date'
					];

					$model->options['title'] = 'Events';

					sq::widget('breadcrumbs')->add('Events', sq::route()->to([
						'module' => 'admin',
						'model' => $this->layout->modelName
					]).'?where=type&value=event');
				}
				if ($value == 'page') {
					$model->options['fields']['list'] = [
						'name' => 'text',
						'description' => 'blurb'
					];

					$model->options['title'] = 'Information Pages';

					sq::widget('breadcrumbs')->add('Information Pages', sq::route()->to([
						'module' => 'admin',
						'model' => $this->layout->modelName
					]).'?where=type&value=page');
				}
				$model->order('name', 'ASC')->search([$where => $value]);
			} else {
				sq::widget('breadcrumbs')->add(ucwords($this->layout->modelName), sq::route()->to([
					'module' => 'admin',
					'model' => $this->layout->modelName
				]));
				$model->paginate()->all();
			}
		}

		$this->layout->model = $model;
		$this->layout->content = $model;
	}

	public function createGetAction($model, $type = null) {
		$model = sq::model($model)->columns();

		if (!$type) {
			$type = $model->options['default-item-type'];
		}

		sq::widget('breadcrumbs')->add('Create');

		$this->layout->view = 'admin/layouts/form';
		$this->layout->content = $model->set('type', $type);
	}

	public function createPostAction($model) {
		$model = sq::request()->model($model);
		if ($model->validate()) {
			$model->save();
		}
		sq::response()->flash('Form saved successfully', 'success')->redirect();
	}

	public function updateGetAction($model, $id = null, $type = null, $where = null, $value = null) {
		if ($id) {
			$model = sq::model($model)->find($id);
		} else {
			$model = sq::model($model)->find([$where => $value]);
		}

		if ($type) {
			$model->set('type', $type);
		}

		sq::widget('breadcrumbs')->add('Update');

		$this->layout->view = 'admin/layouts/form';
		$this->layout->content = $model;
	}

	public function updatePostAction($model) {
		$model = sq::request()->model($model);

		if ($model->validate()) {
			$model->save();
		}

		sq::response()->flash('Form saved successfully', 'success')->redirect();
	}

	public function deleteAction($model, $id) {
		$model = sq::model($model)->find($id);

		if (sq::request()->isPost) {
			$model->delete();

			sq::response()->redirect(sq::base().'admin/'.sq::request()->get('model'));
		} else {
			$model->read();

			sq::widget('breadcrumbs')->add('Delete');

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

	/**
	 * Loads the specified markdown file in a help page
	 *
	 * @param {string} page - The name of the markdown page to load from the
	 * admin/help directory.
	 */
	public function helpAction($page = 'index.md') {
		sq::widget('breadcrumbs')->add('Help', sq::route()->to([
			'module' => 'admin', 'action' => 'help']));

		$parser = new MarkdownExtra;
		$parser->url_filter_func = function($url) {
			return sq::base().'modules/admin/help/src/'.$url;
		};
		$this->layout->content = sq::view('admin/help', [
			'content' => $parser->transform(file_get_contents(sq::root().'modules/admin/help/'.$page))
		]);
	}
}
