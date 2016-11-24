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

use CSIDBundle\Doctrine\UserManager;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CustomerManager extends UserManager
{
	/**
	 * find customer by retailer
	 * @param User $user
	 */
	public function findCustomers($user, $page = 1, $maxPerPage = 10, $term = null)
	{
		$qb = $this->repository->createQueryBuilder('c');
	
		$qb->where('c.retailer = :user')
		->setParameter('user', $user->getId());
		
		if($term != null) {
			$qb->andWhere('c.lastname LIKE :term')
			->setParameter('term', $term . '%');
		}
		
		$qb->setFirstResult(($page - 1) * $maxPerPage)->setMaxResults($maxPerPage);
	
		$paginator = new Paginator($qb);
	
		return array(
			'list' => $qb->getQuery()->getResult(),
			'count' => count($paginator, true)
		);
	}
	
	/**
	 * update a retailer customer
	 * @param \CSIDBundle\Entity\User $currentUser
	 * @param \CSIDBundle\Entity\User $retailer
	 * @param array $data
	 */
	public function updateCustomer($currentUser, $retailer, $data)
	{
		$class = $this->class;
		$customer = null;
		
		if($currentUser->getRetailer() != null) {
			$customer = $currentUser;
		} else {
			if($data['customer_id'] != null) {
				$customer = $this->findById($data['customer_id']);
			
				if($customer->getRetailer()->getId() != $retailer->getId()) {
					$customer = null;
				}
			}
		}
	
		if($customer == null) {
			$customer = new $class();
			$customer->setRetailer($retailer);
			$customer->setUsername(strtolower(substr($data['lastname'], 0, 1).'.'.$data['firstname'].'-'.$retailer->getId()));
			$generator = new SecureRandom();
			$password = $generator->nextBytes(10);
			$customer->setPlainPassword($password);
			$customer->setEmail($data['email']);
			$this->objectManager->persist($customer);
	
			$this->addRole($customer, array('ROLE_CUSTOMER'));
			$this->updatePassword($customer);
		}
	
		if($customer->getEmail() != $data['email']) {
			$customer->setEmail($data['email']);
		}
		
		$customer->setAddress($data['adress']);
		$customer->setLastname($data['lastname']);
		$customer->setFirstname($data['firstname']);
		$customer->setPostalCode($data['postal_code']);
		$customer->setCity($data['city']);
		$customer->setCompany($data['company']);
	
		$this->updateCanonicalFields($customer);
	
		$this->objectManager->flush();
	
		return $customer;
	}
}