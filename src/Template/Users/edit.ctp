<!-- File: src/Template/Articles/edit.ctp -->

<h1>Edit User</h1>
<?php
    echo $this->Form->create($user);
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Form->input('role', ['options' => ['admin' => 'Admin', 'author' => 'Author']]);
    echo $this->Form->button(__('Save user'));
    echo $this->Form->end();
?>