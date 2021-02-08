<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BooksAPITest extends WebTestCase
{
    private $author = [
        'name' => 'Автор из теста'
    ];

    private $book = [
        'name_en' => 'book from test',
        'name_ru' => 'Книга из теста',
    ];

    public function testIndexBook()
    {
        $client = static::createClient();
        $client->request('GET', '/book/');
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $this->assertResponseIsSuccessful();
    }


    public function testShowBook()
    {
        $client = static::createClient();
        $client->request('GET', '/en/book/10');
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $this->assertResponseIsSuccessful();
    }

    public function testSearchBook()
    {
        $client = static::createClient();
        $client->request('GET', '/book/search?query=best');
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $this->assertResponseIsSuccessful();
    }

    public function testStoreBook()
    {
        $client = static::createClient();
        $client->request('POST', '/book/create', $this->book);
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $this->assertResponseIsSuccessful();
    }

    public function testIndexAuthor()
    {
        $client = static::createClient();
        $client->request('GET', '/author/');
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $this->assertResponseIsSuccessful();
    }


    public function testStoreAuthor()
    {
        $client = static::createClient();
        $client->request('POST', '/author/create', $this->author);
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $this->assertResponseIsSuccessful();
    }
}
