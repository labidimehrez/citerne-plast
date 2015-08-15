<?php

namespace MyApp\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 */
class Utilisateur {

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
     * @ORM\Column(name="login", type="string", length=255,nullable=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255,nullable=true)
     */
    private $password;

    /**
     * @var string $email
     * @ORM\Column(name="email", type="string", length=255,nullable=true)
     * 
     */
    
    private $email;

    /**
     * @var string $privilege
     * @ORM\Column(name="privilege", type="string", length=255,nullable=true)
     */
    private $privilege;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="current_log",type="datetime",nullable=true)
     */
    private $datecurrentlog;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_log",type="datetime",nullable=true)
     */
    private $datelastlog;
    
   /**
     * @var boolean
     *
     * @ORM\Column(name="enabled")
     */
    private $enabled;    
    public function getId() {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPrivilege() {
        return $this->privilege;
    }

    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return \DateTime
     */
    public function getDatecurrentlog()
    {
        return $this->datecurrentlog;
    }

    /**
     * @param \DateTime $datecurrentlog
     */
    public function setDatecurrentlog($datecurrentlog)
    {
        $this->datecurrentlog = $datecurrentlog;
    }

    /**
     * @return \DateTime
     */
    public function getDatelastlog()
    {
        return $this->datelastlog;
    }

    /**
     * @param \DateTime $datelastlog
     */
    public function setDatelastlog($datelastlog)
    {
        $this->datelastlog = $datelastlog;
    }


}
