<?php

/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace La2UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\MessageBundle\Model\ParticipantInterface;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class User extends BaseUser implements ParticipantInterface
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @Assert\NotBlank(groups={"registration"})
     * @SecurityAssert\UserPassword(
     *     message = "Mot passe non valide"
     * )
     */
    protected $password;
      
    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    public function setPassword($pass)
    {
        $this->password = $pass;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return integer
     */
    public function getPassword()
    {
        return $this->password;
    }
}
