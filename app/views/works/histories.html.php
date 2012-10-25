
<?php 

$this->title('Artwork History');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		History
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<a href="/works">Index</a>
		</li>

		<li class="active">
			<?=$this->html->link('History','/works/histories'); ?>
		</li>

		<li>
			<?=$this->html->link('Search','/works/search'); ?>
		</li>

	</ul>
	
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/works/add/"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
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

	<?php foreach( $works_histories as $wh ): ?>
		<tr>
			<td style="text-align:center">
				<?php if( $wh->user->id ): ?>
				<strong style="font-size: smaller;">
					<?=$this->html->link($wh->user->initials(),'/users/view/'.$wh->user->username); ?>
				</strong>
				<?php endif; ?>
			</td>
			<td><?=$wh->start_date ?></td>
			<td>
				<?=$this->html->link($wh->title,'/works/history/'.$wh->slug); ?>
			</td>
		</tr>
	<?php endforeach; ?>

	</tbody>

</table>


<div class="pagination">
    <ul>
    <?php if($page > 1):?>
    <li><?=$this->html->link('«', array('Works::histories', 'page'=> $page - 1));?></li> 
    <?php endif;?> 
        <li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
     <?php if($total > ($limit * $page)):?>
     <li><?=$this->html->link('»', array('Works::histories', 'page'=> $page + 1));?></li>
     <?php endif;?> 
    </ul>
</div>
