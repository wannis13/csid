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
 * Fixing
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Fixing
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
	 * @var float
	 *
	 * @ORM\Column(name="price", type="float")
	 * @Assert\Type(
	 *     type="float",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $price;
	
	/**
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Fixing
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
     * @return Fixing
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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
    	$this->file = $file;
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
     * Upload a file
     */
    public function upload()
    {
    	// the file property can be empty if the field is not required
    	if (null === $this->getFile()) {
    		return;
    	}
    
    	if (! file_exists(UPLOAD_FIXING_DIR)) {
    		mkdir(UPLOAD_FIXING_DIR, 0777, true);
    	}
    
    	// we use the original file name here but you should
    	// sanitize it at least to avoid any security issues
    
    	// set the path property to the filename where you've saved the file
    	$this->filename = "fixing_" . $this->getId() . '.' . $this->getFile()->getClientOriginalExtension();
    	 
    	// move takes the target directory and target filename as params
    	$this->getFile()->move(UPLOAD_FIXING_DIR, $this->filename);
    
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
    		return UPLOAD_FIXING_URL . $this->filename;
    	}
    	else {
    		return "";
    	}
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Fixing
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
     * Set price
     *
     * @param float $price
     *
     * @return Fixing
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
}
