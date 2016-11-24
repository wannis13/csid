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
use CSIDBundle\Doctrine\OrderManager;
use CSIDBundle\Entity\Order;

class OrderValidFormHandler
{
    protected $request;
    protected $orderManager;
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
    			$this->onSuccess($order);
    			return true;
    		}
    	}

        return false;
    }
	
    /**
     * 
     * @param Order $order
     */
    protected function onSuccess(Order $order)
    {
    	$this->orderManager->confirmOrder($order, $this->form->getData());
    }
}
