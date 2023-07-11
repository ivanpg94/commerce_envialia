<?php

namespace Drupal\commerce_envialia\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * An example controller.
 */
class urlenvialia extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function completado() {
    $completaEnvio = \Drupal::service('commerce_envialia.completaEnvio')->completa_Envio();
  }

}
