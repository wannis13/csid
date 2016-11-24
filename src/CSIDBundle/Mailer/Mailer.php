<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CSIDBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use CSIDBundle\Entity\Order;
use CSIDBundle\Pdf\Pdf;

class Mailer
{
	/**
	 * 
	 * @var Swift_Mailer
	 */
    protected $mailer;
    /**
     * 
     * @var RouterInterface
     */
    protected $router;
    /**
     * 
     * @var EngineInterface
     */
    protected $templating;
    
    /**
     * 
     * @var Pdf
     */
    protected $pdf;

    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, $pdf)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->pdf = $pdf;
    }
    
    /**
     * 
     * @param Order $order
     * @param array $data
     */
    public function sendOrder($order, $data)
    {
    	$retailer = $order->getUser();
    	
    	$recipients = $data['recipients'];
    	
    	$rendered = $this->templating->render('CSIDBundle:Mailer:send-order.html.twig', array(
    		'order' => $order,
    		'data' => $data
    	));
    	
    	$pdf = $this->pdf->getOrderOutputFromHtml($order);
    	
    	$attachment = \Swift_Attachment::newInstance($pdf, $order->getStatus().'-'.$order->getUser()->getCommandNo().$order->getNumber().'.pdf', 'application/pdf');
    	
    	return $this->sendEmailMessage($rendered, $retailer->getEmail(), $recipients, $attachment);
    }

    /**
     * @param string $renderedTemplate
     * @param string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail, $attachment = null)
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body);
        
       	if($attachment != null) {
       		$message->attach($attachment);
       	}
		
        return $this->mailer->send($message);
    }
}
