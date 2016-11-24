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
use CSIDBundle\Doctrine\BasketManager;
use CSIDBundle\Doctrine\CustomerManager;

class BasketIncreaseDecreaseFormHandler
{
    protected $request;
    protected $basketManager;
    protected $form;

    /**
     * 
     * @param FormInterface $form
     * @param Request $request
     * @param BasketManager $basketManager
     * @param CustomerManager $customerManager
     */
    public function __construct(FormInterface $form, Request $request, BasketManager $basketManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->basketManager = $basketManager;
    }
	
    /**
     * 
     * @param \CSIDBundle\Entity\Order $order
     * @param \CSIDBundle\Entity\User $retailer
     * @return boolean
     */
    public function process($order, $retailer)
    {
    	if ('POST' === $this->request->getMethod()) {
    		$this->form->bind($this->request);
    		
    		if ($this->form->isValid()) {
    			return $this->onSuccess($order, $retailer);
    		} else {
    			
    		}
    	}

        return false;
    }
	
    /**
     * 
     * @param \CSIDBundle\Entity\Order $order
     * @param \CSIDBundle\Entity\User $retailer
     */
    protected function onSuccess($order, $retailer)
    {
    	return $this->basketManager->addIncreaseDecrease($this->form->getData(), $order, $retailer);
    }
}
