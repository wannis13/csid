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
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Dimension
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Technical
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
	 * @ORM\Column(name="type", type="integer")
	 */
	private $type;
	
	/**
	 * @Assert\Image(minWidth = 100, maxWidth = 600)
	 *
	 * @var UploadedFile
	 */
	private $file;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="filename", type="string", length=100)
	 */
	private $filename;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text", nullable=true)
	 */
	private $description;
	
	/**
	 *
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="Matter")
	 */
	private $matters;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="blackplate", type="boolean", nullable=false)
	 */
	private $blackplate;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="can_have_hole", type="boolean", nullable=false)
	 */
	private $canHaveHole = true;

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
     * @return Technical
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
     * Set filename
     *
     * @param string $filename
     *
     * @return Technical
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
     * Set description
     *
     * @param string $description
     *
     * @return Technical
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
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
    	return $this->file;
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
     *
     * @return string
     */
    public function getWebPath()
    {
    	if($this->filename != "") {
    		return UPLOAD_TECHNICALS_URL . $this->filename;
    	}
    	return "";
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
    
    	if (! file_exists(UPLOAD_TECHNICALS_DIR)) {
    		mkdir(UPLOAD_TECHNICALS_DIR, 0777, true);
    	}
    
    	// we use the original file name here but you should
    	// sanitize it at least to avoid any security issues
    
    	// move takes the target directory and target filename as params
    	$this->getFile()->move(UPLOAD_TECHNICALS_DIR, $this->getFile()->getClientOriginalName());
    
    	// set the path property to the filename where you've saved the file
    	$this->filename = $this->getFile()->getClientOriginalName();
    
    	// clean up the file property as you won't need it anymore
    	$this->setFile(null);
    }
    
    /**
     * @ORM\PrePersist
     */
    public function lifecycleFileUpload()
    {
    	$this->upload();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add matter
     *
     * @param \CSIDBundle\Entity\Matter $matter
     *
     * @return Technical
     */
    public function addMatter(\CSIDBundle\Entity\Matter $matter)
    {
        $this->matters[] = $matter;

        return $this;
    }

    /**
     * Remove matter
     *
     * @param \CSIDBundle\Entity\Matter $matter
     */
    public function removeMatter(\CSIDBundle\Entity\Matter $matter)
    {
        $this->matters->removeElement($matter);
    }

    /**
     * Get matters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatters()
    {
        return $this->matters;
    }

    /**
     * Set blackplate
     *
     * @param boolean $blackplate
     *
     * @return Technical
     */
    public function setBlackplate($blackplate)
    {
        $this->blackplate = $blackplate;

        return $this;
    }

    /**
     * Get blackplate
     *
     * @return boolean
     */
    public function getBlackplate()
    {
        return $this->blackplate;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Technical
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set canHaveHole
     *
     * @param boolean $canHaveHole
     *
     * @return Technical
     */
    public function setCanHaveHole($canHaveHole)
    {
        $this->canHaveHole = $canHaveHole;

        return $this;
    }

    /**
     * Get canHaveHole
     *
     * @return boolean
     */
    public function getCanHaveHole()
    {
        return $this->canHaveHole;
    }
}
