<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariantRepository")
 * @Cache("NONSTRICT_READ_WRITE")
 */
class Variant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $color;

    /**
     * @ManyToOne(targetEntity="Product", inversedBy="variants")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * Variant constructor.
     * @param $id
     * @param $price
     * @param $color
     * @param $product
     */
    public function __construct($price = '', $color = '', $product = null)
    {
        $this->price = $price;
        $this->color = $color;
        $this->product = $product;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }


    /**
     * This function generates a human-readable version of object as a string
     * @return string
     */
    public function getName(): string
    {
        return sprintf("%s: (%s, %s)", $this->getProduct()->getTitle(), $this->getColor(), $this->getPrice());
    }

}
