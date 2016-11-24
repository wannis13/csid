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
use CSIDBundle\Entity\User;
use CSIDBundle\Entity\Order;
use CSIDBundle\Entity\Product;
use Application\Sonata\MediaBundle\Entity\Media;
use CSIDBundle\Entity\OrderIncreaseDecrease;
use Doctrine\ORM\Query\Expr\Join;

class BasketManager
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
	 * @var string
	 */
	protected $orderIDClass;
	
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
	 * @var \Doctrine\ORM\EntityRepository
	 */
	protected $orderIncreaseDecreaseRepository;
	
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
	 * constructor 
	 * 
	 * @param ObjectManager $om
	 * @param unknown $class
	 */
	public function __construct(ObjectManager $om, $orderClass, $orderItemClass, $orderIDClass, $mediaManager, $mediaProvider)
	{
        $this->objectManager = $om;
        $this->orderRepository = $om->getRepository($orderClass);
        $this->orderItemRepository = $om->getRepository($orderItemClass);
        $this->orderIncreaseDecreaseRepository = $om->getRepository($orderIDClass);

        $metadata = $om->getClassMetadata($orderClass);
        $this->orderClass = $metadata->getName();
        
        $metadata = $om->getClassMetadata($orderItemClass);
        $this->orderItemClass = $metadata->getName();
        
        $metadata = $om->getClassMetadata($orderIDClass);
        $this->orderIDClass = $metadata->getName();
        
        $this->mediaManager = $mediaManager;
        $this->mediaProvider = $mediaProvider;
	}
	
	/**
	 * 
	 * get order by user and status
	 * 
	 * @param User $user
	 * 
	 * @return Order
	 */
	public function getOrderByUser($user, $status = 'creation')
	{
		$order = $this->orderRepository->findOneBy(array(
			'createdBy' => $user->getId(),
			'status' => $status
		));
		
		if(!$order instanceof Order) {
			/** @var $order \CSIDBundle\Entity\Order */
			$class = $this->orderClass;
			$order = new $class;
			$order->setCreatedBy($user);
			$order->setStatus('creation');
			 
			$retailer = $user;
			 
			if($user->getRetailer() != null) {
				$retailer = $user->getRetailer();
			}
			 
			$order->setRetailer($retailer);
			 
			// get last order number
			$query = $this->orderRepository->createQueryBuilder('o');
			$query->select('MAX(o.number) AS max_number');
			$query->where('o.retailer = :retailer')->setParameter('retailer', $retailer);
			$query->setMaxResults(1);
			$query->orderBy('max_number', 'DESC');
			 
			$result = $query->getQuery()->getOneOrNullResult();
			 
			$number = $result['max_number'] + 1;
			 
			$order->setNumber($number);
			 
			$this->objectManager->persist($order);
		}
		
		return $order;
	}
	
	/**
	 * delete item by item id
	 * @param string $itemId
	 * @param User $retailer
	 * @return boolean|\CSIDBundle\Entity\Order
	 */
	public function deleteItemByItem($itemId, $retailer)
	{
		$orderItem = $this->orderItemRepository->findOneBy(array(
			'id' => $itemId
		));
		
		if($orderItem instanceof Product) {
			$order = $orderItem->getOrder();
			
			// check user 
			if($order->getRetailer()->getId() != $retailer->getId()) {
				return false;
			}
			
			foreach($order->getItems() as $item) {
				if($item == $orderItem) {
					$order->removeItem($item);
				}
			}
			
			$this->updateAmount($order);
			
			// delete item
			$this->objectManager->remove($orderItem);
			$this->objectManager->flush();
			
			return $order;
		}
		
		return false;
	}
	
	/**
	 * update quantity
	 * 
	 * @param array $data
	 * @param User $user
	 */
	public function updateQty($data, $user)
	{
		$order = $this->getOrderByUser($user);
		
		/** @var $item Product **/
		foreach($order->getItems() as $item) {
			if($item->getId() == $data['product']) {
				if($data['qty'] > 0) {
					$item->setQty($data['qty']);
				} else {
					$this->objectManager->remove($item);
					$order->removeItem($item);
				}
			}
		}
		
		$this->updateAmount($order);
		
		$this->objectManager->flush();
		
		return true;
	}

	/**
	 * 
	 * @param User $user
	 * @param User $retailer
	 * @param string $productId
	 */
	public function addProduct($user, $retailer, $productId)
	{
		$order = $this->getOrderByUser($user);
		
		$product = $this->getProductsById($retailer, $productId);
		
		$newProduct = clone $product;
		$newProduct->setQty(1);
		$newProduct->setOrder($order);
		$this->objectManager->detach($newProduct);
		$this->objectManager->persist($newProduct);
		
		$order->addItem($newProduct);
		$this->updateAmount($order);
		
		
		$this->objectManager->flush();
		
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
	 * delete increase or decrease by id
	 *
	 * @param string $itemId
	 * @param User $retailer
	 */
	public function deleteIncreaseOrDecreaseById($itemId, $retailer)
	{
		$increaseOrDecrease = $this->orderIncreaseDecreaseRepository->findOneBy(array(
			'id' => $itemId
		));
	
		if($increaseOrDecrease instanceof OrderIncreaseDecrease) {
			
			$order = $increaseOrDecrease->getOrder();
				
			// check user
			if($order->getRetailer()->getId() != $retailer->getId()) {
				return false;
			}
			
			foreach($order->getIncreaseOrDecrease() as $item) {
				if($item == $increaseOrDecrease) {
					$order->removeIncreaseOrDecrease($item);
				}
			}
			
			// @TODO check order status
			$this->updateAmount($order);
			
			// delete item
			$this->objectManager->remove($increaseOrDecrease);
			$this->objectManager->flush();
				
			return $order;
		}
	
		return false;
	}
	
	/**
	 * get item by item id
	 * @param string $itemId
	 * @param User $user
	 */
	public function getItemById($itemId, $user)
	{
		$orderItem =  $this->orderItemRepository->findOneBy(array(
			'id' => $itemId
		));

		if($orderItem instanceof Product) {
			$order = $orderItem->getOrder();

			// check user
			if($order->getUser()->getId() != $user->getId()) {
				return false;
			}



			return $orderItem;
		}

		return false;
	}

	/**
	 * empty user basket
	 * 
	 * @param User $user
	 * @return boolean
	 */
	public function emptyBasket($user)
	{
		$order = $this->getOrderByUser($user);
		
		if($order instanceof Order) {
			foreach($order->getItems() as $orderItem) {
				$this->objectManager->remove($orderItem);
			}
			$order->setAmount(0);
			$this->objectManager->flush();
			return true;
		}
		
		return false;
	}
	
	/**
	 * 
	 * @param \CSIDBundle\Entity\Product $product
	 * @return number
	 */
	public function calculateItmPrice($product)
	{
		// m² plaque
		$a1 = $product->getPlateHeight() * $product->getPlateWidth() * 0.000001 ;
		// m² contre-plaque
		$a2 = $product->getPlateHeight() * $product->getPlateWidth() * 0.000001 ;
		// m² air d'impression
		$a3 = $product->getPrintHeight() * $product->getPrintWidth() * 0.000001 ;
		// nb perçage
		$a4 = $product->getNbHoles();
		// prix porte drapeau
		$a5 = 0;
			
		$total = 0;
			
		if($product->getPlateMatter() != null) {
			$total += ($a1 * $product->getPlateMatter()->getPricePerM2());
			$total += ($a3 * $product->getPlateMatter()->getPrciePrintPerM2());
			$total += ($a4 * $product->getPlateMatter()->getPricePerHole());
			
			if($product->getStandardBearer() != null) {
				$a5 = $product->getPlateMatter()->getPriceFixedFlagship();
				$a6 = 0;
				switch ($product->getStandardBearer()) {
					case 'left' : 
						$a6 = $product->getPlateHeight();
						break;
					case 'right' :
						$a6 = $product->getPlateHeight();
						break;
					case 'top' :
						$a6 = $product->getPlateWidth();
						break;
					case 'bottom' :
						$a6 = $product->getPlateWidth();
						break;
				}
				$a5 += ($a6 * 30 * 0.000001) * $product->getPlateMatter()->getPricePerM2();
				$total += $a5;
			}
		}
			
		if($product->getBackplateMatter() != null) {
			$total += ($a2 * $product->getBackplateMatter()->getPricePerM2());
		}
			
		$total += $a5;
			
		if($product->getFixing() != null) {
			$total += ($product->getFixing()->getPrice());
		}
		
		return $total;
	}
	
	/**
	 * 
	 * @return \CSIDBundle\Entity\Product
	 */
	public function newProduct()
	{
		$class = $this->orderItemClass;
		return new $class;
	}
	
	/**
	 * add an item to basket
	 * 
	 * @param \CSIDBundle\Entity\Product $product
	 * @param User $user
	 */
	public function addItem($product, $user)
	{
		$order = $this->getOrderByUser($user);
		
		if($product instanceof Product) {
			
			$retailer = $order->getRetailer();
			$product->setOrder($order);
						
			$amount = $this->calculateItmPrice($product);
			
			$margin = $retailer->getMarginPercent();
			$vat = $retailer->getTva();
			$amountTVA = $amount * (1+$vat/100);
			$amountWithMargin = $amount * (1+$margin/100);
			$amountVATWithMargin = $amountWithMargin * (1+$vat/100);
			
			$product->setVat($vat);
			$product->setMargin($margin);
			$product->setAmount($amount);
			$product->setAmountVAT($amountTVA);
			$product->setAmountWithMargin($amountWithMargin);
			$product->setAmountVATWithMargin($amountVATWithMargin);

			$order->addItem($product);
			
			$this->objectManager->persist($order);
			
			$this->updateAmount($order);
			
			$mediaManager = $this->mediaManager;
			
			$svg = $product->getSvg();
			
			$tmp = time();
			
			$svgFile = UPLOAD_DIR . '/' . $tmp . '.svg';
			$jpgFile = UPLOAD_DIR . '/' . $tmp . '.png';
			
			// Save SVG
			$handle = fopen($svgFile, "w");
			fwrite($handle, $svg);
			fclose($handle);
			
			$media = new Media();
			$media->setBinaryContent($svgFile);
			$media->setContext('product');
			$media->setProviderName('sonata.media.provider.file');
			$mediaManager->save($media);
			
			$product->setMediaSVG($media);
			
			// Save JPG 
			$data = $product->getPng();
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			file_put_contents($jpgFile, $data);
						
			$media = new Media();
			$media->setBinaryContent($jpgFile);
			$media->setContext('product');
			$media->setProviderName('sonata.media.provider.image');
			$mediaManager->save($media);
			
			$product->setMediaJPG($media);
			
			$this->objectManager->flush();
			
			unlink($svgFile);
			unlink($jpgFile);
			
		}
	}
	
	/**
	 * 
	 * @param Order $order
	 */
	public function updateAmount($order)
	{
		$order->setAmount(0);
		$order->setAmountWithMargin(0);
		$order->setAmountVAT(0);
		$order->setAmountVATWithMargin(0);
		
		/** @var $product Product **/
		foreach($order->getItems() as $product) {
			$order->setAmount($order->getAmount() + ($product->getAmount() * $product->getQty()));
			$order->setAmountWithMargin($order->getAmountWithMargin() + ($product->getAmountWithMargin() * $product->getQty()));
			$order->setAmountVAT($order->getAmountVAT() + ($product->getAmountVAT() * $product->getQty()));
			$order->setAmountVATWithMargin($order->getAmountVATWithMargin() + ($product->getAmountVATWithMargin() * $product->getQty()));
		}
		
		foreach($order->getIncreaseOrDecrease() as $increaseOrDecrease) {
			// Augmentation
			if($increaseOrDecrease->getIsIncrease()) {
				$order->setAmount($order->getAmount() + $increaseOrDecrease->getAmount());
				$order->setAmountVAT($order->getAmountVAT() + $increaseOrDecrease->getAmountVAT());
				$order->setAmountWithMargin($order->getAmountWithMargin() + $increaseOrDecrease->getAmount());
				$order->setAmountVATWithMargin($order->getAmountVATWithMargin() + $increaseOrDecrease->getAmountVAT());
			} else {
				$order->setAmount($order->getAmount() - $increaseOrDecrease->getAmount());
				$order->setAmountVAT($order->getAmountVAT() - $increaseOrDecrease->getAmountVAT());
				$order->setAmountWithMargin($order->getAmountWithMargin() - $increaseOrDecrease->getAmount());
				$order->setAmountVATWithMargin($order->getAmountVATWithMargin() - $increaseOrDecrease->getAmountVAT());
			}
		}
		
	}
	
	/**
	 * confirm basket 
	 * @param array $data
	 * @param User $user
	 * @param User $customer
	 */
	public function confirm($data, $user, $customer)
	{
		$order = $this->getOrderByUser($user);
		
		if(!$order instanceof Order) {
			return false;
		}
		
		if(count($order->getItems()) == 0) {
			return false;
		}
		
		if(isset($data['hide_to_csid'])) {
			$order->setHideToCSID($data['hide_to_csid']);
		}
		
		$order->setQuotationDate(new \DateTime('now'));
		$order->setCustomer($customer);
		$order->setStatus('quotation');
		$this->objectManager->persist($order);
		$this->objectManager->flush();
	}
	
	/**
	 * add increase or decrease
	 * @param array $data
	 * @param order $order
	 */
	public function addIncreaseDecrease($data, $order, $retailer)
	{
		if($order == 'order') {
			return;
		}
		
		if($order->getRetailer() != $retailer) {
			return;
		}
		
		$class = $this->orderIDClass;
		/** @var $increaseOrDecrease \CSIDBundle\Entity\OrderIncreaseDecrease **/
		$increaseOrDecrease = new $class;
		$increaseOrDecrease->setAmount($data['amount']);
		$increaseOrDecrease->setVat($data['vat']);
		$increaseOrDecrease->setAmountVAT($data['amount'] * (1+$data['vat']/100));
		$increaseOrDecrease->setOrder($order);
		$increaseOrDecrease->setIsIncrease($data['type']);
		$increaseOrDecrease->setLabel($data['label']);
		$this->objectManager->persist($increaseOrDecrease);
		
		$order->addIncreaseOrDecrease($increaseOrDecrease);
		
		$this->updateAmount($order);
		
		$this->objectManager->flush();
		
		return true;
	}
}