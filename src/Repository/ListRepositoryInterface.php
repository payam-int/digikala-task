<?php
/**
 * Created by PhpStorm.
 * User: payam
 * Date: 1/23/18
 * Time: 4:34 PM
 */

namespace App\Repository;


use Pagerfanta\Pagerfanta;

interface ListRepositoryInterface
{
    public function getList(int $page): Pagerfanta;
}