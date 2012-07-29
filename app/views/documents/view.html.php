<?php 

$this->title($document->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Documents','/documents'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($document->title,'/documents/view/'.$document->slug); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/documents/edit/<?=$document->slug ?>">
			<i class="icon-pencil icon-white"></i> Edit Document
		</a>
		<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="">
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="/documents/edit/<?=$document->slug ?>">
					<i class="icon-pencil"></i> Edit
				</a>
			</li>
			<li>
				<a data-toggle="modal" href="#deleteModal">
					<i class="icon-trash"></i> Delete
				</a>
			</li>
		</ul>

	</div>
	
<?php endif; ?>

</div>

<div class="row">
	<div class="span6">
	
		<ul class="thumbnails">
		<?php $span = 'span6'; ?>
		<li class="<?=$span?>" >
		<a href="/files/download/<?=$document->slug?>.<?=$document->format->extension?>" class="thumbnail">
		<img src="/files/small/<?=$document->slug?>.jpeg" alt="<?=$document->title ?>">
		</a>
		</li>
		</ul>
		
	</div>
	
	<div class="span4">
	
   		<?php foreach($works_documents as $wd): ?>
			<div class="alert alert-block">
				<p>
					<?php echo $wd->work->caption(); ?>
					
					<?php
							echo "(Photo &copy; ";
							if($document->credit) { echo $document->credit . ', '; }
							echo $document->year() . ').';
					?>
					
				</p>
			</div>
		<?php endforeach; ?>
	
		<table class="table">
			<tbody>
				<tr>
					<td><i class="icon-picture"></i></td>
					<td class="meta">Artwork</td>
					<td>
						<ul class="unstyled" style="margin-bottom:0">
						
							<?php foreach($works_documents as $wd): ?>
							<li><strong><?=$this->html->link(
								$wd->work->title,
								'/works/view/'.$wd->work->slug
							);?></strong></li>
							<?php endforeach; ?>
						
						</ul>
					</td>
				</tr>
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">File Type</td>
					<td>
						<span class="label"><?= $document->format->mime_type ?></span>
					</td>
				</tr>
				<tr>
					<td><i class="icon-bookmark"></i></td>
					<td class="meta">Title</td>
					<td><?=$document->title ?></td>
				</tr>
				<tr>
					<td><i class="icon-calendar"></i></td>
					<td class="meta">Date</td>
					<td><?=$document->file_date ?></td>
				</tr>
				<tr>
					<td><i class="icon-camera"></i></td>
					<td class="meta">PhotoCredit</td>
					<td>
					<?php
						if($document->credit) { 
							echo $document->credit;
						 } else {
						 	echo '<span class="label label-warning">Unknown</span>';
						 }
					?>
					</td>
				</tr>
			</tbody>
		
		</table>
	
	</div>
</div>



<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Document</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$document->title; ?></strong>?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($document, array('url' => "/documents/delete/$document->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
