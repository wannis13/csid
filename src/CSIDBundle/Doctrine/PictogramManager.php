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
use CSIDBundle\Model\PictogramManager as BasePictogramManager;

class PictogramManager extends BasePictogramManager
{
	/**
	 * 
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $objectManager;
	
	/**
	 * 
	 * @var string
	 */
	protected $class;
	
	/**
	 * 
	 * @var \Doctrine\ORM\EntityRepository
	 */
	protected $repository;
	
	/**
	 * constructor 
	 * 
	 * @param ObjectManager $om
	 * @param unknown $class
	 */
	public function __construct(ObjectManager $om, $class)
	{
		parent::__construct();

        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);
        
        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
	}
	
	/**
	 * list pictograms
	 */
	public function findPictograms($searchData = array())
	{
		return $this->repository->findAll();
	}
}