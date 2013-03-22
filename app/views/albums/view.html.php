<?php 

$this->title($album->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Albums', $this->url(array('Albums::index'))); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($album->title, $this->url(array('Albums::view', 'slug' => $album->archive->slug))); ?>
	</li>

	</ul>

</div>

<div class="actions">

	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#">View</a>
		</li>

		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
		
			<li><?=$this->html->link('Edit', $this->url(array('Albums::edit', 'slug' => $album->archive->slug))); ?></li>
		
		<?php endif; ?>

		<li><?=$this->html->link('History', $this->url(array('Albums::history', 'slug' => $album->archive->slug))); ?></li>

		<li><?=$this->html->link('Packages', $this->url(array('Albums::package', 'slug' => $album->archive->slug))); ?></li>

	</ul>

	<?php if($li3_pdf): ?>

	<div class="btn-toolbar">
		<div class="btn-group">
			<button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown"><i class="icon-print icon-white"></i> Print <span class="caret"</span></button>
			<ul class="dropdown-menu">
				<li><a href="<?=$this->url(array('Albums::publish', 'slug' => $album->archive->slug)); ?>?view=artwork"><i class="icon-picture"></i> Print Artwork</a></li>
				<li><a href="<?=$this->url(array('Albums::publish', 'slug' => $album->archive->slug)); ?>?view=images"><i class="icon-camera"></i> Print Images</a></li>
			</ul>
		</div>
	</div>
</div>

	<?php endif; ?>
<?php if($album->remarks): ?>
	<div class="alert alert-info">
	<p><?=$album->remarks ?></p>
	</div>
<?php endif; ?>

<?php if(sizeof($archives_documents) > 0): ?>

	<?=$this->partial->archives_documents(array('archives_documents' => $archives_documents, 'showBar' => false)); ?>

<?php endif; ?>

<table class="table table-bordered">

<thead>
	<tr>
		<th>Year</th>
		<th>Image</th>
		<th>Notes</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($works as $work): ?>

<tr>
	<td class="meta"><?=$work->archive->years(); ?> </td>
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
	
		<?php 
			$document = $work->documents('first');
		
			if($document->id) {
				$thumbnail = $document->view();
				$work_slug = $work->archive->slug;
				echo "<a href='/works/view/$work_slug'>";
				echo "<img width='125' height='125' src='/files/$thumbnail' />";
				echo "</a>";
			} else {
				echo '<span class="label label-warning">No Image</span>';
			}
		
		?>
	
	</td>
    <td>
		<h5><?=$this->html->link($work->title,'/works/view/'.$work->archive->slug); ?></h5>
		<p><small><?=$this->artwork->caption($work->archive, $work); ?></small></p>
		<blockquote class="pull-right"><?=$work->annotation ?></blockquote>
</tr>
    
<?php endforeach; ?>

</tbody>
