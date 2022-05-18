<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Form\TicketsType;
use App\Entity\Courses;
use App\Entity\User;
use App\Repository\TicketsRepository;
use App\Repository\CoursesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Notification\CreationAnullerNotifiaction;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Security\LoginFormAuthenticator;

/**
 * @Route("/tickets")
 */
class TicketsController extends AbstractController

{   
     
    /**
     * @Route("/", name="app_tickets_index", methods={"GET"})
     */
    public function index(TicketsRepository $ticketsRepository): Response
    {
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_tickets_new", methods={"GET","POST"})
     */
     
    public function new(CoursesRepository $coursesRepository,$id): Response
    {
        //$user=$this->get('security.token_storage')->getToken()->getUser();
        
        $c=$coursesRepository->find($id);
        return $this->render('tickets/new.html.twig', [
            'course'=>$c,
        ]);
    }

    /**
     * @Route("/ajouterTicker" , name="ajouterTicket")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @param Request $request
     * @param MailerInterface $mailer
     */
    public function ajouterTicket(Request $request,CoursesRepository $coursesRepository, MailerInterface $mailer){
            $ticket=new Tickets();

            $type=$request->get('type');
            $course=$coursesRepository->find(intval($request->get('course')));
            $ticket->setType($type);
            $ticket->setCourse($course);
            $ticket->setUser($this->getUser());
            $em=$this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();
            $this->sendEmail($this->getUser()->getEmail(), $ticket ,$mailer);

            return $this->redirectToRoute('app_courses_index_front');
    }

    /**
     * @Route("/{id}", name="app_tickets_show", methods={"GET"})
     * @param Request $request
     * @param MailerInterface $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function show(Tickets $ticket, MailerInterface $mailer): Response
    {   $this->sendEmail($this->getUser()->getEmail(), $ticket ,$mailer);
        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tickets_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tickets $ticket, TicketsRepository $ticketsRepository): Response
    {
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketsRepository->add($ticket);
            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_tickets_delete", methods={"POST"})
     */
    public function delete(Request $request, Tickets $ticket, TicketsRepository $ticketsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $ticketsRepository->remove($ticket);
        }

        return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
    }



    public function sendEmail($rec,Tickets $ticket ,MailerInterface $mailer): Response
    {   

            $email = (new Email())
            ->from('formulamailone@gmail.com')
            ->to($rec)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Reservation!')
            ->text('New email!')
            ->html('<p>Ticket a ete reserver  !</p>');

        $mailer->send($email);

        return $this->redirectToRoute('app_courses_index_front', [], Response::HTTP_SEE_OTHER);
    }

    
}
