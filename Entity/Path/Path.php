<?php

namespace Innova\PathBundle\Entity\Path;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Innova\PathBundle\Entity\Step;

/**
 * Path
 *
 * @ORM\Table(name="innova_path")
 * @ORM\Entity(repositoryClass="Innova\PathBundle\Repository\PathRepository")
 */
class Path extends AbstractResource implements PathInterface
{   
    /**
     * Name of the path (only for forms)
     * @var string
     * 
     * @Assert\NotBlank
     */
    protected $name;
    
    /**
     * JSON structure of the path
     * @var string
     *
     * @ORM\Column(name="structure", type="text")
     */
    protected $structure;

    /**
     * Steps linked to the path
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Innova\PathBundle\Entity\Step", mappedBy="path", indexBy="id", cascade={"persist", "remove"})
     * @ORM\OrderBy({"order" = "ASC"})
     */
    protected $steps;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @var boolean
     *
     * @ORM\Column(name="modified", type="boolean")
     */
    protected $modified;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;
    
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->published = false;
        $this->modified = false;
    }
    
    /**
     * Set json structure
     * @param  string $structure
     * @return \Innova\PathBundle\Entity\Path\Path
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get JSON structure
     * @return string
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set published
     * @param  boolean $published
     * @return \Innova\PathBundle\Entity\Path\Path
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Is path already published
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * Set modified
     * @param  boolean $modified
     * @return \Innova\PathBundle\Entity\Path\Path
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Is path modified since the last deployment
     * @return boolean
     */
    public function isModified()
    {
        return $this->modified;
    }

    /**
     * Add step
     * @param  \Innova\PathBundle\Entity\Step $step
     * @return \Innova\PathBundle\Entity\Path\Path
     */
    public function addStep(Step $step)
    {
        $this->steps->set($step->getId(), $step);
        $step->setPath($this);
        
        return $this;
    }

    /**
     * Remove step
     * @param \Innova\PathBundle\Entity\Step $step
     * @return \Innova\PathBundle\Entity\Path\Path
     */
    public function removeStep(Step $step)
    {
        $this->steps->removeElement($step);
        $step->setPath(null);
        
        return $this;
    }

    /**
     * Get steps
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set description
     * @param string $description
     * @return \Innova\PathBundle\Entity\Path\Path
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * Get root step of the path
     * @throws \Exception
     * @return \Innova\PathBundle\Entity\Step
     */
    public function getRootStep()
    {
        if (empty($this->steps)) {
            // Current path has no step
            throw new \Exception('Unable to find root Step for ' . get_class($this) . ' (ID = ' . $this->id . '). Path has no step.');
        }
        
        $root = null;
        foreach ($this->steps as $step) {
            if (null === $step->getParent()) {
                // Root step found
                $root = $step;
                break;
            }
        }
        
        if (empty($root)) {
            // Unable to find root step in steps list
            throw new \Exception('Unable to find root Step for ' . get_class($this) . ' (ID = ' . $this->id . ').');
        }
        
        return $root;
    }
    
    /**
     * Initialize a new path entity with required info and structure
     * @param string $name
     * @return \Innova\PathBundle\Entity\Path\Path
     */
    public static function initialize($name = null)
    {
        $path = new Path();
        $path->setName($name);

        // Init path structure
        $path->initializeStructure();
    
        return $path;
    }

    /**
     * Initialize JSON structure
     * @param array $steps
     * @return \Innova\PathBundle\Entity\Path\Path
     */
    public function initializeStructure(array $steps = array())
    {
        $structure = array (
            'name' => $this->getName(),
            'description' => $this->getDescription()
        );
        
        if (!empty($steps)) {
            // Rename first step to give it the same name as the Path
            if (!empty($steps[0])) {
                $steps[0]['name'] = $this->getName();
            }

            $structure['steps'] = $steps;
        } else {
            $structure['steps'] = array (
                array (
                    'id'         => 1,
                    'lvl'        => 0,
                    'resourceId' => null,
                    'name'       => $this->getName(),
                    'withTutor'  => false,
                    'children'   => $steps,
                ),
            );
        }
    
        $this->setStructure(json_encode($structure));
        
        return $this;
    }

    /**
     * Update JSON structure from Path data
     */
    public function updateStructure()
    {
        $structure = array (
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'steps' => array (),
        );

        foreach ($this->steps as $step) {

        }

        /*$this->setStructure(json_encode($structure));*/

        return $this;
    }

    /**
     * Wrapper to access workspace of the Path
     * @return \Claroline\CoreBundle\Entity\Workspace\Workspace
     */
    public function getWorkspace()
    {
        $workspace = null;
        if (!empty($this->resourceNode)) {
            $workspace = $this->resourceNode->getWorkspace();
        }

        return $workspace;
    }

    /**
     * Wrapper to access creator of the Path
     * @return \Claroline\CoreBundle\Entity\User
     */
    public function getCreator()
    {
        $creator = null;
        if (!empty($this->resourceNode)) {
            $creator = $this->resourceNode->getCreator();
        }

        return $creator;
    }
}
