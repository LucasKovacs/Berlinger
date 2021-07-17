<?php

namespace Tests\Feature;

use App\Models\Picture;
use Tests\TestCase;

class PictureTest extends TestCase
{
    public function testGetAllPicturesNotEmpty(): void
    {
        $picture = Picture::factory()->create();

        $response = $this->json('GET', '/api/pictures/');

        $response->assertStatus(200)
            ->assertExactJson([
                'success' => true,
                'code' => 200,
                'error' => '',
                'results' => [
                    [
                        'id' => $picture->id,
                        'title' => $picture->title,
                        'url' => $picture->url,
                        'description' => $picture->description,
                        'exif' => $picture->exif,
                        'created_at' => $picture->created_at,
                        'updated_at' => $picture->updated_at,
                    ],
                ],
            ]);
    }

    public function testGetAllPicturesStructureOk(): void
    {
        $response = $this->json('GET', '/api/pictures/')
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'success',
                    'code',
                    'error',
                    'results',
                ]
            );
    }

    public function testGetAllPicturesEmpty(): void
    {
        $response = $this->json('GET', '/api/pictures')
            ->assertStatus(200)
            ->assertJson([
                "success" => false,
                "code" => 200,
                "error" => "No results",
                'results' => [],
            ]);
    }

    public function testGetOnePicture(): void
    {
        $picture = Picture::factory()->create();

        $response = $this->json('GET', '/api/pictures/' . $picture->id);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'id' => $picture->id,
                    'title' => $picture->title,
                    'url' => $picture->url,
                    'description' => $picture->description,
                    'exif' => $picture->exif,
                    'created_at' => $picture->created_at,
                    'updated_at' => $picture->updated_at,
                ]
            );
    }
}
