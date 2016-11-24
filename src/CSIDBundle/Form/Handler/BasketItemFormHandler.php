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
use CSIDBundle\Entity\Product;

class BasketItemFormHandler
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
     * @param UserInterface $user
     * @param Product
     * @return boolean
     */
    public function process(UserInterface $user, $product = null)
    {
    	if ('POST' === $this->request->getMethod()) {
    		$this->form->bind($this->request);
    		
    		if ($this->form->isValid()) {
    			$this->onSuccess($user);
    			return true;
    		}
    		
    	} else {
    		
    		if($product != null) {
    			$this->form->setData($product);
    		}
    	}

        return false;
    }
	
    /**
     * 
     * @param UserInterface $user
     */
    protected function onSuccess($user)
    {
    	$this->basketManager->addItem($this->form->getData(), $user);
    }
}
