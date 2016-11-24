<?php
namespace La2MessageBundle\Entity;

use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;

class ThreadMetadata extends BaseThreadMetadata
{
    /**
     * 
     * @var unknown
     */
    protected $id;

    /**
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $participant;
}
