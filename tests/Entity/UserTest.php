<?php

use MiW\Results\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User('testuser', 'test@example.com', 'password123', true, false);
    }

    public function testGetUsername(): void
    {
        $this->assertEquals('testuser', $this->user->getUsername());
    }

    public function testSetUsername(): void
    {
        $this->user->setUsername('newuser');
        $this->assertEquals('newuser', $this->user->getUsername());
    }

    public function testGetEmail(): void
    {
        $this->assertEquals('test@example.com', $this->user->getEmail());
    }

    public function testSetEmail(): void
    {
        $this->user->setEmail('new@example.com');
        $this->assertEquals('new@example.com', $this->user->getEmail());
    }

    public function testPasswordHashing(): void
    {
        $this->assertTrue($this->user->validatePassword('password123'));
    }

    public function testSetPassword(): void
    {
        $this->user->setPassword('newpassword');
        $this->assertTrue($this->user->validatePassword('newpassword'));
    }

    public function testIsEnabled(): void
    {
        $this->assertTrue($this->user->isEnabled());
    }

    public function testSetEnabled(): void
    {
        $this->user->setEnabled(false);
        $this->assertFalse($this->user->isEnabled());
    }

    public function testIsAdmin(): void
    {
        $this->assertFalse($this->user->isAdmin());
    }

    public function testSetIsAdmin(): void
    {
        $this->user->setIsAdmin(true);
        $this->assertTrue($this->user->isAdmin());
    }
}