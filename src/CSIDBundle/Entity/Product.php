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
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="products")
 */
class Product
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
	 */
	private $amount = 0;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount_vat", type="float")
	 */
	private $amountVAT = 0;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount_with_margin", type="float")
	 */
	private $amountWithMargin = 0;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount_vat_with_margin", type="float")
	 */
	private $amountVATWithMargin = 0;
	
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
	 * @var integer
	 *
	 * @ORM\Column(name="qty", type="integer", length=11)
	 */
	private $qty = 1;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="nb_holes", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $nbHoles = 0;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="holes_diameter", type="float", nullable=true)
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $holesDiameter = 0.0;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="plate_height", type="float")
	 * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
	 */
	private $plateHeight;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="plate_width", type="float")
	 * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
	 */
	private $plateWidth;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="print_height", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $printHeight;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="print_width", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $printWidth;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="json", type="text", length=65535)
	 */
	private $json = "";
	
	/**
	 * @var \Application\Sonata\MediaBundle\Entity\Media
	 * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="media_svg", referencedColumnName="id")
	 * })
	 */
	private $mediaSVG;
	
	/**
	 * @var \Application\Sonata\MediaBundle\Entity\Media
	 * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="media_jpg", referencedColumnName="id")
	 * })
	 */
	private $mediaJPG;
	
	/**
	 * 
	 * @var string
	 */
	private $svg = "";
	
	/**
	 * 
	 * @var string
	 */
	private $png = "";
	
	/**
	 *
	 * @var Technical
	 *
	 * @ORM\ManyToOne(targetEntity="Technical")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="technical", referencedColumnName="id")
	 * })
	 */
	private $technical;
	
	/**
	 *
	 * @var Fixing
	 *
	 * @ORM\ManyToOne(targetEntity="Fixing")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="fixing", referencedColumnName="id")
	 * })
	 */
	private $fixing;
	
	/**
	 *
	 * @var Matter
	 *
	 * @ORM\ManyToOne(targetEntity="Matter")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="plate_matter", referencedColumnName="id")
	 * })
	 */
	private $plateMatter;
	
	/**
	 *
	 * @var MatterColor
	 *
	 * @ORM\ManyToOne(targetEntity="MatterColor")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="plate_matter_color", referencedColumnName="id")
	 * })
	 */
	private $plateMatterColor;
	
	/**
	 *
	 * @var Matter
	 *
	 * @ORM\ManyToOne(targetEntity="Matter")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="backplate_matter", referencedColumnName="id")
	 * })
	 */
	private $backplateMatter;
	
	/**
	 *
	 * @var MatterColor
	 *
	 * @ORM\ManyToOne(targetEntity="MatterColor")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="backplate_matter_color", referencedColumnName="id")
	 * })
	 */
	private $backplateMatterColor;
	
	/**
	 * @var decimal
	 *
	 * @ORM\Column(name="margin", type="decimal", precision=5, scale=2)
	 * @Assert\Range(
	 * 		min = 0,
	 *     	minMessage = "Min % is 0"
	 * )
	 */
	private $margin = 0;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="standard_bearer", type="string", length=6, nullable=true)
	 */
	private $standardBearer;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="rounded_corner", type="boolean")
	 */
	private $roundedCorner = false;

	public function __clone() {
		$this->id = null;
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
     * Set order
     *
     * @param \CSIDBundle\Entity\Order $order
     *
     * @return Product
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
     * Set fixing
     *
     * @param \CSIDBundle\Entity\Fixing $fixing
     *
     * @return Product
     */
    public function setFixing(\CSIDBundle\Entity\Fixing $fixing = null)
    {
        $this->fixing = $fixing;

        return $this;
    }

    /**
     * Get fixing
     *
     * @return \CSIDBundle\Entity\Fixing
     */
    public function getFixing()
    {
        return $this->fixing;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Product
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
     * Set technical
     *
     * @param \CSIDBundle\Entity\Technical $technical
     *
     * @return Product
     */
    public function setTechnical(\CSIDBundle\Entity\Technical $technical = null)
    {
        $this->technical = $technical;

        return $this;
    }

    /**
     * Get technical
     *
     * @return \CSIDBundle\Entity\Technical
     */
    public function getTechnical()
    {
        return $this->technical;
    }

    /**
     * Set json
     *
     * @param string $json
     *
     * @return Product
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get json
     *
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }
    
    /**
     * 
     * @param string $svg
     */
    public function setSvg($svg)
    {
    	$this->svg = $svg;
    }
    
    /**
     * 
     * @return string
     */
    public function getSvg()
    {
    	return $this->svg;
    }
    
    /**
     *
     * @param string $png
     */
    public function setPng($png)
    {
    	$this->png = $png;
    }
    
    /**
     *
     * @return string
     */
    public function getPng()
    {
    	return $this->png;
    }

    /**
     * Set plateHeight
     *
     * @param float $plateHeight
     *
     * @return Product
     */
    public function setPlateHeight($plateHeight)
    {
        $this->plateHeight = $plateHeight;

        return $this;
    }

    /**
     * Get plateHeight
     *
     * @return float
     */
    public function getPlateHeight()
    {
        return $this->plateHeight;
    }

    /**
     * Set plateWidth
     *
     * @param float $plateWidth
     *
     * @return Product
     */
    public function setPlateWidth($plateWidth)
    {
        $this->plateWidth = $plateWidth;

        return $this;
    }

    /**
     * Get plateWidth
     *
     * @return float
     */
    public function getPlateWidth()
    {
        return $this->plateWidth;
    }

    /**
     * Set printHeight
     *
     * @param float $printHeight
     *
     * @return Product
     */
    public function setPrintHeight($printHeight)
    {
        $this->printHeight = $printHeight;

        return $this;
    }

    /**
     * Get printHeight
     *
     * @return float
     */
    public function getPrintHeight()
    {
        return $this->printHeight;
    }

    /**
     * Set printWidth
     *
     * @param float $printWidth
     *
     * @return Product
     */
    public function setPrintWidth($printWidth)
    {
        $this->printWidth = $printWidth;

        return $this;
    }

    /**
     * Get printWidth
     *
     * @return float
     */
    public function getPrintWidth()
    {
        return $this->printWidth;
    }

    /**
     * Set plateMatter
     *
     * @param \CSIDBundle\Entity\Matter $plateMatter
     *
     * @return Product
     */
    public function setPlateMatter(\CSIDBundle\Entity\Matter $plateMatter = null)
    {
        $this->plateMatter = $plateMatter;

        return $this;
    }

    /**
     * Get plateMatter
     *
     * @return \CSIDBundle\Entity\Matter
     */
    public function getPlateMatter()
    {
        return $this->plateMatter;
    }

    /**
     * Set plateMatterColor
     *
     * @param \CSIDBundle\Entity\MatterColor $plateMatterColor
     *
     * @return Product
     */
    public function setPlateMatterColor(\CSIDBundle\Entity\MatterColor $plateMatterColor = null)
    {
        $this->plateMatterColor = $plateMatterColor;

        return $this;
    }

    /**
     * Get plateMatterColor
     *
     * @return \CSIDBundle\Entity\MatterColor
     */
    public function getPlateMatterColor()
    {
        return $this->plateMatterColor;
    }

    /**
     * Set backplateMatter
     *
     * @param \CSIDBundle\Entity\Matter $backplateMatter
     *
     * @return Product
     */
    public function setBackplateMatter(\CSIDBundle\Entity\Matter $backplateMatter = null)
    {
        $this->backplateMatter = $backplateMatter;

        return $this;
    }

    /**
     * Get backplateMatter
     *
     * @return \CSIDBundle\Entity\Matter
     */
    public function getBackplateMatter()
    {
        return $this->backplateMatter;
    }

    /**
     * Set backplateMatterColor
     *
     * @param \CSIDBundle\Entity\MatterColor $backplateMatterColor
     *
     * @return Product
     */
    public function setBackplateMatterColor(\CSIDBundle\Entity\MatterColor $backplateMatterColor = null)
    {
        $this->backplateMatterColor = $backplateMatterColor;

        return $this;
    }

    /**
     * Get backplateMatterColor
     *
     * @return \CSIDBundle\Entity\MatterColor
     */
    public function getBackplateMatterColor()
    {
        return $this->backplateMatterColor;
    }

    /**
     * Set nbHoles
     *
     * @param float $nbHoles
     *
     * @return Product
     */
    public function setNbHoles($nbHoles)
    {
        $this->nbHoles = $nbHoles;

        return $this;
    }

    /**
     * Get nbHoles
     *
     * @return float
     */
    public function getNbHoles()
    {
        return $this->nbHoles;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     *
     * @return Product
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return integer
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set mediaSVG
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaSVG
     *
     * @return Product
     */
    public function setMediaSVG(\Application\Sonata\MediaBundle\Entity\Media $mediaSVG = null)
    {
        $this->mediaSVG = $mediaSVG;

        return $this;
    }

    /**
     * Get mediaSVG
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMediaSVG()
    {
        return $this->mediaSVG;
    }

    /**
     * Set mediaJPG
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaJPG
     *
     * @return Product
     */
    public function setMediaJPG(\Application\Sonata\MediaBundle\Entity\Media $mediaJPG = null)
    {
        $this->mediaJPG = $mediaJPG;

        return $this;
    }

    /**
     * Get mediaJPG
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMediaJPG()
    {
        return $this->mediaJPG;
    }

    /**
     * Set margin
     *
     * @param float $margin
     *
     * @return Product
     */
    public function setMargin($margin)
    {
        $this->margin = $margin;

        return $this;
    }

    /**
     * Get margin
     *
     * @return float
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * Set tva
     *
     * @param string $tva
     *
     * @return Product
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get tva
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set amountVAT
     *
     * @param float $amountVAT
     *
     * @return Product
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
     * Set amountWithMargin
     *
     * @param float $amountWithMargin
     *
     * @return Product
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
     * Set amountVATWithMargin
     *
     * @param float $amountVATWithMargin
     *
     * @return Product
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
     * Set holesDiameter
     *
     * @param float $holesDiameter
     *
     * @return Product
     */
    public function setHolesDiameter($holesDiameter)
    {
        $this->holesDiameter = $holesDiameter;

        return $this;
    }

    /**
     * Get holesDiameter
     *
     * @return float
     */
    public function getHolesDiameter()
    {
        return $this->holesDiameter;
    }

    /**
     * Set standardBearer
     *
     * @param string $standardBearer
     *
     * @return Product
     */
    public function setStandardBearer($standardBearer)
    {
        $this->standardBearer = $standardBearer;

        return $this;
    }

    /**
     * Get standardBearer
     *
     * @return string
     */
    public function getStandardBearer()
    {
        return $this->standardBearer;
    }

    /**
     * Set roundedCorner
     *
     * @param boolean $roundedCorner
     *
     * @return Product
     */
    public function setRoundedCorner($roundedCorner)
    {
        $this->roundedCorner = $roundedCorner;

        return $this;
    }

    /**
     * Get roundedCorner
     *
     * @return boolean
     */
    public function getRoundedCorner()
    {
        return $this->roundedCorner;
    }
}
