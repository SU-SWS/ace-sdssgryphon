<?php

/**
 * @file
 * SAML config settings.
 */

use Acquia\Blt\Robo\Common\EnvironmentDetector;

// SimpleSAMLphp configuration
// Set the workgroup api cert paths.
$config['stanford_ssp.settings'] = [
  'workgroup_api_cert' => DRUPAL_ROOT . "/../keys/saml/workgroup_api.cert",
  'workgroup_api_key' => DRUPAL_ROOT . "/../keys/saml/workgroup_api.key",
];

if (EnvironmentDetector::isAhEnv()) {
  $group = EnvironmentDetector::getAhGroup();
  $environment = EnvironmentDetector::getAhEnv();

  // SimpleSAMLphp configuration
  // Set the workgroup api cert paths.
  $config['stanford_ssp.settings'] = [
    'workgroup_api_cert' => "/mnt/gfs/$group.$environment/nobackup/apikeys/saml/workgroup_api.cert",
    'workgroup_api_key' => "/mnt/gfs/$group.$environment/nobackup/apikeys/saml/workgroup_api.key",
  ];
}

$config['simplesamlphp_auth.settings'] = [
  'langcode' => 'en',
  'default_langcode' => 'en',
  'activate' => TRUE,
  'mail_attr' => 'mail',
  'unique_id' => 'uid',
  'user_name' => 'uid',
  'auth_source' => 'default-sp',
  'login_link_display_name' => 'Stanford Login',
  'header_no_cache' => TRUE,
  'user_register_original' => 'visitors',
  'register_users' => TRUE,
  'autoenablesaml' => TRUE,
  'debug' => FALSE,
  'secure' => TRUE,
  'httponly' => TRUE,
  'role' => [
    'eval_every_time' => 2,
  ],
  'allow' => [
    'set_drupal_pwd' => FALSE,
    'default_login' => TRUE,
  ],
  'sync' => [
    'mail' => TRUE,
    'user_name' => TRUE,
  ],
];


// Don't enable SAML configs if we're on CI systems.
if (!EnvironmentDetector::isCiEnv()) {
  $env = EnvironmentDetector::getAhEnv() ?: '';
  $normalized_env = "01dev";
  $idp = 'https://idp-uat.stanford.edu/';
  $login = 'https://login-uat.stanford.edu/idp/profile/SAML2/Redirect/SSO';

  if (EnvironmentDetector::isAhStageEnv()) {
    $normalized_env = "01test";
  } elseif (EnvironmentDetector::isAhProdEnv()) {
    $normalized_env = "01live";
    $idp = 'https://idp.stanford.edu/';
    $login = 'https://login.stanford.edu/idp/profile/SAML2/Redirect/SSO';
  }

  $config['samlauth.authentication'] = [
    'user_name_attribute' => 'uid',
    'idp_entity_id' => $idp,
    'sp_entity_id' => "https://acquiacloudsitefactory.stanford.edu/$normalized_env",
    'idp_single_sign_on_service' => $login,
    'sp_x509_certificate' => 'file:' . EnvironmentDetector::getAhFilesRoot() . '/nobackup/apikeys/saml/cert/saml.crt',
    'sp_private_key' => 'file:' . EnvironmentDetector::getAhFilesRoot() . '/nobackup/apikeys/saml/cert/saml.pem',
    'idp_certs' => [
      'file:' . EnvironmentDetector::getAhFilesRoot() . '/nobackup/apikeys/saml/cert/signing.crt',
    ],
  ];
  $config['stanford_samlauth.settings'] = [
    'role_mapping' => [
      'workgroup_api' => [
        'cert' => EnvironmentDetector::getAhFilesRoot() . '/nobackup/apikeys/saml/workgroup_api.cert',
        'key' => EnvironmentDetector::getAhFilesRoot() . '/nobackup/apikeys/saml/workgroup_api.key',
      ],
    ],
  ];
}
