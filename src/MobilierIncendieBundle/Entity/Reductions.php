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
 *  Modele
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Reductions
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
	 * @var integer
	 *
	 * @ORM\Column(type="integer")
	 */
	 private $quantite_min;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer")
	 */
	 private $quantite_max;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
	private $prix_unitaire;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer" )
     */
    private $reduction=0;

	/**
	 * Many Features have One Product.
	 * @ORM\ManyToOne(targetEntity="MobilierIncendieBundle\Entity\Produits", inversedBy="tarifs_degressifs")
	 * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
	 */
	private $produits;
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
     * @return int
     */
    public function getQuantiteMin()
    {
        return $this->quantite_min;
    }

    /**
     * @param int $quantite_min
     */
    public function setQuantiteMin($quantite_min)
    {
        $this->quantite_min = $quantite_min;
    }

    /**
     * @return int
     */
    public function getQuantiteMax()
    {
        return $this->quantite_max;
    }

    /**
     * @param int $quantite_max
     */
    public function setQuantiteMax($quantite_max)
    {
        $this->quantite_max = $quantite_max;
    }

    /**
     * @return float
     */
    public function getPrixUnitaire()
    {
        return $this->prix_unitaire;
    }

    /**
     * @param float $prix_unitaire
     */
    public function setPrixUnitaire($prix_unitaire)
    {
        $this->prix_unitaire = $prix_unitaire;
    }

    /**
     * @return int
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * @param int $reduction
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;
    }
   public function _toString()
   {
      return (string)$this->getId();
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
  public function __toString()
  {
	  return (string)$this->getId();
  }
}
