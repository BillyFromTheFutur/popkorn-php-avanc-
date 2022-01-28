<?php

namespace App\Controller;
use App\Services\Description;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Form\MdpType;
use App\Form\ImporterFilmType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->findBy([],['score'=>'DESC','name' => 'ASC']),
        ]);
    }
    /**
     * @Route("/import", name="movie_import", methods={"GET", "POST"})
     */
    public function Import(Request $request,MovieRepository $movieRepository,EntityManagerInterface $entityManager): Response
    {
        // fonction trouvé sur un forum
        $form = $this->createForm(ImporterFilmType::class);
        $form->handleRequest($request);
        $erreur = "";


        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('fichier_csv')->getData();
            $csvFile = $csvFile->getRealPath();


            $tdmMovie = $this->getDoctrine()->getManager();



            if ( $xlsx = \SimpleXLSX::parse($csvFile) ) {
                
                foreach( $xlsx->rows() as $key=> $data ) {
                    $movie= new Movie();
                    $movie->setName($data[0]);
                    $movie->setDescription($data[1]);
                    $movie->setScore((int) $data[2]);
                    $tdmMovie->persist($movie);
                    $tdmMovie->flush();
                }
                

            } else {
                echo \SimpleXLSX::parseError();
                return $this->redirectToRoute('movie_new', ['erreur'=>"Le fichier n'est pas compatible"], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->renderForm('movie/new.html.twig', [
            'erreur'=>$erreur,
            'form' => $form,

        ]);
    }
    /**
     * @Route("/new", name="movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $erreur= "";
        $Description= new Description();
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        $movie->setVotersNumber(1);
        
        if ($form->isSubmitted() && $form->isValid() ) {
            $filmExist = $Description->getDescriptionFromApi();
            if($filmExist == "Le film n\'existe pas"){
                return $this->render('movie/error.html.twig', []);
            }
            if($filmExist->data->Response == 'True'){
                
                $movie->setName($filmExist->data->Title);
                $movie->setDescription($filmExist->data->Plot);
                $entityManager->persist($movie);
                $entityManager->flush();
    
                return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
            }else{
                
            }

        }

        return $this->renderForm('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(MdpType::class, $movie);
        $form->handleRequest($request);
        $mdp = $this->getParameter('app.password');

        if ($form->isSubmitted()) {

            $mdpPosted = $_POST['mdp']['mot_de_passe'];


            if($mdp == $mdpPosted){
                $id = $request->attributes->get('id');
                $movie = $entityManager->getRepository(Movie::class)->find($id);
                $entityManager->remove($movie);
                $entityManager->flush();
                return $this->redirectToRoute('movie_index', [[]], Response::HTTP_SEE_OTHER);
            }
            else{
                return $this->render('movie/error_mdp.html.twig', []);
            }

        }

        return $this->renderForm('movie/mdp.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }
}