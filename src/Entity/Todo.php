<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodoRepository")
 */
class Todo {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $todo;

    public function getId(): int {
        return $this->id;
    }

    public function getTodo() {
        return $this->todo;
    }

    public function setTodo(string $todo): self {
        $this->todo = $todo;

        return $this;
    }

}
