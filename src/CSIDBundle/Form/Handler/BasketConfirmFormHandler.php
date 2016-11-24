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

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use CSIDBundle\Doctrine\BasketManager;
use CSIDBundle\Doctrine\CustomerManager;

/**
 * 
 * @author Barbara
 *
 */
class BasketConfirmFormHandler
{
    protected $request;
    protected $basketManager;
    protected $customerManager;
    protected $form;

    /**
     * 
     * @param FormInterface $form
     * @param Request $request
     * @param BasketManager $basketManager
     * @param CustomerManager $customerManager
     */
    public function __construct(FormInterface $form, Request $request, BasketManager $basketManager, CustomerManager $customerManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->basketManager = $basketManager;
        $this->customerManager = $customerManager;
    }
	
    /**
     * 
     * @param \CSIDBundle\Entity\User $currentUser
     * @param \CSIDBundle\Entity\User $retailer
     * @return boolean
     */
    public function process($currentUser, $retailer)
    {
    	if ('POST' === $this->request->getMethod()) {
    		$this->form->bind($this->request);
    		
    		if ($this->form->isValid()) {
    			$this->onSuccess($currentUser, $retailer);
    			return true;
    		}
    	} else {
    		if($currentUser->getRetailer() != null) {
    			$this->form->setData(array(
    				'customer_id' => $currentUser->getId(),
    				'email' => $currentUser->getEmail(),
    				'lastname' => $currentUser->getLastname(),
    				'firstname' => $currentUser->getFirstname(),
    				'adress' => $currentUser->getAddress(),
    				'postal_code' => $currentUser->getPostalCode(),
    				'city' => $currentUser->getCity(),
    				'company' => $currentUser->getCompany()
    			));
    		}
    		
    	}

        return false;
    }
	
    /**
     * 
     * @param \CSIDBundle\Entity\User $user
     * @param \CSIDBundle\Entity\User $retailer
     */
    protected function onSuccess($currentUser, $retailer)
    {
    	$customer = $this->customerManager->updateCustomer($currentUser, $retailer, $this->form->getData());
    	
    	$this->basketManager->confirm($this->form->getData(), $currentUser, $customer);
    }
}
