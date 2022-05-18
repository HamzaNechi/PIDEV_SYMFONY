<?php

namespace App\Controller;

use App\Entity\Saisons;
use App\Entity\ClassementPilotes;
use App\Form\ClassementPilotesType;
use App\Repository\ClassementPilotesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/classement/pilotes")
 */
class ClassementPilotesController extends AbstractController
{
    /**
     * @Route("/", name="app_classement_pilotes_index", methods={"GET"})
     */
    public function index(ClassementPilotesRepository $classementPilotesRepository): Response
    {
        return $this->render('classement_pilotes/index.html.twig', [
            'classement_pilotes' => $classementPilotesRepository->findAll(),
        ]);
    }



  /**
     * @Route("/listp", name="classement_list", methods={"GET"})
     */
    public function listp(ClassementPilotesRepository $classementPilotesRepository): Response
    {


         // Configure Dompdf according to your needs
         $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');
         
         // Instantiate Dompdf with our options
         $dompdf = new Dompdf($pdfOptions);



         
         // Retrieve the HTML generated in our twig file
         $html = $this->renderView('classement_pilotes/listp.html.twig', [
            'classement_pilotes' => $classementPilotesRepository->findAll(),
         ]);
         
         // Load HTML to Dompdf
         $dompdf->loadHtml($html);
         
         // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
         $dompdf->setPaper('A4', 'portrait');
 
         // Render the HTML as PDF
         $dompdf->render();
 
         // Output the generated PDF to Browser (force download)
         $dompdf->stream("mypdf.pdf", [
             "Attachment" => false
         ]);


    }




    

    /**
     * @Route("/new", name="app_classement_pilotes_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClassementPilotesRepository $classementPilotesRepository): Response
    {
        $classementPilote = new ClassementPilotes();
        $form = $this->createForm(ClassementPilotesType::class, $classementPilote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classementPilotesRepository->add($classementPilote);
            return $this->redirectToRoute('app_classement_pilotes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classement_pilotes/new.html.twig', [
            'classement_pilote' => $classementPilote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{classementPId}", name="app_classement_pilotes_show", methods={"GET"})
     */
    public function show(ClassementPilotesRepository $repo ,$classementPId): Response
    {
        $classementPilote=$repo->find($classementPId);
        return $this->render('classement_pilotes/show.html.twig', [
            'classement_pilote' => $classementPilote,
        ]);
    }

    /**
     * @Route("/{classementPId}/edit", name="app_classement_pilotes_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,ClassementPilotesRepository $classementPilotesRepository,$classementPId): Response
    {   $classementPilote=$classementPilotesRepository->find($classementPId);
        $form = $this->createForm(ClassementPilotesType::class, $classementPilote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classementPilotesRepository->add($classementPilote);
            return $this->redirectToRoute('app_classement_pilotes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classement_pilotes/edit.html.twig', [
            'classement_pilote' => $classementPilote,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @return Response
     * @Route("/tri", name="tri")
     */
    function orderbyPartcipants(ClassementPilotesRepository $repositery)
    {
        $classementPilotes = $repositery->orderbyNbrPartcipants();

        
        return $this->render('classementPilote/index.html.twig', [
            'classement_pilote' => $classementPilotes,
        ]);
    }
    /**
     * @Route("/{classementPId}", name="app_classement_pilotes_delete", methods={"POST"})
     */
    public function delete(Request $request, ClassementPilotesRepository $classementPilotesRepository,$classementPId): Response
    {
        $classementPilote=$classementPilotesRepository->find($classementPId);
        if ($this->isCsrfTokenValid('delete'.$classementPilote->getId(), $request->request->get('_token'))) {
            $classementPilotesRepository->remove($classementPilote);
        }

        return $this->redirectToRoute('app_classement_pilotes_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/frontcp", name="app_classement_pilotes_indexf", methods={"GET"})
     */
    public function indexf(ClassementPilotesRepository $classementPilotesRepository): Response
    {
        return $this->render('classement_pilotes/ClassementPiloteF.html.twig', [
            'classement_pilotes' => $classementPilotesRepository->findAll(),
        ]);
    }


  
 
/**********************************Mobile */
/**
     * @Route("/ajoutermobile", name="ajoutermobile")
     */
    public function registerMobile(Request $request){
        $pilotesPiloteId = $request->query->get("pilotesPiloteId");
        $saisonsYear = $request->query->get("saisonsYear");
        $pointsTotal = $request->query->get("pointsTotal");
        $position = $request->query->get("position");
        
     
    
        $ClassementPilotes = new ClassementPilotes();
        $ClassementPilotes->setPilotesPiloteId($pilotesPiloteId);
        $ClassementPilotes->setSaisonsYear($saisonsYear);
        $ClassementPilotes->setPointsTotal($pointsTotal);
        $ClassementPilotes->setPosition($position);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($ClassementPilotes);
            $em->flush();
    
            return new JsonResponse("ajout avec succes", 200);
        }catch(\Exception $ex){
            return new Response("exception".$ex->getMessage());
        }
    }

    /**
     * @Route("/affichermobile", name="afficher")
     */
public function AfficherMobile(ClassementPilotesRepository $repository)
{
    $ClassementPilotes = $this->getDoctrine()->getManager()->getRepository(ClassementPilotes::class)->findAll();
    $ClassementPilotes = $repository->findAll();
    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize($ClassementPilotes);
    return new JsonResponse($formatted);
}


/**
 * @Route("/deleteclassementP/{id}", name="deleteclassementP")
 */
public function deleteclassementP($id){
    $ClassementPilotes=$this->getDoctrine()->getRepository(ClassementPilotes::class)->find($id);
    if($ClassementPilotes != null){
        $em=$this->getDoctrine()->getManager();
        $em->remove($ClassementPilotes);
        $em->flush();
        return new JsonResponse("classement supprimé avec succé");
    }else{
        return new JsonResponse("classement pas trouvé");
    }
    
}

    
}
