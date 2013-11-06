<?php

/**
 * @file
 * Definition of Drupal\taxonomy\TermViewBuilder.
 */

namespace Drupal\taxonomy;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\entity\Entity\EntityDisplay;

/**
 * Render controller for taxonomy terms.
 */
class TermViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildContent(array $entities, array $displays, $view_mode, $langcode = NULL) {
    parent::buildContent($entities, $displays, $view_mode, $langcode);

    foreach ($entities as $entity) {
      // Add the description if enabled.
      $display = $displays[$entity->bundle()];
      if (!empty($entity->description->value) && $display->getComponent('description')) {
        $entity->content['description'] = array(
          '#markup' => check_markup($entity->description->value, $entity->format->value, '', TRUE),
          '#prefix' => '<div class="taxonomy-term-description">',
          '#suffix' => '</div>',
        );
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getBuildDefaults(EntityInterface $entity, $view_mode, $langcode) {
    $return = parent::getBuildDefaults($entity, $view_mode, $langcode);

    // TODO: rename "term" to "taxonomy_term" in theme_taxonomy_term().
    $return['#term'] = $return["#{$this->entityType}"];
    unset($return["#{$this->entityType}"]);

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityDisplay $display, $view_mode, $langcode = NULL) {
    parent::alterBuild($build, $entity, $display, $view_mode, $langcode);
    $build['#attached']['css'][] = drupal_get_path('module', 'taxonomy') . '/css/taxonomy.module.css';
    $build['#contextual_links']['taxonomy_term'] = array(
      'route_parameters' => array('taxonomy_term' => $entity->id()),
    );
  }

}
