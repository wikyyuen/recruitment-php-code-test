<?php


namespace Test\App;


use App\Service\AppLogger;
use App\App\Demo;
use App\App\HttpRequest;
use PHPUnit\Framework\TestCase;

class DemoTest extends TestCase
{
    public function test_get_user_info()
    {
        $expetation = '{"error":0,"data":{"id":1,"username":"hello world"}}';

        $logger = new AppLogger();
        $demo = new Demo($logger,new HttpRequest());
        $result = $demo->get_user_info();

        $this->assertEquals($expetation, $result);
    }
}