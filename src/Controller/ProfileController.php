<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile")
     */
    public function profile() {
        $user = $this->getUser();
        return $this->render("pages/profile/profile.html.twig", ['user' => $user]);
    }

}
