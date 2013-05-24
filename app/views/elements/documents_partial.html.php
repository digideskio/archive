<?php if (isset($showBar) && $showBar): ?>

<div class="navbar">
	<div class="navbar-inner">
	<ul class="nav">
		<li class="meta"><a href="#">Documents</a></li>
	</ul>
	</div>
</div>

<?php endif; ?>

<form action="/works/add" method="post">

	<table class="table table-bordered">
	<tbody>
	<tr><td>
	<?=$this->form->submit('Create Artwork', array('class' => 'btn btn-small  batch-edit-btn', 'disabled' => 'disabled')); ?>
	</td></tr>
	</tbody>
	</table>

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
			<label class="batch-checkbox" for="Document-<?=$document->id?>">
			<?=$this->form->checkbox('documents[]', array('id' => "Document-$document->id", 'value' => $document->id, 'hidden' => false, 'class' => 'checkdocs'));?>
			</label>
		</li>

	<?php endforeach; ?>

	</ul>

<?=$this->form->end(); ?>

<script>

$(document).ready(function() {

	function handleButtons() {
		if ($('.checkdocs:checked').length) {
			$('.batch-edit-btn').removeAttr('disabled');
		} else {
			$('.batch-edit-btn').attr('disabled', 'disabled');
		}
	}

	$('.checkdocs').change(function() {
		if ($(this).attr('checked')) {
			$(this).closest('.batch-checkbox').addClass('checked');
		} else {
			$(this).closest('.batch-checkbox').removeClass('checked');
		}

		handleButtons();

	});

	handleButtons();

});

</script>
