<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase {

    public function testShow() {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Todo', $client->getResponse()->getContent());
    }

    public function testCreateTodo() {
        $client = static::createClient();

        $client->request('POST', '/');
        $crawler = $client->submitForm('create_todo[save]', ['create_todo[todo]' => 'test1']);

        // Assert
        $this->assertContains('test1', $client->getResponse()->getContent());
    }

}
