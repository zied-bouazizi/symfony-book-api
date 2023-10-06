<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;

class BookService
{
    private $httpClient;
    private $serializer;

    public function __construct(HttpClientInterface $httpClient, SerializerInterface $serializer)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    public function getBooks(string $url): JsonResponse
    {
        try {
            $response = $this->httpClient->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2OTY1MTgwNzgsImV4cCI6MTY5NjUyMTY3OCwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6ImFkbWluQGJvb2thcGkuY29tIn0.GgtYSfBvmIhnl2APX8kyF3tWHe2bvR5s_M0XcoNvhy48YJOqRGxHrTThXSi0UF4bvu1ZFzXInogePh8AFrZ0X3O0LnVJItIN7NJZ-i-abZKjyWpqpvlLB7-TaSF4u_axa1j5q-YBm3fZxy6mUZ1I0Ro33EUPBK_SU6br7MEX21KHOgB7XBovv2MDH0rhrVJHHX7EeTO8F01ADVc-TPOeY7PCD3NQ6u3ycJfVdv7UiTmLbIsdhhgPVN5SjudkhDRSMvr9ddsHpPGdsBxTBiuUnIKqOzonOAIG7sJEvUdMQGfj2lcut9llh0d9qrDslE1KRcZGPR2w6CHcF12N98eKtkeuq79qOSbzsRqdVs4P8Xqfs2VRg0tM75jUR9w188ZBfDAQIDsq0Y9mwqhcvHVm5_DWNBCwKj-tc7gqhwTzTfPXwQz4wQVPtFQ1fkKVYPL1XoFROFmhE_F5kE-SepkUTOFavBReEFvJwrUkXeOVlHKqQrjehI5ryNN-9_L0JBVh0vrLjWYvxA0IM49u8dYK-zUL00FEwZeIsZEefD26ekE4y1RkA5OeVH6GUbe138AM4PpCa7Djcuq4sSLEAFR8RgTOsXaAT_YWK78d-o56PikoHVWhApExvhr4Y_Byj3reB4ExIAu4lWSciSJo8Yq82gq26DHw76vcjUiUqRx6B9g',
                ],
            ]);

            // Check if the response status code is OK (200)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                $parsedResponse = $response->toArray();
                $jsonBookList = $this->serializer->serialize($parsedResponse, 'json');

                return new JsonResponse($jsonBookList, JsonResponse::HTTP_OK, [], true);
            } else {
                // Handle non-OK status codes if needed
                return new JsonResponse(['error' => 'API returned a non-OK status code'], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            // Handle any exceptions that might occur during the HTTP request
            return new JsonResponse(['error' => 'An error occurred while fetching data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteBook(string $url): JsonResponse
    {
        $token = $this->getToken();
        $response = $this->httpClient->request('DELETE', $url, [
            'headers' => [
                'Authorization' => $token,
            ],
        ]);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function getToken()
    {
        $url = "http://127.0.0.1:8000/api/login_check";
        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => [
                'username' => 'admin@bookapi.com',
                'password' => 'password',
            ],
        ]);
        dd($response->getContent());
        return $response;
    }
}
