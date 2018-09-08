<?php

namespace App\tests\Entity;

use App\Entity\Post;

use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testGetSetTitle()
    {
        $post = new Post();
        $result = $post->setTitle("12345");

        // assert that your calculator added the numbers correctly!
        $this->assertEquals("12345", $result->getTitle());
    }
}
