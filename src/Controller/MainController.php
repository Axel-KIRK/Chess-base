<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;




class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $articleRepository)
    {
        
        if (true === $this->getUser()->IsInGame()){
            $games = $this->getUser()->getGames();
        }

        return $this->render('main/index.html.twig', [
            'article' => $articleRepository->findLastArticle(),
            'games' => isset($games) ? $games : false,
        ]);
    }

     /**
     * @Route("/friends", name="friendList")
     */
    public function FriendList()
    {
        $list = $this->getUser()->getMyFriends();

        return $this->render('main/ListFriend.html.twig', array(
            'list' => $list
        ));
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function addAction($id)
    {
        $list = $this->getUser()->getMyFriends();

        $user = $this->getDoctrine()
                ->getRepository('App:User')
                ->find($id)
            ;
        if ($this->getUser() != $user) {
                $this->getUser()->addMyFriend($user);
                $list = $this->getUser()->getMyFriends();
                $this->getDoctrine()->getManager()->flush();
                return $this->render('main/ListFriend.html.twig', array(
                'list' => $list
                ));
        }
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function removeAction($id)
    {
        $list = $this->getUser()->getMyFriends();

        $user = $this->getDoctrine()
                     ->getRepository('App:User')
                     ->find($id)
                    ;

            $this->getUser()->removeMyFriend($user);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('main/ListFriend.html.twig', array(
            'friend' => $user,
            'list' => $list
            ));
    }
}
