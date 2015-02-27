<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class User extends Entity 
{
	protected $_accessible = [ '*' => true ];	

	public function _setPassword($value)
	{
		return ((new DefaultPasswordHasher())->hash($value));
	}
}