<?php

namespace Tests;

use App;
use App\Domain\User\UserTypeEnum;
use App\Models\User;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    const BASE_ROUTE = 'api/';

    protected User $employee;
    protected User $recruiter;
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();

        $mock = Mockery::mock(App\Http\Middleware\Authenticate::class)
            ->shouldReceive('handle')
            ->andReturnUsing(function ($request, $next, ...$guards) {
                return $next($request);
            });

        App::instance(App\Http\Middleware\Authenticate::class, $mock->getMock());

        $this->faker = Faker::create('pt_BR');
    }

    public function tearDown(): void
    {
        App::bind(App\Http\Middleware\Authenticate::class, App\Http\Middleware\Authenticate::class);

        parent::tearDown();
    }

    protected function makeEmployee(): void
    {
        $this->employee = User::factory()->create([
            'type' => UserTypeEnum::Employee->value,
        ]);
    }

    protected function makeRecruiter(): void
    {
        $this->employee = User::factory()->create([
            'type' => UserTypeEnum::Recruiter->value,
        ]);
    }
}
