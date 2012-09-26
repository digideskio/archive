<?php

$this->title($publication->title);

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($publication->title,'/publications/view/'.$publication->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/publications/view/'.$publication->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
</ul>

<div class="row">

	<div class="span5">
		<div class="well">
		<?=$this->form->create($publication); ?>
			<legend>Info</legend>
			
			<?=$this->form->label('type', 'Category'); ?>
			<select name="type">
				<option value=''>Choose one...</option>
				<?php foreach($publications_types as $pt): ?>
				<option value="<?=$pt ?>" <?php if ($publication->type == $pt) { echo 'selected="selected"'; } ?>>
					<?=$pt ?>
				</option>
				<?php endforeach; ?>
			</select>
			
			 <span class="help-block">Is the publication an interview?</span>
			
			<label class="checkbox">
			<?=$this->form->checkbox('interview');?> Interview
			</label>

			<?=$this->form->field('author');?>
			<?=$this->form->field('title');?>
			<?=$this->form->field('publisher');?>
			<?=$this->form->field('earliest_date', array('value' => $publication->start_date()));?>
			<?=$this->form->field('latest_date', array('value' => $publication->end_date()));?>
			<?=$this->form->field('pages');?>
			<?=$this->form->field('url', array('label' => 'Website'));?>
			<?=$this->form->field('subject');?>
			<?=$this->form->field('remarks', array('type' => 'textarea'));?>
			<?=$this->form->field('language');?>
			<?=$this->form->field('storage_location');?>
			<?=$this->form->field('storage_number');?>
			<?=$this->form->field('publication_number', array('label' => 'Publication ID'));?>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/publications/view/' . $publication->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>


				
		<div class="well">

			<legend>Edit</legend>

			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Publication
			</a>

		</div>
	</div>

	<div class="span5">

		<div class="well">
			<legend>Documents</legend>
			<table class="table">
			
				<?php foreach($publication_documents as $pd): ?>
				
					<tr>
						<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
							<?php $px = '260'; ?>
							<a href="/documents/view/<?=$pd->document->slug ?>" title="<?=$pd->document->title ?>">
							<img width="125" height="125" src="/files/<?=$pd->document->view(); ?>" alt="<?=$pd->document->title ?>">
							</a>
						</td>
						<td align="right" style="text-align:right">
				<?=$this->form->create($pd, array('url' => "/publications_documents/delete/$pd->id", 'method' => 'post')); ?>
				<input type="hidden" name="publication_slug" value="<?=$publication->slug ?>" />
				<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
				<?=$this->form->end(); ?>
						</td>
					</tr>
				
				<?php endforeach; ?>
				
				</table>
			
			<?=$this->form->create(null, array('url' => "/publications_documents/add/", 'method' => 'post')); ?>
				<legend>Add a Document</legend>
				<span class="help-block">Find the document you want to add, click the <code>Edit</code> button, copy the text in the <code>Permalink</code> field, and paste it here.</span>
				<?=$this->form->field('document_slug', array('label' => 'Document Permalink'));?>
				
				<input type="hidden" name="publication_slug" value="<?=$publication->slug ?>" />
				<input type="hidden" name="publication_id" value="<?=$publication->id ?>" />
			
			<?=$this->form->submit('Add Document', array('class' => 'btn btn-inverse')); ?>
			<?=$this->form->end(); ?>
			
		</div>
	</div>
</div>


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Publication</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$publication->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Publication from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($publication, array('url' => "/publications/delete/$publication->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
