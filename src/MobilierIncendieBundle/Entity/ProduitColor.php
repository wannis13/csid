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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MatterColor
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ProduitColor
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
     * @ORM\Column(name="code_color", type="string", length=50)
     */
    private $code_color;

    /**
     * @Assert\Image(minWidth = 100, maxWidth = 600)
     *
     * @var UploadedFile
     */
    private $file;


    /**
     * @var string
     *
     * @ORM\Column(name="original_image", type="string", length=100 ,nullable=true)
     */
    private $originalImage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;



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
     * @return ProduitColor
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

        if (!file_exists(UPLOAD_IMAGE_COLOR_DIR)) {
            mkdir(UPLOAD_IMAGE_COLOR_DIR, 0777, true);
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and target filename as params
        $this->getFile()->move(UPLOAD_IMAGE_COLOR_DIR, $this->getFile()->getClientOriginalName());

        // set the path property to the filename where you've saved the file
        $this->originalImage = $this->getFile()->getClientOriginalName();

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
        if ($this->originalImage != null) {
            return UPLOAD_IMAGE_COLOR . $this->originalImage;
        }
        return "";
    }

    /**
     * @return mixed
     */
    public function getCodeColor()
    {
        return $this->code_color;
    }

    /**
     * @param mixed $code_color
     */
    public function setCodeColor($code_color)
    {
        $this->code_color = $code_color;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }  


}
