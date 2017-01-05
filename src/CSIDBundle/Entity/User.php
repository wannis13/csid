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
use La2UserBundle\Entity\User as baseUser;

/**
 * User
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class User extends baseUser
{	
	/**
	 *
	 * @ORM\Column(name="address", type="string", length=100, nullable=true)
	 */
	protected $address;
	
	/**
	 *
	 * @ORM\Column(name="city", type="string", length=50, nullable=true)
	 */
	protected $city;
	
	/**
	 *
	 * @ORM\Column(name="postal_code", type="string", length=10, nullable=true)
	 */
	protected $postalCode;
	
	/**
	 *
	 * @ORM\Column(name="siren", type="string", length=13, nullable=true)
	 */
	protected $siren;
	
	/**
	 *
	 * @ORM\Column(name="company", type="string", length=30, nullable=true)
	 */
	protected $company;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="margin_percent", type="decimal", precision=5, scale=2, nullable=true)
	 * @Assert\Range(
	 *     min = 0,
	 *     minMessage = "Min % is 0",
	 * )
	 */
	protected $marginPercent = 0;
	
	/**
	 * @ORM\Column(name="command_no", type="string", length=11, nullable=true)
	 */
	protected $commandNo = "";
	
	/**
	 * @var \CSIDBundle\Entity\User
	 * 
	 * @ORM\ManyToOne(targetEntity="CSIDBundle\Entity\User", cascade={"persist"})
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="retailer", referencedColumnName="id")
	 * })
	 */
	protected $retailer;
	
	/**
	 * @var \Application\Sonata\MediaBundle\Entity\Media
	 * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="logo", referencedColumnName="id")
	 * })
	 */
	protected $logo;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="tva", type="decimal", precision=5, scale=2, nullable=true)
	 * @Assert\Range(
	 * 		min = 0,
	 * 		max = 100,
	 *     	minMessage = "Min % is 0",
	 *      maxMessage = "Max % is 100"
	 * )
	 */
	protected $tva = 20;
	
	/**
	 * @ORM\OneToMany(targetEntity="CSIDBundle\Entity\Order", mappedBy="user", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
	 */
	protected $orders;


    /**
     * @ORM\OneToMany(targetEntity="MobilierIncendieBundle\Entity\Adress", mappedBy="user", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     */
   // protected $adressLivraisonFacturation;

    /**
     * @ORM\OneToOne(targetEntity="MobilierIncendieBundle\Entity\Adress")
     * @@ORM\JoinColumn(name="adresse_livraison_id", referencedColumnName="id")
     */
    protected $adressLivraison;

    /**
     * @ORM\OneToOne(targetEntity="MobilierIncendieBundle\Entity\Adress")
     * @@ORM\JoinColumn(name="adresse_facturation_id", referencedColumnName="id")
     */
    protected $adressFacturation;




    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return User
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set siren
     *
     * @param string $siren
     *
     * @return User
     */
    public function setSiren($siren)
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * Get siren
     *
     * @return string
     */
    public function getSiren()
    {
        return $this->siren;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set commandNo
     *
     * @param integer $commandNo
     *
     * @return User
     */
    public function setCommandNo($commandNo)
    {
        $this->commandNo = $commandNo;

        return $this;
    }

    /**
     * Get commandNo
     *
     * @return integer
     */
    public function getCommandNo()
    {
        return $this->commandNo;
    }

    /**
     * Set retailer
     *
     * @param \CSIDBundle\Entity\User $retailer
     *
     * @return User
     */
    public function setRetailer(\CSIDBundle\Entity\User $retailer = null)
    {
        $this->retailer = $retailer;

        return $this;
    }

    /**
     * Get retailer
     *
     * @return \CSIDBundle\Entity\User
     */
    public function getRetailer()
    {
        return $this->retailer;
    }

    /**
     * Set logo
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $logo
     *
     * @return User
     */
    public function setLogo(\Application\Sonata\MediaBundle\Entity\Media $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Add order
     *
     * @param \CSIDBundle\Entity\Order $order
     *
     * @return User
     */
    public function addOrder(\CSIDBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \CSIDBundle\Entity\Order $order
     */
    public function removeOrder(\CSIDBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set marginPercent
     *
     * @param string $marginPercent
     *
     * @return User
     */
    public function setMarginPercent($marginPercent)
    {
        $this->marginPercent = $marginPercent;

        return $this;
    }

    /**
     * Get marginPercent
     *
     * @return string
     */
    public function getMarginPercent()
    {
        return $this->marginPercent;
    }

    /**
     * Set tva
     *
     * @param string $tva
     *
     * @return User
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return string
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @return mixed
     */
    public function getAdressLivraison()
    {
        return $this->adressLivraison;
    }

    /**
     * @param mixed $adressLivraison
     */
    public function setAdressLivraison($adressLivraison)
    {
        $this->adressLivraison = $adressLivraison;
    }

    /**
     * @return mixed
     */
    public function getAdressFacturation()
    {
        return $this->adressFacturation;
    }

    /**
     * @param mixed $adressFacturation
     */
    public function setAdressFacturation($adressFacturation)
    {
        $this->adressFacturation = $adressFacturation;
    }
    
}
