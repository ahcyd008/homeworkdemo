<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Answer
 *
 * @ORM\Table()
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AnswerRepository")
 */
class Answer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;
    
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="text", nullable=true)
     */
    private $username;

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
     * Set content
     *
     * @param string $content
     * @return Answer
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
    
        
    /**
     * Set username
     *
     * @param string $username
     * @return Homework
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="answer_file", fileNameProperty="name")
     * 
     * @var File $file
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255, name="name", nullable=true)
     *
     * @var string $name
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime $updatedAt
     */
    protected $updatedAt;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Attachment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * Get FileUri
     *
     * @return string 
     */
    public function getFileUri()
    {
        $value = $this->name;
        try{
           if($value){
               $value = substr($value,strpos($value,'_')+1, strlen($value)-strpos($value,'_')); 
           }
        } catch (Exception $ex) {

        }
        return "<a href='/homework/answers/".$this->name."'>".$value."</a>";
    }
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Homework", inversedBy="answers", cascade={"persist"})
     * @ORM\JoinTable(name="homeworks_answers")
     */
    protected $homeowrks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->homeowrks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add homeowrks
     *
     * @param \AppBundle\Entity\Homework $homeowrks
     * @return Answer
     */
    public function addHomeowrk(\AppBundle\Entity\Homework $homeowrks)
    {
        $this->homeowrks[] = $homeowrks;

        return $this;
    }

    /**
     * Remove homeowrks
     *
     * @param \AppBundle\Entity\Homework $homeowrks
     */
    public function removeHomeowrk(\AppBundle\Entity\Homework $homeowrks)
    {
        $this->homeowrks->removeElement($homeowrks);
    }

    /**
     * Get homeowrks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHomeowrks()
    {
        return $this->homeowrks;
    }
}
