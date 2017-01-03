<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MobilierIncendieBundle\Pdf;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use MobilierIncendieBundle\Entity\OrderMobilierIncendie;

class Pdf
{

	/**
	 *
	 * @var \Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator
	 */
	protected $snappy;

	/**
	 *
	 * @var EngineInterface
	 */
	protected $templating;

	/**
	 * 
	 * @param \Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator $snappy
	 * @param EngineInterface $templating
	 */
	public function __construct ($snappy, EngineInterface $templating)
	{
		$this->snappy = $snappy;
		$this->templating = $templating;
	}
	
	public function getOrderOutputFromHtml (OrderMobilierIncendie $order ,$nomAffaire=null)
	{
		$html = $this->templating->render('MobilierIncendieBundle:panier:pdf.html.twig', array(
			'order' => $order,
			'nomAffaire'=>$nomAffaire
		));
		
		return $this->snappy->getOutputFromHtml($html, array(
			'encoding' => 'utf-8'
		));
	}
}
