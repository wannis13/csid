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
use CSIDBundle\Entity\Technical;
use CSIDBundle\Entity\Matter;
use CSIDBundle\Entity\MatterColor;
use CSIDBundle\Entity\Fixing;
use CSIDBundle\Entity\Dimension;

class DefaultController extends Controller
{
	function rgb2hex($rgb) {
		$hex = "#";
		$hex .= str_pad(dechex($rgb['r']), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb['g']), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb['b']), 2, "0", STR_PAD_LEFT);

		return $hex; // returns the hex value including the number sign (#)
	}
	
	/**
	 * generator
	 * @param Request $request
	 */
	public function indexAction (Request $request)
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('mobilier_incendie_choix_du_mode');
		}
		
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_CUSTOMER')) {
			$retailer = $retailer->getRetailer();
		}
		
		$form = $this->container->get('csid_basket.item.form');
		
		// get technicals
		$technicalManager = $this->getTechnicalManager();

		$technicals = [];
		foreach($technicalManager->findTechnicals() as $technical) {
			if($technical instanceof Technical) {
				$tab = array(
					'id' => $technical->getId(),
					'name' => $technical->getName(),
					'webPath' => $technical->getWebPath(),
					'matters' => [],
					'needBackPlate' => $technical->getBlackplate(),
					'type' => $technical->getType(),
					'canHaveHole' => $technical->getCanHaveHole()
				);
				foreach($technical->getMatters() as $matter) {
					
					if($matter instanceof Matter) {
						$matterTab = array(
							'id' => $matter->getId(),
							'name' => $matter->getName(),
							'webPath' => $this->get('liip_imagine.cache.manager')->getBrowserPath($matter->getWebPath(), 'thumb_item_owl'),
							'description' => twig_truncate_filter($this->get('twig'), strip_tags($matter->getDescription()), 100),
							'colors' => [],
							'fixings' => [],
							'dimensions' => [],
							'priceMatterPerM2'=>$matter->getPricePerM2(),
							'pricePerHole'=>$matter->getPricePerHole(),
							'pricePrintPerM2'=>$matter->getPrciePrintPerM2(),
							'priceRoundedCorners'=>$matter->getPricePerRoundedCorner(),
							'priceFixedFlagship'=>$matter->getPriceFixedFlagship(),
							'maxHeight' => $matter->getMaxHeight(),
							'maxWidth' => $matter->getMaxWidth()
						);
						
						foreach($matter->getColors() as $color) {
							if($color instanceof MatterColor) {
								$matterTab['colors'][] = array(
									'id' => $color->getId(),
									'name' => $color->getName(),
									'original' => $this->get('liip_imagine.cache.manager')->getBrowserPath($color->getWebPath(), 'thumb_item_owl'),
									'dark' => $this->get('liip_imagine.cache.manager')->getBrowserPath($color->getDarkImagePath(), 'thumb_item_owl'),
								);
							}
						}
						
						foreach($matter->getFixings() as $fixing) {
							if($fixing instanceof Fixing) {
								$matterTab['fixings'][] = array(
									'id' => $fixing->getId(),
									'name' => $fixing->getName(),
									'webPath' => $this->get('liip_imagine.cache.manager')->getBrowserPath($fixing->getWebPath(), 'thumb_item_owl'),
									'description' => twig_truncate_filter($this->get('twig'), strip_tags($fixing->getDescription()), 100),
									'price' => $fixing->getPrice()
								);
							}
						}
						
						foreach($matter->getDimensions() as $dimension) {
							if($dimension instanceof Dimension) {
								$matterTab['dimensions'][] = array(
									'id' => $dimension->getId(),
									'name' => $dimension->getName(),
									'width' => $dimension->getWidth(),
									'height' => $dimension->getHeight()
								);
							}
						}
						
						$tab['matters'][] = $matterTab;
					}
				}
				$technicals[] = $tab;
			}
		}
		
		// get matters
		$matterManager = $this->getMatterManager();
		$matters = $matterManager->findMatters();
		
		// get fixings
		$fixingManager = $this->getFixingManager();
		$fixings = $fixingManager->findFixing();
		
		return $this->render('CSIDBundle:Default:index.html.twig', 
			array(
				'form' => $form->createView(),
				'technicals' => $technicals,
				'matters' => $matters,
				'fixings' => $fixings,
				'retailer' => $retailer
			));
	}
	
	public function editAction($id)
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			$retailer = $retailer->getRetailer();
		}
		
		$orderManager = $this->getOrderManager();
		$product = $orderManager->getProductsById($retailer, $id);
		
		$form = $this->container->get('csid_basket.item.form');
		$formHandler = $this->container->get('csid_basket.item.form.handler');
		$formHandler->process($retailer, $product);
		
		// get technicals
		$technicalManager = $this->getTechnicalManager();
		
		$technicals = [];
		foreach($technicalManager->findTechnicals() as $technical) {
			if($technical instanceof Technical) {
				$tab = array(
					'id' => $technical->getId(),
					'name' => $technical->getName(),
					'webPath' => $technical->getWebPath(),
					'matters' => [],
					'needBackPlate' => $technical->getBlackplate(),
					'type' => $technical->getType(),
					'canHaveHole' => $technical->getCanHaveHole()
				);
				foreach($technical->getMatters() as $matter) {
						
					if($matter instanceof Matter) {
						$matterTab = array(
							'id' => $matter->getId(),
							'name' => $matter->getName(),
							'webPath' => $this->get('liip_imagine.cache.manager')->getBrowserPath($matter->getWebPath(), 'thumb_item_owl'),
							'description' => twig_truncate_filter($this->get('twig'), strip_tags($matter->getDescription()), 100),
							'colors' => [],
							'fixings' => [],
							'dimensions' => [],
							'priceMatterPerM2'=>$matter->getPricePerM2(),
							'pricePerHole'=>$matter->getPricePerHole(),
							'pricePrintPerM2'=>$matter->getPrciePrintPerM2(),
							'priceRoundedCorners'=>$matter->getPricePerRoundedCorner(),
							'priceFixedFlagship'=>$matter->getPriceFixedFlagship(),
							'maxHeight' => $matter->getMaxHeight(),
							'maxWidth' => $matter->getMaxWidth()
						);
		
						foreach($matter->getColors() as $color) {
							if($color instanceof MatterColor) {
								$matterTab['colors'][] = array(
									'id' => $color->getId(),
									'name' => $color->getName(),
									'original' => $this->get('liip_imagine.cache.manager')->getBrowserPath($color->getWebPath(), 'thumb_item_owl'),
									'dark' => $this->get('liip_imagine.cache.manager')->getBrowserPath($color->getDarkImagePath(), 'thumb_item_owl'),
								);
							}
						}
		
						foreach($matter->getFixings() as $fixing) {
							if($fixing instanceof Fixing) {
								$matterTab['fixings'][] = array(
									'id' => $fixing->getId(),
									'name' => $fixing->getName(),
									'webPath' => $this->get('liip_imagine.cache.manager')->getBrowserPath($fixing->getWebPath(), 'thumb_item_owl'),
									'description' => twig_truncate_filter($this->get('twig'), strip_tags($fixing->getDescription()), 100),
									'price' => $fixing->getPrice()
								);
							}
						}
		
						foreach($matter->getDimensions() as $dimension) {
							if($dimension instanceof Dimension) {
								$matterTab['dimensions'][] = array(
									'id' => $dimension->getId(),
									'name' => $dimension->getName(),
									'width' => $dimension->getWidth(),
									'height' => $dimension->getHeight()
								);
							}
						}
		
						$tab['matters'][] = $matterTab;
					}
				}
				$technicals[] = $tab;
			}
		}
		
		// get matters
		$matterManager = $this->getMatterManager();
		$matters = $matterManager->findMatters();
		
		// get fixings
		$fixingManager = $this->getFixingManager();
		$fixings = $fixingManager->findFixing();
		
		return $this->render('CSIDBundle:Default:index.html.twig',
			array(
				'form' => $form->createView(),
				'technicals' => $technicals,
				'matters' => $matters,
				'fixings' => $fixings,
				'retailer' => $retailer,
				'product' => $product
			));
	}

	/**
	 *
	 * @return \CSIDBundle\Doctrine\TechnicalManager
	 */
	public function getTechnicalManager ()
	{
		return $this->container->get('csid.technichal_manager');
	}
	
	/**
	 * @return \CSIDBundle\Doctrine\MatterManager
	 */
	public function getMatterManager()
	{
		return $this->container->get('csid.matter_manager');
	}
	
	/**
	 * @return \CSIDBundle\Doctrine\FixingManager
	 */
	public function getFixingManager()
	{
		return $this->container->get('csid.fixing_manager');
	}
	
	/**
	 *
	 * @return \CSIDBundle\Doctrine\OrderManager
	 */
	public function getOrderManager ()
	{
		return $this->container->get('csid.order_manager');
	}
}
