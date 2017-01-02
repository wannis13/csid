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
use Doctrine\Common\Collections\ArrayCollection;


/**
 *  Modele
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Produits
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;


    /**
     * @var integer
     *
     * @ORM\Column(type="integer" ,nullable=true)
     */
    private $Hauteur;
    /**
     * @var string
     *
     * @ORM\Column(type="integer" ,nullable=true)
     */
    private $Largeur;
    /**
     * @var string
     *
     * @ORM\Column(type="integer" ,nullable=true)
     */
    private $Profondeur;
    /**
     * @var string
     *
     * @ORM\Column(type="integer" ,nullable=true)
     */
    private $Poids;

    /**
     * @var string
     *
     * @ORM\Column(type="string" ,length=100)
     */
    private $reference;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getHauteur()
    {
        return $this->Hauteur;
    }

    /**
     * @param int $Hauteur
     */
    public function setHauteur($Hauteur)
    {
        $this->Hauteur = $Hauteur;
    }

    /**
     * @return string
     */
    public function getLargeur()
    {
        return $this->Largeur;
    }

    /**
     * @param string $Largeur
     */
    public function setLargeur($Largeur)
    {
        $this->Largeur = $Largeur;
    }

    /**
     * @return string
     */
    public function getProfondeur()
    {
        return $this->Profondeur;
    }

    /**
     * @param string $Profondeur
     */
    public function setProfondeur($Profondeur)
    {
        $this->Profondeur = $Profondeur;
    }

    /**
     * @return string
     */
    public function getPoids()
    {
        return $this->Poids;
    }

    /**
     * @param string $Poids
     */
    public function setPoids($Poids)
    {
        $this->Poids = $Poids;
    }

    /**
     *
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\ProduitColor" ,mappedBy="produits" , cascade={"persist", "remove"})
     *
     */
    private $coloris;


    /**
     *
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\Options", mappedBy="produits" , cascade={"persist", "remove"})
     */
    private $options;



    /**
     *
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\Modele", mappedBy="produits" , cascade={"persist", "remove"})
     */
    private $versions;

    /**
     *
     * @ORM\ManyToOne(targetEntity="MobilierIncendieBundle\Entity\Categorie", inversedBy="produits")
     * @ORM\joinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categories;

    public function __construct()
    {
        $this->coloris = new \Doctrine\Common\Collections\ArrayCollection();
        $this->versions = new \Doctrine\Common\Collections\ArrayCollection();
       // $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tarifs_degressifs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tarifs_clints = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tarifs_livraison = new \Doctrine\Common\Collections\ArrayCollection();
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getColoris()
    {
        return $this->coloris;
    }

    /**
     * @param mixed $coloris
     */
    public function setColoris($coloris)
    {
        $this->coloris = $coloris;
    }

    /**
     * @return mixed
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * @param mixed $versions
     */
    public function setVersions($versions)
    {
        //$this->versions = $versions;

        if (count($versions) > 0) {
            foreach ($versions as $i) {
                $this->addVersion($i);
                $i->setProduits($this);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }


    /**
     *
     * @ORM\ManyToMany(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     *
     * @ORM\JoinTable(name="images_produits",
     *      joinColumns={@ORM\JoinColumn(name="produit_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")}
     *      )
     *
     */
    private $images;



    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="plaquette_id", referencedColumnName="id")
     */


    private $plaquette_pdf;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="tarifs_distributeurs_pdf_id", referencedColumnName="id")
     */
    private $tarifs_distributeurs_pdf;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="code_ral_pdf_id", referencedColumnName="id")
     */


    private $code_ral_pdf;
    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="dossier_zip_image_id", referencedColumnName="id")
     */
    private $dossier_zip_image;


    /**
     *
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\Reductions", mappedBy="produits" , cascade={"persist", "remove"})
     */
    private $tarifs_degressifs;


    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\TarifClientSpecial", mappedBy="produits" , cascade={"persist", "remove"})
     */
    private $tarifs_clints;

    /**
     *
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\TarifsLivraison", mappedBy="produits" , cascade={"persist", "remove"})
     */
    private $tarifs_livraison;

    /**
     *
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\TarifsLivraisonParClient", mappedBy="produits" , cascade={"persist", "remove"})
     */
    private $tarifs_livraison_par_client;


    /**
     * @var float
     *
     * @ORM\Column(name="prix_achat", type="float")
     *
     * )
     */
    private $prix_achat;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_de_vente", type="float")
     *
     * )
     */
    private $prix;


    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    public function addImage(\Application\Sonata\MediaBundle\Entity\Media $images)
    {

        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media
     */
    public function removeImage(\Application\Sonata\MediaBundle\Entity\Media $images)
    {
        $this->images->removeElement($images);
    }

    public function addTarifsDegressif(\MobilierIncendieBundle\Entity\Reductions $tarifs_degressifs)
    {

        $this->tarifs_degressifs[] = $tarifs_degressifs;

        return $this;
    }

    public function addTarifsClint(\MobilierIncendieBundle\Entity\TarifClientSpecial $tarifs_clints)
    {

        $this->tarifs_clints[] = $tarifs_clints;

        return $this;
    }

    public function addTarifslivraison(\MobilierIncendieBundle\Entity\TarifsLivraison $tarifs_livraison)
    {
        $this->tarifs_livraison[] = $tarifs_livraison;
        return $this;
    }
    public function addTarifslivraisonParClient(\MobilierIncendieBundle\Entity\TarifsLivraisonParClient $tarifs_livraison_par_client)
    {
        $this->tarifs_livraison_par_client[] = $tarifs_livraison_par_client;
        return $this;
    }
    public function addOption(\MobilierIncendieBundle\Entity\Options $options)
    {
        $this->options[] = $options;
        return $this;
    }
    public function addVersion(\MobilierIncendieBundle\Entity\Modele $versions)
    {
        $this->versions[] = $versions;
        return $this;
    }

    public function removeTarifsDegressif(\MobilierIncendieBundle\Entity\Reductions $tarifs_degressifs)
    {
        $this->tarifs_degressifs->removeElement($tarifs_degressifs);
    }

    public function removeTarifsClints(\MobilierIncendieBundle\Entity\TarifClientSpecial $tarifs_clints)
    {
        $this->tarifs_clints->removeElement($tarifs_clints);
    }

    public function removeTarifslivraison(\MobilierIncendieBundle\Entity\TarifsLivraison $tarifs_livraison)
    {
        $this->tarifs_livraison->removeElement($tarifs_livraison);
    }
    public function removeOptions(\MobilierIncendieBundle\Entity\Options $options)
    {
        $this->options->removeElement($options);
    }
    public function removeVersions(\MobilierIncendieBundle\Entity\Modele $options)
    {
        $this->options->removeElement($options);
    }

    /**
     * @return mixed
     */
    public function getPlaquettePdf()
    {
        return $this->plaquette_pdf;
    }

    /**
     * @param mixed $plaquette_pdf
     */
    public function setPlaquettePdf($plaquette_pdf)
    {
        $this->plaquette_pdf = $plaquette_pdf;
    }

    /**
     * @return mixed
     */
    public function getCodeRalPdf()
    {
        return $this->code_ral_pdf;
    }

    /**
     * @param mixed $code_ral_pdf
     */
    public function setCodeRalPdf($code_ral_pdf)
    {
        $this->code_ral_pdf = $code_ral_pdf;
    }

    /**
     * @return mixed
     */
    public function getDossierZipImage()
    {
        return $this->dossier_zip_image;
    }

    /**
     * @param mixed $dossier_zip_image
     */
    public function setDossierZipImage($dossier_zip_image)
    {
        $this->dossier_zip_image = $dossier_zip_image;
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
                $i->setProduits($this);
                $i->setPrixUnitaire($this->getPrix());
                $this->addTarifsDegressif($i);

            }
        }

        return $this;


    }

    /**
     * @return mixed
     */
    public function getTarifsClints()
    {
        return $this->tarifs_clints;
    }

    /**
     * @param mixed $tarifs_clints
     */
    public function setTarifsClints($tarifs_clints)
    {
        //$this->tarifs_clints = $tarifs_clints;

        if (count($tarifs_clints) > 0) {

            foreach ($tarifs_clints as $i) {

                $this->addTarifsClint($i);
                $i->setProduits($this);
            }
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getPrixAchat()
    {
        return $this->prix_achat;
    }

    /**
     * @param float $prix_achat
     */
    public function setPrixAchat($prix_achat)
    {
        $this->prix_achat = $prix_achat;
    }

    /**
     * @return mixed
     */
    public function getTarifsLivraison()
    {
        return $this->tarifs_livraison;
    }

    /**
     * @param mixed $tarifs_livraison
     */
    public function setTarifsLivraison($tarifs_livraison)
    {
        // $this->tarifs_livraison = $tarifs_livraison;
        if (count($tarifs_livraison) > 0) {
            foreach ($tarifs_livraison as $i) {
                $this->addTarifslivraison($i);
                $i->setProduits($this);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTarifsLivraisonParClient()
    {
        return $this->tarifs_livraison_par_client;
    }

    /**
     * @param mixed $tarifs_livraison_par_client
     */
    public function setTarifsLivraisonParClient($tarifs_livraison_par_client)
    {
        //$this->tarifs_livraison_par_client = $tarifs_livraison_par_client;
        if (count($tarifs_livraison_par_client) > 0) {
            foreach ($tarifs_livraison_par_client as $i) {
                $this->addTarifslivraisonParClient($i);
                $i->setProduits($this);

            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        //$this->options = $options;
        if (count($options) > 0) {
            foreach ($options as $i) {
                $this->addOption($i);
                $i->setProduits($this);
            }
        }

        return $this;
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
     * @return mixed
     */
    public function getTarifsDistributeursPdf()
    {
        return $this->tarifs_distributeurs_pdf;
    }

    /**
     * @param mixed $tarifs_distributeurs_pdf
     */
    public function setTarifsDistributeursPdf($tarifs_distributeurs_pdf)
    {
        $this->tarifs_distributeurs_pdf = $tarifs_distributeurs_pdf;
    }


    public function _toString()
    {
        return (string)$this->getName();  
    }
}
