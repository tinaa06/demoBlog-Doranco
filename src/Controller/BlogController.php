<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(ArticleRepository $repo): Response
    {
        //
        $articles = $repo->findAll();
        //findAll pour récupérer tous les articles en BDD

        return $this->render('blog/index.html.twig', [
            'tabArticles' =>$articles// j'envoie le tableau d'article au template
        ]);
    
    //touts les méthode d'un controller renvoient un objet de typa response
    }
    #[Route("/", name:"home")]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title'=> 'Bienvenue sur le blog',
            'age'=> 28
        ]);
    }
    #[Route('/blog/show/{id}', name: 'blog_show')]
    // {id} est un paramètre de route, ce sera l'id d'un article
    public function show($id, ArticleRepository $repo)
    {
        $article = $repo->find($id);
        //  je passe à find() l'id dans ma route pour récupérer l'article correspondant en BDD
        return $this ->render('blog/show.html.twig', [
            'article'=> $article
    ]);
    }

    
    #[Route("/blog/new", name :"blog_create")]
    #[Route("/blog/edit/{id}", name :"blog_edit")]
    
   
    public function form(Request $superglobals, EntityManagerInterface $manager, Article $article = null)
    {




if($article == null)
{
    $article = new Article;
    $article->setCreatedAt(new \DateTime());

}



        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($superglobals);


       ;

        if($form->isSubmitted() && $form->isValid())
        {
           
            //ajout de la date de creation c'est sulment a l'isertion d'un article
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show',[
                'id' =>$article->getId()
            ]);
        }

        return $this->renderForm("blog/form.html.twig", [

            'formArticle' => $form,
            'editMode'=> $article->getId() !== NULL
            // si nous somme sur la route /new, $article n'a pas encore d'id, donc etidmode = 0
            // sinon, editMode = 1
        ]);
    }

        #[Route("/blog/delete/{id}", name:"blog_delete")]
        public function delet(EntityManagerInterface $manager, $id, ArticleRepository $repo)
        {
 
            $article = $repo->find($id);

            $manager->remove($article);
            //remove() prépare le suppression d'un article

            $manager->flush();
            // exécute la requéte préparée (suppression)

            $this->addFlash('success', "l'article a bien été supprimé");
            // addFlach() permet de créer un msg de notification 
            // le prenier arg est le type du massage
            //le 2 eme arg est le message 

            return $this->redirectToRoute("app_blog");
            // redirection vers la liste des articles 
            // nous afficheron le message Flash sur le template affiché sur la route app_blog (index.html.twig)
        }

   

     

    

}
