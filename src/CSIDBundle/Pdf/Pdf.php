<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CSIDBundle\Pdf;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use CSIDBundle\Entity\Order;

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
	
	public function getOrderOutputFromHtml (Order $order)
	{
		$html = $this->templating->render('CSIDBundle:Orders:pdf.html.twig', array(
			'order' => $order
		));
		
		return $this->snappy->getOutputFromHtml($html, array(
			'encoding' => 'utf-8'
		));
	}
}
