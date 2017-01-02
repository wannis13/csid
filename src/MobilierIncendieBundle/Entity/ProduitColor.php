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
     * @var float
     *
     * @ORM\Column(name="prix", type="float" ,nullable=true)
     *
     */
    private $prix=0;


/*Option coloris RAL personnalisé : RAL à indiquer suivant palette RAL classique téléchargeable en .pdf / accessible uniquement à partir de 25 unités : +15E H.T par unité.*/
    /**
     * @var float
     *
     * @ORM\Column(name="quanitite_min", type="integer" ,nullable=true)
     *
     */
    private $quanitite_min=1;


    /**
     * @var string
     *
     * @ORM\Column(name="code_color", type="string", length=50 ,nullable=true)
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
     *
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\Reductions", mappedBy="coloris" , cascade={"persist", "remove"})
     */
    private $tarifs_degressifs;
    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="MobilierIncendieBundle\Entity\Produits", inversedBy="coloris")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produits;

    public function __construct()
    {
        $this->tarifs_degressifs = new \Doctrine\Common\Collections\ArrayCollection();

    }
    public function addTarifsDegressif(\MobilierIncendieBundle\Entity\Reductions $tarifs_degressifs)
    {

        $this->tarifs_degressifs[] = $tarifs_degressifs;

        return $this;
    }
    public function removeTarifsDegressif(\MobilierIncendieBundle\Entity\Reductions $tarifs_degressifs)
    {
        $this->tarifs_degressifs->removeElement($tarifs_degressifs);
    }
    /**
     * @return mixed
     */
    public function getTarifsDegressifs()
    {
        return $this->tarifs_degressifs;
    }

    /**
     * @param mixed $tarifs_degressifs
     */
    public function setTarifsDegressifs($tarifs_degressifs)
    {
        // $this->tarifs_degressifs = $tarifs_degressifs;

        if (count($tarifs_degressifs) > 0) {
            foreach ($tarifs_degressifs as $i) {
                $this->addTarifsDegressif($i);
                $i->setColoris($this);
               $i->setPrixUnitaire($this->getPrix());
            }
        }
        return $this;
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
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
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

    /**
     * @return mixed
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @param mixed $produits
     */
    public function setProduits($produits)
    {
        $this->produits = $produits;
    }

    /**
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return float
     */
    public function getQuanititeMin()
    {
        return $this->quanitite_min;
    }

    /**
     * @param float $quanitite_min
     */
    public function setQuanititeMin($quanitite_min)
    {
        $this->quanitite_min = $quanitite_min;
    }

}
