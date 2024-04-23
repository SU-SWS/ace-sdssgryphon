<?php

namespace Gryphon\Blt\Plugin\Commands;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GryphonNetDBCommands {
{
  public function __construct()
  {
    $this->client_id = getenv('services.netdb.key');
    $this->client_secret = getenv('services.netdb.secret');
    //currently set to development version.
    $this->api_base_url = 'https://netdb-api.stanford.edu';
    parent::__construct();
  }

  protected function getAccessToken()
  {
    $provider = new GenericProvider([
      'clientId'                => $this->client_id,
      'clientSecret'            => $this->client_secret,
      'urlAuthorize'            => '',
      'urlAccessToken'          => 'https://authz.itlab.stanford.edu/token',
      'urlResourceOwnerDetails' => '',
      'scopes'                  => 'netdb:all',
    ]);

    try {
      $token = $provider->getAccessToken('client_credentials');
      return $token->jsonSerialize();
    } catch (IdentityProviderException $e) {
      exit($e->getMessage());
    }
  }

  /**
   * Use a GET method to retrieve information from the API.
   */
  public function get(string $url, array $queryStrings = [], $content_type = 'application/json')
  {
    $data = [
      'headers' => [
        'Authorization' => 'Bearer ' . $this->token['access_token'],
        'Content-Type' => $content_type,
        'Accept-Encoding' => 'gzip',
        'decode_content' => 'gzip',
      ],
    ];
    if (!empty($queryStrings)) {
      $data['query'] = $queryStrings;
    }
    try {
      $response = $this->client->request('GET', $url, $data);
      return $this->castResponseAsArray($response);
    } catch (RequestException $e) {
      $response = [
        'GuzzleRequestException' => [
          'Class' => $e::class,
          'Request' => Psr7\Message::toString($e->getRequest()),
        ],
      ];
      if ($e->hasResponse()) {
        $response['GuzzleRequestException']['Response'] = Psr7\Message::toString($e->getResponse());
        $response['GuzzleRequestException']['Object'] = var_export(json_decode($e->getResponse()->getBody(), true), true);
      }
      return $response;
    }
  }
}
