<?php

use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->title = 'TodoList';
?>

<div class="todo-wrapper">
	<h2>TodoList</h2>
	<?php $form = ActiveForm::begin([
		'options' => [
			'class' => 'todo-form'
		]
	]);?>
		<strong>Add new Todo</strong>:
		<input type="text" name="title" placeholder="New Todo..." />
		<button type="submit" class="add-btn">Add</button>
		<?php if ($model->errors) : ?>
			<?php foreach ($model->errors as $error) : ?>
				<div class="alert alert-danger"><?php echo $error[0];?></div>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php ActiveForm::end(); ?>

	<div class="todo-list">
		<div class="todo-filter">
			<strong>Filter by:</strong>
			<?php $i = 0; ?>
			<?php foreach ($filters as $status => $flag) : ?>
				<?php $i++; ?>
				<?php if ($flag) : ?><strong><?php endif; ?>
				<a href="<?php echo Url::to(['todo/index', 'filter' => $status]);?>"><?php echo $status; ?></a> <?php echo ($i < count($filters)) ? '|'  : '' ?>
				<?php if ($flag) : ?></strong><?php endif; ?>
			<?php endforeach; ?>
		</div>

		<?php if ($data) : ?> 
			<?php foreach ($data['data'] as $item) : ?>
				<p>
					<a id="<?php echo $item->id;?>" class="todo-item <?php echo 'todo-'.$item->status;?>" href="<?php echo Url::to(['todo/update', 'id' => $item->id]);?>"><?php echo $item->title; ?></a>
					<a class="btn-delete" href="<?php echo Url::to(['todo/delete', 'id' => $item->id]);?>">x</a>
				</p>
			<?php endforeach; ?>

			<?php
				echo LinkPager::widget([
					'pagination' => $data['pagination']
				]);
			?>
		<?php endif; ?>

	</div>
</div>