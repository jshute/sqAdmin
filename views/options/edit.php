<?=form::open(['enctype' => 'multipart/form-data']) ?>

<header class="form-header">
    <h2><?=$model->getTitle() ?></h2>
    <div class="sq-help-text">Control general options for how your website is displayed.</div>
</header>

<div class="sq-form">
    <? foreach ($model as $item): ?>
        <div class="sq-form-row sq-<?=$item->format ?>-form-row">
            <?=form::label('options['.$item->id.']', $item->title) ?>
            <?=form::{$item->format}('options['.$item->id.']', $item->value) ?>
            <? if ($item->help): ?>
    	    	<span class="sq-help-text"><?=$item->help ?></span>
            <? endif ?>
        </div>
    <? endforeach ?>
</div>

<div class="sq-actions sq-form-actions">
	<input class="sq-action sq-save-action" type="submit" name="button" value="Save"/>
	<a class="sq-cancel" href="">Clear Changes</a>
</div>

<?=form::close() ?>