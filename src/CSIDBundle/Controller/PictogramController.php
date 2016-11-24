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

class PictogramController extends Controller
{
	/**
	 * list pictograms
	 * @param Request $request
	 */
    public function listAction(Request $request)
    {
    	$pictogramCategoryManager = $this->getPictogramCategoryManager();
    	$categories = $pictogramCategoryManager->findPictogramCategories();
        return $this->render('CSIDBundle:Pictogram:list.html.twig', array('categories' => $categories));
    }
    
    /**
     * @return \CSIDBundle\Doctrine\PictogramManager
     */
    public function getPictogramManager()
    {
    	return $this->container->get('csid.pictogram_manager');
    }
    
    /**
     * @return \CSIDBundle\Doctrine\PictogramCategoryManager
     */
    public function getPictogramCategoryManager()
    {
    	return $this->container->get('csid.pictogram_category_manager');
    }
}
