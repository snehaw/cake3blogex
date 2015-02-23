<!-- File: src/Template/Users/index.ctp -->
<?php //pr($users->toArray()); ?>
<div class="actions columns large-2 medium-3">
    <?= $this->element('actions'); ?>
</div>
<div class="users index large-10 medium-9 columns">
<h1>Users List</h1>
<?= $this->Html->link(__('Add User'), ['action' => 'add']) ?>
<table>
    <tr>
        <th>Id</th>
        <th>Username</th>
        <th>Role</th>
        <th>Created</th>
        <th>Action</th>
    </tr>

    <!-- Here is where we iterate through our $users query object, printing out article info -->
	<?php if(!empty($users->toArray())) { ?>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user->id ?></td>
        <td>
            <?= $this->Html->link($user->username, ['action' => 'view', $user->id]) ?>
        </td>
        <td><?= $user->role ?></td>
        <td>
            <?= $user->created->format(DATE_RFC850) ?>
        </td>
        <td>
        	<?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
        	<?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
        	<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete user {0} ?', $user->id)]) ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php } else { ?>
    <tr>
    	<td colspan=5 class="not-found">
    		<?= __('No users found') ?>
    	</td>
    </tr>
	<?php }//endif; ?>
</table>
</div>