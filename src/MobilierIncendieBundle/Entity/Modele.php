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
class Modele
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
     * @ORM\Column(name="prix", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
	private $prix;

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


	public  function __toString()
	{
		return $this->getName();
	}


}
