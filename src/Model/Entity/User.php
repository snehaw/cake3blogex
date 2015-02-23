<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class User extends Entity 
{
	protected $accessible = [ '*' => true ];	

	public function _setPassword($value)
	{
		return ((new DefaultPasswordHasher())->hash($value));
	}
}