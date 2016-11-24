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

use Sonata\UserBundle\Controller\RegistrationFOSUser1Controller AS SonotaRegisterBaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationController extends SonotaRegisterBaseController
{
	/**
	 * 
	 * 
	 * {@inheritDoc}
	 * @see \Sonata\UserBundle\Controller\RegistrationController::registerAction()
	 */
	public function registerAction()
	{		
		//RESELLER
		$userCurrent = $this->container->get('security.context')->getToken()->getUser();
		if ($userCurrent instanceof UserInterface && 'POST' === $this->container->get('request')->getMethod()) {
			$this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
			$url = $this->container->get('router')->generate('sonata_user_profile_show');
			return new RedirectResponse($url);
		}
		$form = $this->container->get('csid_user.askaccount.form');
		$formHandler = $this->container->get('csid_user.askaccount.form.handler');
		$response = $formHandler->process(true, 'ROLE_RESELLER');
		
		if($response != false)
			return $response;
		else{
			$this->container->get('session')->set('sonata_user_redirect_url', $this->container->get('request')->headers->get('referer'));
		 	return $this->container->get('templating')->renderResponse('SonataUserBundle:Registration:register.html.'.$this->getEngine(), array(
	            'form' => $form->createView()
	        ));
		}
	}
	

	

}