<?php 

$this->title('Albums');

$auth = $this->authority->auth();

if ($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Albums',$this->url(array('Albums::index'))); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('Index',$this->url(array('Albums::index'))); ?>
		</li>
		<li>
			<?=$this->html->link('Packages',$this->url(array('Albums::packages'))); ?>
		</li>
	</ul>

	<div class="btn-toolbar">
		<?php if($this->authority->canEdit()): ?>

				<a class="btn btn-inverse" href="<?=$this->url(array('Albums::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add an Album</a>
		
		<?php endif; ?>
	</div>
</div>

<?php if ($albums->count() == 0): ?>

	<div class="alert alert-danger">There are no Albums in the Archive.</div>

	<?php if($this->authority->canEdit()): ?>

		<div class="alert alert-success">You can create the first Album by clicking the <strong><?=$this->html->link('Add an Album',$this->url(array('Albums::add'))); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if ($albums->count() > 0): ?>

<table class="table">
<thead>
	<th>
		Title
		<?=$this->html->link(
			'&uarr;&darr;',
			$this->url(array("Albums::index")) . '?order=title',
			array(
				'escape' => false,
				'title' => 'Order by Title'
			)
		); ?>
	</th>
	<th>
		Date
		<?=$this->html->link(
			'&uarr;&darr;',
			$this->url(array("Albums::index")) . '?order=date',
			array(
				'escape' => false,
				'title' => 'Order by Date'
			)
		); ?>
	</th>
	<th>User</th>
</thead>
<tbody>

<?php foreach($albums as $album): ?>
<?php
	$archive = $album->archive;

	$update_time = new DateTime($archive->date_created);

	if (isset($tz)) {
		$update_time->setTimeZone($tz);
	}

	$update_date = $update_time->format("d M Y");
?>
<tr>
	<td>
		<strong>
    	<?=$this->html->link($archive->name, $this->url(array('Albums::view', 'slug' => $archive->slug))); ?>
		</strong>
		<?php if ($album->remarks != ''): ?>
			&ndash; <em><?=$album->remarks ?></em>
		<?php endif; ?>
	</td>
	<td>
		<small>
			<?=$update_date ?>
		</small>
	</td>
	<td>
		<?php if (!empty($archive->user->id)): ?>
		<small>
			<?php $user_name = str_replace(' ', '&nbsp;', $this->escape($archive->user->name)); ?>
			<?=$this->html->link($user_name,'/users/view/'.$archive->user->username, array('escape' => false)); ?>
		</small>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?=$this->pagination->pager('albums', 'pages', $page, $total, $limit, array('limit' => $limit)); ?>

<?php endif; ?>
