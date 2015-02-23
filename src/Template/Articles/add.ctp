<h1>Add Article</h1>
<?= $this->Form->create() ?>
<?= $this->Form->input('title') ?>
<?= $this->Form->input('body') ?>
<?= $this->Form->input('category') ?>
<?= $this->Form->button('Add Article') ?>
<?= $this->Form->end() ?>