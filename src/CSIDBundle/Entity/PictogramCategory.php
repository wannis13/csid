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
 * PictogramCategory
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class PictogramCategory
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
     * @ORM\Column(name="filename", type="string", length=100)
     */
    private $filename;
    
    /**
     * @ORM\ManyToMany(targetEntity="Pictogram", mappedBy="categories", cascade={"persist", "merge"})
     */
    private $pictograms;
    
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
     * @return PictogramCategory
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
    
    public function __toString()
    {
    	return $this->getName();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pictograms = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add pictogram
     *
     * @param \CSIDBundle\Entity\Pictogram $pictogram
     *
     * @return PictogramCategory
     */
    public function addPictogram(\CSIDBundle\Entity\Pictogram $pictogram)
    {
        $this->pictograms[] = $pictogram;

        return $this;
    }

    /**
     * Remove pictogram
     *
     * @param \CSIDBundle\Entity\Pictogram $pictogram
     */
    public function removePictogram(\CSIDBundle\Entity\Pictogram $pictogram)
    {
        $this->pictograms->removeElement($pictogram);
    }

    /**
     * Get pictograms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictograms()
    {
        return $this->pictograms;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return PictogramCategory
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
    	
    	if (! file_exists(UPLOAD_PICTOGRAMS_CATEGORIES_DIR)) {
    		mkdir(UPLOAD_PICTOGRAMS_CATEGORIES_DIR, 0777, true);
    	}
    
    	// we use the original file name here but you should
    	// sanitize it at least to avoid any security issues
    
    	// move takes the target directory and target filename as params
    	$this->getFile()->move(UPLOAD_PICTOGRAMS_CATEGORIES_DIR, $this->getFile()->getClientOriginalName());
    
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
     *
     * @return string
     */
    public function getWebPath()
    {
    	if($this->filename != null) {
    		return UPLOAD_PICTOGRAMS_CATEGORIES_URL . $this->filename;
    	} else {
    		return "";
    	}
    }
}
