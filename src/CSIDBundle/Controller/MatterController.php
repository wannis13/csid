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

class MatterController extends Controller
{
	/**
	 * list matters 
	 * @param Request $request
	 */
    public function listAction(Request $request)
    {
    	$matterManager = $this->getMatterManager();
    	$matters = $matterManager->findMatters();
    	
        return $this->render('CSIDBundle:Matter:list.html.twig', array('matters' => $matters));
    }
    
    /**
     * @return \CSIDBundle\Doctrine\MatterManager
     */
    public function getMatterManager()
    {
    	return $this->container->get('matter_manager');
    }
}
