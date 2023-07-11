<?php

namespace Drupal\commerce_envialia;

use Drupal\Core\Session\AccountInterface;

class consulta_Envio
{
  protected $currentUser;

  /**
   * CustomService constructor.
   * @param AccountInterface $currentUser
   */
  public function __construct(AccountInterface $currentUser) {
    $this->currentUser = $currentUser;
  }

  function consultaEnvio($id_session, $albaran, $api_url, $api_agencia)
  {
    $xml = '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope
            xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema">
            <soap:Header>
            <ROClientIDHeader xmlns="http://tempuri.org/">
            <ID>' . $id_session . '</ID>
            </ROClientIDHeader>
            </soap:Header>
            <soap:Body>
            <WebServService___ConsEnvio2>
            <strCodAgeCargo>'. $api_agencia .'</strCodAgeCargo>
            <strAlbaran>' . $albaran . '</strAlbaran>
            </WebServService___ConsEnvio2>
            </soap:Body>
            </soap:Envelope>';


    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $xml,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/xml',
        'Accept' => 'application/xml',
      ),
    ));

    $response = curl_exec($curl);

    // Valida si se ha producido errores y muestra el mensaje de error
    if($errno = curl_errno($curl)) {
      $error_message = curl_strerror($errno);
      echo "cURL error ({$errno}):\n {$error_message}";
    }

    curl_close($curl);


    return $response;


  }
}
