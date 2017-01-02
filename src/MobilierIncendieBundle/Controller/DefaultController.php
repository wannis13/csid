<?php

namespace MobilierIncendieBundle\Controller;

use MobilierIncendieBundle\Entity\OrderDetail;
use MobilierIncendieBundle\Entity\OrderMobilierIncendie;
use MobilierIncendieBundle\Entity\Questions;
use MobilierIncendieBundle\Form\OrderDetailType;
use MobilierIncendieBundle\Form\QuestionsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class DefaultController extends Controller
{
    public function choixDuModeAction()
    {
        if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('MobilierIncendieBundle:Default:choix-mode.html.twig');
    }
        public function choixDuModuleAction($mode)
    {
        if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
           return $this->redirectToRoute('mobilier_incendie_choix_du_mode');
        }
        if($mode=='presentation'){
            return $this->render('MobilierIncendieBundle:Default:choix-module-presentation.html.twig' );
        }
        if($mode=='achat'){
            return $this->render('MobilierIncendieBundle:Default:choix-module-achat.html.twig' );
        }
    
    }
    public function mobilierIncendieAchatAction($cat_id)
    {
        if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('mobilier_incendie_choix_du_mode');
        }
        $em=$this->getDoctrine()->getEntityManager();
        $repo=$em->getRepository('MobilierIncendieBundle:Categorie');
        $categories=$repo->findAll();
        $produits=null;
        if($cat_id==0){

            $cat=$repo->find(1);
            $produits=$cat->getProduits();

            //dump($produits);
            //exit();

        }
        else{
            $cat=$repo->find($cat_id);
            $produits=$cat->getProduits();
        }

        return $this->render('MobilierIncendieBundle:Default:mobilier-incendie-achat.html.twig',
            array('categories'=>$categories ,'produits'=>$produits ,'cat'=>$cat)
            );
    }
    public function mobilierIncendiePresentationAction()
    {
        if (! $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('mobilier_incendie_choix_du_mode');
        }
        return $this->render('MobilierIncendieBundle:Default:mobilier-incendie-presentation.html.twig' );
    }
    public function afficherProduitModeAchatAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getEntityManager();
        $repo=$em->getRepository('MobilierIncendieBundle:Produits');
        $prod=$repo->find($id);
        $question = new Questions();
        $form = $this->createForm(new QuestionsType(), $question ,array('action'=>$this->generateUrl('mobilier_incendie_produit_achat' ,array('id'=>$id) ) ,'attr' =>array('id' =>'form_question')));
        $form= $form->handleRequest($request);
       
        $ligne_order=new OrderDetail();
        $ligne_order_form=new OrderDetailType();
        $json["nom"] = "Dupont";
        $json["prenom"] = "Pierre";
        $json["pays"] = "France";
        $json= json_encode($json);
       $form_order=$this->createForm($ligne_order_form ,$ligne_order ,
           array('action'=>$this->generateUrl('add_produit_panier' ,array('id'=>$id) ) ,'attr' =>array('id' =>'form_ajouter_produit_au_panier')
           ));
       
        if ($form->isValid()) {
              $question= $form->getData();
              $em = $this->getDoctrine()->getManager();
              $question->setProduits($prod);
              $em->persist($question);
              $em->flush();
            $this->addFlash(
                'notice',
                'Votre message a bien été envoyé'
            );
            
            
            

        }

        return $this->render('MobilierIncendieBundle:Default:produit.html.twig' ,array('prod'=>$prod ,'form'=>$form->createView() ,'form_order'=>$form_order->createView() ,'json'=>$json));
    }

    public function afficherProduitModePresentationAction($id)
    {

        return $this->render('MobilierIncendieBundle:Default:produit.html.twig' );
    }
    public function panierAction()
    {

        return $this->render('MobilierIncendieBundle:Default:panier.html.twig' );
    }
    public function ajouterProduitPanierAction()
    {
           $em=$this->getDoctrine()->getManager();
           $order=new OrderMobilierIncendie();
           $order->setAmount(20);
           $order->setAmountVAT(30);

            $em->persist($order);
            $em->flush();

        return $this->render('MobilierIncendieBundle:Default:panier.html.twig' );
    }
}
