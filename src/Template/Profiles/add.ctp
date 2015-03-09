<div class="actions columns large-2 medium-3">
    <?php echo $this->element('actions'); ?>
    <!-- <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Profiles'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul> -->
</div>
<div class="profiles form large-10 medium-9 columns">
    <?php if(!empty($profile)): ?>
    <?= $this->Form->create($profile, ['type' => 'file']); ?>
    <fieldset>
        <legend><?= __('Add Profile') ?></legend>
        <?php
            // echo $this->Form->input('user_id', ['options' => $users, 'empty' => true]);
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            echo $this->Form->input('image', ['type' => 'file']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    <?php else: ?>
    <br>
    <p><?= $this->Html->link(__('<-- Back to list'), ['action' => 'index']) ?></p>
    <p><?= $this->Html->link(__('Edit Profile'), ['action' => 'edit', $profileid->id]) ?></p>
    <?php endif; ?>
</div>
