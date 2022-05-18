<?php

namespace App\Controller;

use App\Entity\Membres;
use App\Entity\Participation;
use App\Entity\Pilotes;
use App\Entity\Equipes;
use App\Entity\Courses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ParticipationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Dompdf\Dompdf;
use Dompdf\Options;
use Normalizer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ParticipationController extends AbstractController
{
    /**
     * @Route("/participation", name="participation")
     * 
     */
    public function indexx(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participations = $repo->findAll();
        return $this->render('participation/index.html.twig', [
            'controller_name' => 'ParticipationController',
            'participations' => $participations
        ]);
    }

    /**
     * @Route("/participationDetail/{id}", name="participationDetail")
     */
    public function participationDetail($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $rep = $this->getDoctrine()->getRepository(Membres::class);

        $participation = $repo->findOneBy(['id' => $id]);
        $pilote = $rep->findOneBy(['id' => $participation->getPilote()->getId()]);
        return $this->render('participation/participationDetail.html.twig', [
            'controller_name' => 'ParticipationController',
            'participation' => $participation,
            'pilote' => $pilote
        ]);
    }


    /**
     * @Route("/participationDetailPDF/{id}", name="participationDetailPDF")
     */
    public function participationDetailPDF($id)
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $rep = $this->getDoctrine()->getRepository(Membres::class);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $participation = $repo->findOneBy(['id' => $id]);
        $pilote = $rep->findOneBy(['id' => $participation->getPilote()->getId()]);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('participation/listP.html.twig', [
            'controller_name' => 'ParticipationController',
            'participation' => $participation,
            'pilote' => $pilote
        ]);
        $nom = $pilote->getNom();
        $nomC = $participation->getCourse()->getNom();
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Participation details $nom - $nomC ", [
            "Attachment" => true
        ]);
    }



    /**
     * @Route("/addParticipation", name="addParticipation")
     */

    public function addParticipation(Request $request): Response
    {
        $participation = new Participation();
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->add('Ajouter', SubmitType::class);
        $form->add('Annuler', ResetType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();
            return $this->redirectToRoute('participation');
        }
        return $this->render(
            'participation/ajouterParticipation.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * @Route("/addPart/{c}", name="addPart")
     */

    public function addPart(Request $request, $c)
    {
        $participation = new Participation();
        $course =  $this->getDoctrine()->getRepository(Courses::class)->findBy(['id' => $c]);

        $form = $this->createForm(ParticipationType::class, $participation);

        $form->add('Ajouter', SubmitType::class);
        $form->add('Annuler', ResetType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        $form->get('course')->setData($c);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isSubmitted() && $form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($participation);
                $em->flush();
                return $this->redirectToRoute('courseParticipation', ['course' => $c]);
            }
        }
        return $this->render(
            'participation/ajouterPartOrg.html.twig',
            [
                'form' => $form->createView(),
                'course' => $course
            ]
        );
    }

    /**
     * @Route("/deletePartOrg/{id}" , name="deletePartOrg")
     */
    public function deletePartOrg($id)
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participation = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($participation);
        $em->flush();
        return $this->redirectToRoute('courseParticipation', ['course' => $participation->getCourse()->getId()]);
    }


    
    /**
     * @Route("/editPartOrg/{id}", name="editPartOrg")
     */
    public function editPartOrg(Request $request, $id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participation = $repo->find($id);

        $form = $this->createForm(ParticipationType::class, $participation);
        $form->add('Modifier', SubmitType::class);
        $form->add('Annuler', ResetType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('courseParticipation', ['course' => $participation->getCourse()->getId()]);
        }
        return $this->render(
            'participation/editPartOrg.html.twig',
            ['form' => $form->createView()]
        );
    }



    /**
     * @Route("/deleteParticipation/{id}" , name="deleteParticipation")
     */
    public function deleteParticipation($id)
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participation = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($participation);
        $em->flush();
        return $this->redirectToRoute('participation');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $val = $request->get("search");
        $participations =  $this->getDoctrine()->getRepository(Participation::class)->searchB($val);
        return $this->render('participation/index.html.twig', [
            'controller_name' => 'ParticipationController',
            'participations' => $participations
        ]);
    }



    /**
     * @Route("/participations", name="participations")
     */
    public function vitrineParticipation(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participations = $repo->findAll();
        return $this->render('participation/index_vitrine.html.twig', [
            'controller_name' => 'ParticipationController',
            'participations' => $participations
        ]);
    }


    /**
     * @Route("/editParticipation/{id}", name="editParticipation")
     */
    public function editParticipation(Request $request, $id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participation = $repo->find($id);

        $form = $this->createForm(ParticipationType::class, $participation);
        $form->add('Modifier', SubmitType::class);
        $form->add('Annuler', ResetType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('participation');
        }
        return $this->render(
            'participation/editParticipation.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * @Route("/sendmail/{idp}", name="sendmail")
     */
    function mailing(MailerInterface $mailer, $idp)
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $rep = $this->getDoctrine()->getRepository(Membres::class);
        $participation = $repo->findOneBy(['id' => $idp]);
        $pilote = $rep->findOneBy(['id' => $participation->getPilote()->getId()]);

        $email = (new TemplatedEmail())
            ->from('bright.light.pidev@gmail.com')
            ->to($participation->getEquipe()->getEmail())
            ->subject('hello')
            ->htmlTemplate('participation/mailTemp.html.twig')
            ->context([

                'participation' => $participation,
                'pilote' => $pilote
            ]);

        $mailer->send($email);
        return $this->redirectToRoute('participationDetail', ['id' => $idp]);
    }


    /*--------------------- web service ----------------------------*/

    /**
     * @Route("/mobile/participation", name="participation_mobile")
     * @Method("GET")
     */
    public function mobileAffiche()
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participations = $repo->findAll();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object;
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($participations);
        return new JsonResponse($formatted);
    }



    /**
     * @Route("/mobile/addParticipation", name="addParticipation_mobile")
     * @Method("POST")
     */

    public function mobilAddParticipation(Request $request)
    {
        $participation = new Participation();
        $pilote = $request->query->get('pilote');
        $equipe = $request->query->get('equipe');
        $course = $request->query->get('course');
        $grid = $request->query->get('grid');
        $position = $request->query->get('position');
        $points = $request->query->get('points');
        $qualifying = $request->query->get('position');

        $participation->setPilote($pilote);
        $participation->setEquipe($equipe);
        $participation->setCourse($course);
        $participation->setGrid($grid);
        $participation->setPosition($position);
        $participation->setPoints($points);
        $participation->setQualifying($qualifying);

        $em = $this->getDoctrine()->getManager();
        $em->persist($participation);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($participation);
        return new JsonResponse($formatted);
    }




    /*--------------------- web service ----------------------------*/

    /**
     * @Route("/mobile/participation", name="participation_mobile")
     * @Method("GET")
     */
    public function mobileAfficher()
    {
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $participations = $repo->findAll();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object;
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($participations);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/mobile/addParticipation", name="addParticipation_mobile")
     * @Method("POST")
     */
    public function mobileAddParticipation(Request $request, NormalizerInterface $normalizer)
    {
        $participation = new Participation();
        $grid = $request->query->get('grid');
        $position = $request->query->get('position');
        $points = $request->query->get('points');
        $qualifying = $request->query->get('position');

        $pilote = $this->getDoctrine()->getRepository(Pilotes::class)->find($request->query->get('pilote'));
        $equipe = $this->getDoctrine()->getRepository(Equipes::class)->find($request->query->get('equipe'));
        $course = $this->getDoctrine()->getRepository(Courses::class)->find($request->query->get('course'));


        $participation->setEquipe($equipe);
        $participation->setCourse($course);
        $participation->setPilote($pilote);
        $participation->setGrid($grid);
        $participation->setPosition($position);
        $participation->setPoints($points);

        $em = $this->getDoctrine()->getManager();
        $em->persist($participation);
        $em->flush();

        $jsonContent = $normalizer->normalize($participation, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/mobile/deleteParticipation", name="deleteParticipation_mobile")
     * @Method("DELETE")
     */
    public function mobileDeleteParticipation(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $part = $em->getRepository(Participation::class)->find($id);

        if ($part != null) {
            $em->remove($part);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize('Participation supprimee.');
            return new JsonResponse($formatted);
        }
        return new JsonResponse("Participation invalid");
    }

    /**
     * @Route("/mobile/updateParticipation", name="updateParticipation_mobile")
     * @Method("PUT")
     */
    public function mobileUpdateParticipation(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $part = $this->getDoctrine()
            ->getManager()
            ->getRepository(Participation::class)
            ->find($request->get("id"));

        $grid = $request->query->get('grid');
        $position = $request->query->get('position');
        $points = $request->query->get('points');
        $qualifying = $request->query->get('position');
        $pilote = $this->getDoctrine()->getRepository(Pilotes::class)->find($request->query->get('pilote'));
        $equipe = $this->getDoctrine()->getRepository(Equipes::class)->find($request->query->get('equipe'));
        $course = $this->getDoctrine()->getRepository(Courses::class)->find($request->query->get('course'));


        $part->setEquipe($equipe);
        $part->setCourse($course);
        $part->setPilote($pilote);
        $part->setGrid($grid);
        $part->setPosition($position);
        $part->setPoints($points);
        $part->setQualifying($qualifying);

        $em->persist($part);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($part);
        return new JsonResponse("Participation a ete modifie");
    }

    /**
     * @Route("/mobile/sendmail", name="sendMailMobile")
     * @Method("POST")
     */
    function mobileMailing(MailerInterface $mailer, Request $request)
    {
        $idp = $request->get("id");
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $rep = $this->getDoctrine()->getRepository(Membres::class);
        $participation = $repo->findOneBy(['id' => $idp]);
        $pilote = $rep->findOneBy(['id' => $participation->getPilote()->getId()]);

        $email = (new TemplatedEmail())
            ->from('bright.light.pidev@gmail.com')
            ->to($participation->getEquipe()->getEmail())
            ->subject('hello')
            ->htmlTemplate('participation/mailTemp.html.twig')
            ->context([

                'participation' => $participation,
                'pilote' => $pilote
            ]);
        $mailer->send($email);
        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize('Mail envoye.');
        return new JsonResponse($formatted);
    }
}
