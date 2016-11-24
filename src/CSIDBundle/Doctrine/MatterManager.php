<?php
/**
 * This file is part of the CSID project.
 *
 * (c) Barbara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CSIDBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;

class MatterManager
{
	protected $objectManager;
	protected $class;
	protected $repository;
	
	/**
	 * constructor 
	 * 
	 * @param ObjectManager $om
	 * @param unknown $class
	 */
	public function __construct(ObjectManager $om, $class)
	{
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
	}
	
	/**
	 * list matters
	 */
	public function findMatters()
	{
		return $this->repository->findAll();
	}
}