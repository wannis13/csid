<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CSIDBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OrderIncreaseDecrease
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="orders_increase_decrease")
 */
class OrderIncreaseDecrease
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
	 *
	 * @var Order
	 *
	 * @ORM\ManyToOne(targetEntity="Order")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
	 * })
	 */
	private $order;
	
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
	 * @var string
	 *
	 * @ORM\Column(name="label", type="string", length=50)
	 */
	private $label;
	
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
	private $vat = 0;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_increase", type="boolean")
	 */
	private $isIncrease = true;

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
     * Set amount
     *
     * @param float $amount
     *
     * @return OrderIncreaseDecrease
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
     * Set amountVAT
     *
     * @param float $amountVAT
     *
     * @return OrderIncreaseDecrease
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
     * Set vat
     *
     * @param string $vat
     *
     * @return OrderIncreaseDecrease
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set order
     *
     * @param \CSIDBundle\Entity\Order $order
     *
     * @return OrderIncreaseDecrease
     */
    public function setOrder(\CSIDBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \CSIDBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return OrderIncreaseDecrease
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set isIncrease
     *
     * @param boolean $isIncrease
     *
     * @return OrderIncreaseDecrease
     */
    public function setIsIncrease($isIncrease)
    {
        $this->isIncrease = $isIncrease;

        return $this;
    }

    /**
     * Get isIncrease
     *
     * @return boolean
     */
    public function getIsIncrease()
    {
        return $this->isIncrease;
    }
}
