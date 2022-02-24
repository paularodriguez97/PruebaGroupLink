<?php

namespace Drupal\my_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
* Implements the Register form controller.
*
* @see \Drupal\Core\Form\FormBase
*/

class Register extends FormBase {
    /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
    
    protected $database;
    protected $currentUser;

    public function __construct(Connection $database, AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager) {
        $this->database = $database;
        $this->currentUser = $current_user;
        $this->entityTypeManager = $entity_type_manager;
    }

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('database'),
            $container->get('current_user'),
            $container->get('entity_type.manager')
        );
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $fileDepartement = file_get_contents(drupal_get_path('module', 'my_module') . '/files/department/departamentos.json');
        $json_decodeDepartement = json_decode($fileDepartement);
        $fileCity = file_get_contents(drupal_get_path('module', 'my_module') . '/files/city/ciudades.json');
        $json_decodeCity = json_decode($fileCity);

        

        $form['nombre'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Nombres:'),
            '#size' => 60,
            '#required' => TRUE,
        ];

        $form['apellidos'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Apellidos:'),
            '#size' => 60,
            '#required' => TRUE,
        ];
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Correo electrónico'),
            '#help_text' => $this->t('El correo electrónico que ingresaste no es válido. Por favor verifícalo e intenta de nuevo.'),
            '#attributes' => [
                'data-format-email' => TRUE,
                'data-centinel' => 'false',
                'pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$',
            ],
        ];

        $form['telefono'] = [
            '#type' => 'tel',
            '#title' => $this->t('Telefono'),
            '#maxlength' => 14,
            '#help_text' => $this->t('Por favor, ingresa el número de telefono celular.'),
            '#attributes' => [
                'data-format-movil' => TRUE,
                'data-centinel' => 'false',
                'pattern' => '[0-9]{10}',
            ],
        ];

        $departament = [];
        $departament[0] = 'Seleccione un Departamento';
    
        foreach ($json_decodeDepartement as $item) {
          $departament[$item->CDDC] = $item->provinceName;
        }
        $this->departament = $departament;
    
        $form['department'] = [
          '#type' => 'select',
          '#title' => $this->t('Departamento'),
          '#options' => $departament,
          '#required' => TRUE,
          '#ajax' => [
            'callback' => '::wrapperModalNotification',
            'wrapper' => 'dropdown-second-replace',
          ],
          '#attributes' => [
            'data-format-year' => TRUE,
            'data-centinel' => 'false',
          ],
        ];
    

        $cities = [];
        $cities[0] = 'Seleccione una Ciudad';
    
        $departament = $form_state->getValue('department') ? $form_state->getValue('department') : NULL;

        if ($departament) {
          foreach ($json_decodeCity as $item) {
            if ($item->CDDC == $departament) {
              $cities[$item->CM] = ucfirst(mb_strtolower($item->NM));
            }
          }
        }
    
        $this->cities = $cities;
    
        $form['container-dropdown'] = [
          '#type' => 'container',
          '#attributes' => [
            'id' => 'dropdown-second-replace',
          ],
        ];
    
    
        $form['container-dropdown']['city'] = [
          '#type' => 'select',
          '#title' => $this->t('Ciudad'),
          '#options' => $cities,
          '#required' => TRUE,
          '#attributes' => [
            'data-format-year' => TRUE,
            'data-centinel' => 'false',
            'id' => 'edit-city',
          ],
        ];

        $form['mesagge'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Mensaje'),
          ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Submit',
            '#name' => 'submit_send', 
            '#attributes' => [
              'class' => ['button--submit'],
            ],
        ];

        $form['#attributes']['class'][] = 'form_general';
        $form['#id'] = 'block-constructhor-webform';
        $form['#attached']['library'][] = 'my_module/js_validate';


        // Modal

        $form['notification_send'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => ['modal'],
            ],
          ];

        $name = $form_state->getTriggeringElement()['#name'];

        if ($name === 'submit_send'){
            $form['notification_send']['container']['container2'] = [
                '#type' => 'container',
                '#attributes' => [
                    'class' => ['modal-notification'],
                  ],
                ];

            $form['notification_send']['container']['container2']['close'] = [
                '#type' => 'submit', 
                '#value' => 'x', 
                ];
    
            $form['notification_send']['container']['container2']['content'] = [
            '#markup' => $this->t('<h2 class="cardtigo__title">Gracias !!!</h2>'),
            ];
        }
        else {
            $form['notification_send']['#attributes']['class'][] = 'hidden-modal-notification';
        }
       
        return $form;
    }
 
    public function wrapperModalNotification(array &$form, FormStateInterface $form_state) {
        $form_state->setRebuild();
        return $form['container-dropdown'];
    }

    public function getFormId() {
        return 'my_module_forms_register';
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        $name = $form_state->getValue('nombre');
        $apellidos = $form_state->getValue('apellidos');
        if (strlen($name) > 60) {
          $form_state->setErrorByName('nombre', $this->t('El nombre debe ser maximo de 60 caracteres.'));
        }

        if (strlen($apellido) > 60) {
            $form_state->setErrorByName('apellidos', $this->t('Los apellidos deben ser maximo de 60 caracteres.'));
        }

        
    }
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_state->setRebuild();
    }
}