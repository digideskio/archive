<?php

$this->title($work->title);

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($work->title,'/works/view/'.$work->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Attachments
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/works/view/'.$work->archive->slug); ?></li>
	<li><?=$this->html->link('Edit','/works/edit/'.$work->archive->slug); ?></li>
	<li class="active">
		<a href="#">
			Attachments
		</a>
	</li>
	<li><?=$this->html->link('History','/works/history/'.$work->archive->slug); ?></li>
</ul>

<div class="row">

	<div class="span5">
		<?=$this->partial->archives_documents_edit(array(
			'model' => $work,
			'archives_documents' => $archives_documents,
		)); ?>		

		<?=$this->partial->archives_links_edit(array(
			'archive' => $work->archive,
			'archives_links' => $archives_links,
		)); ?>		

	</div>

	<div class="span5">

	<?=$this->partial->albums_archives_edit(array(
		'archive' => $work->archive,
		'component_type' => 'albums_works',
		'albums' => $albums,
		'other_albums' => $other_albums,
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
			<input type="hidden" name="work_slug" value="<?=$work->archive->slug ?>" />
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
	
<div class="modal fade hide" id="exhibitionModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Artwork to an Exhibition</h3>
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
			<input type="hidden" name="archive_id2" value="<?=$work->id ?>" />
			<input type="hidden" name="type" value="exhibitions_works" />
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

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Artwork</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$work->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Artwork from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?php $slug = $work->archive->slug; ?>
			<?=$this->form->create($work, array('url' => "/works/delete/$slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>

	</div>

</div>
