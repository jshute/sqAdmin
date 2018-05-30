<?php foreach ($model->options['inline-actions'] as $key => $val):
	$action = $val;

	if (is_int($key)) {
		$action = ['action' => $val];
	}

	if (is_string($val)) {
		$action = [
			'label' => $val
		];
	}

	if (empty($action['action'])) {
		$action['action'] = $key;
	}

	if (empty($action['label'])) {
		$action['label'] = ucwords(str_replace('_', ' ', $action['action']));
	}

	if (isset($action['type']) && is_string($action['type'])) {
		$action['type'] = [$action['type']];
	}

	if (!empty($action['type']) && !empty($item->type) && !in_array($item->type, $action['type'])) {
		continue;
	}

	$url = sq::route()->current()->append([
		'action' => $action['action'],
		'id' => $item->id
	])->remove(['where', 'value', 'page']);

	if (sq::request()->get('where') == 'type'):
		$url .= '?type='.sq::request()->get('value');
	endif;

	echo '<a href="'.$url.'" class="sq-action sq-'.$action['action'].'-action">'.$action['label'].'</a>';
endforeach ?>