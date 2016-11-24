<?php

namespace CSIDBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use CSIDBundle\Doctrine\OrderManager;
use CSIDBundle\Entity\Order;

/**
 * 
 * @author Barbara
 *
 */
class OrderSendFormHandler
{
	/**
	 * 
	 * @var Request
	 */
    protected $request;
    /**
     * 
     * @var OrderManager
     */
    protected $orderManager;
    
    /**
     * 
     * @var FormInterface
     */
    protected $form;

    /**
     * 
     * @param FormInterface $form
     * @param Request $request
     * @param OrderManager $orderManager
     */
    public function __construct(FormInterface $form, Request $request, OrderManager $orderManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->orderManager = $orderManager;
    }

    /**
     * 
     * @param Order $order
     * @return boolean
     */
    public function process(Order $order)
    {
    	if ('POST' === $this->request->getMethod()) {
    		$this->form->bind($this->request);
    		
    		if ($this->form->isValid()) {
    			return $this->onSuccess($order);
    		}
    	} else {
    		
    		$this->form->setData(array(
    			'recipients' => array(
    				0 => $order->getCustomer()->getEmail()
    			)
    		));
    	}

        return false;
    }
	
    /**
     * 
     * @param Order $order
     */
    protected function onSuccess($order)
    {
    	return $this->orderManager->send($order, $this->form->getData());
    }
}
