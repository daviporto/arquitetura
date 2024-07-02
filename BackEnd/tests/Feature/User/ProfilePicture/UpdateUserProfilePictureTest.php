<?php

namespace Tests\Feature\User\ProfilePicture;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UpdateUserProfilePictureTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user/profile-picture/update';

//    public function testUpdateUserProfilePicture()
//    {
//        Storage::fake('public');
//
//        $this->makeEmployee();
//
//        $payload = $this->getPayload();
//
//        $this->employee->update([
//            'profilePicturePath' => 'test'
//        ]);
//
//        $this
//            ->actingAs($this->employee)
//            ->json('POST', self::ROUTE, $payload)
//            ->assertStatus(Response::HTTP_OK)
//            ->assertJsonStructure(['url']);
//
//        $this->employee->refresh();
//
//        $this->assertNotEquals('test', $this->employee->profile_picture_path);
//
//        $explodedUrl = explode('/', $this->employee->profile_picture_path);
//        $this->assertTrue(
//            Storage::disk('public')
//                ->exists(end($explodedUrl))
//        );
//    }

    private function getPayload(): array
    {
        return ['profile_picture' => UploadedFile::fake()->image('profile_picture.jpg')];
    }
}
