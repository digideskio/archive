<?php

	$this->title('Users'); 

?>

<div id="location" class="row-fluid">

<ul class="breadcrumb">
  <li class="active">
      <?= $this->title() ?>
  </li>

    </ul>

</div>


<div class="actions">
		
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#">Index</a>
		</li>
	</ul>

	<div class="btn-toolbar">

	<?php if($this->authority->isAdmin()): ?>

		<div class="action btn-group">

			<a class="btn btn-inverse" href="/users/add/">
				<i class="icon-plus-sign icon-white"></i> Add User
			</a>

		</div>

	<?php endif; ?>

	</div>
</div>

<table class="table table-striped table-bordered">

<thead>
    <tr>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
    </tr>
</thead>
<tbody>

<?php foreach($users as $user): ?>

<?php $row_class = $user->active ? '' : 'error'; ?>
<?php $text_class = $user->active ? '' : 'text-error'; ?>

<tr class="<?=$row_class ?>">

	<td><?=$this->html->link(
			$user->username,'/users/view/'.$user->username,
			array('class' => $text_class));
		?>
	</td>
	<td class="muted"><?= $user->name; ?></td>
	<td><a class="<?=$text_class ?>" href="mailto:<?= $user->email ?>"><?= $user->email ?></a></td>
	<td class="meta">
	<?php if($user->active): ?>
		<?= $user->role->name; ?>
	<?php else: ?>
		Disabled
	<?php endif; ?>
	</td>

</tr>
    
<?php endforeach; ?>



</tbody></table>
