<?php

namespace SERCOM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;



/**
 * Person
 * @ORM\Entity(repositoryClass="SERCOM\AppBundle\Entity\Repository\PersonRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="person")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 *
 */

class Person implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     * @ORM\Column(name="person_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $person_id;
    /**
     * @var string
     * @Assert\Length(min = "2", max = "50")
     * @Assert\NotBlank()
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     */
    private $lastname;
    /**
     * @var string
     * @Assert\Length(min = "2", max = "50")
     * @Assert\NotBlank()
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     */
    private $firstname;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(checkHost=true, message="Adresse mail non valide")
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(name="tel", type="string", length=25, nullable=true)
     */
    private $tel ;
    /**
     * @var string
     * @ORM\Column(name="telgsm", type="string", length=25, nullable=true)
     */
    private $telgsm;
    /**
     * @var string
     * @ORM\Column(name="ban", type="boolean", nullable=false)
     */
    private $ban;
    /**
     * @var string
     * @ORM\Column(name="activationcode", type="string", length=255, nullable=true, unique=true)
     */
    private $activationcode;
    /**
     * @var string
     * @ORM\Column(name="emailvalid", type="boolean", nullable=false)
     */
    private $emailvalid;
    /**
     * @var string
     * @ORM\Column(name="validate", type="boolean", nullable=false)
     */
    private $validate;
    /**
     * @var string
     * @Assert\Length(min = "6", max = "25")
     * @Assert\NotBlank()
     * @ORM\Column(name="username", type="string", length=25, nullable=false, unique=true)
     */
    private $username;
    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;
    /**
     * @var string
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity="SERCOM\AppBundle\Entity\Address", inversedBy="persons")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="address_id", referencedColumnName="address_id", nullable=true)})
     */
    private $address;
    /**
    * @var
    * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\Country", inversedBy="persons")
    * @ORM\JoinTable(name="person_country",
    * 	joinColumns={
    *		@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
    *       inverseJoinColumns={
    *		@ORM\JoinColumn(name="country_id", referencedColumnName="country_id")})
    */
    private $countries;
    /**
     * @ORM\OneToOne(targetEntity="SERCOM\AppBundle\Entity\Member", mappedBy="person", cascade={"all"})
     */
    private $member;
    /**
     * @ORM\OneToOne(targetEntity="SERCOM\AppBundle\Entity\Teacher", mappedBy="person" , cascade={"all"})
     */
    private $teacher;
    /**
     * @ORM\OneToOne(targetEntity="SERCOM\AppBundle\Entity\Student", mappedBy="person" , cascade={"all"})
     */
    private $student;
    /**
     * @var string
     * @ORM\Column(name="date_inscription", type="datetime", nullable=false)
     */
    private $date_inscription;
    /**
     * @var Tag
     * @ORM\ManyToMany(targetEntity="SERCOM\AppBundle\Entity\SiteGroup", inversedBy="persons", cascade={"all"})
     * @ORM\JoinTable(name="person_sitegroups",
     * 	joinColumns={
     *		@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
     *       inverseJoinColumns={
     *		@ORM\JoinColumn(name="sitegroup_id", referencedColumnName="sitegroup_id")})
     */
    private $sitegroups;
    /**
     * @Assert\Image(maxSize="5M", mimeTypes={"image/jpeg","image/gif","image/png"}, mimeTypesMessage="Format de fichier non valide", maxSizeMessage="Le fichier selectionné est trop gros")
     */
    private $file;




    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }



    /**
     * Set password
     *
     * @param string $password
     * @return Person
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
        return serialize(array(
            $this->person_id,
            $this->username,
            $this->password

        ));
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
        list ( $this->person_id , $this->username ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
       // return array('ROLE_USER');
        $roles = $this->getSitegroups();
        $list = array();
        foreach ( $roles as $role){
            $v = $role->getName();
            array_push($list, $v);
        }
        return $list;

    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return $this->getDateInscription()->format('Y-m-d H:i:s');

    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.

    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        // TODO: Implement isAccountNonExpired() method.
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        // TODO: Implement isAccountNonLocked() method.
        return (!$this->ban);
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        // TODO: Implement isCredentialsNonExpired() method.
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
        return $this->validate;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sitegroups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get person_id
     *
     * @return integer 
     */
    public function getPersonId()
    {
        return $this->person_id;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Person
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Person
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Person
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Person
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set telgsm
     *
     * @param string $telgsm
     * @return Person
     */
    public function setTelgsm($telgsm)
    {
        $this->telgsm = $telgsm;

        return $this;
    }

    /**
     * Get telgsm
     *
     * @return string 
     */
    public function getTelgsm()
    {
        return $this->telgsm;
    }

    /**
     * Set ban
     *
     * @param boolean $ban
     * @return Person
     */
    public function setBan($ban)
    {
        $this->ban = $ban;

        return $this;
    }

    /**
     * Get ban
     *
     * @return boolean 
     */
    public function getBan()
    {
        return $this->ban;
    }

    /**
     * Set activationcode
     *
     * @param string $activationcode
     * @return Person
     */
    public function setActivationcode($activationcode)
    {
        $this->activationcode = $activationcode;

        return $this;
    }

    /**
     * Get activationcode
     *
     * @return string 
     */
    public function getActivationcode()
    {
        return $this->activationcode;
    }

    /**
     * Set emailvalid
     *
     * @param boolean $emailvalid
     * @return Person
     */
    public function setEmailvalid($emailvalid)
    {
        $this->emailvalid = $emailvalid;

        return $this;
    }

    /**
     * Get emailvalid
     *
     * @return boolean 
     */
    public function getEmailvalid()
    {
        return $this->emailvalid;
    }

    /**
     * Set validate
     *
     * @param boolean $validate
     * @return Person
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Get validate
     *
     * @return boolean 
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Person
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set date_inscription
     *
     * @param \DateTime $dateInscription
     * @return Person
     */
    public function setDateInscription($dateInscription)
    {
        $this->date_inscription = $dateInscription;

        return $this;
    }

    /**
     * Get date_inscription
     *
     * @return \DateTime 
     */
    public function getDateInscription()
    {
        return $this->date_inscription;
    }

    /**
     * Set address
     *
     * @param \SERCOM\AppBundle\Entity\Address $address
     * @return Person
     */
    public function setAddress(\SERCOM\AppBundle\Entity\Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \SERCOM\AppBundle\Entity\Address 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add countries
     *
     * @param \SERCOM\AppBundle\Entity\Country $countries
     * @return Person
     */
    public function addCountry(\SERCOM\AppBundle\Entity\Country $countries)
    {
        $this->countries[] = $countries;

        return $this;
    }

    /**
     * Remove countries
     *
     * @param \SERCOM\AppBundle\Entity\Country $countries
     */
    public function removeCountry(\SERCOM\AppBundle\Entity\Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Set member
     *
     * @param \SERCOM\AppBundle\Entity\Member $member
     * @return Person
     */
    public function setMember(\SERCOM\AppBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \SERCOM\AppBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set teacher
     *
     * @param \SERCOM\AppBundle\Entity\Teacher $teacher
     * @return Person
     */
    public function setTeacher(\SERCOM\AppBundle\Entity\Teacher $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \SERCOM\AppBundle\Entity\Teacher 
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set student
     *
     * @param \SERCOM\AppBundle\Entity\Student $student
     * @return Person
     */
    public function setStudent(\SERCOM\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \SERCOM\AppBundle\Entity\Student 
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Add sitegroups
     *
     * @param \SERCOM\AppBundle\Entity\SiteGroup $sitegroups
     * @return Person
     */
    public function addSitegroup(\SERCOM\AppBundle\Entity\SiteGroup $sitegroups)
    {
        $this->sitegroups[] = $sitegroups;

        return $this;
    }

    /**
     * Remove sitegroups
     *
     * @param \SERCOM\AppBundle\Entity\SiteGroup $sitegroups
     */
    public function removeSitegroup(\SERCOM\AppBundle\Entity\SiteGroup $sitegroups)
    {
        $this->sitegroups->removeElement($sitegroups);
    }

    /**
     * Get sitegroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSitegroups()
    {
        return $this->sitegroups;
    }

    public function __toString(){
        return $this->lastname." ".$this->firstname;

    }

    public function isGranted($role){
        return in_array($role, $this->getRoles());
    }


}
