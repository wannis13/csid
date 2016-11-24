<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CSIDBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use La2UserBundle\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Sonata\UserBundle\Entity\UserManager as BaseUserManager;


class UserManager extends BaseUserManager 
{	
	/**
	 * find all users
	 */
	public function findAllUsers()
	{
		return $this->repository->findAll();
	}
	
	/**
	 * add a role to an user
	 * 
	 * @param User $user
	 * @param array $role
	 */
	public function addRole(User $user, array $role){
		foreach($role as $itemRole){
			$user->addRole($itemRole);
		}
		
		$this->objectManager->persist($user);
		$this->objectManager->flush();
	}
	
	/**
	 * find user by id
	 *
	 * @param integer $id
	 * @return \La2UserBundle\Entity\User
	 */
	public function findById($id)
	{
		return $this->repository->find($id);
	}
	
	/**
	 * delete user by id
	 *
	 * @param integer $id
	 * 
	 */
	public function deletedById($id)
	{
		try{
			$user = $this->repository->find($id);
			$this->objectManager->persist($user);
			$this->objectManager->remove($user);
			$this->objectManager->flush();
		} catch (EntityNotFoundException $ex) {
        	echo "Exception Found - " . $ex->getMessage() . "<br/>";
		}
	}
}