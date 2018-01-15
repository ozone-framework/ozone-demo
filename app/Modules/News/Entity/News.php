<?php

namespace App\Modules\News\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use App\Modules\Category\Entity\Category;
use App\Acme\Traits\CreatedUpdateAtTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 * @ORM\HasLifecycleCallbacks
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="title",type="string", length=64)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $image;


    /**
     * @ORM\Column(type="boolean")
     */
    protected $status = true;

    /**
     * @ORM\Column(type="text",name="details")
     */
    protected $details;

    /**
     * @ManyToMany(targetEntity="App\Modules\Category\Entity\Category", inversedBy="news")
     */
    private $categories;

    use CreatedUpdateAtTrait;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param mixed $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return Category[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[]|\Doctrine\Common\Collections\ArrayCollection $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        if (false === $this->categories->contains($category)) {
            return;
        }
        $this->categories->removeElement($category);
        $category->removeNews($this);
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category)
    {
        if (true === $this->categories->contains($category)) {
            return;
        }
        $this->categories->add($category);
        $category->addNews($this);
    }

}
