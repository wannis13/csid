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
 * @ORM\Table(name="order_detail")
 */
class OrderDetail
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
     *
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="OrderMobilierIncendie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="qty", type="integer", length=11)
     */

    private $qty = 1;
    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="amount_vat", type="float")
     */
    private $amountVAT = 0;



    /**
     *
     * @var color
     *
     * @ORM\ManyToOne(targetEntity="ProduitColor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_color", referencedColumnName="id" ,nullable=true)
     * })
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="code_color_ral", type="string" ,nullable=true)
     */
    private $code_color_ral ;
    /**
     *
     * @var Modele
     *
     * @ORM\ManyToOne(targetEntity="Modele")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_version", referencedColumnName="id" ,nullable=true)
     * })
     */
    private $version;
    /**
     *
     * @var Options
     *
     * @ORM\ManyToOne(targetEntity="Options")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_option", referencedColumnName="id" ,nullable=true)
     * })
     */
    private $option;

    /**
     *
     * @var Produits
     *
     * @ORM\ManyToOne(targetEntity="Produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id" ,nullable=true)
     * })
     */
    private $produit;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @ORM\PrePersist
     */
    public function lifecyclePrePersist()
    {
        $this->setCreated(new \DateTime());
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param int $qty
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getAmountVAT()
    {
        return $this->amountVAT;
    }

    /**
     * @param float $amountVAT
     */
    public function setAmountVAT($amountVAT)
    {
        $this->amountVAT = $amountVAT;
    }

    /**
     * @return color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param color $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getCodeColorRal()
    {
        return $this->code_color_ral;
    }

    /**
     * @param string $code_color_ral
     */
    public function setCodeColorRal($code_color_ral)
    {
        $this->code_color_ral = $code_color_ral;
    }

    /**
     * @return Modele
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param Modele $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return Options
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param Options $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }

    /**
     * @return Produits
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param Produits $produit
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;
    }

    /**
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param float $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    

}
