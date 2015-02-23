<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;

class ArticlesController extends AppController
{
	public function initialize()
	{
		parent::initialize();

	}
	
	public function isAuthorized($user)
	{
		$action = $this->request->params['action'];
		if(isset($user['role']) && 'author' === $user['role']) {
			if(in_array($action, ['edit', 'delete'])) {
				$articleId = (int)$this->request->params['pass'][0];
				if($this->Articles->isOwnedBy($articleId, $user['id']))
				{
					return true;
				}
			}
		}

		return parent::isAuthorized($user);
	}

	public function index()
	{
		$articles = $this->Articles->find();
		$this->set('articles', $articles);
	}

	public function add()
	{
		$article = $this->Articles->newEntity($this->request->data);
		if($this->request->is('post')){
			if($this->Articles->save($article)) {
				$this->Flash->success(__('Article has been saved succesfully.'));
				return $this->redirect(['action' => 'index']);
			} 
			$this->Flash->error(__('The article could not be saved. Please, try again.'));
		}
		$this->set(compact('article'));
	}

	public function edit($id = null)
	{
		if(!$id) {
			throw new NotFoundException(__('Invalid Article Request.'));
		}

		$article = $this->Articles->get($id);

		if($this->request->is(['post', 'put'])) {
			$this->Articles->patchEntity($article, $this->request->data);
			if ( $this->Articles->save($article)) {
				$this->Flash->success(__('Your article has been updated.'));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('Unable to update your article.'));
		}
		$this->set('article', $article);
	}

	public function view($id = null)
	{
		if (!$id) {
			throw new NotFoundException("Invalid Article Request");
		}

		$article = $this->Articles->get($id);
		$this->set('article', $article);
		
	}

	public function delete($id = null)
	{
		// if (!$id) {
		// 	throw new NotFoundException("Invalid Article Request");			
		// }

		$this->request->allowMethod(['post', 'delete']);
		$article = $this->Articles->get($id);
		if($this->Articles->delete($article)){
			$this->Flash->success(__('The article with id: {0} has been deleted.', h($id)));
        		return $this->redirect(['action' => 'index']);
		}
	}
}