<?php
namespace La2MessageBundle\Entity;

use FOS\MessageBundle\Entity\Thread as BaseThread;

class Thread extends BaseThread
{
    /**
     * 
     * @var unknown
     */
    protected $id;

    /**
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $createdBy;

    /**
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * Remove message
     *
     * @param \La2MessageBundle\Entity\Message $message
     */
    public function removeMessage(\La2MessageBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Add metadatum
     *
     * @param \La2MessageBundle\Entity\ThreadMetadata $metadatum
     *
     * @return Thread
     */
    public function addMetadatum(\La2MessageBundle\Entity\ThreadMetadata $metadatum)
    {
        $this->metadata[] = $metadatum;

        return $this;
    }

    /**
     * Remove metadatum
     *
     * @param \La2MessageBundle\Entity\ThreadMetadata $metadatum
     */
    public function removeMetadatum(\La2MessageBundle\Entity\ThreadMetadata $metadatum)
    {
        $this->metadata->removeElement($metadatum);
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
