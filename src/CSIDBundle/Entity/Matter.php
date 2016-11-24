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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Matter
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Matter
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
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=50)
	 */
	private $name;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text")
	 */
	private $description;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="maxHeight", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $maxHeight;
       
   	/**
	 * @var float
	 *
	 * @ORM\Column(name="pricePerM2", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $pricePerM2;
	
    /**
     * @var float
     *
     * @ORM\Column(name="prciePrintPerM2", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $prciePrintPerM2;

    /**
     * @var float
     *
     * @ORM\Column(name="pricePerHole", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $pricePerHole;
    
     /**
     * @var float
     *
     * @ORM\Column(name="pricePlywood", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $pricePlywood;

    /**
     * @var float
     *
     * @ORM\Column(name="pricePerRightCorner", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $pricePerRightCorner;

    /**
     * @var float
     *
     * @ORM\Column(name="pricePerRoundedCorner", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $pricePerRoundedCorner;

     /**
     * @var float
     *
     * @ORM\Column(name="priceFixedFlagship", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $priceFixedFlagship;

	/**
	 * @var Float
	 *
	 * @ORM\Column(name="maxWidth", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $maxWidth;
	
	/**
	 * @ORM\OneToMany(targetEntity="MatterColor", mappedBy="matter", fetch="EXTRA_LAZY", cascade={"remove"})
	 */
	private $colors;
	
	/**
	 *
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="Dimension")
	 */
	private $dimensions;
	
	/**
	 *
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="Fixing")
	 */
	private $fixings;
	
	/**
	 * @Assert\Image(
	 * minWidth = 100,
	 * maxWidth = 600,
	 * )
	 *
	 * @var UploadedFile
	 */
	private $file;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="filename", type="string", length=100)
	 */
	private $filename = "";

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
     * Set name
     *
     * @param string $name
     *
     * @return Matter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Matter
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * 
     */
    public function __toString()
    {
    	return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->colors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add color
     *
     * @param \CSIDBundle\Entity\MatterColor $color
     *
     * @return Matter
     */
    public function addColor(\CSIDBundle\Entity\MatterColor $color)
    {
        $this->colors[] = $color;

        return $this;
    }

    /**
     * Remove color
     *
     * @param \CSIDBundle\Entity\MatterColor $color
     */
    public function removeColor(\CSIDBundle\Entity\MatterColor $color)
    {
        $this->colors->removeElement($color);
    }

    /**
     * Get colors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Matter
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
    	$this->file = $file;
    }
     /**
     * Sets file.
     *
     * @param UploadedFile $fileFoncee 
     */
    public function setFileFoncee(UploadedFile $fileFoncee = null)
    {
        $this->fileFoncee  = $fileFoncee ;
    }
     /**
     * Sets file.
     *
     * @param UploadedFile $fileOriginale
     */
    public function setFileOriginale (UploadedFile $fileOriginale  = null)
    {
        $this->fileOriginale  = $fileOriginale ;
    }
    /**
     * Sets file.
     *
     * @param UploadedFile $fileOriginale
     */
    public function setFileContrePlaque (UploadedFile $fileContrePlaque  = null)
    {
        $this->fileContrePlaque  =$fileContrePlaque ;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
    	return $this->file;
    }
     /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFileFoncee()
    {
        return $this->fileFoncee;
    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFileOriginale()
    {
        return $this->fileOriginale;
    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFileContrePlaque()
    {
        return $this->fileContrePlaque;
    }
    /**
     * Upload a file
     */
    public function upload()
    {
    	// the file property can be empty if the field is not required
    	if (null === $this->getFile()) {
    		return;
    	}
    	 
    	if (! file_exists(UPLOAD_MATTERS_DIR)) {
    		mkdir(UPLOAD_MATTERS_DIR, 0777, true);
    	}
    
    	// we use the original file name here but you should
    	// sanitize it at least to avoid any security issues
    
    	// set the path property to the filename where you've saved the file
    	$this->filename = "matter_" . $this->getId() . '.' . $this->getFile()->getClientOriginalExtension();
        if (file_exists(UPLOAD_MATTERS_DIR.''.$this->filename)) {
            unlink(UPLOAD_MATTERS_DIR.''.$this->filename);
        }
    	
    	// move takes the target directory and target filename as params
    	$this->getFile()->move(UPLOAD_MATTERS_DIR, $this->filename);
    
    	// clean up the file property as you won't need it anymore
    	$this->setFile(null);
    }
    
    /**
     *
     * @return string
     */
    public function getWebPath()
    {
    	if($this->filename != null) {

    		return UPLOAD_MATTERS_URL . $this->filename;
    	}
    	else {
    		return "";
    	}
    }

    /**
     * Add dimension
     *
     * @param \CSIDBundle\Entity\Dimension $dimension
     *
     * @return Matter
     */
    public function addDimension(\CSIDBundle\Entity\Dimension $dimension)
    {
        $this->dimensions[] = $dimension;

        return $this;
    }

    /**
     * Remove dimension
     *
     * @param \CSIDBundle\Entity\Dimension $dimension
     */
    public function removeDimension(\CSIDBundle\Entity\Dimension $dimension)
    {
        $this->dimensions->removeElement($dimension);
    }

    /**
     * Get dimensions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Set maxHeight
     *
     * @param float $maxHeight
     *
     * @return Matter
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    /**
     * Get maxHeight
     *
     * @return float
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }
     /////////////////
     /**
     * Set pricePerM2
     *
     * @param float $pricePerM2
     *
     * @return Matter
     */
    public function setPricePerM2($pricePerM2)
    {
        $this->pricePerM2 = $pricePerM2;

        return $this;
    }

    /**
     * Get pricePerM2
     *
     * @return float
     */
    public function getPricePerM2()
    {
        return $this->pricePerM2;
    }
     /**
     * Set prciePrintPerM2
     *
     * @param float $prciePrintPerM2
     *
     * @return Matter
     */
    public function setPrciePrintPerM2($prciePrintPerM2)
    {
        $this->prciePrintPerM2 = $prciePrintPerM2;

        return $this;
    }

    /**
     * Get prciePrintPerM2
     *
     * @return float
     */
    public function getPrciePrintPerM2()
    {
        return $this->prciePrintPerM2;
    }
     /**
     * Set pricePerHole
     *
     * @param float $pricePerHole
     *
     * @return Matter
     */
    public function setPricePerHole($pricePerHole)
    {
        $this->pricePerHole = $pricePerHole;

        return $this;
    }

    /**
     * Get pricePerHole
     *
     * @return float
     */
    public function getPricePerHole()
    {
        return $this->pricePerHole;
    }
     /**
     * Set pricePlywood
     *
     * @param float $pricePlywood
     *
     * @return Matter
     */
    public function setPricePlywood($pricePlywood)
    {
        $this->pricePlywood = $pricePlywood;

        return $this;
    }

    /**
     * Get pricePerHole
     *
     * @return float
     */
    public function getPricePlywood()
    {
        return $this->pricePlywood;
    }
     /**
     * Set pricePerRightCorner
     *
     * @param float $pricePerRightCorner
     *
     * @return Matter
     */
    public function setPricePerRightCorner($pricePerRightCorner)
    {
        $this->pricePerRightCorner= $pricePerRightCorner;

        return $this;
    }

    /**
     * Get pricePerRightCorner
     *
     * @return float
     */

    public function getPricePerRightCorner()
    {
        return $this->pricePerRightCorner;
    }
     /**
     * Set pricePerRoundedCorner
     *
     * @param float $pricePerRoundedCorner
     *
     * @return Matter
     */
    public function setPricePerRoundedCorner($pricePerRoundedCorner)
    {
        $this->pricePerRoundedCorner= $pricePerRoundedCorner;

        return $this;
    }

    /**
     * Get pricePerRoundedCorner
     *
     * @return float
     */

    public function getPricePerRoundedCorner()
    {
        return $this->pricePerRoundedCorner;
    }
    /**
     * Set priceFixedFlagship
     *
     * @param float $priceFixedFlagship
     *
     * @return Matter
     */
    public function setPriceFixedFlagship($priceFixedFlagship)
    {
        $this->priceFixedFlagship= $priceFixedFlagship;

        return $this;
    }
    /**
     * Get priceFixedFlagship
     *
     * @return float
     */

    public function getPriceFixedFlagship()
    {
        return $this->priceFixedFlagship;
    }
    /**
     * Set maxWidth
     *
     * @param float $maxWidth
     *
     * @return Matter
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;

        return $this;
    }

    /**
     * Get maxWidth
     *
     * @return float
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Add fixing
     *
     * @param \CSIDBundle\Entity\Fixing $fixing
     *
     * @return Matter
     */
    public function addFixing(\CSIDBundle\Entity\Fixing $fixing)
    {
        $this->fixings[] = $fixing;

        return $this;
    }

    /**
     * Remove fixing
     *
     * @param \CSIDBundle\Entity\Fixing $fixing
     */
    public function removeFixing(\CSIDBundle\Entity\Fixing $fixing)
    {
        $this->fixings->removeElement($fixing);
    }

    /**
     * Get fixings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFixings()
    {
        return $this->fixings;
    }
}
