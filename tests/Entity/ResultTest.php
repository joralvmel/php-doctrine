<?php

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    private Result $result;
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User('testuser', 'test@example.com', 'password123', true, false);
        $this->result = new Result(100, $this->user, new DateTime('2023-01-01 00:00:00'));
    }

    public function testGetResult(): void
    {
        $this->assertEquals(100, $this->result->getResult());
    }

    public function testSetResult(): void
    {
        $this->result->setResult(200);
        $this->assertEquals(200, $this->result->getResult());
    }

    public function testGetUser(): void
    {
        $this->assertEquals($this->user, $this->result->getUser());
    }

    public function testSetUser(): void
    {
        $newUser = new User('newuser', 'new@example.com', 'newpassword', false, true);
        $this->result->setUser($newUser);
        $this->assertEquals($newUser, $this->result->getUser());
    }

    public function testGetTime(): void
    {
        $this->assertEquals('2023-01-01 00:00:00', $this->result->getTime()->format('Y-m-d H:i:s'));
    }

    public function testSetTime(): void
    {
        $newTime = new DateTime('2023-12-31 23:59:59');
        $this->result->setTime($newTime);
        $this->assertEquals('2023-12-31 23:59:59', $this->result->getTime()->format('Y-m-d H:i:s'));
    }
}