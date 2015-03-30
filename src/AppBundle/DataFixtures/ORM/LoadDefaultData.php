<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cmet\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadDefaultData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface { 
    
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        
        $userManager = $this->container->get('fos_user.user_manager');
        //add teacher
        $teacher = $userManager->createUser();
        $teacher->setUsername('teacher');
        $teacher->setTeacher(true);
        $teacher->setEmail('810137476@qq.com');
        $teacher->setPlainPassword('123');
        $teacher->setPhone("15656590430");
        $teacher->setDepartment("软件学院");
        $teacher->setEnabled(true);
        $userManager->updateUser($teacher);
        
        //add student
        $student = $userManager->createUser();
        $student->setUsername('student');
        $student->setStudent(true);
        $student->setEmail('wingchen@mail.ustc.edu.com');
        $student->setPlainPassword('123');
        $student->setPhone('15656590431');
        $student->setDepartment('软件学院');
        $student->setEnabled(true);
        $userManager->updateUser($student);
        
        for($i=0; $i<10; $i++){
            $student = $userManager->createUser();
            $student->setUsername('student'.$i);
            $student->setStudent(true);
            $student->setEmail('student'.$i.'@test.com');
            $student->setPlainPassword('123');
            $student->setPhone(''.$i);
            $student->setDepartment('软件学院');
            $student->setEnabled(true);
            $userManager->updateUser($student);
        }
        for($i=0; $i<10; $i++){
            $teacher = $userManager->createUser();
            $teacher->setUsername('teacher'.$i);
            $teacher->setTeacher(true);
            $teacher->setEmail('teacher'.$i.'@test.com');
            $teacher->setPlainPassword('123');
            $teacher->setPhone(''.$i);
            $teacher->setDepartment('软件学院');
            $teacher->setEnabled(true);
            $userManager->updateUser($teacher);
        }
        for($i=0; $i<10; $i++){
            $guest = $userManager->createUser();
            $guest->setUsername('guest'.$i);
            $guest->setEmail('guest'.$i.'@test.com');
            $guest->setPlainPassword('123');
            $guest->setPhone(''.$i);
            $guest->setDepartment('软件学院');
            $guest->setEnabled(true);
            $userManager->updateUser($guest);
        }
        
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // number in which order to load fixtures
    }

}
