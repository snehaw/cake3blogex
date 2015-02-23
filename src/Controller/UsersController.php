<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\Event\Event;

class UsersController extends AppController 
{
	public function index()
	{
		$users = $this->Users->find();
		$this->set(compact('users'));
	}

	public function add()
	{
		$user = $this->Users->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if($this->Users->save($user)) {
				$this->Flash->success(__('User has been added successfully.'));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('User cannot be added please try again. '));
		}

		$this->set(compact('user'));
	}

	public function edit($id = null)
	{
		if(!$id) {
			throw new NotFoundException(__('Invalid User Request.'));
		}
		$user = $this->Users->get($id);
		if($this->request->is(['post', 'put'])) {
			$this->Users->patchEntity($user, $this->request->data);
			if ( $this->Users->save($user)) {
				$this->Flash->success(__('Your user has been updated.'));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('Unable to update your user.'));
		}
		$this->set('user', $user);
	}

	public function view($id = null)
	{
		if (!$id) {
			throw new NotFoundException("Invalid User Request");
		}

		$user = $this->Users->get($id);
		$this->set('user', $user);
	}

	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$user = $this->Users->get($id);
		if($this->Users->delete($user)){
			$this->Flash->success(__('The article with id: {0} has been deleted.', h($id)));
        		return $this->redirect(['action' => 'index']);
		}
	}

	public function login()
	{
		if($this->request->is('post')){
			$user = $this->Auth->identify();
			// debug($user);exit;
			if($user){
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl());
        	}
        	$this->Flash->error(__('Invalid username or password, try again'));
		}
	}

	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}
}