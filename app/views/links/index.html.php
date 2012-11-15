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

<?php if($total > 0): ?>

<table class="table table-striped table-bordered">

<tbody>

<?php foreach ($links as $link): ?>

<tr <?php if ($link->id == $saved) { echo 'class="success"'; } ?>>
	<td>
		<?php $title = $link->title ?: $link->url; ?>

		<p>
			<?=$this->html->link($title, $link->url); ?>
				<?php if($auth->role->name == 'Admin'): ?>

				<a href="/links/edit/<?=$link->id ?>" title="Edit Link"><i class="icon icon-edit"></i></a>

				<?php if ($link->id == $saved): ?>
					<span class="label">Saved</span>
				<?php endif; ?>

				<?php endif; ?>
		</p>

		<blockquote><?=$link->description ?></blockquote>

		<p><small style="font-size: smaller;"><?=$link->date_modified ?></small></p>

	</td>
</tr>

<?php endforeach; ?>

</tbody>

</table>

<div class="pagination">
    <ul>
    <?php if($page > 1):?>
    <li><?=$this->html->link('«', array('Links::index', 'page'=> $page - 1));?></li> 
    <?php endif;?> 
        <li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
     <?php if($total > ($limit * $page)):?>
     <li><?=$this->html->link('»', array('Links::index', 'page'=> $page + 1));?></li>
     <?php endif;?> 
    </ul>
</div>

<?php endif; ?>
