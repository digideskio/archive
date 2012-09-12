<?php 

$this->title('Search');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Search','/search'); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		<?=$this->html->link($query,'/search?query='.$query); ?>
	</li>

	</ul>

</div>

<div class="navbar">
	<div class="navbar-inner">
	<ul class="nav">
		<li class="meta"><a href="#">Artwork</a></li>
	</ul>
	</div>
</div>

<table class="table table-bordered">

<thead>
	<tr>
		<th>ID</th>
		<th>Image</th>
		<th>Title</th>
		<th>Year</th>
		<th>Notes</th>
		<th>Classification</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($works as $work): ?>

<tr>
	<td><?=$work->creation_number?></td>
	
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $work->documents('first'); if($document->id) { ?>	
			<a href="/works/view/<?=$work->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>
    <td><?=$this->html->link($work->title,'/works/view/'.$work->slug); ?></td>
    <td><?=$work->years(); ?></td>
    <td><?php echo $work->notes(); ?></td>
    <td><?=$work->classification ?></td>
</tr>

<?php endforeach; ?>

</tbody>
</table>

<div class="navbar">
	<div class="navbar-inner">
	<ul class="nav">
		<li class="meta"><a href="#">Documents</a></li>
	</ul>
	</div>
</div>

<ul class="thumbnails">
<?php foreach ($documents as $document): ?>

	<?php
		$span = 'span2';
	?>
	
	<li class="<?=$span?>">
		<a href="/documents/view/<?=$document->slug?>"  class="thumbnail" title="<?=$document->slug?>">
			<img src="/files/thumb/<?=$document->slug?>.jpeg" alt="<?=$document->title ?>">
		</a>
	</li>

<?php endforeach; ?>
</ul>
