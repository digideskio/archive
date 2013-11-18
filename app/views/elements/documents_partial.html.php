<?php
	$authority_can_edit = $this->authority->canEdit();
?>

<?php if (isset($showBar) && $showBar): ?>

<div class="navbar">
	<div class="navbar-inner">
	<ul class="nav">
		<li class="meta"><a href="#">Documents</a></li>
	</ul>
	</div>
</div>

<?php endif; ?>

<?php if($authority_can_edit): ?>
<form method="post">

	<table class="table table-bordered">
	<tbody>
	<tr><td>
	<div id="docs-toolbar" class="btn-toolbar">
		<div id="select-docs" class="btn-group">
		  <a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="icon-ok"></i> 
			<span class="caret"></span>
		  </a>
		  <ul class="dropdown-menu">
			<li id="select-all-docs"><a href="#"><i class="icon-ok"></i> Select All</a></li>
			<li id="select-no-docs"><a href="#"><i class="icon-remove"></i> Select None</a></li>
		  </ul>
		</div>
		<div class="btn-group">
		<?=$this->form->submit('Create Artwork', array('onclick' => "this.form.action='/works/add'", 'class' => 'btn btn-small batch-edit-btn', 'disabled' => 'disabled')); ?>
		</div>
		<div class="btn-group">
		<?=$this->form->submit('Create Publication', array('onclick' => "this.form.action='/publications/add'", 'class' => 'btn btn-small batch-edit-btn', 'disabled' => 'disabled')); ?>
		</div>
	</div>
	</td></tr>
	</tbody>
	</table>
<?php endif; ?>

	<ul class="thumbnails">

	<?php foreach($documents as $document): ?>

		<?php
			$span = 'span2';
		?>
		
		<li class="<?=$span?>">
			<div style="position:relative;">
			<a href="/documents/view/<?=$document->slug?>" class="thumbnail" title="<?=$document->title?>">
				<img src="/files/thumb/<?=$document->slug?>.jpeg" alt="<?=$document->title ?>">
			</a>
	<?php if($authority_can_edit): ?>
			<label class="batch-checkbox doc-checkbox" for="Document-<?=$document->id?>">
			<?=$this->form->checkbox('documents[]', array('id' => "Document-$document->id", 'value' => $document->id, 'hidden' => false, 'class' => 'checkdocs'));?>
			</label>
	<?php endif; ?>
			</div>
		</li>

	<?php endforeach; ?>

	</ul>

<?php if($authority_can_edit): ?>
<?=$this->form->end(); ?>

<script>

$(document).ready(function() {

	$('#select-docs #select-all-docs').click(function(event) {
		event.preventDefault();
		$('.checkdocs').attr('checked', true);
		$('.batch-checkbox').addClass('checked');
		handleDocsButtons();
	});

	$('#select-docs #select-no-docs').click(function(event) {
		event.preventDefault();
		$('.checkdocs').attr('checked', false);
		$('.batch-checkbox').removeClass('checked');
		handleDocsButtons();
	});

	function handleDocsButtons() {
		if ($('.checkdocs:checked').length) {
			$('#docs-toolbar .batch-edit-btn').removeAttr('disabled');
			$('#docs-toolbar .batch-edit-btn').addClass('btn-success');
		} else {
			$('#docs-toolbar .batch-edit-btn').attr('disabled', 'disabled');
			$('#docs-toolbar .batch-edit-btn').removeClass('btn-success');
		}
	}

	$('.checkdocs').change(function() {
		if ($(this).attr('checked')) {
			$(this).closest('.batch-checkbox').addClass('checked');
		} else {
			$(this).closest('.batch-checkbox').removeClass('checked');
		}

		handleDocsButtons();

	});

	handleDocsButtons();

});

</script>
<?php endif; ?>
