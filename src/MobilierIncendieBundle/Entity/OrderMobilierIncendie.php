<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MobilierIncendieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Order
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="orders_mobilier_incendie")
 */
class OrderMobilierIncendie
{	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var datetime
	 *
	 * @ORM\Column(name="created", type="datetime")
	 */
	private $created;
	

	
	/**
	 * @var datetime
	 *
	 * @ORM\Column(name="signature_date", type="datetime", nullable=true)
	 */
	private $signatureDate;
	
	/**
	 * @var \CSIDBundle\Entity\User
	 *
	 * @ORM\ManyToOne(targetEntity="CSIDBundle\Entity\User", cascade={"persist"})
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
	 * })
	 */
	private $createdBy;
	
	/**
	 * @var \CSIDBundle\Entity\User
	 *
	 * @ORM\ManyToOne(targetEntity="CSIDBundle\Entity\User", cascade={"persist"})
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="retailer", referencedColumnName="id" ,nullable=true)
	 * })
	 */
	private $retailer;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="number", type="integer", length=11)
	 */
	private $number = 1;
	
	/**
	 * @var \CSIDBundle\Entity\User
	 *
	 * @ORM\ManyToOne(targetEntity="CSIDBundle\Entity\User", cascade={"persist"})
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="customer", referencedColumnName="id" ,nullable=true)
	 * })
	 */
	private $customer;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="status", type="string", length=15 ,nullable=true)
	 */
	private $status;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="hide_to_csid", type="boolean")
	 */
	private $hideToCSID = false;
	
	/**
     *@var OrderDetail
	 * @ORM\OneToMany(targetEntity="OrderDetail", mappedBy="order", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     *
     */
	private $ligne_order;
	
	/**
	 * @ORM\OneToMany(targetEntity="OrderIncreaseDecrease", mappedBy="order", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
	 */
	//private $increaseOrDecrease;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $amount = 0;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount_vat", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $amountVAT = 0;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount_with_margin", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $amountWithMargin = 0;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount_vat_with_margin", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $amountVATWithMargin = 0;
	
	/**
	 * @var \Application\Sonata\MediaBundle\Entity\Media
	 * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="signature", referencedColumnName="id" ,nullable=true)
	 * })
	 */
	 private $signature;

    /**
     * @var float
     *
     * @ORM\Column(name="vat", type="decimal", precision=5, scale=2)
     * @Assert\Range(
     * 		min = 0,
     * 		max = 100,
     *     	minMessage = "Min % is 0",
     *      maxMessage = "Max % is 100"
     * )
     */
    private $vat = 20;

	/**
	 * @ORM\PrePersist
	 */
	public function lifecyclePrePersist()
	{
		$this->setCreated(new \DateTime());
	}
	
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Order
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ligne_order = new \Doctrine\Common\Collections\ArrayCollection();
       // $this->increaseOrDecrease = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add OrderDetail
     *
     * @param \MobilierIncendieBundle\Entity\OrderDetail $ligne_order
     *
     * @return Order
     */
    public function addLigneOrder(\MobilierIncendieBundle\Entity\OrderDetail $ligne_order)
    {
        $this->ligne_order[] = $ligne_order;

        return $this;
    }

    /**
     * Remove OrderDetail
     *
     * @param \CSIDBundle\Entity\OrderDetail $ligne_order
     */
    public function removeLigneOrder(\MobilierIncendieBundle\Entity\OrderDetail $ligne_order)
    {
        $this->ligne_order->removeElement($ligne_order);
    }



    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Order
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set customer
     *
     * @param \CSIDBundle\Entity\User $customer
     *
     * @return Order
     */
    public function setCustomer(\CSIDBundle\Entity\User $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \CSIDBundle\Entity\User
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Order
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set signatureDate
     *
     * @param \DateTime $signatureDate
     *
     * @return Order
     */
    public function setSignatureDate($signatureDate)
    {
        $this->signatureDate = $signatureDate;

        return $this;
    }

    /**
     * Get signatureDate
     *
     * @return \DateTime
     */
    public function getSignatureDate()
    {
        return $this->signatureDate;
    }

    /**
     * Set signature
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $signature
     *
     * @return Order
     */
    public function setSignature(\Application\Sonata\MediaBundle\Entity\Media $signature = null)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set amountWithMargin
     *
     * @param float $amountWithMargin
     *
     * @return Order
     */
    public function setAmountWithMargin($amountWithMargin)
    {
        $this->amountWithMargin = $amountWithMargin;

        return $this;
    }

    /**
     * Get amountWithMargin
     *
     * @return float
     */
    public function getAmountWithMargin()
    {
        return $this->amountWithMargin;
    }

    /**
     * Set amountVAT
     *
     * @param float $amountVAT
     *
     * @return Order
     */
    public function setAmountVAT($amountVAT)
    {
        $this->amountVAT = $amountVAT;

        return $this;
    }

    /**
     * Get amountVAT
     *
     * @return float
     */
    public function getAmountVAT()
    {
        return $this->amountVAT;
    }

    /**
     * Set amountVATWithMargin
     *
     * @param float $amountVATWithMargin
     *
     * @return Order
     */
    public function setAmountVATWithMargin($amountVATWithMargin)
    {
        $this->amountVATWithMargin = $amountVATWithMargin;

        return $this;
    }

    /**
     * Get amountVATWithMargin
     *
     * @return float
     */
    public function getAmountVATWithMargin()
    {
        return $this->amountVATWithMargin;
    }

    /**
     * Set createdBy
     *
     * @param \CSIDBundle\Entity\User $createdBy
     *
     * @return Order
     */
    public function setCreatedBy(\CSIDBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \CSIDBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set retailer
     *
     * @param \CSIDBundle\Entity\User $retailer
     *
     * @return Order
     */
    public function setRetailer(\CSIDBundle\Entity\User $retailer = null)
    {
        $this->retailer = $retailer;

        return $this;
    }

    /**
     * Get retailer
     *
     * @return \CSIDBundle\Entity\User
     */
    public function getRetailer()
    {
        return $this->retailer;
    }

    /**
     * Add increaseOrDecrease
     *
     * @param \CSIDBundle\Entity\OrderIncreaseDecrease $increaseOrDecrease
     *
     * @return Order
     */
    public function addIncreaseOrDecrease(\CSIDBundle\Entity\OrderIncreaseDecrease $increaseOrDecrease)
    {
        $this->increaseOrDecrease[] = $increaseOrDecrease;

        return $this;
    }

    /**
     * Remove increaseOrDecrease
     *
     * @param \CSIDBundle\Entity\OrderIncreaseDecrease $increaseOrDecrease
     */
    public function removeIncreaseOrDecrease(\CSIDBundle\Entity\OrderIncreaseDecrease $increaseOrDecrease)
    {
       // $this->increaseOrDecrease->removeElement($increaseOrDecrease);
    }

    /**
     * Get increaseOrDecrease
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncreaseOrDecrease()
    {
        //return $this->increaseOrDecrease;
    }

    /**
     * Set hideToCSID
     *
     * @param boolean $hideToCSID
     *
     * @return Order
     */
    public function setHideToCSID($hideToCSID)
    {
        $this->hideToCSID = $hideToCSID;

        return $this;
    }

    /**
     * Get hideToCSID
     *
     * @return boolean
     */
    public function getHideToCSID()
    {
        return $this->hideToCSID;
    }

    /**
     * Set quotationDate
     *
     * @param \DateTime $quotationDate
     *
     * @return Order
     */
    public function setQuotationDate($quotationDate)
    {
        $this->quotationDate = $quotationDate;

        return $this;
    }

    /**
     * Get quotationDate
     *
     * @return \DateTime
     */
    public function getQuotationDate()
    {
        return $this->quotationDate;
    }

    /**
     * @return OrderDetail
     */
    public function getLigneOrder()
    {
        return $this->ligne_order;
    }

    /**
     * @param OrderDetail $ligne_order
     */
    public function setLigneOrder($ligne_order)
    {
        $this->ligne_order = $ligne_order;
    }

    /**
     * @return mixed
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param mixed $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

}
