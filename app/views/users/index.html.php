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



<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/users/add/">
			<i class="icon-plus-sign icon-white"></i> Add User
		</a>

	</div>

<?php endif; ?>

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

<tr>

	<th><?=$this->html->link($user->username,'/users/view/'.$user->username); ?></th>
	<td><?= $user->name; ?></td>
	<td><a href="mailto:<?= $user->email ?>"><?= $user->email ?></a></td>
	<td><?= $user->role->name; ?></td>

</tr>
    
<?php endforeach; ?>



</tbody></table>
