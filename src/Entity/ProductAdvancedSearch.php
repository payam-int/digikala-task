<?php
/**
 * Created by PhpStorm.
 * User: payam
 * Date: 1/24/18
 * Time: 10:38 PM
 */

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Class AdvancedSearch
 * @package App\Entity
 */
class ProductAdvancedSearch
{

    /**
     * @Assert\Choice(choices={"title", "description", "variants.color"}, message="Choose a valid search type.", multiple=true)
     */
    protected $searchFields;

    /**
     * @var string
     */
    protected $searchQuery;

    protected $minPrice;

    protected $maxPrice;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

        if ($this->getMaxPrice() && $this->getMinPrice() && $this->getMinPrice() > $this->getMaxPrice()) {
            $context->buildViolation('Maximum price could not be less than minimum price!')
                ->atPath('maxPrice')
                ->addViolation();
        }
    }

    /**
     * @return mixed
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * @param mixed $minPrice
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;
    }

    /**
     * @return mixed
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * @param mixed $maxPrice
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
    }


    /**
     * @return mixed
     */
    public function getSearchFields()
    {
        return $this->searchFields;
    }

    /**
     * @param mixed $searchFields
     */
    public function setSearchFields($searchFields)
    {
        $this->searchFields = $searchFields;
    }

    /**
     * @return mixed
     */
    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    /**
     * @param mixed $searchQuery
     */
    public function setSearchQuery($searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }


}