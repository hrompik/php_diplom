<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['phone'], message: 'Такой телефон уже занят')]
#[UniqueEntity(fields: ['email'], message: 'Такая почта уже занята')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Минимальная длина пароля 6',
        maxMessage: 'Максимальная длина пароля 50',
    )]
    private ?string $plainPassword = null;
    private ?string $plainPasswordReply = null;


    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->plainPasswordReply !== $this->plainPassword) {
            $context->buildViolation('Пароли должны совпадать!')
                ->atPath('plainPassword')
                ->addViolation();
        }
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPlainPasswordReply(): ?string
    {
        return $this->plainPasswordReply;
    }

    public function setPlainPasswordReply(?string $plainPasswordReply): void
    {
        $this->plainPasswordReply = $plainPasswordReply;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->id;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = self::clearPhone($phone);
        return $this;
    }

    public static function clearPhone(string $phone): string
    {
        $phone = str_replace([' ', '(', ')', '-', '_', '+'], '', $phone);
        return substr($phone, 1);
    }

    public function getFormattedPhone(): string
    {
        $phone = $this->getPhone();
        return '+7 (' .
            substr($phone, 0, 3) .
            ') ' .
            substr($phone, 3, 3) .
            '-' .
            substr($phone, 6, 2) .
            '-' .
            substr($phone, 8, 2);
    }

    public function getFio(): ?string
    {
        return $this->fio;
    }

    public function setFio(?string $fio): self
    {
        $this->fio = $fio;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
