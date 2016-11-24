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
 * MatterColor
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class MatterColor
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
     * @Assert\Image(minWidth = 100, maxWidth = 600)
     * 
     * @var UploadedFile
     */
    private $file;
    
    /**
     * @Assert\Image(minWidth = 100, maxWidth = 600)
     *
     * @var UploadedFile
     */
    private $darkFile;
	
    /**
     * @var string
     *
     * @ORM\Column(name="original_image", type="string", length=100)
     */
    private $originalImage;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dark_image", type="string", length=100)
     */
    private $darkImage;
    
    /**
     *
     * @var Matter
     *
     * @ORM\ManyToOne(targetEntity="Matter")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="matter", referencedColumnName="id")
     * })
     */
    private $matter;

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
     * 
     * @return string
     */
    public function __toString()
    {
    	return $this->name;
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
     * Get file.
     *
     * @return UploadedFile
     */
    public function getDarkFile()
    {
    	return $this->darkFile;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setDarkFile(UploadedFile $file = null)
    {
    	$this->darkFile = $file;
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
    	 
    	// move takes the target directory and target filename as params
    	$this->getFile()->move(UPLOAD_MATTERS_DIR, $this->getFile()->getClientOriginalName());
    
    	// set the path property to the filename where you've saved the file
    	$this->originalImage = $this->getFile()->getClientOriginalName();
    
    	// clean up the file property as you won't need it anymore
    	$this->setFile(null);
    }
    
    public function uploadDarkImage()
    {
    	// the file property can be empty if the field is not required
    	if (null === $this->getDarkFile()) {
    		return;
    	}
    	 
    	if (! file_exists(UPLOAD_MATTERS_DIR)) {
    		mkdir(UPLOAD_MATTERS_DIR, 0777, true);
    	}
    
    	// we use the original file name here but you should
    	// sanitize it at least to avoid any security issues
    
    	// move takes the target directory and target filename as params
    	$this->getDarkFile()->move(UPLOAD_MATTERS_DIR, $this->getDarkFile()->getClientOriginalName());
    
    	// set the path property to the filename where you've saved the file
    	$this->darkImage = $this->getDarkFile()->getClientOriginalName();
    
    	// clean up the file property as you won't need it anymore
    	$this->setDarkFile(null);
    }
    
    /**
     * @ORM\PrePersist
     */
    public function lifecycleFileUpload()
    {
    	$this->upload();
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return MatterColor
     */
    public function setOriginalImage($originalImage)
    {
        $this->originalImage = $originalImage;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getOriginalImage()
    {
        return $this->originalImage;
    }
    
    /**
     * 
     * @return string
     */
    public function getWebPath()
    {
    	if($this->originalImage != null) {
    		return UPLOAD_MATTERS_URL . $this->originalImage;
    	}
    	return "";
    }
    
    /**
     *
     * @return string
     */
    public function getDarkImagePath()
    {
    	if($this->darkImage != null) {
    		return UPLOAD_MATTERS_URL . $this->darkImage;
    	}
    	return "";
    }

    /**
     * Set matter
     *
     * @param \CSIDBundle\Entity\Matter $matter
     *
     * @return MatterColor
     */
    public function setMatter(\CSIDBundle\Entity\Matter $matter = null)
    {
        $this->matter = $matter;

        return $this;
    }

    /**
     * Get matter
     *
     * @return \CSIDBundle\Entity\Matter
     */
    public function getMatter()
    {
        return $this->matter;
    }

    /**
     * Set darkImage
     *
     * @param string $darkImage
     *
     * @return MatterColor
     */
    public function setDarkImage($darkImage)
    {
        $this->darkImage = $darkImage;

        return $this;
    }

    /**
     * Get darkImage
     *
     * @return string
     */
    public function getDarkImage()
    {
        return $this->darkImage;
    }
}
