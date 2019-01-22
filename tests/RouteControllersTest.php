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
        $test_routes = [
            'get' => [
                'simple' => [
                    'route_params' => [],
                    'form_params' => [],
                ],
                'with-param' => [
                    'route_params' => ['p1','p2'],
                    'form_params' => [],
                ],
            ],
            'post' => [
                'simple' => [
                    'route_params' => [],
                    'form_params' => [],
                ]
            ],
            'any' => [
                'simple' => [
                    'route_params' => [],
                    'form_params' => [],
                ]
            ],
            'put' => [
                'simple' => [
                    'route_params' => [],
                    'form_params' => [],
                ]
            ],
            'patch' => [
                'simple' => [
                    'route_params' => [],
                    'form_params' => [],
                ]
            ],
            'options' => [
                'simple' => [
                    'route_params' => [],
                    'form_params' => [],
                ]
            ],
            'delete' => [
                'simple' => [
                    'route_params' => [],
                    'form_params' => [],
                ]
            ]
        ];

        $post_routes = ['simple'];
        foreach ($test_routes as $verb => $item) {
            foreach ($item as $route => $params) {
                $realRoute = count($params['route_params'])?$route."/".join("/",$params['route_params']):$route;
                switch ($verb) {
                    case 'get':
                    case 'any':
                        $response = $this->get($realRoute);
                        echo $response->content() . "\n";
                        $response->assertStatus(200);
                        break;
                    case 'post':
                        $response = $this->post($realRoute,$params['form_params']);
                        echo $response->content() . "\n";
                        $response->assertStatus(200);
                        break;
                    case 'put':
                        $response = $this->put($realRoute,$params['form_params']);
                        echo $response->content() . "\n";
                        $response->assertStatus(200);
                        break;
                    case 'patch':
                        $response = $this->patch($realRoute,$params['form_params']);
                        echo $response->content() . "\n";
                        $response->assertStatus(200);
                        break;
                    case 'options':
//                        $response = $this->($realRoute,$params['form_params']);
//                        echo $response->content();
//                        $response->assertStatus(200);
                        break;
                    case 'delete':
                        $response = $this->delete($realRoute,$params['form_params']);
                        echo $response->content() . "\n";
                        $response->assertStatus(200);
                        break;
                    default:
                        throw new \Exception("Invalid verb  $verb");
                }
            }


        }
    }
}
