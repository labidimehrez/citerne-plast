<?php

namespace MyApp\FrontofficeBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 */
class Message
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255,nullable=true)
     */

    private $name;
    /**
     * @var string $email
     * @ORM\Column(name="email", type="string", length=255,nullable=true)
     *
     */

    private $email;

    /**
     * @var string $subject
     * @ORM\Column(name="subject", type="string", length=255,nullable=true)
     */
    private $subject;

    /**
     * /**
     * @var string $message
     * @ORM\Column(name="message", type="text", length=255,nullable=true)
     */
    private $message;
    /**
     * @var boolean
     *
     * @ORM\Column(name="lu",nullable=true)
     */
    private $lu;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation",type="datetime",nullable=true)
     */
    private $dateCreation;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return boolean
     */
    public function isLu()
    {
        return $this->lu;
    }

    /**
     * @param boolean $lu
     */
    public function setLu($lu)
    {
        $this->lu = $lu;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param \DateTime $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }


}
