<?php

//namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteControllersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

        $test_params = [
            [
                "type" => "string"
            ],
            [
                "type" => "string",
                "--class" => "TestController"
            ],
//            [
//                "type" => "string",
//                "--path" => "Auth"
//            ],
//            [
//                "type" => "string",
//                "--path" => "Auth",
//                "--class" => "RegisterController"
//            ],
        ];
        foreach ($test_params as $params) {
            echo "Test \n";
            print_r($params);
            $exitCode = Artisan::call('route-controllers:generate',$params);
            $code = Artisan::output();
            eval($code);
            echo $code;

            $this->assertTrue($exitCode==0);
            echo "-------------------------";
        }
        // --path=Auth --class=RegisterController
        $response = $this->get('/simple');
        echo $response->content();
        $response->assertStatus(200);
    }
}
