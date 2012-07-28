<?php 

$this->title('Documents');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Documents','/documents'); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/documents/add/">
			<i class="icon-plus-sign icon-white"></i> Add a Document
		</a>

	</div>

<?php endif; ?>

</div>

<?php if(sizeof($documents) == 0): ?>

	<div class="alert alert-danger">There are no Documents in the Archive.</div>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

		<div class="alert alert-success">You can add the first Document by clicking the <strong><?=$this->html->link('Add a Document','/documents/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<table class="table table-bordered">


<thead>
	<tr>
		<th>Preview</th>
		<th>Title</th>
		<th>Date</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($documents as $document): ?>

	<tr>
		<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $px = '260'; ?>
		<a href="/documents/view/<?=$document->slug ?>">
		<img width='125' height='125' src="/uploads/<?=$document->hash?>_<?=$px?>x<?=$px?>.<?=$document->format->extension?>" alt="<?=$document->title ?>">
		</a>
		</td>
		<td><?=$this->html->link($document->title, 'documents/view/'.$document->slug); ?></td>
		<td><?=$document->file_date ?></td>
	</tr>

<?php endforeach; ?>


    
</tbody>
</table>
