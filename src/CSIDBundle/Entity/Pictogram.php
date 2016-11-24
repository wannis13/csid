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
 * Pictogram
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Pictogram
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
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;
    
    /**
     * 
     * @var array
     * 
     * @ORM\ManyToMany(targetEntity="PictogramCategory")
     */
    private $categories;
    
    /**
     * @Assert\File( maxSize="10M", mimeTypes={"image/svg+xml"} )
     * 
     * @var UploadedFile
     */
    private $file;

    public function __construct()
    {
    	$this->created = new \DateTime();
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
     * @return Pictogram
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
     * Upload a file
     */
    public function upload()
    {
    	// the file property can be empty if the field is not required
    	if (null === $this->getFile()) {
    		return;
    	}
    
    	// we use the original file name here but you should
    	// sanitize it at least to avoid any security issues
    	
    	// move takes the target directory and target filename as params
    	$this->getFile()->move(UPLOAD_PICTOGRAMS_DIR, $this->getFile()->getClientOriginalName());
    
    	// set the path property to the filename where you've saved the file
    	$this->filename = $this->getFile()->getClientOriginalName();
    
    	// clean up the file property as you won't need it anymore
    	$this->setFile(null);
    	
    	$imagine = new \Imagine\Imagick\Imagine();
    	$imagine->open(UPLOAD_PICTOGRAMS_DIR . $this->filename)->save(UPLOAD_PICTOGRAMS_DIR . $this->filename . '.jpg');
    }
    
    /**
     * @ORM\PrePersist
     */
    public function lifecycleFileUpload()
    {
    	$this->upload();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function refreshUpdated()
    {
    	$this->setUpdated(new \DateTime());
    	$this->upload();
    }
    
    /*public function getSVG()
    {
    	if (file_exists(UPLOAD_PICTOGRAMS_DIR . $this->getId() . '.svg')) {
    		return file_get_contents(UPLOAD_PICTOGRAMS_DIR . $this->getId() . '.svg');
    	}
    	return "";
    }
    
    public function getPng()
    {
    	if (file_exists(UPLOAD_PICTOGRAMS_DIR . $this->getId() . '.png')) {
    		return UPLOAD_PICTOGRAMS_URL . $this->getId() . '.png';
    	}
    	return "";
    }
    
    public function setSVG($svg)
    {
    	if($this->getId() != "") {
    		
    		if (! file_exists(UPLOAD_PICTOGRAMS_DIR)) {
    			mkdir(UPLOAD_PICTOGRAMS_DIR, 0777, true);
    		}
    		
    		if($svg != null) {
    			$svgFile = UPLOAD_PICTOGRAMS_DIR . $this->getId() . '.svg';
    			$pngFile = UPLOAD_PICTOGRAMS_DIR . $this->getId() . '.png';
    			$epsFile = UPLOAD_PICTOGRAMS_DIR . $this->getId() . '.eps';
    			$handle = fopen($svgFile, "w");
    			fwrite($handle, $svg);
    			fclose($handle);
    			
    			$imagine = new \Imagine\Imagick\Imagine();
    			$imagine->open($svgFile)->save($pngFile);
    			
    			//$imagine = new \Imagine\Imagick\Imagine();
    			//$imagine->open($svgFile)->save($epsFile);
    		} else {
    			
    		}
    	}
    }*/

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Pictogram
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
     * 
     * @return string
     */
    public function getJpg()
    {
    	return UPLOAD_PICTOGRAMS_URL . $this->filename . '.jpg';
    }
    
    /**
     *
     * @return string
     */
    public function getWebPath()
    {
    	return UPLOAD_PICTOGRAMS_URL . $this->filename;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Pictogram
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Pictogram
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
     * Add category
     *
     * @param \CSIDBundle\Entity\PictogramCategory $category
     *
     * @return Pictogram
     */
    public function addCategory(\CSIDBundle\Entity\PictogramCategory $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \CSIDBundle\Entity\PictogramCategory $category
     */
    public function removeCategory(\CSIDBundle\Entity\PictogramCategory $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
