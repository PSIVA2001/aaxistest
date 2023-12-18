<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class TokenService
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher,
                                private readonly JWTEncoderInterface         $encoder,
                                private readonly EntityManagerInterface      $entityManager)
    {

    }

    public function generateToken($username, $password)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        if (!$user) {
            throw new UserNotFoundException();
        }
        $isValid = $this->hasher->isPasswordValid($user, $password);
        if (!$isValid) {
            throw new BadCredentialsException();
        }
        $token = $this->encoder->encode([
            'username' => $user->getUsername(),
            'exp' => time() + 3600 // 1 hour expiration
        ]);
        return $token;
    }

}