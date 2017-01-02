<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MobilierIncendieBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use MobilierIncendieBundle\Entity\OrderDetail;
use MobilierIncendieBundle\Entity\OrderMobilierIncendie;
use MobilierIncendieBundle\Form\OrderDetailType;
use Symfony\Component\HttpFoundation\Response;


class PanierController extends Controller
{

	/**
	 * list produits
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


		$order = $this->getOrderByUser($user);

		/*$form = $this->container->get('csid_basket.increase_decrease.form');
		$formHandler = $this->container->get('csid_basket.increase_decrease.form.handler');

		$formQty = $this->container->get('csid_basket.update_qty.form');

		$process = $formHandler->process($order, $retailer);

		if ($process) {
			return $this->redirectToRoute('csid_basket');
		}*/

		return $this->render('MobilierIncendieBundle:panier:index.html.twig', array(
			'order' => $order,
			//'form' => $form->createView(),
			//'formQty' => $formQty->createView(),
		));
	}

	/**
	 * add item to basket
	 * @param Request $request
	 * @param $id
	 * @throws AccessDeniedException
	 */
	public function addAction(Request $request, $id)
	{
		/** @var $retailer \La2UserBundle\Entity\User * */
		 $user = $this->container->get('security.context')
			->getToken()
			->getUser();

		if (!$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			$retailer = $user->getRetailer();
		}
		$ligne_order = new OrderDetail();
		$ligne_order_form = new OrderDetailType();

		$form_order = $this->createForm($ligne_order_form, $ligne_order);

		$form = $form_order->handleRequest($request);
		if ($form->isValid()) {
			$order = $this->getOrderByUser( $user, "creation");
			$em = $this->getDoctrine()->getManager();
			$repoProduit = $em->getRepository('MobilierIncendieBundle:Produits');
			$prod = $repoProduit->find($id);

			$ligne_order->setProduit($prod);
			$ligne_order = $form->getData();
			$option=$ligne_order->getOption();
			$color=$ligne_order->getColor();
			$version=$ligne_order->getVersion();


			$repoLigneProduit = $em->getRepository('MobilierIncendieBundle:OrderDetail');
			$ligneOrder=$repoLigneProduit->findOneBy(array('produit'=>$prod ,'color'=>$color ,'version'=>$version ,'option'=>$option ,'order'=>$order));

			if($ligneOrder!=null){
				$ligneOrder->setQty($ligneOrder->getQty()+1);
				$em->persist($ligneOrder);
			}
			else{
				$em->persist($ligne_order);
				$ligne_order->setOrder($order);
			}
			$em->flush();
		/*	$this->addFlash(
				'notice',
				'Produit ajouté au panier avec succès'
			);*/
			return $this->redirectToRoute('index_panier');

		}

	}
	public function getOrderByUser($user, $status = 'creation')
	{
		$em=$this->getDoctrine()->getManager();
		$repo=$em->getRepository('MobilierIncendieBundle:OrderMobilierIncendie');
		$order = $repo->findOneBy(array(
			'createdBy' => $user->getId(),
			'status' => $status
		));

		if(!($order instanceof OrderMobilierIncendie)) {
			$order=new OrderMobilierIncendie();
			$order->setCreatedBy($user);
			$order->setStatus('creation');

		 $retailer = $user;

		if($user->getRetailer() != null) {
				$retailer = $user->getRetailer();
			}

			 $order->setRetailer($retailer);

			$em->persist($order);
			$em->flush();

		}
		return $order;
	}

	public function getPrixDegressifAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$id_prod = $request->request->get('id');
		$quantite = $request->request->get('qte');
		$res = 0;
		if ($request->isMethod('POST')) {
			$repotarif = $em->getRepository('MobilierIncendieBundle:Reductions');
			$single = $repotarif->getPrixDegressifProduit($id_prod, $quantite);

			if ($single != null) {
				$prod = $single[0];
				$prix = $prod->getPrixUnitaire() - $prod->getReduction();
				$res = array('prix' => $prix);
			} else {
				$repo = $em->getRepository('MobilierIncendieBundle:Produits');
				$prix = $repo->find($id_prod)->getPrix();
				$res = array('prix' => $prix);
			}

		}

		$response = new  JsonResponse($res);

		return $response;
	}

	public function getQuantiteMinTarifDegressifProduitAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$res = 0;
		$repotarif = $em->getRepository('MobilierIncendieBundle:Reductions');
		$single = $repotarif->findOneBy(array('produits'=>$id) ,array('quantite_min' =>'ASC'));
		if($single!=null){
			$res=$single->getQuantiteMin();
			 
		}
		$response = new   Response($res);
		return $response;
	}
	public function getQuantiteMinTarifDegressifOptionAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$res = 0;
		$repotarif = $em->getRepository('MobilierIncendieBundle:Reductions');
		$single = $repotarif->findOneBy(array('options'=>$id) ,array('quantite_min' =>'ASC'));
		if($single!=null){
			$res=$single->getQuantiteMin();

		}
		$response = new   Response($res);
		return $response;
	}
	public function getQuantiteMinTarifDegressifColorAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$res = 0;
		$repotarif = $em->getRepository('MobilierIncendieBundle:Reductions');
		$single = $repotarif->findOneBy(array('coloris'=>$id) ,array('quantite_min' =>'ASC'));
		if($single!=null){
			$res=$single->getQuantiteMin();

		}
		$response = new   Response($res);
		return $response;
	}

	public function getPrixDegressifOptionAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$id_prod = $request->request->get('id');
		$quantite = $request->request->get('qte');

		$res = 0;
		if ($request->isMethod('POST')) {
			$repotarif = $em->getRepository('MobilierIncendieBundle:Reductions');
			$single = $repotarif->getPrixDegressifOption($id_prod, $quantite);

			if ($single != null) {
				$prod = $single[0];
				$prix = $prod->getPrixUnitaire() - $prod->getReduction();
				$res = array('prix' => $prix);
			} else {
				$repo = $em->getRepository('MobilierIncendieBundle:Options');
				$prix = $repo->find($id_prod)->getPrix();
				$res = array('prix' => $prix);
			}

		}

		$response = new  JsonResponse($res);

		return $response;
	}
	public function getPrixDegressifColorAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$id_prod = $request->request->get('id');
		$quantite = $request->request->get('qte');

		$res = 0;
		if ($request->isMethod('POST')) {
			$repotarif = $em->getRepository('MobilierIncendieBundle:Reductions');
			$single = $repotarif->getPrixDegressifColor($id_prod, $quantite);

			if ($single != null) {
				$prod = $single[0];
				$prix = $prod->getPrixUnitaire() - $prod->getReduction();
				$res = array('prix' => $prix);
			} else {
				$repo = $em->getRepository('MobilierIncendieBundle:ProduitColor');
				$prix = $repo->find($id_prod)->getPrix();
				$res = array('prix' => $prix);
			}
		}
		$response = new  JsonResponse($res);
		return $response;
	}

	public function UpdateQtyProductAction(Request $request)
	{

		$em = $this->getDoctrine()->getManager();

		if ($request->isMethod('POST')) {
			$id_prod = $request->request->get('id');
			$quantite = $request->request->get('qte');
			$prix = $request->request->get('prix');
			$repoLigneProduit = $em->getRepository('MobilierIncendieBundle:OrderDetail');
			$ligneOrder = $repoLigneProduit->find($id_prod);

			if ($ligneOrder != null) {
				$ligneOrder->setQty($quantite);
				$ligneOrder->setAmount($prix);
				$ligneOrder->setAmountVAT($prix * (1.2));

				$em->persist($ligneOrder);
				$em->flush();
				$res='ok';

			}else{
				$res='Nook';
			}


		}else{
			$res='Nook';
		}
		$response = new Response($res);
		return $response;
	}
	public function UpdatePrixPanierAction(Request $request)
	{

		$em = $this->getDoctrine()->getManager();

		if ($request->isMethod('POST')) {
			$id_prod = $request->request->get('id');
			$prix_ttc = $request->request->get('prix_ttc');
			$prix_ht = $request->request->get('prix_ht');
			$repoLigneProduit = $em->getRepository('MobilierIncendieBundle:OrderMobilierIncendie');
			$ligneOrder = $repoLigneProduit->find($id_prod);

			if ($ligneOrder != null) {
				//$ligneOrder->setQty($quantite);
				$ligneOrder->setAmount($prix_ht);
				$ligneOrder->setAmountVAT($prix_ttc);

				$em->persist($ligneOrder);
				$em->flush();
				$res='ok';

			}else{
				$res='Nook';
			}


		}else{
			$res='Nook';
		}
		$response = new Response($res);
		return $response;
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

		
		/*$basketManager = $this->getBasketManager();
		$basketManager->addProduct($user, $retailer, $id);*/
		
		return new Reponse('ok');
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
