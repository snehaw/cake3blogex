<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ArticlesTable extends Table 
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		// Just add the Belongs to relationship with CategoriesTable
		$this->belongsTo('Users', [
			'foreignKey' => 'user_id'
			]);
		// $this->belongsTo('Categories', [
		// 	'foreignKey' => 'category_id',
		// 	]);
	}

	public function validationDefault(Validator $validator)
	{
		$validator
			->notEmpty('title')
			->notEmpty('body');
			
		return $validator;
	}

	public function isOwnedBy($articleId, $user_id)
	{
		return $this->exists(['id' => $articleId, 'user_id' => $user_id]);
	}
}