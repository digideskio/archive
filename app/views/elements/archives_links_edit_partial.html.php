<?php
	$add_link_url = $this->url(array("ArchivesLinks::add"));
?>

	<div class="well">
		<legend>Links</legend>
		<table class="table">
			<?php foreach ($archives_links as $al): ?>
			
<?php

	$delete_link_url = $this->url(array("ArchivesLinks::delete", 'id' => $al->id));
	
	//  When generating the edit link URL, indicate to LinksController to redirect back here
	$edit_link_url = $this->url(array('Links::edit', 'id' => $al->link->id)) . '?redirect=1';

?>
				<tr>
					<td>
						<?=$this->html->link($al->link->elision(), $al->link->url); ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($al, array('url' => $delete_link_url, 'method' => 'post')); ?>
			<input type="hidden" name="archive_id" value="<?=$archive->id ?>" />
			<?=$this->html->link('Edit', $edit_link_url, array('class' => 'btn btn-mini')); ?>
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>

			<?php endforeach; ?>
		</table>

		<?=$this->form->create(null, array('url' => $add_link_url, 'method' => 'post')); ?>
			<legend>Add a Link</legend>
			<?=$this->form->field('url', array('label' => 'URL'));?>
			
			<input type="hidden" name="title" value="<?=$archive->name ?>" />
			<input type="hidden" name="archive_id" value="<?=$archive->id ?>" />
		
		<?=$this->form->submit('Add Link', array('class' => 'btn btn-inverse')); ?>
		<?=$this->form->end(); ?>
	</div>
