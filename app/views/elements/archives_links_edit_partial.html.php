<?php

	$add_link_action = "/$controller/add/";

	$archive_slug_name = $model . "_slug";
	$archive_slug_value = $archive->slug;

	$archive_id_name = $model . "_id";
	$archive_id_value = $archive->id;

?>


	<div class="well">
		<legend>Links</legend>
		<table class="table">
			<?php foreach ($archives_links as $ad): ?>
			
<?php

	$remove_link_action = "/$controller/delete/$ad->id";

	//The query parameter ?model=slug is used as a callback. If we edit this record,
	//we want to be able to return here after saving (see Links::edit)
	$edit_link_action = '/links/edit/' . $ad->link->id . "?$model=" . $archive->slug;

?>
				<tr>
					<td>
						<?=$this->html->link($ad->link->elision(), $ad->link->url); ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($ad, array('url' => $remove_link_action, 'method' => 'post')); ?>
			<input type="hidden" name="<?=$archive_slug_name?>" value="<?=$archive_slug_value ?>" />
			<?=$this->html->link('Edit', $edit_link_action, array('class' => 'btn btn-mini')); ?>
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>

			<?php endforeach; ?>
		</table>

		<?=$this->form->create(null, array('url' => $add_link_action, 'method' => 'post')); ?>
			<legend>Add a Link</legend>
			<?=$this->form->field('url', array('label' => 'URL'));?>
			
			<input type="hidden" name="title" value="<?=$archive->title ?>?" />
			<input type="hidden" name="<?=$archive_slug_name ?>" value="<?=$archive_slug_value ?>" />
			<input type="hidden" name="<?=$archive_id_name ?>" value="<?=$archive_id_value ?>" />
		
		<?=$this->form->submit('Add Link', array('class' => 'btn btn-inverse')); ?>
		<?=$this->form->end(); ?>
	</div>
