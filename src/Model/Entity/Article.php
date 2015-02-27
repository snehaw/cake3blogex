<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Article extends Entity
{
	protected $_accessible = [
        '*' => true ];

  //   $slug = $this->slug('My article name');
		// pr($slug);

}