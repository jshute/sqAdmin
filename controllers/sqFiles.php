<?php

abstract class sqFiles extends admin {
	public $layout = 'admin/layouts/main';
	
	public function uploadGetAction() {
		$this->layout->content = sq::view('admin/forms/upload');
	}
	
	public function uploadPostAction($path = null) {
		sq::model('files', ['path' => $path])->upload($_FILES['file']);
		sq::response()->redirect([
			'module' => 'admin',
			'model' => 'files',
			'controller' => 'files',
			'path' => $path
		]);
	}
	
	public function folderGetAction() {
		$this->layout->content = sq::view('admin/forms/folder');
	}
	
	public function folderPostAction($path = null, $folder = null) {
		if (!$path) {
			$path = sq::model('files')->options['path'];
		}
		
		$path = sq::root().$path.'/'.$folder;
		if (file_exists($path)) {
			sq::response()->flash('A folder with that name already exists.')->review();
		}
		
		if (mkdir($path)) {
			sq::response()->redirect([
				'module' => 'admin',
				'model' => 'files',
				'controller' => 'files',
				'path' => $path
			]);
		} else {
			sq::response()->flash('Folder creation failed.')->review();
		}
	}
	
	public function indexAction($path = null) {
		$this->layout->view = 'admin/layouts/listing';
		if ($path) {
			$files = sq::model('files')->search(['path' => $path])->order('type', 'ASC')->paginate();
		} else {
			$files = sq::model('files')->all()->order('type', 'ASC')->paginate();
		}
		
		$this->layout->actions = ['upload'];
		$this->layout->content = $files;
		$this->layout->model = $files;
	}
}

?>