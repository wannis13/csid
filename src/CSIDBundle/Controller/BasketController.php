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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class BasketController extends Controller
{
	/**
	 * add item to basket
	 * @param Request $request
	 * @throws AccessDeniedException
	 */
	public function addAction (Request $request)
	{
		$user = $this->container->get('security.context')
			->getToken()
			->getUser();
		
		if (! is_object($user) || ! $user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		$form = $this->container->get('csid_basket.item.form');
		$formHandler = $this->container->get('csid_basket.item.form.handler');
		
		$process = $formHandler->process($user);
		
		if ($process) {} else {
			
			$errors = $this->get('form_serializer')->serializeFormErrors($form, true, true);
			return new JsonResponse($errors);
			
			return $this->render('CSIDBundle:Basket:error.html.twig', array(
				'form' => $form->createView()
			));
		}
		
		return new JsonResponse(array(
			'success' => true,
			'redirection' => '/basket'
		));
	}
	
	/**
	 * Calculate a product price
	 * @param Request $request
	 * @throws AccessDeniedException
	 */
	public function calculateItemAction(Request $request)
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $retailer \CSIDBundle\Entity\User **/
		$retailer = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			$retailer = $retailer->getRetailer();
		}
		
		$formHandler = $this->container->get('csid_basket.calculate_price_item.form.handler');
		
		$price = $formHandler->process();
		$priceWithMargin = $price * (1+($retailer->getMarginPercent()/100));
		
		return new JsonResponse(array(
			'price' => $priceWithMargin
		));
	}
	
	/**
	 * empty basket
	 * @param Request $request
	 */
	public function emptyAction(Request $request)
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		$user = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		$orderManager = $this->getBasketManager();
		$orderManager->emptyBasket($user);
		
		return new JsonResponse(array(
			'success' => true
		));
	}

	/**
	 * delete an item
	 * @param string $id
	 */
	public function deleteItemAction ($id)
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
		
		$orderManager = $this->getBasketManager();
		$order = $orderManager->deleteItemByItem($id, $retailer);
		
		if($order) {
			return new JsonResponse(array(
				'success' => true,
				'amount' => $order->getAmountWithMargin(),
				'amountVAT' => $order->getAmountVATWithMargin()
			));
		}
		
		return new JsonResponse(array(
			'success' => false
		));
	}
	
	/**
	 * delete an increase or decrease
	 * @param string $id
	 */
	public function deleteIncreaseDecreaseAction($id)
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
		
		$orderManager = $this->getBasketManager();
		$order = $orderManager->deleteIncreaseOrDecreaseById($id, $retailer);
		
		if($order) {
			return new JsonResponse(array(
				'success' => true,
				'amount' => $order->getAmountWithMargin(),
				'amountVAT' => $order->getAmountVATWithMargin()
			));
		}
		
		return new JsonResponse(array(
			'success' => false
		));
	}

	/**
	 * view an item 
	 * @param string $id
	 */
	public function showItemAction ($id)
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}

		$user = $this->container->get('security.context')
			->getToken()
			->getUser();
		
		$orderManager = $this->getBasketManager();
		$result = $orderManager->getItemById($id, $user);
		return $this->render('CSIDBundle:Basket:show.html.twig', array(
			'order' => $result
		));
	}

	/**
	 * list items
	 * @param Request $request
	 */
	public function indexAction (Request $request)
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $user = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			$retailer = $retailer->getRetailer();
		}
		
		$orderManager = $this->getBasketManager();
		$order = $orderManager->getOrderByUser($user);
		
		$form = $this->container->get('csid_basket.increase_decrease.form');
		$formHandler = $this->container->get('csid_basket.increase_decrease.form.handler');
		
		$formQty = $this->container->get('csid_basket.update_qty.form');
		
		$process = $formHandler->process($order, $retailer);
		
		if ($process) {
			return $this->redirectToRoute('csid_basket');
		}
		
		return $this->render('CSIDBundle:Basket:index.html.twig', array(
			'order' => $order,
			'form' => $form->createView(),
			'formQty' => $formQty->createView(),
		));
	}
	
	public function updateQtyAction(Request $request)
	{
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $user = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			$retailer = $retailer->getRetailer();
		}
		
		$formHandler = $this->container->get('csid_basket.update_qty.form.handler');
		
		$formHandler->process($user);
		
		return $this->redirectToRoute('csid_basket');
	}
	
	public function updateQtyFormAction($id, $qty)
	{
		$formQty = $this->container->get('csid_basket.update_qty.form');
		
		return $this->render('CSIDBundle:Basket:update-qty-form.html.twig', array(
			'formQty' => $formQty->createView(),
			'qty' => $qty,
			'id' => $id
		));
	}
	
	public function addProductAction($id)
	{
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $user = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			$retailer = $retailer->getRetailer();
		}
		
		$basketManager = $this->getBasketManager();
		$basketManager->addProduct($user, $retailer, $id);
		
		return $this->redirectToRoute('csid_basket');
	}
	
	/**
	 * valid basket 
	 * @param Request $request
	 */
	public function validAction(Request $request)
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
		
		$form = $this->container->get('csid_basket.confirm.form');
		$formHandler = $this->container->get('csid_basket.confirm.form.handler');
		
		$currentUser = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		$process = $formHandler->process($currentUser, $retailer);
		
		if ($process) {
			return $this->redirectToRoute('csid_orders');
		}
		
		return $this->render('CSIDBundle:Basket:valid.html.twig', array(
			'form' => $form->createView(),
			'currentUser' => $currentUser,
			'retailer' => $retailer
		));
	}

	/**
	 *
	 * @return \CSIDBundle\Doctrine\BasketManager
	 */
	public function getBasketManager ()
	{
		return $this->container->get('basket_manager');
	}
}
