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

class BasketCalculateItemPriceFormHandler
{
    protected $request;
    protected $basketManager;
    protected $form;
    protected $mailer;
    protected $tokenGenerator;

    /**
     * 
     * @param FormInterface $form
     * @param Request $request
     * @param BasketManager $basketManager
     */
    public function __construct(FormInterface $form, Request $request, BasketManager $basketManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->basketManager = $basketManager;
    }

    /**
     * 
     * @return boolean
     */
    public function process()
    {
    	if ('POST' === $this->request->getMethod()) {
    		
    		$this->form->bind($this->request);
    		
    		if ($this->form->isValid()) {
    			return $this->onSuccess();
    		} 
    	}

        return false;
    }
	
    /**
     * 
     */
    protected function onSuccess()
    {
    	return $this->basketManager->calculateItmPrice($this->form->getData());
    }
}
