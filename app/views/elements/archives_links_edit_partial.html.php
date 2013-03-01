<?php

	//Get 'Works' from 'app\models\Works', etc.
	$model_name = basename(str_replace('\\', '/', $model->model()));
	$model_name_sing = lithium\util\Inflector::singularize($model_name);

	//Get 'WorksLinks' from 'app\models\WorksLinks', etc.
	$junction_name = basename(str_replace('\\', '/', $junctions->model()));

	//Get 'works_links/add', etc.
	$add_junction_url = $this->url(array("$junction_name::add"));

	//Get 'work_id', etc.
	$model_id_name = lithium\util\Inflector::underscore("$model_name_sing id");
	$model_id_value = $model->id;

	//Get 'work_slug', etc.
	$model_slug_name = lithium\util\Inflector::underscore("$model_name_sing slug");
	$model_slug_value = $model->slug ?: $model->archive->slug;
	
?>

	<div class="well">
		<legend>Links</legend>
		<table class="table">
			<?php foreach ($junctions as $junction): ?>
			
<?php

	$delete_junction_url = $this->url(array("$junction_name::delete", 'id'=> $junction->id));

	//The query parameter ?model=slug is used as a callback. If we edit this record,
	//we want to be able to return here after saving (see Links::edit)
	$edit_link_url = $this->url(array('Links::edit', 'id' => $junction->link->id)) . 
		"?" . strtolower($model_name_sing) . "=" . $model_slug_value; 

?>
				<tr>
					<td>
						<?=$this->html->link($junction->link->elision(), $junction->link->url); ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($junction, array('url' => $delete_junction_url, 'method' => 'post')); ?>
			<input type="hidden" name="<?=$model_slug_name?>" value="<?=$model_slug_value ?>" />
			<?=$this->html->link('Edit', $edit_link_url, array('class' => 'btn btn-mini')); ?>
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>

			<?php endforeach; ?>
		</table>

		<?=$this->form->create(null, array('url' => $add_junction_url, 'method' => 'post')); ?>
			<legend>Add a Link</legend>
			<?=$this->form->field('url', array('label' => 'URL'));?>
			
			<input type="hidden" name="title" value="<?=$model->title ?>" />
			<input type="hidden" name="<?=$model_slug_name ?>" value="<?=$model_slug_value ?>" />
			<input type="hidden" name="<?=$model_id_name ?>" value="<?=$model_id_value ?>" />
		
		<?=$this->form->submit('Add Link', array('class' => 'btn btn-inverse')); ?>
		<?=$this->form->end(); ?>
	</div>
