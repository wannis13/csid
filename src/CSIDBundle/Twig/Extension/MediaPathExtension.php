<?php

namespace CSIDBundle\Twig\Extension;

use Sonata\CoreBundle\Model\ManagerInterface;
use Symfony\Component\DependencyInjection\Container;

class MediaPathExtension extends \Twig_Extension
{

    /**
     *
     * @var type Container
     */
    protected $container;

    /**
     *
     * @var type ManagerInterface
     */
    protected $mediaManager;

    public function __construct(Container $container, $mediaManager)
    {
        $this->container = $container;
        $this->mediaManager = $mediaManager;
    }

    public function getFunctions()
    {
        return array
        (
            'media_public_url' => new \Twig_Function_Method($this, 'getMediaPublicUrl')
        );
    }

    /**
     * @param mixed $media
     *
     * @return null|\Sonata\MediaBundle\Model\MediaInterface
     */
    private function getMedia($media)
    {
        $media = $this->mediaManager->findOneBy(array(
            'id' => $media
        ));

        return $media;
    }

    public function getMediaPublicUrl($media, $format)
    {
        $media = $this->getMedia($media);

        $provider = $this->container->get($media->getProviderName());

        return $provider->generatePublicUrl($media, $format);
    }

    public function getName()
    {
        return 'SocialbitSonataMediaTwigExtensionBundleMediaPathExtension';
    }
}
?>