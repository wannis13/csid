<?php

namespace AppBundle\Security;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;

class OAuthUserProvider implements OAuthAwareUserProviderInterface
{
	/**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var array
     */
    protected $properties = array(
        'identifier' => 'id',
    );

    /**
     * @var PropertyAccessor
     */
    protected $accessor;
    
	public function __construct(UserManagerInterface $userManager)
	{
		$this->userManager = $userManager;
		$this->accessor    = PropertyAccess::createPropertyAccessor();
	}
	
	public function loadUserByOAuthUserResponse(UserResponseInterface $response)
	{
		$facebookId = $response->getUsername();
		$username = $response->getRealName();
		
		$user = $this->userManager->findUserBy(array('facebookId' => $facebookId));
		
		if (null === $user || null === $username) {
			$email = $response->getEmail();
			
			$user = $this->userManager->findUserBy(array('email' => $email));
			
			if(!$user) {
				$user = $this->userManager->createUser();
				$user->setEmail($email);
				$user->setUsername($username);
				$user->setPassword("");
				$user->setEnabled(true);
			}
			
			$user->setFacebookId($facebookId);
			
			
			$this->userManager->updateUser($user);
			
		}
		
		return $user;
	}

	// other methods
}