<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SelectRoleType;
use AppBundle\Entity\SelectRole;
use AppBundle\Entity\Homework;
use AppBundle\Form\HomeworkType;
use AppBundle\Entity\Answer;
use AppBundle\Form\AnswerType;
use AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $normal = false;
        $form = null;
        $formView = null;
        if ($user) {
            if ($user->isTeacher()) {
                return $this->getTeacherResponse($request);
            } else if($user->isStudent()) {
                return $this->getStudentResponse($request);
            } else {
                $role = new SelectRole();
                $form = $this->createForm(new SelectRoleType(), $role);
                $form->handleRequest($request);
                $normal = true;
                $formView = $form->createView();
            }
        }
        if ($normal && $request->getMethod() == 'POST' && $form->isValid()) {
            if($role->getRole() == "学生"){
                $user->setStudent(true);
            } else if($role->getRole() == "老师"){
                $user->setTeacher(true);
            }
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("homepage");
        }
        
        $em    = $this->getDoctrine()
                    ->getManager();
        $dql   = "SELECT a FROM AppBundle:User a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        // parameters to template
        return $this->render('default/index.html.twig', 
                array(
                    'pagination' => $pagination,
                    'normal' => $normal,
                    'form' => $formView
                ));
    }
    
    public function getStudentResponse(Request $request)
    {
        $user = $this->getUser();
        
        $em    = $this->getDoctrine()
                    ->getManager();
        $dql   = "SELECT a, e FROM AppBundle:Answer a left join a.homeowrks e where a.username=:p1";
        $query = $em->createQuery($dql)
                ->setParameters(array('p1'=>$user->getUsername()));
        //$query->setFetchMode("AppBundle\Answer", "Homework", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        
        $dql   = "SELECT a, e FROM AppBundle:Homework a left join a.answers e";
        $query = $em->createQuery($dql);
        $query->setFetchMode("AppBundle\Homework", "Anwser", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
        $homework_pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        
        return $this->render('default/student.html.twig',
                array(
                    'pagination' => $pagination,
                    'homework_pagination' => $homework_pagination,
                    'user' => $user
                ));
    }
    
    public function getTeacherResponse(Request $request)
    {
        $user = $this->getUser();
        
        $homework = new Homework();
        $form = $this->createForm(new HomeworkType(), $homework);
        $form->handleRequest($request);
        $formView = $form->createView();
        
        if ($request->getMethod() == 'POST' && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $homework->setUsername($user->getUsername());
            $em->persist($homework);
            $em->flush();
            return $this->redirectToRoute("homepage");
        }
        
        $em    = $this->getDoctrine()
                    ->getManager();
        $dql   = "SELECT a, aw FROM AppBundle:Homework a left join a.answers aw";
        $query = $em->createQuery($dql);
        //$query->setFetchMode("AppBundle\Homework", "Answer", \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER);
        //$res = $query->getResult(); 
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        //parameters to template
        return $this->render('default/teacher.html.twig',
                array(
                    'pagination' => $pagination,
                    'user' => $user,
                    'form' => $formView
                ));
    }
    
    /**
     * @Route("/homework/{id}", name="homeworkpage", options={"expose"=true})
     */
    public function homeworkAction(Request $request, $id)
    {
        $user = $this->getUser();
        $homework = $this->getDoctrine()
                    ->getManager()
                    ->getRepository("AppBundle:Homework")
                    ->find($id);
        $em    = $this->getDoctrine()
                    ->getManager();
        $dql   = "SELECT a, e FROM AppBundle:Answer a left join a.homeowrks e where e.id=:p1";
        $query = $em->createQuery($dql)
                ->setParameters(array('p1'=>$id));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        // parameters to template
        return $this->render('default/homework.html.twig',
                array(
                    'pagination' => $pagination,
                    'homework' => $homework,
                    'user' => $user
                ));
    }
    
    /**
     * @Route("/answer/{id}", name="answerpage", options={"expose"=true})
     */
    public function answerAction(Request $request, $id)
    {
        $user = $this->getUser();
        
        $homework = $this->getDoctrine()
                    ->getManager()
                    ->getRepository("AppBundle:Homework")
                    ->find($id);
        
        if(!$homework){
            throw new InvalidArgumentException("无此作业记录！");
        }
                
        $answer = new Answer();
        $form = $this->createForm(new AnswerType(), $answer);
        $form->handleRequest($request);
        $formView = $form->createView();
        
        if ($request->getMethod() == 'POST' && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $homework->addAnswer($answer);
            $answer->addHomeowrk($homework);
            $answer->setUsername($user->getUsername());
            $em->persist($answer);
            $em->persist($homework);
            $em->flush();
            return $this->redirectToRoute("answerpage", array("id" => $id));
        }
        
        $em    = $this->getDoctrine()
                    ->getManager();
        $dql   = "SELECT a, e FROM AppBundle:Answer a left join a.homeowrks e where e.id=:p1";
        $query = $em->createQuery($dql)
                ->setParameters(array('p1'=>$id));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        // parameters to template
        return $this->render('default/answer.html.twig',
                array(
                    'pagination' => $pagination,
                    'homework' => $homework,
                    'user' => $user,
                    'form' => $formView
                ));
    }
}
