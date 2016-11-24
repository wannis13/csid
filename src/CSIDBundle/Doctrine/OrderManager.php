<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CSIDBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use La2UserBundle\Entity\User;
use CSIDBundle\Entity\Order;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Application\Sonata\MediaBundle\Entity\Media;
use CSIDBundle\Mailer\Mailer;
use CSIDBundle\Entity\Product;

class OrderManager
{
	/**
	 *
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $objectManager;
	
	/**
	 * 
	 * @var string
	 */
	protected $orderClass;
	
	/**
	 *
	 * @var string
	 */
	protected $orderItemClass;
	
	/**
	 * 
	 * @var \Doctrine\ORM\EntityRepository
	 */
	protected $orderRepository;
	
	/**
	 *
	 * @var \Doctrine\ORM\EntityRepository
	 */
	protected $orderItemRepository;
	
	/**
	 *
	 * @var \Sonata\MediaBundle\Entity\MediaManager
	 */
	protected $mediaManager;
	
	/**
	 *
	 * @var \Sonata\MediaBundle\Provider\ImageProvider
	 */
	protected $mediaProvider;
	
	/**
	 * 
	 * @var Mailer
	 */
	protected $mailer;
	
	/**
	 * constructor
	 *
	 * @param ObjectManager $om
	 * @param unknown $class
	 */
	public function __construct(ObjectManager $om, $orderClass, $orderItemClass, $mediaManager, $mediaProvider, $mailer)
	{
		$this->objectManager = $om;
		$this->orderRepository = $om->getRepository($orderClass);
		$this->orderItemRepository = $om->getRepository($orderItemClass);
	
		$metadata = $om->getClassMetadata($orderClass);
		$this->orderClass = $metadata->getName();
	
		$metadata = $om->getClassMetadata($orderItemClass);
		$this->orderItemClass = $metadata->getName();
		
		$this->mediaManager = $mediaManager;
		$this->mediaProvider = $mediaProvider;
		
		$this->mailer = $mailer;
	}
	
	/**
	 * find orders by user
	 * 
	 * @param User $user
	 */
	public function findOrders($retailer, $page = 1, $maxPerPage = 10, $customerId = null)
	{
		$qb = $this->orderRepository->createQueryBuilder('o');
		
		$qb->where('o.retailer = :retailer')
		->andWhere('o.status != :status_not_in');
		
		$qb->setParameter('retailer', $retailer->getId())
		->setParameter('status_not_in', array('creation'));
		
		if($customerId != null) {
			$qb->andWhere('o.customer = :customer');
			$qb->setParameter('customer', $customerId);
		}
		
		$qb->setFirstResult(($page - 1) * $maxPerPage)->setMaxResults($maxPerPage);
		
		$paginator = new Paginator($qb);
		
		return array(
			'list' => $qb->getQuery()->getResult(),
			'count' => count($paginator, true)
		);
	}

	
	/**
	 * get order by id
	 * 
	 * @param User $user
	 *
	 * @return Order
	 */
	public function getOrderById($user, $id)
	{
		$order = $this->orderRepository->findOneBy(array(
			'retailer' => $user->getId(),
			'id' => $id
		));
	
		return $order;
	}
	
	/**
	 * get order item by item id
	 * 
	 * @param User $retailer
	 *
	 * @return Product
	 */
	public function getProductsById($retailer, $id)
	{
		$qb = $this->orderItemRepository->createQueryBuilder('oi');
		$qb->join('CSIDBundle\Entity\Order', 'o', Join::WITH, 'oi.order = o.id');
		
		$qb->andWhere('o.retailer = :user');
		$qb->andWhere('oi.id = :id');
		
		$qb->setParameter('user', $retailer->getId())
		->setParameter('id', $id);
		
		return $qb->getQuery()->getOneOrNullResult();
	}
	
	/**
	 * get products by retailer id
	 *
	 * @param User $retailer
	 * @return array
	 */
	public function findProductsByRetailer($retailer, $page = 1, $maxPerPage = 10, $searchField = null, $searchString = null) 
	{
		$qb = $this->orderItemRepository->createQueryBuilder('oi');
		$qb->join('CSIDBundle\Entity\Order', 'o', Join::WITH, 'oi.order = o.id');
		
		$qb->andWhere('o.retailer = :user')
		->andWhere('o.status != :status_not_in');
		
		$qb->setParameter('user', $retailer->getId())
		->setParameter('status_not_in', array('creation'));
		$qb->setFirstResult(($page - 1) * $maxPerPage)->setMaxResults($maxPerPage);
		
		if($searchField != null && $searchString != -1) {
			$qb->andWhere('oi.'.$searchField.' = :search');
			$qb->setParameter('search', $searchString);
		}
		
		$paginator = new Paginator($qb);
		
		return array(
			'list' => $qb->getQuery()->getResult(),
			'count' => count($paginator, true)
		);
	}
	
	/**
	 * confirm an order and save signature
	 * 
	 * @param Order $order
	 * @param array $data
	 */
	public function confirmOrder(Order $order, $data)
	{
		if (! file_exists(UPLOAD_DIR . '/signatures/')) {
			mkdir(UPLOAD_DIR . '/signatures/', 0777, true);
		}
		
		$order->setStatus('order');
		$order->setSignatureDate(new \DateTime('now'));
		
		// save signature
		$mediaManager = $this->mediaManager;
		
		$tmp = time();
		$svg = $data['signature_svg'];
		$svgFile = UPLOAD_DIR . '/' . $tmp . '.svg';
		$jpgFile = UPLOAD_DIR . '/' . $tmp . '.jpg';
		
		$handle = fopen($svgFile, "w");
		fwrite($handle, $svg);
		fclose($handle);
	
		$imagine = new \Imagine\Imagick\Imagine();
		$imagine->open($svgFile)->save($jpgFile);
		
		$media = new Media();
		$media->setBinaryContent($jpgFile);
		$media->setContext('signature');
		$media->setProviderName('sonata.media.provider.image');
		$mediaManager->save($media);
		
		unlink($svgFile);
		unlink($jpgFile);
		
		$order->setSignature($media);
		
		$this->objectManager->flush($order);
	}
	
	/**
	 * 
	 * @param Order $order
	 * @param array $data
	 */
	public function send(Order $order, $data)
	{
		return $this->mailer->sendOrder($order, $data);
	}
	
	public function deleteById($retailer, $id)
	{
		$em = $this->objectManager;
		$order = $this->getOrderById($retailer, $id);
		
		$em->remove($order);
		
		$em->flush();
		
		return true;
	}
	
	public function ordersStats($retailer, $dateBegin, $dateEnd)
	{
		$qb = $this->orderRepository->createQueryBuilder('o');
	
		$qb->addSelect('count(o) as total, SUM(o.amountWithMargin) AS amountWithMargin, DATE_FORMAT(o.signatureDate, \'%Y-%m-%d\') as signatureDate');
		$qb->where('o.retailer = :retailer')
		->andWhere('o.status = :status');
	
		$qb->andWhere('o.signatureDate BETWEEN :begin AND :end');
		$qb->setParameter('begin', $dateBegin);
		$qb->setParameter('end', $dateEnd);
		$qb->groupBy('signatureDate');
	
		$qb->setParameter('retailer', $retailer->getId())
		->setParameter('status', 'order');
	
		return $qb->getQuery()->getResult();
	}
	
	public function quotationsStats($retailer, $dateBegin, $dateEnd)
	{
		$qb = $this->orderRepository->createQueryBuilder('o');
		
		$qb->addSelect('count(o) as total, SUM(o.amountWithMargin) AS amountWithMargin, DATE_FORMAT(o.quotationDate, \'%Y-%m-%d\') as quotationDate');
		$qb->where('o.retailer = :retailer')
		->setParameter('retailer', $retailer->getId())
		->andWhere('o.status IN (:status)')
		->setParameter('status', array('quotation', 'order'));
		
		$qb->andWhere('o.quotationDate BETWEEN :begin AND :end');
		$qb->setParameter('begin', $dateBegin);
		$qb->setParameter('end', $dateEnd);
		$qb->groupBy('quotationDate');
		
		
		
		
		return $qb->getQuery()->getResult();
	}
}