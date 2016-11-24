<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CSIDBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class UserController extends Controller
{
	public function indexAction()
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		return $this->render('CSIDBundle:User:index.html.twig', array(
		));
	}
	
	/**
	 * edit account
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function editAction()
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $currentUser \CSIDBundle\Entity\User **/
		$currentUser = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if($currentUser->getRetailer() == null) {
			$form = $this->container->get('csid_user.edit.form');
			$formHandler = $this->container->get('csid_user.edit.form.handler');
			$template = 'CSIDBundle:User:edit.html.twig';
		} else {
			$form = $this->container->get('csid_customer.edit.form');
			$formHandler = $this->container->get('csid_customer.edit.form.handler');
			$template = 'CSIDBundle:Customers:edit.html.twig';
		}
		
		$response = $formHandler->process($currentUser);

		if($response != false)
			return $this->redirectToRoute('csid_user_edit');
		else{
			return $this->container->get('templating')->renderResponse($template, 
				array('user' => $currentUser, 'form' => $form->createView()
			));
		}
	}
	
	/**
	 * edit password
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function editPassAction()
	{
		if (! $userCurrent = $this->container->get('security.token_storage')->getToken()->getUser()) {
			throw $this->createAccessDeniedException();
		}

		$form = $this->container->get('csid_user.changepass.form');
		$formHandler = $this->container->get('csid_user.changepass.form.handler');

		$response = $formHandler->process($userCurrent, $this->get('security.encoder_factory'));

		if($response != false)
			return $this->redirectToRoute('csid_user_edit_pass');
		else{
			return $this->container->get('templating')->renderResponse('CSIDBundle:User:editPass.html.twig', array('customerCurrent' => $userCurrent,'form' => $form->createView()
			));

		}
	}

}