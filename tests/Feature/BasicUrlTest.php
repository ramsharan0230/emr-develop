<?php

namespace Tests\Feature;

use App\CogentUsers;
use Tests\TestCase;

class BasicUrlTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        $appURL = env('APP_URL', "http://localhost/chirayu/public");

        $urls = [
            '/admin/dashboard',
            '/patient'
        ];

        $user = factory(CogentUsers::class)->create();

        echo PHP_EOL;

        foreach ($urls as $url) {
            $response = $this->actingAs($user)->get($url);
            print_r($response);
            if ((int)$response->status() !== 200) {
                echo $appURL . $url . ' (FAILED) did not return a 200.';
                $this->assertTrue(false);
            } else {
                echo $appURL . $url . ' (success ?)';
                $this->assertTrue(true);
            }
            echo PHP_EOL;
        }

    }
}
