<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    const ROLE_TEACHER = 'ROLE_TEACHER';
    const ROLE_STUDENT = 'ROLE_STUDENT';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=255, nullable=true)
     */
    private $department;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set department
     *
     * @param string $department
     * @return User
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return string 
     */
    public function getDepartment()
    {
        return $this->department;
    }
    
    /**
     * Set user to a student
     * 
     *@param boolean $boolean
     * @return User
     */
    public function setStudent($boolean)
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_STUDENT);
        } else {
            $this->removeRole(static::ROLE_STUDENT);
        }

        return $this;
    }
    
    /**
     * Get is a student
     *
     * @return boolean 
     */
    public function isStudent(){
        return $this->hasRole(static::ROLE_STUDENT);
    }
    
    /**
     * Set user to a teacher
     * 
     *@param boolean $boolean
     * @return User
     */
    public function setTeacher($boolean)
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_TEACHER);
        } else {
            $this->removeRole(static::ROLE_TEACHER);
        }

        return $this;
    }
    
    /**
     * Get is a teacher
     *
     * @return boolean 
     */
    public function isTeacher(){
        return $this->hasRole(static::ROLE_TEACHER);
    }
}
