<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MobilierIncendieBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ReductionsRepository extends EntityRepository
{
    function getPrixDegressifProduit($id_produit, $quantite)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('MobilierIncendieBundle:Reductions', 'u')
            ->Where($qb->expr()->between(':quantite', 'u.quantite_min', 'u.quantite_max'))->andWhere('u.quantite_max is not null')
            ->orWhere('u.quantite_max is null')->andWhere('u.quantite_min<=:quantite')
            ->andWhere('u.produits = :id_prod')
            ->orderBy('u.quantite_min', 'ASC')
            ->setParameter('id_prod', $id_produit)
            ->setParameter('quantite', $quantite);
        $query = $qb->getQuery();

        $single = $query->getResult();
        return $single;

    }

    function getPrixDegressifOption($id_option, $quantite)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('MobilierIncendieBundle:Reductions', 'u')
            ->Where($qb->expr()->between(':quantite', 'u.quantite_min', 'u.quantite_max'))->andWhere('u.quantite_max is not null')
            ->orWhere('u.quantite_max is null')->andWhere('u.quantite_min<=:quantite')
            ->andWhere('u.options = :id_prod')
            ->orderBy('u.quantite_min', 'ASC')
            ->setParameter('id_prod', $id_option)
            ->setParameter('quantite', $quantite);
        $query = $qb->getQuery();


        $single = $query->getResult();

        return $single;

    }
    function getPrixDegressifColor($id_color, $quantite)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('MobilierIncendieBundle:Reductions', 'u')
            ->Where($qb->expr()->between(':quantite', 'u.quantite_min', 'u.quantite_max'))->andWhere('u.quantite_max is not null')
            ->orWhere('u.quantite_max is null')->andWhere('u.quantite_min<=:quantite')
            ->andWhere('u.coloris = :id_prod')
            ->orderBy('u.quantite_min', 'ASC')
            ->setParameter('id_prod', $id_color)
            ->setParameter('quantite', $quantite);
        $query = $qb->getQuery();


        $single = $query->getResult();

        return $single;

    }
}
