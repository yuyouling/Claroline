<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CursusBundle\Entity;

use Claroline\CoreBundle\Entity\Workspace\Workspace;
use Claroline\CursusBundle\Entity\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Claroline\CursusBundle\Repository\CursusRepository")
 * @ORM\Table(name="claro_cursusbundle_cursus")
 * @Gedmo\Tree(type="nested")
 * @DoctrineAssert\UniqueEntity("code")
 */
class Cursus
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api"})
     */
    protected $id;
    
    /**
     * @ORM\Column(unique=true, nullable=true)
     * @Groups({"api"})
     * @SerializedName("code")
     */
    protected $code;
    
    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     * @Groups({"api"})
     * @SerializedName("title")
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api"})
     * @SerializedName("description")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\CursusBundle\Entity\Course"
     * )
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"api"})
     */
    protected $course;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"api"})
     * @SerializedName("blocking")
     */
    protected $blocking = false;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $details;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\CursusBundle\Entity\Cursus",
     *     inversedBy="children"
     * )
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Claroline\CursusBundle\Entity\Cursus",
     *     mappedBy="parent"
     * )
     */
    protected $children;

    /**
     * @ORM\Column(name="cursus_order", type="integer")
     * @Groups({"api"})
     * @SerializedName("cursusOrder")
     */
    protected $cursusOrder;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Claroline\CursusBundle\Entity\CursusUser",
     *     mappedBy="cursus"
     * )
     */
    protected $cursusUsers;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Claroline\CursusBundle\Entity\CursusGroup",
     *     mappedBy="cursus"
     * )
     */
    protected $cursusGroups;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $icon;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\CoreBundle\Entity\Workspace\Workspace"
     * )
     * @ORM\JoinColumn(name="workspace_id", nullable=true, onDelete="SET NULL")
     */
    protected $workspace;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     * @Groups({"api"})
     * @SerializedName("root")
     */
    private $root;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     * @Groups({"api"})
     * @SerializedName("lvl")
     */
    private $lvl;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     * @Groups({"api"})
     * @SerializedName("lft")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     * @Groups({"api"})
     * @SerializedName("rgt")
     */
    private $rgt;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->cursusUsers = new ArrayCollection();
        $this->cursusGroups = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getTitle()
    {   
        return $this->title;
    }

    public function setTitle($title)
    {   
        $this->title = $title;
    }

    public function getDescription()
    {   
        return $this->description;
    }

    public function setDescription($description)
    {   
        $this->description = $description;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse(Course $course)
    {
        $this->course = $course;
    }

    public function isBlocking()
    {
        return $this->blocking;
    }

    public function setBlocking($blocking)
    {
        $this->blocking = $blocking;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(Cursus $parent)
    {
        $this->parent = $parent;
    }

    public function getChildren()
    {
        return $this->children->toArray();
    }

    public function getCursusOrder()
    {
        return $this->cursusOrder;
    }

    public function setCursusOrder($cursusOrder)
    {
        $this->cursusOrder = $cursusOrder;
    }

    public function getCursusUsers()
    {
        return $this->cursusUsers->toArray();
    }

    public function getCursusGroups()
    {
        return $this->cursusGroups->toArray();
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getWorkspace()
    {
        return $this->workspace;
    }

    public function setWorkspace(Workspace $workspace = null)
    {
        $this->workspace = $workspace;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function getLvl()
    {
        return $this->lvl;
    }

    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }

    public function getLft()
    {
        return $this->lft;
    }

    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    public function getRgt()
    {
        return $this->rgt;
    }

    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    public function getTitleAndCode()
    {
        $result = $this->title;

        if (!is_null($this->code)) {
            $result .= ' [' . $this->code . ']';
        }

        return $result;
    }

    /**
     * @Groups({"api"})
     * @VirtualProperty
     * @SerializedName("parentId")
     */
    public function getParentId()
    {
        return is_null($this->parent) ? null : $this->parent->getId();
    }
}