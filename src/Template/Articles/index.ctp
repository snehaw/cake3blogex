<!-- File: src/Template/Articles/index.ctp -->
<?php //pr($articles->toArray()); ?>
<div class="actions columns large-2 medium-3">
    <?= $this->element('actions') ?>
</div>
<div class="articles index large-10 medium-9 columns">
<h1>Blog articles</h1>
<?= $this->Html->link(__('Add Article'), ['action' => 'add']) ?>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Created</th>
        <th>Action</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->
	<?php if(!empty($articles->toArray())) { ?>
    <?php foreach ($articles as $article): ?>
    <tr>
        <td><?= $article->id ?></td>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->id]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
        <td>
        	<?= $this->Html->link(__('Edit'), ['action' => 'edit', $article->id]) ?>
        	<?= $this->Html->link(__('View'), ['action' => 'view', $article->id]) ?>
        	<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $article->id], ['confirm' => __('Are you sure you want to delete article {0} ?', $article->id)]) ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php } else { ?>
    <tr>
    	<td colspan=4 class="not-found">
    		<?= __('No articles found') ?>
    	</td>
    </tr>
	<?php }//endif; ?>
</table>
</div>