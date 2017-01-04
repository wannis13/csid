<?php
/**
 * Created by PhpStorm.
 * User: wannis
 * Date: 04/01/2017
 * Time: 12:55
 */

namespace MobilierIncendieBundle\EventListener;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class RouterListener
{
    protected $container;

    public function __construct(ContainerInterface $container) // this is @service_container
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $response = new Response();

        $kernel = $event->getKernel();
        $request = $event->getRequest();
        $container = $this->container;
        $name_route = $request->get('_route');
        $session = $request->getSession();
        switch ($name_route) {
            case 'csid_homepage':
                $session->set('menu', 'signalletique-sur-mesure');
                break;

            case 'mobilier_incendie_page_achat':
                $session->set('menu', 'mobilie-incendie');
                break;
            case 'mobilier_incendie_page_presentation':
                $session->set('menu', 'mobilie-incendie');
                break;
                $response->sendHeaders();
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response  = $event->getResponse();
        $request   = $event->getRequest();
        $kernel    = $event->getKernel();
        $container = $this->container;
       $name_route= $request->get('_route');


      /*  switch ($name_route) {
            case 'csid_homepage':
                //$response->setContent('Blah');
               // echo 'csid_homepage';
                $response->headers->setCookie(new Cookie('menu', 'signalletique-sur-mesure'));

                break;

            case 'mobilier_incendie_page_achat':
               // echo 'mobilier_incendie_page_achat';
                $response->headers->setCookie(new Cookie('menu', 'mobilie-incendie'));
                break;
            case 'mobilier_incendie_page_presentation':
               // echo 'mobilier_incendie_page_presentation';
                 $response->headers->setCookie(new Cookie('menu', 'mobilie-incendie'));
                break;



        }*/
    }
}