<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table {

	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		$this->hasOne('Profiles', [
            'className' => 'Profiles',
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);
	}


	public function validationDefault(Validator $validator)
	{
		$validator
			->notEmpty('username')
			->notEmpty('password')
			->notEmpty('role');

			return $validator;
	}
}