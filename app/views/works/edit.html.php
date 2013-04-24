<?php

$this->title($work->title);

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

$artist_names = json_encode($artists);

$classification_names = json_encode($classifications);

$work_classes_list = array_combine($classifications, $classifications);

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
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/works/view/'.$work->archive->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
	<li><?=$this->html->link('History','/works/history/'.$work->archive->slug); ?></li>
</ul>



<div class="row">

	<div class="span5">
		<div class="well">
		<?=$this->form->create($work, array('id' => 'WorksForm')); ?>
			<legend>Info</legend>

			<?php $work_classes_list = array_merge(array('' => 'Choose one...'), $work_classes_list); ?>

			<?=$this->form->label('classification', 'Classification'); ?>
			<?=$this->form->select('classification', $work_classes_list, array('value' => $work->archive->classification)); ?>

    		<?=$this->form->field('artist', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $artist_names));?>
			<?=$this->form->field('artist_native_name', array('label' => 'Artist (Native Language)', 'autocomplete' => 'off', 'value' => $work->attribute('artist_native_name')));?>
    		<?=$this->form->field('title', array('autocomplete' => 'off'));?>
			<?=$this->form->field('earliest_date', array('autocomplete' => 'off', 'value' => $work->archive->start_date_formatted()));?>
			<?=$this->form->field('latest_date', array('autocomplete' => 'off', 'value' => $work->archive->end_date_formatted()));?>
			<?=$this->form->field('creation_number', array('autocomplete' => 'off', 'label' => 'Artwork ID'));?>
			<?=$this->form->field('materials', array('type' => 'textarea'));?>
			<?=$this->form->field('edition', array('autocomplete' => 'off', 'value' => $work->attribute('edition')));?>
			<?=$this->form->field('quantity', array('autocomplete' => 'off'));?>
			<?=$this->form->field('remarks', array('type' => 'textarea'));?>
			<?=$this->form->field('height', array(
				'label' => "Height (cm)",
				'class' => 'dim two-d',
				'autocomplete' => 'off',
				'value' => $work->height ?: ''
			));?>
			<?=$this->form->field('width', array(
				'label' => "Width (cm)",
				'class' => 'dim two-d',
				'autocomplete' => 'off',
				'value' => $work->width ?: ''
			));?>
			<?=$this->form->field('depth', array(
				'label' => "Depth (cm)",
				'class' => 'dim three-d',
				'autocomplete' => 'off',
				'value' => $work->depth ?: ''
			));?>
			<?=$this->form->field('diameter', array(
				'label' => "Diameter (cm)",
				'class' => 'dim three-d',
				'autocomplete' => 'off',
				'value' => $work->diameter ?: ''
			));?>
			<?=$this->form->field('running_time', array('autocomplete' => 'off', 'class' => 'dim four-d'));?>
			<?=$this->form->field('measurement_remarks', array('type' => 'textarea', 'class' => 'dim remarks'));?>
			<label><span class="two-d">Additional Notes</span></label>
			<div class="signed">
				<label class="checkbox">
				<?=$this->form->checkbox('signed', array('class' => 'two-d', 'checked' => $work->attribute('signed')));?> Artwork is Signed
				</label>
			</div>
			<div class="framed">
				<label class="checkbox">
				<?=$this->form->checkbox('framed', array('class' => 'two-d', 'checked' => $work->attribute('framed')));?> Artwork is Framed
				</label>
			</div>
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/works/view/'.$work->archive->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>
		
<script>

$(document).ready(function() {

	function handleFields() {
		var work = $('#WorksClassification').val();

		$('#WorksForm .dim').parent().hide();

		if (work) {
			$('#WorksForm .dim.remarks').parent().fadeIn();
		} else {
			$('#WorksForm .dim.remarks').parent().hide();
		}

		if (work == 'Audio' || work == 'Video') {
			$('#WorksForm .four-d').parent().fadeIn();
		} else {
			$('#WorksForm .four-d').parent().hide();
		}

		if (work == 'Painting' || work == 'Photography' || work == 'Poster and Design' || work == 'Works on Paper' ||
				work == 'Furniture' || work == 'Installation' || work == 'Object' || work == 'Porcelain' || work == 'Pottery') { 
			
			$('#WorksForm .two-d').parent().fadeIn();
		} else {
			$('#WorksForm .two-d').parent().hide();
		}

		if (work == 'Furniture' || work == 'Installation' || work == 'Object' || work == 'Porcelain' || work == 'Pottery') {
			$('#WorksForm .three-d').parent().fadeIn();
		} else {
			$('#WorksForm .three-d').parent().hide();
		}
			
			
	}

	$('#WorksClassification').change(function() {
		handleFields();
	});

	handleFields();

});

</script>
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

			<?=$this->form->hidden('title'); ?>

			<?php //FIXME set the dates in the annotation form; if they have no value, they will be set back to empty in the Archives class ?>
			<?=$this->form->hidden('earliest_date', array('value' => $work->archive->start_date_formatted())); ?>
			<?=$this->form->hidden('latest_date', array('value' => $work->archive->end_date_formatted())); ?>

			<?=$this->form->hidden('edition', array('value' => $work->attribute('edition'))); ?>
			<?=$this->form->hidden('signed', array('value' => $work->attribute('signed'))); ?>
			<?=$this->form->hidden('framed', array('value' => $work->attribute('framed'))); ?>
			<?=$this->form->hidden('artist_native_name', array('value' => $work->attribute('artist_native_name'))); ?>
		
		
			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/works/view/'.$work->archive->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		
	</div>

	<?=$this->partial->archives_links_edit(array(
		'model' => $work,
		'junctions' => $work_links,
	)); ?>		

	<div class="well">
		<legend>Albums</legend>
		<table class="table">
		
			<?php foreach($albums as $album): ?>
			<?php $component = $album->components[0]; ?> 
				<tr>
					<td>
						<?=$this->html->link($album->title, $this->url(array('Albums::view', 'slug' => $album->archive->slug))); ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($component, array('url' => $this->url(array('Components::delete', 'id' => $component->id)), 'method' => 'post')); ?>
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($other_albums) > 0): ?>
			
			<tr>
				<td></td>
				<td align="right" style="text-align:right">
					<a data-toggle="modal" href="#albumModal" class="btn btn-mini btn-inverse">Add an Album</a>
				</td>
			</tr>
			
			<?php endif; ?>
			
			</table>
		
	</div>
	
	<div class="well">
		<legend>Exhibitions</legend>
		<table class="table">
		
			<?php foreach($exhibitions as $exhibition): ?>
			<?php $component = $exhibition->components[0]; ?>
				<tr>
					<td>
						<?=$this->html->link($exhibition->title, $this->url(array('Exhibitions::view', 'slug' => $exhibition->archive->slug))); ?>
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
	
<div class="modal fade hide" id="albumModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Artwork to an Album</h3>
		</div>
		<div class="modal-body">
			<table class="table"><tbody>
			<?php foreach($other_albums as $oc): ?>
				<tr>
					<td>
						<strong>
							<?=$this->html->link($oc->title, $this->url(array('Albums::view', 'slug' => $oc->archive->slug))); ?>
						</strong><br/>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oc, array('url' => $this->url(array('Components::add')), 'method' => 'post')); ?>
			<input type="hidden" name="archive_id1" value="<?=$oc->id ?>" />
			<input type="hidden" name="archive_id2" value="<?=$work->id ?>" />
			<input type="hidden" name="type" value="albums_works" />
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

	<?=$this->partial->archives_documents_edit(array(
		'model' => $work,
		'archives_documents' => $archives_documents,
	)); ?>		

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
