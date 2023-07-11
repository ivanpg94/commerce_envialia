<?php

namespace Drupal\commerce_envialia;

use Drupal\Core\Session\AccountInterface;

class graba_Envio
{
  protected $currentUser;

  /**
   * CustomService constructor.
   * @param AccountInterface $currentUser
   */
  public function __construct(AccountInterface $currentUser) {
    $this->currentUser = $currentUser;
  }

  function grabaEnvio($id_session, $api_url, $api_agencia, $api_user)
  {

    $form = \Drupal::formBuilder()->getForm('Drupal\commerce_envialia\Form\CommerceEnvialiaForm');
  //  $api_url_action = ($form['api_url_settings']['api_url_action']['#value']);

    $request = \Drupal::service('request_stack')->getCurrentRequest();
    $requestUri = $request->getRequestUri();
    $urlcut = explode("/generate_label_envialia", $requestUri);
    $urlcut2 = explode("orders/", $urlcut[0]);
    $orderid = $urlcut2[1];
    $price = \Drupal::entityTypeManager()->getStorage('commerce_order')->load($orderid)->getTotalPrice()->getNumber();
    // dump($order);
    $email = \Drupal::entityTypeManager()->getStorage('commerce_order')->load($orderid)->getEmail();

    $entityManager = \Drupal::entityTypeManager();

    //customer data
    $order = $entityManager->getStorage('commerce_order')->load($orderid);
    $country_code = $order->shipments->entity->shipping_profile->entity->address->getValue()[0]['country_code'];
    $city = $order->shipments->entity->shipping_profile->entity->address->getValue()[0]['administrative_area'];
    $locality = $order->shipments->entity->shipping_profile->entity->address->getValue()[0]['locality'];
    $postal_code = $order->shipments->entity->shipping_profile->entity->address->getValue()[0]['postal_code'];
    $address_line1 = $order->shipments->entity->shipping_profile->entity->address->getValue()[0]['address_line1'];
    $address_line2 = $order->shipments->entity->shipping_profile->entity->address->getValue()[0]['address_line2'];
    $name = $order->shipments->entity->shipping_profile->entity->address->getValue()[0]['given_name'];
    $surname = $order->shipments->entity->shipping_profile->entity->address->getValue()[0]['family_name'];

    if(!isset($shop_address_line2)){
      $shop_address_line2='';;
    }

    //shop data
    $store = $entityManager->getStorage('commerce_store')->load(2);
    $shop_country_code = $store->address->getValue()[0]['country_code'];
    $shop_administrative_area = $store->address->getValue()[0]['administrative_area'];
    $shop_locality = $store->address->getValue()[0]['locality'];
    $shop_postal_code = $store->address->getValue()[0]['postal_code'];
    $shop_address_line1 = $store->address->getValue()[0]['address_line1'];
    $shop_address_line2 = $store->address->getValue()[0]['address_line2'];
    $shop_name = $store->name->getValue()[0]['value'];

    $telefono = $order->billing_profile->entity->phone->getValue()[0]['value'];

    //date
    $date = date('Y-m-d');
    $hour = date('H:i:s');

    //tipo de servicio
    $tipo_servicio = '24';
    if($country_code == 'ES' && $city == 'PM') {
      $tipo_servicio = '72';
    }
    //reembolso
    $reembolsovalor = $order->payment_gateway->entity->get('plugin');
    $reembolso = 0;
    if($reembolsovalor== 'manual') {
      $reembolso = round($price, 2);
    }

    //observaciones
    $observaciones = '';

    //persona contacto
    $personacontacto = '';



    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Header>
        <ROClientIDHeader xmlns="http://tempuri.org/">
            <ID>'.$id_session.'</ID>
        </ROClientIDHeader>
    </soap:Header>
    <soap:Body>
        <WebServService___GrabaEnvio8 xmlns="http://tempuri.org/">
            <strCodAgeCargo>'.$api_agencia.'</strCodAgeCargo>
            <strCodAgeOri>'.$api_agencia.'</strCodAgeOri>
            <strAlbaran/>
            <dtFecha>'.$date.'</dtFecha>
            <strCodAgeDes/>
           <strCodTipoServ>'.$tipo_servicio.'</strCodTipoServ>
            <strCodCli>'.$api_user.'</strCodCli>
            <strCodCliDep/>
            <strNomOri>'.$api_agencia.'</strNomOri>
            <strTipoViaOri/>
            <strDirOri>'. $shop_address_line1 .'</strDirOri>
            <strNumOri>'.$shop_address_line2.'</strNumOri>
            <strPisoOri/>
            <strPobOri>'.$shop_country_code.'</strPobOri>
            <strCPOri>'. $shop_postal_code .'</strCPOri>
            <strCodProOri>'.$shop_locality.'</strCodProOri>
            <strTlfOri>'.$telefono.'</strTlfOri>
            <strNomDes>'.$name.' '.$surname.'</strNomDes>
            <strTipoViaDes/>
            <strDirDes>'. $address_line1 .' '.$address_line2.'</strDirDes>
            <strNumDes></strNumDes>
            <strPisoDes/>
            <strPobDes>'.$city.'</strPobDes>
            <strCPDes>'. $postal_code .'</strCPDes>
            <strCodProDes/>
            <strTlfDes></strTlfDes>
            <intDoc>1</intDoc>
            <intPaq>0</intPaq>
            <dPesoOri>15</dPesoOri>
            <dAltoOri>0</dAltoOri>
            <dAnchoOri>0</dAnchoOri>
            <dLargoOri>0</dLargoOri>
            <dValor>'. $price .'</dValor>
            <dAnticipo>0.0</dAnticipo>
            <dCobCli>0.0</dCobCli>
            <strObs>'.$observaciones.'</strObs>
            <boSabado>false</boSabado>
            <boRetorno>false</boRetorno>
            <boGestOri>false</boGestOri>
            <boGestDes>false</boGestDes>
            <boAnulado>false</boAnulado>
            <boAcuse>false</boAcuse>
            <strRef>1438022</strRef>
            <strCodSalRuta/>
            <dBaseImp>0.0</dBaseImp>
            <dImpuesto>0.0</dImpuesto>
            <boPorteDebCli>false</boPorteDebCli>
            <strPersContacto>'.$personacontacto.'</strPersContacto>
            <strCodPais/>
            <strDesMoviles/>
            <strDesDirEmails>'. $email .'</strDesDirEmails>
            <strFranjaHoraria/>
            <dtHoraEnvIni/>
            <dtHoraEnvFin/>
            <boInsert>true</boInsert>
            </WebServService___GrabaEnvio8>
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
