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
use CSIDBundle\Entity\Order;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
	/**
	 * list orderes
	 * @param Request $request
	 */
	public function indexAction(Request $request)
	{
		if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $retailer \CSIDBundle\Entity\User **/
		/** @var $currentUser \CSIDBundle\Entity\User **/
		$retailer = $currentUser = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			$retailer = $retailer->getRetailer();
		}
		
		if($request->isXmlHttpRequest()) {
			
			$page = $request->query->get('page');
			$maxPerPage = $request->query->get('rows');
			
			$customerId = $request->query->get('customer_id');
			
			if($currentUser->getRetailer() != null) {
				$customerId = $currentUser->getId();
			}
			
			$orderManager = $this->getOrderManager();
			$orders = [];
			
			$result = $orderManager->findOrders($retailer, $page, $maxPerPage, $customerId);
			
			/** @var $order Order **/
			foreach($result['list'] as $order) {
				$orders[] = array(
					'number' => $order->getRetailer()->getCommandNo() . $order->getNumber(),
					'id' => $order->getId(),
					'amount' => $order->getAmountWithMargin(),
					'margin' => ($order->getAmountWithMargin()) - ($order->getAmount()),
					'items' => count($order->getItems()),
					'status' => $this->get('translator')->trans($order->getStatus(), array(), 'csid'),
					'customer' => $order->getCustomer()->getLastname() . ' ' . $order->getCustomer()->getFirstname(),
					'date' => $order->getCreated()->format('d/m/Y H:i'),
					'customer_id' => $order->getCustomer()->getId()
				);
			}
			
			$data = array(
				'page' => 1,
				'records' => $maxPerPage,
				'rows' => $orders,
				'total' => $result['count']
			);
			
			return new JsonResponse($data);
		}
		
		return $this->render('CSIDBundle:Orders:index.html.twig',
			array(
				
			));
	}
	
	/**
	 * view order
	 * @param string $id
	 * @throws NotFoundHttpException
	 */
	public function viewAction($id)
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
		$order = $orderManager->getOrderById($retailer, $id);
		
		if($order == null) {
			throw new NotFoundHttpException(sprintf('La commande "%s" n\'existe pas', $id));
		}
		
		$form = $this->container->get('csid_basket.increase_decrease.form');
		$formHandler = $this->container->get('csid_basket.increase_decrease.form.handler');
		
		$process = $formHandler->process($order, $retailer);
		
		if ($process) {
			return $this->redirectToRoute('csid_order_view', array('id' => $id));
		}
		
		return $this->render('CSIDBundle:Orders:view.html.twig',
			array(
				'order' => $order,
				'form' => $form->createView()
			));
	}
	
	public function deleteAction($id)
	{
		if (! $this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $this->container->get('security.context')
		->getToken()
		->getUser();
				
		$orderManager = $this->getOrderManager();
		$result = $orderManager->deleteById($retailer, $id);
		
		return new JsonResponse(array(
			'success' => $result
		));
	}
	
	public function productsAction(Request $request)
	{
		if (! $this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
	
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		$technicalManager = $this->getTechnicalManager();
		
		$technicals = [];
		$technicals[] = "-1:Tous";
		foreach($technicalManager->findTechnicals() as $technical) {
			$technicals[] = $technical->getId().':'.$technical->getName();
		}
	
		if($request->isXmlHttpRequest()) {
				
			$page = $request->query->get('page');
			$maxPerPage = $request->query->get('rows');
			$searchField = $request->query->get('searchField');
			$searchString = $request->query->get('searchString');
				
			$orderManager = $this->getOrderManager();
			$products = [];
				
			$result = $orderManager->findProductsByRetailer($retailer, $page, $maxPerPage, $searchField, $searchString);
				
			/** @var $product \CSIDBundle\Entity\Product  **/
			foreach($result['list'] as $product) {
				
				$media = $product->getMediaJPG();
				$provider = $this->get($media->getProviderName());
				$thumbnail = $this->container->get('liip_imagine.cache.manager')->getBrowserPath(
					$provider->generatePublicUrl($media, 'reference'),
					'heighten_300');
				
				$products[] = array(
					'id' => $product->getId(),
					'technical' => $product->getTechnical()->getName(),
					'image' => $thumbnail,
					'price' => $product->getAmount(),
					'price_with_margin' => $product->getAmountWithMargin()
				);
			}
				
			$data = array(
				'page' => 1,
				'records' => $maxPerPage,
				'rows' => $products,
				'total' => $result['count']
			);
				
			return new JsonResponse($data);
		}
		
		return $this->render('CSIDBundle:Orders:products.html.twig',
			array(
				'technicals' => implode(';',$technicals)
			));
		
	}
	
	/**
	 * generate pdf
	 * @param string $id
	 * @throws NotFoundHttpException
	 */
	public function pdfAction($id)
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
		$order = $orderManager->getOrderById($retailer, $id);
		
		if($order == null) {
			throw new NotFoundHttpException(sprintf('La commande "%s" n\'existe pas', $id));
		}
		
		/** @var $pdf \CSIDBundle\Pdf\Pdf **/
		$pdf = $this->get('csid.pdf');
		
		return new Response(
		    $pdf->getOrderOutputFromHtml($order),
		    200,
		    array(
		        'Content-Type'          => 'application/pdf',
		        'Content-Disposition'   => 'attachment; filename="file.pdf"'
		    )
		);
	}
	
	/**
	 * send order 
	 * @param unknown $id
	 * @throws NotFoundHttpException
	 */
	public function sendAction($id)
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
		$order = $orderManager->getOrderById($retailer, $id);
		
		if($order == null) {
			throw new NotFoundHttpException(sprintf('La commande "%s" n\'existe pas', $id));
		}
		
		$form = $this->container->get('csid_order.send.form');
		
		$formHandler = $this->container->get('csid_order.send.form.handler');
		
		$process = $formHandler->process($order);
		
		if ($process) {
			$this->container->get('session')
			->getFlashBag()
			->set('alert-success', 'Votre devis a bien été envoyé.');
			return $this->redirectToRoute('csid_order_view', array('id' => $id));
		} 
		
		return $this->render('CSIDBundle:Orders:send.html.twig',
			array(
				'order' => $order,
				'form' => $form->createView(),
			));
	}
	
	/**
	 * valid an order quotation to order
	 * @param string $id
	 * @throws NotFoundHttpException
	 */
	public function validAction($id)
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
		$order = $orderManager->getOrderById($retailer, $id);
		
		if($order == null) {
			throw new NotFoundHttpException(sprintf('Le devis "%s" n\'existe pas', $id));
		}
		
		if($order->getRetailer() != $retailer) {
			throw new NotFoundHttpException(sprintf('Le devis "%s" n\'existe pas', $id));
		}
		
		if($order->getStatus() != "quotation") {
			throw new NotFoundHttpException(sprintf('Le devis "%s" n\'existe pas', $id));
		}
		
		$form = $this->container->get('csid.order.confirm.form');
		$formHandler = $this->container->get('csid.order.confirm.form.handler');
		
		$process = $formHandler->process($order);
		
		if ($process) {
			
			$this->container->get('session')
			->getFlashBag()
			->set('alert-success', 'Votre devis a bien été validé.');
			
			return $this->redirectToRoute('csid_order_view', array('id' => $id));
		}
		
		return $this->render('CSIDBundle:Orders:save.html.twig',
			array(
				'order' => $order,
				'form' => $form->createView(),
			));
	}
	
	/**
	 *
	 * @return \CSIDBundle\Doctrine\OrderManager
	 */
	public function getOrderManager ()
	{
		return $this->container->get('csid.order_manager');
	}
	
	/**
	 *
	 * @return \CSIDBundle\Doctrine\TechnicalManager
	 */
	public function getTechnicalManager ()
	{
		return $this->container->get('csid.technichal_manager');
	}
}