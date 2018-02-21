<?php

namespace AppBundle\Entity;

/**
 * Task
 */
class Task implements \Serializable, \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $content;
    
    /**
     * @var \DateTime
     */
    private $createdAt;
    
    /**
     * @var boolean
     */
    private $isCompleted;
    
    /**
     * @var User
     */
    private $user;
    
    /**
     * Task constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->createdAt   = new \DateTime();
        $this->isCompleted = false;
        $this->user = $user;
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }
    
    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
    
    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }
    
    /**
     * @param bool $isCompleted
     */
    public function setIsCompleted(bool $isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }
    
    public function setIsCompletedTrue()
    {
        $this->isCompleted = true;
    }
    
    public function setIsCompletedFalse()
    {
        $this->isCompleted = false;
    }
    
    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    
    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->content,
                $this->createdAt,
                $this->isCompleted,
                $this->user,
            ]
        );
    }
    
    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->content,
            $this->createdAt,
            $this->isCompleted,
            $this->user,
        ]
            = unserialize($serialized);
    }
    
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'          => $this->id,
            'content'     => $this->content,
            'createdAt'   => $this->createdAt,
            'isCompleted' => $this->isCompleted,
        ];
    }
}

