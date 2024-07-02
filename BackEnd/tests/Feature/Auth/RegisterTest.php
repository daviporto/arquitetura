<?php

namespace Tests\Feature\Auth;

use App\Domain\User\UserTypeEnum;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    const ROUTE = self::BASE_ROUTE . 'auth/register';


    public function testUniqueEmail()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $payload = $this->generatePayload();
        $payload['email'] = $user->email;

        $this->json('POST', self::ROUTE, $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['email']
            ])
            ->assertJson([
                'errors' => [
                    'email' => [
                        'Email is already in use'
                    ]
                ]
            ]);
    }

    public function testUniquePhone()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $payload = $this->generatePayload();
        $payload['phone'] = $user->phone;

        $this->json('POST', self::ROUTE, $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['phone']
            ])
            ->assertJson([
                'errors' => [
                    'phone' => [
                        'Phone number is already in use'
                    ]
                ]
            ]);
    }

    public function testInUserTypeValidation()
    {
        $payload = $this->generatePayload();
        $payload['type'] = 'not a valid type';

        $this->json('POST', self::ROUTE, $payload)
            ->assertJsonStructure([
                'message',
                'errors' => ['type']
            ])
            ->assertJson([
                'message' => 'Type must be one of the following: recruiter,employee',
                'errors' => [
                    'type' => ['Type must be one of the following: recruiter,employee']
                ]
            ]);
    }

    public function testMissingRequiredFields()
    {
        $this->json('POST', self::ROUTE)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'password',
                    'email',
                    'phone',
                    'type'
                ]
            ])
            ->assertJson([
                'message' => 'Name cannot be empty (and 4 more errors)',
                'errors' => [
                    'name' => ['Name cannot be empty'],
                    'password' => ['Password cannot be empty'],
                    'email' => ['Email cannot be empty'],
                    'phone' => ['Phone number cannot be empty'],
                    'type' => ['Type cannot be empty']
                ]
            ]);
    }

    public function testRegisterUserSuccess()
    {
        $payload = $this->generatePayload();

        $response = $this->json('POST', self::ROUTE, $payload);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'name' => $payload['name'],
            'type' => $payload['type'],
            'about_me' => $payload['about_me']
        ]);
    }

    /**
     * @return array{
     *      name: string,
     *      password: string,
     *      email: string,
     *      phone: string
     * }
     */
    public function generatePayload(): array
    {
        return [
            'name' => $this->faker->name,
            'password' => $this->faker->password,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => str_replace('+', '', $this->faker->unique()->e164PhoneNumber()),
            'type' => UserTypeEnum::Recruiter->value,
            'about_me' => $this->faker->paragraph()
        ];
    }

//    public function testWithProfilePicture()
//    {
//        Storage::fake('public');
//
//        $image = UploadedFile::fake()->image('profile_picture.jpg');
//
//        $payload = $this->generatePayloadWithProfilePicture($image);
//
//        $this->json(
//            'POST',
//            self::ROUTE,
//            $payload
//        )->assertStatus(Response::HTTP_NO_CONTENT);
//
//        $this->assertDatabaseHas('users', [
//            'email' => $payload['email'],
//            'phone' => $payload['phone'],
//            'name' => $payload['name'],
//            'type' => $payload['type'],
//            'about_me' => $payload['about_me']
//        ]);
//
//        $user = $this->getUserFromPayload($payload);
//
//        $this->assertNotNull($user->profile_picture_path);
//    }

    private function generatePayloadWithProfilePicture($file): array
    {
        return ['profile_picture' => $file] + $this->generatePayload();
    }

    public function getUserFromPayload(array $payload): User
    {
        return User::where('email', $payload['email'])
            ->where('phone', $payload['phone'])
            ->where('name', $payload['name'])
            ->where('type', $payload['type'])
            ->where('about_me', $payload['about_me'])
            ->whereNotNull('profile_picture_path')
            ->latest()
            ->first();
    }
}
