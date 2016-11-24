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

class CustomerFormHandler 
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
	 * @param UserInterface $customer
	 */
    public function process(UserInterface $customer)
    {
    	$this->form->setData($customer);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);
            if ($this->form->isValid()) {
                $this->onSuccess($customer);
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @param UserInterface $user
     */
    protected function onSuccess(UserInterface $user)
    {
    	$user->setEnabled(true);
    	$this->userManager->updateUser($user);
    }
    
    /**
     * 
     * @param UserInterface $customer
     */
    protected function updateUser(UserInterface $customer)
    {
    	$this->userManager->updateUser($customer);
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
