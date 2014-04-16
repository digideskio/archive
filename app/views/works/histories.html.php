<?php

$this->title('Artwork History');

$auth = $this->authority->auth();

$authority_can_edit = $this->authority->canEdit();

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

?>

<?=$this->partial->breadcrumbs(array(
	'crumbs' => array(
		array('title' => 'Artwork', 'url' => $this->url(array('Works::histories'))),
		array('title' => 'History', 'active' => true)
	)
)); ?>

<div class="actions">

<?=$this->partial->navtabs(array(
	'tabs' => array(
		array('title' => 'Index', 'url' => $this->url(array('Works::index'))),
		array('title' => 'Classifications', 'url' => $this->url(array('Works::classifications'))),
		array('title' => 'Locations', 'url' => $this->url(array('Works::locations'))),
		array('title' => 'History', 'url' => $this->url(array('Works::histories')), 'active' => true),
		array('title' => 'Search', 'url' => $this->url(array('Works::search'))),
	)
)); ?>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

			<a class="btn btn-inverse" href="<?=$this->url(array('Works::add')); ?>"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>

		<?php endif; ?>

	</div>

</div>

<table class="table">

	<thead>
		<tr>
			<th>User</th>
			<th style="min-width:150px">Date</th>
			<th>Title</th>
		<tr>
	</thead>
	<tbody>

	<?php if (sizeof($archives_histories) > 0 ): ?>

	<?php foreach( $archives_histories as $ah ): ?>

		<?php
			$start_date_string = date("Y-m-d H:i:s", $ah->start_date);
			$start_date_time = new DateTime($start_date_string);

			if (isset($tz)) {
				$start_date_time->setTimeZone($tz);
			}
			$start_date_display = $start_date_time->format("Y-m-d H:i:s");
		?>

		<tr>
			<td style="text-align:center">
				<?php if( $ah->user->id ): ?>
				<strong style="font-size: smaller;">
					<?=$this->html->link($ah->user->initials(),'/users/view/'.$ah->user->username); ?>
				</strong>
				<?php endif; ?>
			</td>
			<td><?=$start_date_display ?></td>
			<td>
				<?php if ($ah->archive->id): ?>
					<?=$this->html->link($ah->name,'/works/history/'.$ah->slug); ?>
				<?php else: ?>
					<?=$ah->name ?> <span class="meta muted text-error">&mdash; Deleted</span>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>

	<?php endif; ?>

	</tbody>

</table>

<?=$this->pagination->pager('works', 'histories', $page, $total, $limit); ?>
