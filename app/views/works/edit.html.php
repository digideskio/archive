<?php

$this->title($work->title);

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
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($work->title,'/works/view/'.$work->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/works/view/'.$work->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
	<li><?=$this->html->link('History','/works/history/'.$work->slug); ?></li>
</ul>



<div class="row">

	<div class="span5">
		<div class="well">
		<?=$this->form->create($work); ?>
			<legend>Info</legend>
			<?=$this->form->field('artist');?>
			<?=$this->form->field('title');?>
			<?=$this->form->field('classification');?>
			<?=$this->form->field('earliest_date', array('value' => $work->start_date()));?>
			<?=$this->form->field('latest_date', array('value' => $work->end_date()));?>
			<?=$this->form->field('creation_number', array('label' => 'Artwork ID'));?>
			<?=$this->form->field('materials', array('type' => 'textarea'));?>
			<?=$this->form->field('quantity');?>
			<?=$this->form->field('location');?>
			<?=$this->form->field('lender');?>
			<?=$this->form->field('remarks', array('type' => 'textarea'));?>
			<?=$this->form->field('height');?>
			<?=$this->form->field('width');?>
			<?=$this->form->field('depth');?>
			<?=$this->form->field('diameter');?>
			<?=$this->form->field('weight');?>
			<?=$this->form->field('running_time');?>
			<?=$this->form->field('measurement_remarks', array('type' => 'textarea'));?>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/works/view/'.$work->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>
		
		<div class="well">
		
			<legend>Edit</legend>
		
			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Artwork
			</a>
		
		</div>
		
		
	</div>
	
	<div class="span5">
	
	<div class="well">
		<legend>Annotation</legend>
		<?=$this->form->create($work); ?>
			<?=$this->form->field('annotation', array(
				'type' => 'textarea', 
				'rows' => '10', 
				'style' => 'width:90%;',
				'label' => ''
			));?>
		
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/works/view/'.$work->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		
	</div>
	
	<div class="well">
		<legend>Collections</legend>
		<table class="table">
		
			<?php foreach($collections as $collection): ?>
			<?php $cw = $collection->collections_works[0]; ?> 
				<tr>
					<td>
						<a href="/collections/view/<?=$collection->slug ?>"><?=$collection->title ?></a>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($cw, array('url' => "/collections_works/delete/$cw->id", 'method' => 'post')); ?>
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($other_collections) > 0): ?>
			
			<tr>
				<td></td>
				<td align="right" style="text-align:right">
					<a data-toggle="modal" href="#collectionModal" class="btn btn-mini btn-inverse">Add a Collection</a>
				</td>
			</tr>
			
			<?php endif; ?>
			
			</table>
		
	</div>
	
	
		
	<div class="well">
		<legend>Exhibitions</legend>
		<table class="table">
		
			<?php foreach($exhibitions as $exhibition): ?>
			<?php $ew = $exhibition->exhibitions_works[0]; ?>
				<tr>
					<td>
						<a href="/exhibitions/view/<?=$exhibition->slug ?>"><?=$exhibition->title ?></a> <strong></strong> 
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($ew, array('url' => "/exhibitions_works/delete/$ew->id", 'method' => 'post')); ?>
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
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
	
	<div class="well">
		<legend>Images</legend>
		<table class="table">
		
			<?php foreach($work_documents as $wd): ?>
			
				<tr>
					<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
						<?php $px = '260'; ?>
						<a href="/documents/view/<?=$wd->document->slug ?>" title="<?=$wd->document->title ?>">
						<img width="125" height="125" src="/files/thumb/<?=$wd->document->slug?>.jpeg" alt="<?=$wd->document->title ?>">
						</a>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($wd, array('url' => "/works_documents/delete/$wd->id", 'method' => 'post')); ?>
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
			
			</table>
		
		<?=$this->form->create(null, array('url' => "/works_documents/add/", 'method' => 'post')); ?>
			<legend>Add a Document</legend>
			<span class="help-block">Find the document you want to add, click the <code>Edit</code> button, copy the text in the <code>Permalink</code> field, and paste it here.</span>
			<?=$this->form->field('document_slug', array('label' => 'Document Permalink'));?>
			
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
			<input type="hidden" name="work_id" value="<?=$work->id ?>" />
		
		<?=$this->form->submit('Add Document', array('class' => 'btn btn-inverse')); ?>
		<?=$this->form->end(); ?>
		
	</div>

	</div>

</div>

<div class="modal fade hide" id="collectionModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Artwork to a Collection</h3>
		</div>
		<div class="modal-body">
			<table class="table"><tbody>
			<?php foreach($other_collections as $oc): ?>
				<tr>
					<td>
						<a href="/collections/view/<?=$oc->slug ?>">
							<strong><?=$oc->title ?></a></strong><br/>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oc, array('url' => "/collections_works/add", 'method' => 'post')); ?>
			<input type="hidden" name="collection_id" value="<?=$oc->id ?>" />
			<input type="hidden" name="work_id" value="<?=$work->id ?>" />
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
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
						<a href="/exhibitions/view/<?=$oe->slug ?>">
							<strong><?=$oe->title ?></a></strong><br/>
							<?=$oe->venue ?><br/>
							<?=$oe->dates() ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oe, array('url' => "/exhibitions_works/add", 'method' => 'post')); ?>
			<input type="hidden" name="exhibition_id" value="<?=$oe->id ?>" />
			<input type="hidden" name="work_id" value="<?=$work->id ?>" />
			<input type="hidden" name="work_slug" value="<?=$work->slug ?>" />
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
			<?=$this->form->create($work, array('url' => "/works/delete/$work->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
