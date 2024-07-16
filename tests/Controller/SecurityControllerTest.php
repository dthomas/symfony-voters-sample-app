<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCannotLoginWithNonExistingUser(): void
    {
        // Denied - Can't login with invalid email address.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }

    public function testCannotLoginWithInvalidCredentails()
    {
        // Denied - Can't login with invalid password.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'PersonA',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }

    public function testLoginWithCorrectCredentials()
    {
        $this->client->request('GET', '/login');
        // Success - Login with valid credentials is allowed.
        $this->client->submitForm('Sign in', [
            '_username' => 'PersonA',
            '_password' => 'secret',
        ]);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseStatusCodeSame(200);
        // self::assertResponseIsSuccessful();
    }
}
