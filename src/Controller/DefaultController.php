<?php

namespace App\Controller;


use App\Entity\Advert;
use App\Entity\Comment;
use App\Entity\CommentFlag;
use App\Form\AdvertType;
use App\Form\CommentType;
use App\Repository\AdvertRepository;
use App\Search\Search;
use App\Search\SearchFullType;
use App\Search\SearchType;
use App\Service\PhotoUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(AdvertRepository $advertRepository): Response
    {
        $adverts = $advertRepository->findBy([],['id'=> 'desc'], 9, 0);
        return $this->render('pages/home.html.twig', ['adverts' => $adverts]);
    }

    /**
     * @Route("/category/{id}", name="category")
     */
    public function category(int $id): Response
    {
        return $this->render('pages/category.html.twig', ['id' => $id]);
    }

//    /**
//     * @Route("/search", name="search")
//     */
//    public function search(AdvertRepository $advertRepository): Response
//    {
//        $adverts = $advertRepository->findAllWithCategories();
//        return $this->render('pages/search.html.twig', ['adverts' => $adverts]);
//    }

    /**
     * @Route("/a/{slug}", name="view_advert")
     */
    public function viewAdvert(string $slug, AdvertRepository $advertRepository, Request  $request, EntityManagerInterface $em): Response
    {
        $advert = $advertRepository->findOneBySlug($slug);
        if ($advert === null) {
            throw new NotFoundHttpException("advert inexistante");
        }
        $comment = new Comment();
        $comment->setAdvert($advert);
        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('view_advert', ['slug' => $advert->getSlug()]);
        }

        return $this->render('pages/advert.html.twig', ['advert' => $advert, 'commentForm' => $commentForm->createView()]);
    }

    /**
     * @Route("/new-a", name="create_advert")
     */
    public function createAdvert(Request $request, PhotoUploader $photoUploader, EntityManagerInterface $em): Response
    {
        $advert = new Advert();
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('gallery')->get('photos') as $photoData) {
                $photo = $photoUploader->uploadPhoto($photoData);
                $advert->getGallery()->addPhoto($photo);
                $em->persist($photo);
            }
            $em->persist($advert->getGallery());
            $em->persist($advert);
            $em->flush();
            return $this->redirectToRoute('view_advert', ['slug' => $advert->getSlug()]);
        }
        return $this->render('pages/create-advert.html.twig', ['advertForm' => $form->createView()]);
    }


    /**
     * @Route("/edit-a/{id<\d+>}", name="edit_advert")
     */
    public function editAdvert(int $id, PhotoUploader $photoUploader, Request $request, AdvertRepository $advertRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $advert = $advertRepository->find($id);
        if ($advert->getUser() !== $user) {
            throw new AccessDeniedException();
        }
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->get('gallery')->get('photos') as $photoData) {
               $photo = $photoUploader->uploadPhoto($photoData);
                if($photo != null) {
                    $photo->setGallery($advert->getGallery());
                    $em->persist($photo);
                }

            }
            $em->persist($advert->getGallery());
            $em->persist($advert);
            $em->flush();
            return $this->redirectToRoute('view_advert', ['slug' => $advert->getSlug()]);
        }
        return $this->render('pages/create-advert.html.twig', ['advertForm' => $form->createView()]);

    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, AdvertRepository $advertRepository) {
        $search = new Search();
        $form = $this->createForm(SearchFullType::class, $search);
        $form->handleRequest($request);
        $result = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $advertRepository->findBySearch($search);
        }
        return $this->render('pages/search.html.twig', ['adverts' => $result, 'searchFullForm' => $form->createView() ]);
    }

    /**
     * @Route("/report-comment/{id}", name="reportComment", methods={"GET"})
     */
    public function reportComment(Comment $comment, EntityManagerInterface $em) {
        $flag = new CommentFlag();
        $flag->setComment($comment);
        $em->persist($flag);
        $em->flush();
        return $this->render('elements/flag-response.html.twig');
    }

}
