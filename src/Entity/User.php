<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


use Symfony\Component\Validator\Constraints as Assert ;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Security\Core\User\UserInterface;

//table User correspond à nos utilisateurs qui implémente user interface 
//L’entité User doit implémenter l’interface UserInterface pour qu’elle soit gérée par le composant Symfony security. 

/**
 * @ORM\Entity
* @UniqueEntity(fields="email", message="Email already taken")
* @UniqueEntity(fields="username", message="Username already taken")
*/

class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * 
    */
    private $username;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     * @ORM\Column(type="string", length=64)
     */

    private $password;

    

    public $confirm_password;


   /**
     * @ORM\Column(type="array")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = array('ROLE_USER');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

   
    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }


    //renvoie le role de certain utilisateur 
    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }





}
