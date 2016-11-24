<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CSIDBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\Container;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use La2UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class RegistrationFormHandler 
{
     
	protected $container;
	
	/**
	 * 
	 * @param FormInterface $form
	 * @param Request $request
	 * @param UserManagerInterface $userManager
	 * @param MailerInterface $mailer
	 * @param TokenGeneratorInterface $tokenGenerator
	 * @param Container $container
	 */
	public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, Container $container)
	{
		$this->form = $form;
		$this->request = $request;
		$this->userManager = $userManager;
		$this->mailer = $mailer;
		$this->tokenGenerator = $tokenGenerator;
		$this->container = $container;
	}

	/**
	 * 
	 * @param string $confirmation
	 * @param string $role
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|boolean
	 */
    public function process($confirmation = false, $role = 'ROLE_RESELLER')
    {
        $user = $this->createUser();
        $this->form->setData($user);
        $authUser = false;
        
        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);
            
            if ($this->form->isValid()) {
                $this->onSuccess($user, $confirmation);
                if($role == 'ROLE_RESELLER'){
                	$user = $this->form->getData();
                	$authUser = false;
                	$this->container->get('session')->set('fos_user_send_confirmation_email/email', $this->container->getParameter('admin_email'));
                	$route = 'fos_user_registration_check_email';
                	$userManager = $this->container->get('csid.user_manager');
                	$userManager->addRole($user, array('ROLE_RESELLER'));
                	$this->setFlash('fos_user_success', 'Votre demande a bien été envoyé ! ');
                } else {
                	$authUser = true;
                	$route = $this->container->get('session')->get('sonata_basket_delivery_redirect', 'sonata_user_profile_show');
                	$this->container->get('session')->remove('sonata_basket_delivery_redirect');
                	$customer = $this->form->getData();
                	$userCurrent = $this->container->get('security.context')->getToken()->getUser();
                	$customer->setRetailer($userCurrent);
                	$customerManager = $this->container->get('csid.customer_manager');
                	$customerManager->addRole($customer, array('ROLE_CUSTOMER'));
                }
                
                $url = $this->container->get('session')->get('sonata_user_redirect_url');
                if (null === $url || "" === $url) {
                	$url = $this->container->get('router')->generate($route);
                }
                $response = new RedirectResponse($url);
                if ($authUser) {
                	$this->authenticateUser($userCurrent, $response);
                }
                return $response;
            }
        }

        return false;
    }
    
    /**
     * 
     * @param UserInterface $user
     * @param unknown $confirmation
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
    	if ($confirmation) {
    		$user->setEnabled(false);
    		if (null === $user->getConfirmationToken()) {
    			$user->setConfirmationToken($this->tokenGenerator->generateToken());
    		}
    
    		$this->mailer->sendConfirmationEmailMessage($user);
    	} else {
    		$user->setEnabled(true);
    	}
    
    	$this->userManager->updateUser($user);
    }
    
    /**
     * @return UserInterface
     */
    protected function createUser()
    {
    	return $this->userManager->createUser();
    }
    
    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
    	$this->container->get('session')->getFlashBag()->set($action, $value);
    }
    
    /**
     * Authenticate a user with Symfony Security
     *
     * @param \FOS\UserBundle\Model\UserInterface        $user
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    protected function authenticateUser(UserInterface $user, Response $response)
    {
    	try {
    		$this->container->get('fos_user.security.login_manager')->loginUser(
    			$this->container->getParameter('fos_user.firewall_name'),
    			$user,
    			$response);
    	} catch (AccountStatusException $ex) {
    		// We simply do not authenticate users which do not pass the user
    		// checker (not enabled, expired, etc.).
    	}
    }

}
