<?php

namespace Innova\PathBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Claroline\CoreBundle\Entity\Resource\ResourceNode;

/**
 * Inherited resources
 *
 * @ORM\Table("innova_step_inherited_resources")
 * @ORM\Entity
 */
class InheritedResource
{
    /**
     * Unique identifier
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Depth of the original step of the resource
     * @var integer
     *
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * Step
     * @var \Innova\PathBundle\Entity\Step
     *
     * @ORM\ManyToOne(targetEntity="Innova\PathBundle\Entity\Step", inversedBy="inheritedResources")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $step;

    /**
     * Resource
     * @var \Claroline\CoreBundle\Entity\Resource\ResourceNode
     * @ORM\ManyToOne(targetEntity="Claroline\CoreBundle\Entity\Resource\ResourceNode")
     */
    private $resource;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get level
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set level
     * @param  integer $lvl
     * @return \Innova\PathBundle\Entity\InheritedResource
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get resource
     * @return \Claroline\CoreBundle\Entity\Resource\ResourceNode
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set resource
     * @param \Claroline\CoreBundle\Entity\Resource\ResourceNode $resource
     * @return \Innova\PathBundle\Entity\InheritedResource
     */
    public function setResource(ResourceNode $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get step
     * @return Step
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set step
     * @param Step $step
     * @return \Innova\PathBundle\Entity\InheritedResource
     */
    public function setStep(Step $step = null)
    {
        $this->step = $step;

        return $this;
    }
}
