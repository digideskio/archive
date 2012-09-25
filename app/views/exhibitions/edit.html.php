<?php

$this->title($exhibition->title);

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
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/exhibitions/view/'.$exhibition->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
</ul>

<div class="row">
	<div class="span5">
		<div class="well">
		<?=$this->form->create($exhibition); ?>
			<?=$this->form->field('title');?>
			<?=$this->form->field('curator');?>
			<?=$this->form->field('venue');?>
			<?=$this->form->field('city');?>
			<?=$this->form->field('country');?>
			<?=$this->form->field('earliest_date', array(
				'label' => 'Opening Date',
				'value' => $exhibition->start_date()
			));?>
			<?=$this->form->field('latest_date', array(
				'label' => 'Closing Date',
				'value' => $exhibition->end_date()
			));?>
			<?=$this->form->label('Show Type');?>
			<select name="type">
				<option value="Solo" <?php if ($exhibition->type == "Solo") { echo "selected"; }?>>Solo</option>
				<option value="Group" <?php if ($exhibition->type == "Group") { echo "selected"; }?>>Group</option>
			</select>
			<?=$this->form->field('remarks', array(
				'type' => 'textarea',
			));?>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/exhibitions/view/'.$exhibition->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>


				
		<div class="well">

			<legend>Edit</legend>

			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Exhibition
			</a>

		</div>
	</div>

	<div class="span5">

		<div class="well">
			<legend>Documents</legend>
			<table class="table">
			
				<?php foreach($exhibition_documents as $ed): ?>
				
					<tr>
						<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
							<?php $px = '260'; ?>
							<a href="/documents/view/<?=$ed->document->slug ?>" title="<?=$ed->document->title ?>">
							<img width="125" height="125" src="/files/<?=$ed->document->view(); ?>" alt="<?=$ed->document->title ?>">
							</a>
						</td>
						<td align="right" style="text-align:right">
				<?=$this->form->create($ed, array('url' => "/exhibitions_documents/delete/$ed->id", 'method' => 'post')); ?>
				<input type="hidden" name="exhibition_slug" value="<?=$exhibition->slug ?>" />
				<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
				<?=$this->form->end(); ?>
						</td>
					</tr>
				
				<?php endforeach; ?>
				
				</table>
			
			<?=$this->form->create(null, array('url' => "/exhibitions_documents/add/", 'method' => 'post')); ?>
				<legend>Add a Document</legend>
				<span class="help-block">Find the document you want to add, click the <code>Edit</code> button, copy the text in the <code>Permalink</code> field, and paste it here.</span>
				<?=$this->form->field('document_slug', array('label' => 'Document Permalink'));?>
				
				<input type="hidden" name="exhibition_slug" value="<?=$exhibition->slug ?>" />
				<input type="hidden" name="exhibition_id" value="<?=$exhibition->id ?>" />
			
			<?=$this->form->submit('Add Document', array('class' => 'btn btn-inverse')); ?>
			<?=$this->form->end(); ?>
			
		</div>
	</div>

</div>



<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Exhibition</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$exhibition->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Exhibition from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($exhibition, array('url' => "/exhibitions/delete/$exhibition->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
