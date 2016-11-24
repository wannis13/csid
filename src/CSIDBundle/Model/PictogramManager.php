<?php
namespace CSIDBundle\Model;

use CSIDBundle\Entity\Pictogram;
/**
 * 
 * @author Barbara
 *
 */
class PictogramManager
{
	
	/**
	 * Constructor.
	 *
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * Returns an empty pictogram instance
	 *
	 * @return Pictogram
	 */
	public function createPictogram()
	{
		$class = $this->getClass();
		$user = new $class;
	
		return $user;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getClass()
	{
		return $this->class;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function supportsClass($class)
	{
		return $class === $this->getClass();
	}
}