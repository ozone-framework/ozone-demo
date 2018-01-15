<?php

namespace App\Modules\Category\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Modules\News\Entity\News;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use App\Acme\Traits\CreatedUpdateAtTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks
 */
class Category
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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $slug;
    /**
     * @ORM\Column(name="is_featured",type="boolean", nullable=true)
     */
    protected $isFeatured;
    /**
     * @ORM\Column(name="sort_order",type="integer", nullable=true)
     */
    protected $sortOrder;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $image;
    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    protected $status;
    /**
     * @ORM\Column(type="text",name="details")
     */
    protected $details;
    /**
     * @OneToMany(targetEntity="Category", mappedBy="parent")
     **/
    private $children;
    /**
     * @ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;
    /**
     * Many Categories have Many News.
     * @ManyToMany(targetEntity="App\Modules\News\Entity\News", mappedBy="categories")
     */
    private $news;

    use CreatedUpdateAtTrait;

    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->children = new ArrayCollection();
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

    public function getParent() {
        return $this->parent;
    }

    public function getChildren() {
        return $this->children;
    }

    // always use this to setup a new parent/child relationship
    public function addChild(Category $child) {
        $this->children[] = $child;
        $child->setParent($this);
    }

    public function setParent(Category $parent) {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getisFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * @param mixed $isFeatured
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;
    }


    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
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
     * @return mixed
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param mixed $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    /**
     * @param News $news
     */
    public function removeNews(News $news)
    {
        if (false === $this->news->contains($news)) {
            return;
        }
        $this->news->removeElement($news);
        $news->removeCategory($this);
    }

    /**
     * @param News $news
     */
    public function addNews(News $news)
    {
        if (true === $this->news->contains($news)) {
            return;
        }
        $this->news->add($news);
        $news->addCategory($this);
    }

}
