<?php 

$this->title('Links');

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
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="/links/add"><i class="icon-plus-sign icon-white"></i> Add a Link</a>
		
		<?php endif; ?>

	</div>
<div>

<table class="table table-striped table-bordered">

<tbody>

<?php foreach ($links as $link): ?>

<tr>
	<td>
		<?php $title = $link->title ?: $link->url; ?>

		<p>
			<?=$this->html->link($title, $link->url); ?>
				<?php if($auth->role->name == 'Admin'): ?>

				<a href="/links/edit/<?=$link->id ?>" title="Edit Link"><i class="icon icon-edit"></i></a>
		
				<?php endif; ?>
		</p>

		<p><?=$link->description ?></p>

	</td>
</tr>

<?php endforeach; ?>

</tbody>
