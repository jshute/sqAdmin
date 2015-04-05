<section class="form edit-form">
	<h2><?php echo $model->name ?></h2>
	<div class="secondary">id: <?php echo $model->id ?></div>
	<form enctype="multipart/form-data" method="post">
<?php

switch ($model->type):
	case 'image':
		echo '<div class="inline-form">';
		echo '<div class="form-block">';
		echo form::file('file', $model->content);
		echo '</div>';
		echo '<div class="form-block">';
		echo form::label('alt_text', 'Alt Text');
		echo form::text('alt_text', $model->alt_text);
		echo '</div>';
		echo '</div>';
		break;
	case 'text':
		echo '<div class="form-block">';
		echo form::label('content', 'Content');
		echo form::text('content', $model->content);
		echo '</div>';
		break;
	case 'markdown':
		echo '<div class="form-block">';
		echo form::label('content', 'Content');
		echo form::textarea('content', $model->content, array('class' => 'markdown-field'));
		break;
	case 'html':
		echo '<div class="form-block">';
		echo form::label('content', 'Content');
		echo form::textarea('content', $model->content);
		echo '</div>';
		break;
endswitch;

?>
		<input type="submit" value="Save Changes"/>
	</form>
</section>