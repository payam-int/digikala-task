<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/administrator", name="admin_page")
     */
    public function index()
    {
        // replace this line with your own code!
        return $this->render('admin/admin_home.html.twig');
    }
}
