<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private $baseUrl      = 'http://web3.adgyde.in/api/v1';
    private $token        = '';
    private $testUserId   = '';
    private $testAlbumId  = '';
    private $testPhotoId  = '';
    private $testTagId    = '';
    private $testPersonId = '';

    public function testUserRegistration() // Success
    {
        $data = [
            'username'    => 'testuser_' . time(),
            'email'       => 'test_' . time() . '@example.com',
            'password'    => 'testpassword',
            'domain_name' => 'testdomain.com',
            'full_name'   => 'Test User',
            'phone'       => '+1234567890',
        ];

        $response = $this->makeRequest('POST', '/users/register', $data);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertNotEmpty($response['data']['data']['user_id']);
        $this->testUserId = $response['data']['data']['user_id'];
    }

    public function testUserLogin() // Success
    {
        $data = [
            'email'    => 'test_' . time() . '@example.com',
            'password' => 'testpassword',
        ];

        $response = $this->makeRequest('POST', '/users/login', $data);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertNotEmpty($response['data']['data']['token']);
        $this->token = $response['data']['data']['token'];
    }

    public function testCreateAlbum()
    {
        $data = [
            'title'       => 'Test Album',
            'description' => 'Test album description',
            'visibility'  => 'public',
        ];

        $response = $this->makeRequest('POST', '/albums/create', $data, true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertNotEmpty($response['data']['data']['album_id']);
        $this->testAlbumId = $response['data']['data']['album_id'];
    }

    public function testUploadPhoto()
    {
        $data = [
            'album_id'    => $this->testAlbumId,
            'file_path'   => 'test.jpg',
            'title'       => 'Test Photo',
            'description' => 'Test photo description',
            'visibility'  => 'public',
        ];

        $response = $this->makeRequest('POST', '/photos/upload', $data, true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertNotEmpty($response['data']['data']['photo_id']);
        $this->testPhotoId = $response['data']['data']['photo_id'];
    }

    public function testCreateTag()
    {
        $data = [
            'name' => 'test_tag',
        ];

        $response = $this->makeRequest('POST', '/tags/create', $data, true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertNotEmpty($response['data']['data']['tag_id']);
        $this->testTagId = $response['data']['data']['tag_id'];
    }

    public function testTagPhoto()
    {
        $data = [
            'tag_id' => $this->testTagId,
        ];

        $response = $this->makeRequest('POST', "/photos/{$this->testPhotoId}/tags", $data, true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
    }

    public function testCreatePerson()
    {
        $data = [
            'name'     => 'Test Person',
            'relation' => 'Family',
        ];

        $response = $this->makeRequest('POST', '/persons/create', $data, true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertNotEmpty($response['data']['data']['person_id']);
        $this->testPersonId = $response['data']['data']['person_id'];
    }

    public function testTagPersonInPhoto()
    {
        $data = [
            'person_id'  => $this->testPersonId,
            'x_position' => 100,
            'y_position' => 150,
        ];

        $response = $this->makeRequest('POST', "/photos/{$this->testPhotoId}/tag-person", $data, true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
    }

    public function testLikePhoto()
    {
        $response = $this->makeRequest('POST', "/photos/{$this->testPhotoId}/like", [], true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
    }

    public function testSetOption()
    {
        $data = [
            'key_name' => 'theme',
            'value'    => 'dark',
        ];

        $response = $this->makeRequest('POST', '/options/set', $data, true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
    }

    public function testAddLog()
    {
        $data = [
            'action'  => 'test_action',
            'details' => 'Test log entry',
        ];

        $response = $this->makeRequest('POST', '/logs/add', $data, true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
    }

    public function testGetUserProfile()
    {
        $response = $this->makeRequest('GET', '/users/profile', [], true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertNotEmpty($response['data']['data']);
    }

    public function testGetAlbums()
    {
        $response = $this->makeRequest('GET', '/albums', [], true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertIsArray($response['data']['data']);
    }

    public function testGetPhotos()
    {
        $response = $this->makeRequest('GET', '/photos', [], true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertIsArray($response['data']['data']);
    }

    public function testGetTags()
    {
        $response = $this->makeRequest('GET', '/tags', [], true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertIsArray($response['data']['data']);
    }

    public function testGetPersons()
    {
        $response = $this->makeRequest('GET', '/persons', [], true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertIsArray($response['data']['data']);
    }

    public function testGetOptions()
    {
        $response = $this->makeRequest('GET', '/options', [], true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertIsArray($response['data']['data']);
    }

    public function testGetLogs()
    {
        $response = $this->makeRequest('GET', '/logs', [], true);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['data']['status']);
        $this->assertIsArray($response['data']['data']);
    }

    private function makeRequest($method, $endpoint, $data = [], $auth = false)
    {
        $ch = curl_init($this->baseUrl . $endpoint);

        $headers = ['Content-Type: application/json'];
        if ($auth && $this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // For testing only

        if ($method !== 'GET' && ! empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("CURL Error: " . $error);
        }

        curl_close($ch);

        // Debug output
        echo "\nRequest URL: " . $this->baseUrl . $endpoint . "\n";
        echo "Method: " . $method . "\n";
        echo "Status Code: " . $status . "\n";
        echo "Response: " . $response . "\n";

        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("JSON Decode Error: " . json_last_error_msg() . "\nResponse: " . $response);
        }

        if ($decodedResponse === null) {
            throw new \Exception("Invalid API Response: " . $response);
        }

        return [
            'status' => $status,
            'data'   => $decodedResponse,
        ];
    }
}
