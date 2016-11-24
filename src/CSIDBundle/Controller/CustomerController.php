<?php
namespace CSIDBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class CustomerController extends Controller
{
	/**
	 * list all customers
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction (Request $request)
	{
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && !$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if ($request->isXmlHttpRequest()) {
			/** @var $customerManager \CSIDBundle\Doctrine\CustomerManager **/
			$customerManager = $this->container->get('csid.customer_manager');
			
			$page = $request->query->get('page');
			$maxPerPage = $request->query->get('rows');
			
			$result = $customerManager->findCustomers($retailer, $page, $maxPerPage);
			
			$customers = [];
			
			/** @var $customer \La2UserBundle\Entity\User **/
			foreach ($result['list'] as $customer) {
				$customers[] = array(
					'id' => $customer->getId(),
					'username' => $customer->getUsername(),
					'lastname' => $customer->getLastname(),
					'firstname' => $customer->getFirstname(),
					'email' => $customer->getEmail()
				);
			}
			
			$data = array(
				'page' => 1,
				'records' => $maxPerPage,
				'rows' => $customers,
				'total' => $result['count']
			);
			
			return new JsonResponse($data);
		}
		
		return $this->container->get('templating')->renderResponse('CSIDBundle:Customers:index.html.twig');
	}
	
	/** 
	 * delete customer
	 * @param unknown $id
	 */
	public function deleteAction($id)
	{
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && !$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		$user = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		/** @var $userManager \CSIDBundle\Doctrine\UserManager **/
		$userManager = $this->container->get('csid.user_manager');
		$customer = $userManager->findById($id);
		
		if($customer->getRetailer()->getId() == $user->getId()) {
			$userManager->deleteUser($customer);
		}
		
		return new JsonResponse(array(
			'success' => true
		));
	}

	/** 
	 * list for autocompletion
	 * @param Request $request
	 */
	public function autocompleteAction (Request $request)
	{
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && !$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		$user = $this->container->get('security.context')
			->getToken()
			->getUser();
		$customers = [];
		
		/** @var $customerManager \CSIDBundle\Doctrine\CustomerManager **/
		$customerManager = $this->container->get('csid.customer_manager');
		
		$term = $request->query->get('term');
		
		$result = $customerManager->findCustomers($user, 1, 10, $term);
		
		/** @var $customer \CSIDBundle\Entity\User **/
		foreach ($result['list'] as $customer) {
			$customers[] = array(
				'id' => $customer->getId(),
				'username' => $customer->getUsername(),
				'lastname' => $customer->getLastname(),
				'firstname' => $customer->getFirstname(),
				'email' => $customer->getEmail(),
				'value' => $customer->getLastname(),
				'adress' => $customer->getAddress(),
				'postal_code' => $customer->getPostalCode(),
				'city' => $customer->getCity(),
				'company' => $customer->getCompany()
			);
		}
		
		return new JsonResponse($customers);
	}

	/**
	 * edit customer
	 * @param integer $id        	
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editAction ($id)
	{
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && !$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $customerManager \CSIDBundle\Doctrine\CustomerManager **/
		$customerManager = $this->container->get('csid.customer_manager');
		$customerCurrent = $customerManager->findById($id);
		
		// CUSTOMER FORM
		$userCurrent = $this->container->get('security.context')
			->getToken()
			->getUser();
		
		if ($userCurrent instanceof UserInterface && 'POST' === $this->container->get('request')->getMethod()) {
			$this->container->get('session')
				->getFlashBag()
				->set('sonata_user_error', 'sonata_user_already_authenticated');
			$url = $this->container->get('router')->generate('sonata_user_profile_show');
			return new RedirectResponse($url);
		}
		
		$form = $this->container->get('csid_customer.edit.form');
		$formHandler = $this->container->get('csid_customer.edit.form.handler');
		
		$response = $formHandler->process($customerCurrent);
		
		if($response) {
			
		}
		
		return $this->container->get('templating')->renderResponse('CSIDBundle:Customers:add.html.twig', 
			array(
				'customer' => $customerCurrent,
				'form' => $form->createView()
			));
	}
	
	/**
	 * view customer
	 * @param unknown $id
	 * @throws AccessDeniedException
	 */
	public function viewAction ($id)
	{
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && !$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		$user = $this->container->get('security.context')
			->getToken()
			->getUser();
		if (! is_object($user)) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		/** @var $userManager \CSIDBundle\Doctrine\UserManager **/
		$userManager = $this->container->get('csid.user_manager');
		$customer = $userManager->findById($id);
		
		if ($customer->getRetailer()->getId() != $user->getId()) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		return $this->container->get('templating')->renderResponse('CSIDBundle:Customers:view.html.twig', 
			array(
				'customer' => $customer
			));
	}

	/**
	 * add customer
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addAction ()
	{
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && !$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		$userCurrent = $this->container->get('security.context')
			->getToken()
			->getUser();
		
		if ($userCurrent instanceof UserInterface && 'POST' === $this->container->get('request')->getMethod()) {
			$this->container->get('session')
				->getFlashBag()
				->set('sonata_user_error', 'sonata_user_already_authenticated');
			$url = $this->container->get('router')->generate('sonata_user_profile_show');
			return new RedirectResponse($url);
		}
		
		$form = $this->container->get('csid_user.askaccount.form');
		$formHandler = $this->container->get('csid_user.askaccount.form.handler');		
		$response = $formHandler->process(false, 'ROLE_CUSTOMER');
		
		if ($response != false) {
			$url = $this->container->get('router')->generate('csid_customers');
			return new RedirectResponse($url);
		} else {
			$this->container->get('session')->set('sonata_user_redirect_url', 
				$this->container->get('request')->headers->get('referer'));
			
			return $this->container->get('templating')->renderResponse(
				'CSIDBundle:Customers:add.html.twig', 
				array(
					'form' => $form->createView()
				));
		}
	}
	
	private function getErrorMessages (\Symfony\Component\Form\Form $form)
	{
		$errors = array();
	
		foreach ($form->getErrors() as $key => $error) {
			if ($form->isRoot()) {
				$errors['#'][] = $error->getMessage();
			} else {
				$errors[] = $error->getMessage();
			}
		}
		$translator = $this->container->get('translator');
	
		foreach ($form->all() as $child) {
			if (! $child->isValid()) {
				$errors[$child->getName()] = $this->getErrorMessages($child);
			}
		}
	
		return $errors;
	}
}
