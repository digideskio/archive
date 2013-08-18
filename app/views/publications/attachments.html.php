<?php

$this->title($publication->title);

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($publication->title,'/publications/view/'.$publication->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Attachments
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li>
		<?=$this->html->link('View', $this->url(array('Publications::view', 'slug' => $publication->archive->slug))); ?>
	</li>
	<li>
		<?=$this->html->link('Edit', $this->url(array('Publications::edit', 'slug' => $publication->archive->slug))); ?>
	</li>
	<li class="active">
		<a href="#">
			Attachments
		</a>
	</li>
	<li>
		<?=$this->html->link('History', $this->url(array('Publications::history', 'slug' => $publication->archive->slug))); ?>
	</li>
</ul>


<div class="row">

	<div class="span5">

	<?=$this->partial->archives_documents_edit(array(
		'model' => $publication,
		'archives_documents' => $archives_documents,
	)); ?>		

	</div>
	
	<div class="span5">

	<?=$this->partial->archives_links_edit(array(
		'model' => $publication,
		'junctions' => $publication_links,
	)); ?>		

	<div class="well">
		<legend>Exhibitions</legend>
		<table class="table">
		
			<?php foreach($exhibitions as $exhibition): ?>
			<?php $component = $exhibition->components->first(); ?>
				<tr>
					<td>
						<strong><?=$this->html->link($exhibition->title, $this->url(array('Exhibitions::view', 'slug' => $exhibition->archive->slug))); ?></strong>
						<?php if ($exhibition->archive->years()): ?>
							<small><?=$exhibition->archive->dates(); ?></small>
						<?php endif; ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($component, array('url' => $this->url(array('Components::delete', 'id' => $component->id)), 'method' => 'post')); ?>
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($other_exhibitions) > 0): ?>			
			
			<tr>
				<td></td>
				<td align="right" style="text-align:right">
					<a data-toggle="modal" href="#exhibitionModal" class="btn btn-mini btn-inverse">Add an Exhibition</a>
				</td>
			</tr>
			
			<?php endif; ?>
			
			</table>
		
	</div>

	</div>
</div>

<div class="modal fade hide" id="exhibitionModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Publication to an Exhibition</h3>
		</div>
		<div class="modal-body">
			<table class="table"><tbody>
			<?php foreach($other_exhibitions as $oe): ?>
				<tr>
					<td>
						<a href="/exhibitions/view/<?=$oe->archive->slug ?>">
							<strong><?=$oe->title ?></a></strong><br/>
							<?=$oe->venue ?><br/>
							<?=$oe->archive->dates() ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oe, array('url' => $this->url(array('Components::add')), 'method' => 'post')); ?>
			<input type="hidden" name="archive_id1" value="<?=$oe->id ?>" />
			<input type="hidden" name="archive_id2" value="<?=$publication->id ?>" />
			<input type="hidden" name="type" value="exhibitions_publications" />
			<?=$this->form->submit('Add', array('class' => 'btn btn-mini btn-success')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody></table>
			</div>
			<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
	</div>
</div>
