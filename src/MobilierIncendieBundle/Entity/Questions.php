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
class Questions
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
	 * @ORM\Column(name="nom", type="string", length=50)
	 */
	private $nom;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="prenom", type="string", length=50)
	 */
	private $prenom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=50)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="message", type="text")
	 */
	private $message;
	/**
	 * Many Features have One Product.
	 * @ORM\ManyToOne(targetEntity="MobilierIncendieBundle\Entity\Produits")
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
	public function getNom()
	{
		return $this->nom;
	}

	/**
	 * @param string $nom
	 */
	public function setNom($nom)
	{
		$this->nom = $nom;
	}

	/**
	 * @return string
	 */
	public function getPrenom()
	{
		return $this->prenom;
	}

	/**
	 * @param string $prenom
	 */
	public function setPrenom($prenom)
	{
		$this->prenom = $prenom;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message)
	{
		$this->message = $message;
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
