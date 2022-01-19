<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\CommentFlag;
use App\Form\CommentAdminType;
use App\Repository\CommentFlagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin839", name="adminHome")
     */
    public function home(CommentFlagRepository $commentFlagRepository) {
        $flags = $commentFlagRepository->findBy(['handled' => false], ['date' => 'DESC']);
        $comments = new ArrayCollection();
        foreach ($flags as $flag) {
            if (!$comments->contains($flag->getComment())) {
                $comments->add($flag->getComment());
            }
        }
        return $this->render('pages/admin/home.html.twig', ['commentsFlagged' => $comments]);
    }


    /**
     * @Route("/admin839/reported-comment/{id}", name="adminReportedComment")
     */
    public function reportedComment(Comment $comment, EntityManagerInterface $em, Request $request) {
        $form = $this->createForm(CommentAdminType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $comment->getFlags()->forAll(fn($key, $flag) => $flag->setHandled(true));
            $em->flush();
            return $this->redirectToRoute('adminHome');
        }
        return $this->render('pages/admin/reported-comment.html.twig', ['comment' => $comment, 'form' => $form->createView()]);
    }
}
