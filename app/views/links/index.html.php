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

<style>

	.edit-link {
		color:#D14;
	}

	.view-link {
		color: gray;
	}

</style>

<?php foreach ($links as $link): ?>

<tr <?php if ($link->id == $saved) { echo 'class="success"'; } ?>>
	<td>
		<?php $title = $link->title ?: $link->url; ?>

		<p>
			<?=$this->html->link($title, $link->url); ?>

				<?php 
					$has_works = sizeof($link->works_links) ? true : false; 
					$has_exhibitions = sizeof($link->exhibitions_links) ? true : false; 
					$has_publications = sizeof($link->publications_links) ? true : false; 
				?>
				<?php if($has_works): ?>
					<a href="/links/view/<?=$link->id ?>" rel="tooltip" title="This link has artwork" class="model-tip">
					<i class="icon-picture"></i>
					</a>
				<?php endif; ?>

				<?php if($has_exhibitions): ?>
					<a href="/links/view/<?=$link->id ?>" rel="tooltip" title="This link has exhibitions" class="model-tip">
					<i class="icon-eye-open"></i>
					</a>
				<?php endif; ?>

				<?php if($has_publications): ?>
					<a href="/links/view/<?=$link->id ?>" rel="tooltip" title="This link has publications" class="model-tip">
					<i class="icon-book"></i>
					</a>
				<?php endif; ?>

				<?php if($authority_can_edit): ?>

				<?php if ($link->id == $saved): ?>
					<span class="label">Saved</span>
				<?php endif; ?>

				<?php endif; ?>

		</p>

		<p>
			<?=$link->url ?>
		</p>

		<blockquote><?=$link->description ?></blockquote>

		<p>
			<small style="font-size: smaller;">
				<?php $date = date('Y-m-d', strtotime($link->date_created)); ?>
				<a class="view-link" href="/links/view/<?=$link->id ?>">Added <?=$date ?></a>
			<!--	<a class="edit-link" href="/links/edit/<?=$link->id ?>">Edit</a> -->
			</small>
		</p>

	</td>
</tr>

<?php endforeach; ?>

</tbody>

</table>

<?=$this->pagination->pager('links', 'pages', $page, $total, $limit); ?>

<?php endif; ?>
