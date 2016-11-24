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

class StatisticsController extends Controller
{
	/**
	 * list orderes
	 * @param Request $request
	 */
	public function ordersAction(Request $request)
	{
		if (! $this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		$orderManager = $this->getOrderManager();
		
		$export = $request->query->get('export');
		$dateBegin = $request->query->get('date-begin');
		$dateEnd = $request->query->get('date-end');
				
		if($dateEnd != "") {
			$dateEnd = \DateTime::createFromFormat('d/m/Y', $dateEnd);
		} else {
			$dateEnd = new \DateTime('now');
		}
		
		if($dateBegin != "") {
			$dateBegin = \DateTime::createFromFormat('d/m/Y', $dateBegin);
		} else {
			$dateBegin = clone $dateEnd;
			$interval = new \DateInterval('P1M');
			$interval->invert = 1;
			$dateBegin->add($interval);
		}
		
		$results = $orderManager->ordersStats($retailer, $dateBegin, $dateEnd);
		
		if($export == "csv") {
			
			$handle = fopen('php://memory', 'r+');
			
			fputcsv($handle, array(
				'Date',
				'Total',
				'Montant'
			), ';');
			
			foreach($results as $result) {
				fputcsv($handle, array(
					$result['signatureDate'],
					(int)$result['total'],
					floatval ($result['amountWithMargin'])
				), ';');
			}
			
			rewind($handle);
			$content = stream_get_contents($handle);
			fclose($handle);
			
			return new Response($content, 200, array(
				'Content-Type' => 'application/force-download',
				'Content-Disposition' => 'attachment; filename="export.csv"'
			));
			
		} else {
			$statsCount = [];
			$statsSum = [];
			foreach($results as $result) {
				$statsCount[] = array(
					'label' => $result['signatureDate'],
					'y' => (int)$result['total']
				);
				$statsSum[] = array(
					'label' => $result['signatureDate'],
					'y' => floatval ($result['amountWithMargin'])
				);
			}
			
			return $this->render('CSIDBundle:Statistics:orders.html.twig',
				array(
					'statsCount' => $statsCount,
					'statsSum' => $statsSum,
					'dateBegin' => $dateBegin->format('d/m/Y'),
					'dateEnd' => $dateEnd->format('d/m/Y')
				));
		}
		
	}
	
	/**
	 * list orderes
	 * @param Request $request
	 */
	public function quotationsAction(Request $request)
	{
		if (! $this->get('security.authorization_checker')->isGranted('ROLE_RESELLER')) {
			throw $this->createAccessDeniedException();
		}
		
		/** @var $retailer \La2UserBundle\Entity\User **/
		$retailer = $this->container->get('security.context')
		->getToken()
		->getUser();
		
		$orderManager = $this->getOrderManager();
		
		$export = $request->query->get('export');
		$dateBegin = $request->query->get('date-begin');
		$dateEnd = $request->query->get('date-end');
				
		if($dateEnd != "") {
			$dateEnd = \DateTime::createFromFormat('d/m/Y', $dateEnd);
		} else {
			$dateEnd = new \DateTime('now');
		}
		
		if($dateBegin != "") {
			$dateBegin = \DateTime::createFromFormat('d/m/Y', $dateBegin);
		} else {
			$dateBegin = clone $dateEnd;
			$interval = new \DateInterval('P1M');
			$interval->invert = 1;
			$dateBegin->add($interval);
		}
		
		$results = $orderManager->quotationsStats($retailer, $dateBegin, $dateEnd);
		
		if($export == "csv") {
				
			$handle = fopen('php://memory', 'r+');
				
			fputcsv($handle, array(
				'Date',
				'Total',
				'Montant'
			), ';');
				
			foreach($results as $result) {
				fputcsv($handle, array(
					$result['quotationDate'],
					(int)$result['total'],
					floatval ($result['amountWithMargin'])
				), ';');
			}
				
			rewind($handle);
			$content = stream_get_contents($handle);
			fclose($handle);
				
			return new Response($content, 200, array(
				'Content-Type' => 'application/force-download',
				'Content-Disposition' => 'attachment; filename="export.csv"'
			));
				
		} else {
			$statsCount = [];
			$statsSum = [];
			foreach($results as $result) {
				$statsCount[] = array(
					'label' => $result['quotationDate'],
					'y' => (int)$result['total']
				);
				$statsSum[] = array(
					'label' => $result['quotationDate'],
					'y' => floatval ($result['amountWithMargin'])
				);
			}
				
			return $this->render('CSIDBundle:Statistics:quotations.html.twig',
				array(
					'statsCount' => $statsCount,
					'statsSum' => $statsSum,
					'dateBegin' => $dateBegin->format('d/m/Y'),
					'dateEnd' => $dateEnd->format('d/m/Y')
				));
		}
		
		
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