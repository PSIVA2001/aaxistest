<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\ApiTestCase;

class UserTest extends ApiTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }
    public function testCreateUser(): void
    {
        $user = $this->createUser();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->getUsername(), "adminone");
        $this->assertIsArray($user->getRoles());
    }

    public function createUser(): User
    {
        $user = new User();
        $user->setUsername("adminone");
        $user->setRoles(['ROLE_ADMIN']);
        $plainPassword = '123456';
        $hashedpassword = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedpassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}