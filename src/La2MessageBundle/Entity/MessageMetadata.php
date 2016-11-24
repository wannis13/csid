<?php

namespace La2MessageBundle\Entity;

use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;

class MessageMetadata extends BaseMessageMetadata
{
    /**
     * 
     * @var unknown
     */
    protected $id;

    /**
     * @var \FOS\MessageBundle\Model\MessageInterface
     */
    protected $message;

    /**
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $participant;
}
