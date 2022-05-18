<?php

namespace App\Controller;


use App\Entity\Participation;
use App\Entity\Courses;
use App\Form\CoursesType;
use App\Repository\CoursesRepository;
use App\Repository\SaisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/courses")
 */
class CoursesController extends AbstractController
{
    /**
     * @Route("/", name="app_courses_index", methods={"GET"})
     */
    public function index(CoursesRepository $coursesRepository,SaisonRepository $saisonRepository): Response
    {
        return $this->render('courses/index.html.twig', [
            'courses' => $coursesRepository->findAll(), 'saisons'=>$saisonRepository->findAll(),
                ]);
    }
     /**
     * @Route("/front", name="app_courses_index_front", methods={"GET"})
     */
    public function indexfront(CoursesRepository $coursesRepository,SaisonRepository $saisonRepository): Response
    {
        return $this->render('courses/courses_front.html.twig', [
            'courses' => $coursesRepository->findAll(), 'saisons'=>$saisonRepository->findAll(),
                ]);
    }
    /**
     * @Route("/new", name="app_courses_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $course = new Courses();
        $form = $this->createForm(CoursesType::class, $course,[
            'entity_manager'=>$entityManager
        ]);
        $form->add('Ajouter',SubmitType::class,);
        $form->add('Annuler',ResetType::class,[
            'attr'=>[
                'class'=>'btn btn-dark'
            ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute('app_calender_new');
        }
        return $this->render('courses/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/org/{org}", name="coursesOrg") 
     */
    public function courseOrg($org): Response
    {
        $repo = $this->getDoctrine()->getRepository(Courses::class);
        $courses = $repo->findByOrg($org);
        
        return $this->render('courses/indexOrg.html.twig', [
            'controller_name' => 'CoursesController',
            'courses' => $courses
        ]);
       
        
    }

    

    /**
     * @Route("/participation/{course}", name="courseParticipation")
     * 
     */
    public function courseParticipation($course): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participations = $repo->findByCourse($course);

        return $this->render('participation/listPartOrg.html.twig', [
            'controller_name' => 'ParticipationController',
            'participations' => $participations,
            'course'=>$course
        ]);
    }




    /**
     * @Route("/{id}", name="app_courses_show", methods={"GET"})
     */
    public function show(Courses $course): Response
    {  
        $result = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data('le nom du course : '.$course->getNom().' le nom du circuit :  '.$course->getCircuitid()->getNom().'  pays du match :  '.(String) $course->getCircuitid()->getPays())
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
        
        ->labelText('This is the label')
        ->labelFont(new NotoSans(20))
        ->labelAlignment(new LabelAlignmentCenter())
        ->build();
        // Directly output the QR code
        header('Content-Type: '.$result->getMimeType());
        

        // Save it to a file
        $name=$course->getNom().'.png';
        $result->saveToFile($this->getParameter('kernel.project_dir').'/public/images/QRcode/'.$name);
        

        
       
        return $this->render('courses/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_courses_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id , Courses $course): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $course=$this->getDoctrine()->getManager()->getRepository(Courses::class)->find($id);
        $form = $this->createform(CoursesType::class,$course,[
            'entity_manager'=>$entityManager
        ]);
        $form -> handleRequest($request);
 
        if($form->isSubmitted() && $form->isValid()){
         $em=$this->getDoctrine()->getManager();
         $em->flush();
         return $this->redirectToRoute('app_courses_index');
        }
        return $this->render('courses/edit.html.twig',[
         'f'=>$form->createView()
     ]);
    }

    /**
     * @Route("/{id}", name="app_courses_delete", methods={"POST"})
     */
    public function delete(Request $request, Courses $course, CoursesRepository $coursesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $coursesRepository->remove($course);
        }

        return $this->redirectToRoute('app_courses_index', [], Response::HTTP_SEE_OTHER);
    }
      
    /**
     * @Route("/front/Chercher", name="Chercher",methods={"GET"})
     */
    public function searchCourse(Request $request)
    {
       // echo "wsselit fonction controller";die;
        $repository = $this->getDoctrine()->getRepository(Courses::class);
        $requestString=$request->get('searchValue');
        $course = $repository->findByName($requestString);
        return $this->render('courses/courses_front.html.twig', 
        [
            'courses'=>$course
        ]);

    }
    public function __construct(BuilderInterface $customQrCodeBuilder)
{
    $result = $customQrCodeBuilder
        ->size(400)
        ->margin(20)
        ->build();
}
}
