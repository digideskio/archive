<?php

$this->title($architecture->title);

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
	<?=$this->html->link('Architecture','/architectures'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>


<div class="row">

	<div class="span5">
		<div class="well">
		<?=$this->form->create($architecture); ?>
			<?=$this->form->field('title');?>
			<?=$this->form->field('client');?>
			<?=$this->form->field('project_lead');?>
			<?=$this->form->field('remarks');?>
			<?=$this->form->field('earliest_date', array('label' => 'Design Date'));?>
			<?=$this->form->field('latest_date', array('label' => 'Completion Date'));?>
			<?=$this->form->field('status', array('label' => 'Project Status'));?>
			<?=$this->form->field('location');?>
			<?=$this->form->field('city');?>
			<?=$this->form->field('country');?>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/architectures/view/'.$architecture->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>
	</div>
	
	<div class="span5">
	
	<div class="well">
		<legend>Images</legend>
		<table class="table">
		
			<?php foreach($architecture_documents as $ad): ?>
			
				<tr>
					<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
						<?php $px = '260'; ?>
						<a href="/documents/view/<?=$ad->document->slug ?>" title="<?=$ad->document->title ?>">
						<img width="125" height="125" src="/files/thumb/<?=$ad->document->slug?>.jpeg" alt="<?=$ad->document->title ?>">
						</a>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($ad, array('url' => "/architectures_documents/delete/$ad->id", 'method' => 'post')); ?>
			<input type="hidden" name="architecture_slug" value="<?=$architecture->slug ?>" />
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
			
			</table>
		
		<?=$this->form->create(null, array('url' => "/architectures_documents/add/", 'method' => 'post')); ?>
			<legend>Add a Document</legend>
			<span class="help-block">Find the document you want to add, click the <code>Edit</code> button, copy the text in the <code>Permalink</code> field, and paste it here.</span>
			<?=$this->form->field('document_slug', array('label' => 'Document Permalink'));?>
			
			<input type="hidden" name="architecture_slug" value="<?=$architecture->slug ?>" />
			<input type="hidden" name="architecture_id" value="<?=$architecture->id ?>" />
		
		<?=$this->form->submit('Add Document', array('class' => 'btn btn-inverse')); ?>
		<?=$this->form->end(); ?>
		
	</div>

	</div>

</div>
