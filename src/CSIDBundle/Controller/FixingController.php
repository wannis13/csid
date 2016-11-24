<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CSIDBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FixingController extends Controller
{
	/**
	 * list fixings
	 * @param Request $request
	 */
    public function listAction(Request $request)
    {
    	$fixingManager = $this->getFixingManager();
    	$fixings = $fixingManager->findFixing();
    	
        return $this->render('CSIDBundle:Fixing:list.html.twig', array('fixings' => $fixings));
    }
    
    /**
     * @return \CSIDBundle\Doctrine\FixingManager
     */
    public function getFixingManager()
    {
    	return $this->container->get('fixing_manager');
    }
}
