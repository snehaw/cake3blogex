<h1>Add User</h1>
<?= $this->Form->create() ?>
<?= $this->Form->input('username') ?>
<?= $this->Form->input('password') ?>
<?= $this->Form->input('role', ['options' => ['admin' => 'Admin', 'author' => 'Author']]) ?>
<?= $this->Form->button('Add Article') ?>
<?= $this->Form->end() ?>