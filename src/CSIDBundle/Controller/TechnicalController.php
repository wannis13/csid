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

class TechnicalController extends Controller
{
	/**
	 * list technicals
	 * @param Request $request
	 */
    public function listAction(Request $request)
    {
    	$technicalManager = $this->getTechnicalManager();
    	$technicals = $technicalManager->findTechnicals();
    	
        return $this->render('CSIDBundle:Technical:list.html.twig', array('technicals' => $technicals));
    }
    
    /**
     * @return \CSIDBundle\Doctrine\TechnicalManager
     */
    public function getTechnicalManager()
    {
    	return $this->container->get('technichal_manager');
    }
}
