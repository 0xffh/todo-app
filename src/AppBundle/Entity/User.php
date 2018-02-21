<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, \Serializable, \JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $password
     */
    public function setPlainPassword(string $password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
                $this->plainPassword
            ]
        );
    }

    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->username,
            $this->password,
            $this->plainPassword
        ]
            = unserialize($serialized);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'       => $this->id,
            'username' => $this->username,
        ];
    }
}
