<?php

namespace Drupal\my_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Class FilterSearchBlock.
 *
 * @Block(
 *   id = "my_module_contact_block",
 *   admin_label = @Translation("Contact"),
 * )
 */
class Contact extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  public $formBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilder $formBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = $this->formBuilder->getForm('\Drupal\my_module\Form\Register');
    return $form;
    // [
        // '#theme' => 'my_module_contact',
        // '#form' => $form,
    //   ];
  }

}
