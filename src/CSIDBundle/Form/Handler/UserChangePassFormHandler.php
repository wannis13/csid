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
use FOS\UserBundle\Form\Model\ChangePassword;

use Symfony\Component\DependencyInjection\Container;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use La2UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;


class UserChangePassFormHandler
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
     */
    public function getNewPassword()
    {
        return $this->form->getData()->new;
    }

    /**
     * 
     * @param UserInterface $user
     * @return boolean
     */
    public function process(UserInterface $user)
    {
        $this->form->setData(new ChangePassword());

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user);

                return true;
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
        $user->setPlainPassword($this->getNewPassword());
        $this->setFlash('fos_user_success', 'Mot de passe mis à jour');
        $this->userManager->updateUser($user);
    }

    /**
     * 
     * @param string $confirmation
     * @param UserInterface $customer
     * @param unknown $encoder
     * @return boolean
     */
    public function process2($confirmation = false, UserInterface $customer, $encoder)
    {
        $this->form->setData($customer);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            $encoder = $encoder->getEncoder($customer);

            $passCheck = ($encoder->isPasswordValid($customer->getPassword(), $this->form->getData('password'), $customer->getSalt()));

            if($passCheck) {
                $this->setFlash('fos_user_success', 'Mot de passe valide');
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param UserInterface $user
     */
    protected function updatePass(UserInterface $user)
    {
        $this->userManager->updateUser($user);
    }

    /**
     * @return UserInterface
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

}
