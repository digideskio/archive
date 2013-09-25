<?php 

$this->title('Links');

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Links','/links'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">

		<li class="active">
			<?=$this->html->link('Index','/links'); ?>
		</li>

<!--		<li>
			<?=$this->html->link('Search','/links/search'); ?>
		</li>
	-->

	</ul>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

				<a class="btn btn-inverse" href="/links/add"><i class="icon-plus-sign icon-white"></i> Add a Link</a>
		
		<?php endif; ?>

	</div>
<div>

<?php if($total > 0): ?>

<table class="table table-striped table-bordered">

<tbody>

<script>
$(document).ready(function() {
	$('.model-tip').tooltip();
});
</script>

<?php foreach ($links as $link): ?>

<tr <?php if ($link->id == $saved) { echo 'class="success"'; } ?>>
	<td>
		<?php $title = $link->title ?: $link->url; ?>

		<p class="lead" style="margin-bottom: 0">
			<?=$this->html->link($title, $this->url(array('Links::view', 'id' => $link->id))); ?>

			<small style="font-size: smaller;">
				<?php $date = date('Y-m-d', strtotime($link->date_created)); ?>
					<?=$date ?>
			</small>

				<?php if($authority_can_edit): ?>

				<?php if ($link->id == $saved): ?>
					<span class="label">Saved</span>
				<?php endif; ?>

				<?php endif; ?>

		</p>

		<p>
			<strong>
				<?=$this->html->link($link->url) ?>
			</strong>
		</p>
	
		<?php if (!empty($link->description)): ?>
		<blockquote><?=$link->description ?></blockquote>
		<?php endif; ?>

	</td>
</tr>

<?php endforeach; ?>

</tbody>

</table>

<?=$this->pagination->pager('links', 'pages', $page, $total, $limit); ?>

<?php endif; ?>
