<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Application\Sonata\MediaBundle\ApplicationSonataMediaBundle;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        	new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
        	
        	new FOS\UserBundle\FOSUserBundle(),
        	new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
        	
        	// These are the other bundles the SonataAdminBundle relies on
        	new Sonata\CoreBundle\SonataCoreBundle(),
        	new Sonata\BlockBundle\SonataBlockBundle(),
        	new Sonata\CacheBundle\SonataCacheBundle(),
        	new Sonata\jQueryBundle\SonatajQueryBundle(),
        	new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        	new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
        	
        	new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
        	
        	// And finally, the storage and SonataAdminBundle
        	new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
        	new Sonata\AdminBundle\SonataAdminBundle(),
        	new JMS\SerializerBundle\JMSSerializerBundle(),
        	new Sonata\MediaBundle\SonataMediaBundle(),
        	new Sonata\IntlBundle\SonataIntlBundle(),
        	
        	new Liip\ImagineBundle\LiipImagineBundle(),
        	
        	new FOS\MessageBundle\FOSMessageBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        	
        	new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
        	
            new AppBundle\AppBundle(),
        	new ApplicationSonataMediaBundle(),
            new La2UserBundle\La2UserBundle(),
            new La2MessageBundle\La2MessageBundle(),
            new CSIDBundle\CSIDBundle(),

            new MobilierIncendieBundle\MobilierIncendieBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
