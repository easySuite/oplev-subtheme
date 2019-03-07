<?php

/**
 * @file
 * The primary PHP file for this theme.
 */


/**
 * Implements theme_menu_tree().
 */
function oplev_subtheme_menu_tree__menu_block__1($vars) {
  return '<div class="transform-main-menu"></div><ul class="main-menu">' . $vars['tree'] . '</ul>';
}

/**
 * Implements theme_menu_tree().
 */
function oplev_subtheme_menu_tree__menu_block__2($vars) {
  return '<div class="transform-sub-menu"></div><ul class="secondary-menu">' . $vars['tree'] . '</ul>';
}

/**
 * Implements hook_process_ting_object().
 *
 * Adds wrapper classes to the different groups on the ting object.
 */
function oplev_subtheme_process_ting_object(&$vars) {
  // Add tpl suggestions for node view modes.
  if (isset($vars['elements']['#view_mode'])) {
    $vars['theme_hook_suggestions'][] = $vars['elements']['#bundle'] . '__view_mode__' . $vars['elements']['#view_mode'];
  }

  switch ($vars['elements']['#entity_type']) {
    case 'ting_collection':
      // Add a reference to the ting_object if it's included in a
      // ting_collection.
      foreach ($vars['object']->entities as &$ting_entity) {
        $ting_entity->in_collection = $vars['object'];
      }
      break;

    case 'ting_object':
      $library = variable_get('culture_frontend_external_library', '');
      if (empty($library)) {
        drupal_set_message(t('External library is not set. Set it on the <a href="@path"> @path </a>', array('@path' => '/admin/config/culture')), 'error', FALSE);
        return '';
      }

      $uri_collection = entity_uri('ting_collection', $vars['object']);
      $vars['ting_object_url_collection'] = $library . url($uri_collection['path']);

      $uri_object = entity_uri('ting_object', $vars['object']);
      $vars['ting_object_url_object'] = $library . url($uri_object['path']);

      switch ($vars['elements']['#view_mode']) {

        // Teaser.
        case 'teaser':
          $vars['content']['read_more_button'] = array(
            array(
              '#theme' => 'link',
              '#text' => '<div class="read-more-text">' . t('Read more') . '</div>',
              '#path' => $library . $uri_object['path'],
              '#options' => array(
                'attributes' => array(
                  'class' => array(
                    'action-button',
                    'read-more-button',
                  ),
                  'target' => '_blank',
                ),
                'html' => TRUE,
              ),
            ),
            '#weight' => 9998,
          );

          if ($vars['object']->online_url) {
            // Slice the output, so it only usese the online link button.
            $vars['content']['group_text']['online_link'] = array_slice(ting_ding_entity_buttons(
              'ding_entity',
              $vars['object']
            ), 0, 1);
          }

          // Check if teaser has rating function and remove abstract.
          if (!empty($vars['content']['group_text']['group_rating']['ding_entity_rating_action'])) {
            unset($vars['content']['group_text']['ting_abstract']);
          }

          break;

        // Reference teaser.
        case 'reference_teaser':
          $vars['content']['buttons'] = array(
            '#prefix' => '<div class="buttons">',
            '#suffix' => '</div>',
            '#weight' => 9999,
          );
          $vars['content']['buttons']['read_more_button'] = array(
            array(
              '#theme' => 'link',
              '#text' => t('Read more'),
              '#path' => $uri_object['path'],
              '#options' => array(
                'attributes' => array(
                  'class' => array(
                    'action-button',
                    'read-more-button',
                  ),
                ),
                'html' => FALSE,
              ),
            ),
          );

          if ($vars['object']->online_url) {
            // Slice the output, so it only usese the online link button.
            $vars['content']['buttons']['online_link'] = array_slice(ting_ding_entity_buttons(
              'ding_entity',
              $vars['object']
            ), 0, 1);
          }

          break;

      }
      break;
  }

  // Inject the availability from the collection into the actual ting object.
  // Notice it's only done on the "search_result" view mode.
  if ($vars['elements']['#entity_type'] == 'ting_object' && isset($vars['object']->in_collection)
    && isset($vars['elements']['#view_mode'])
    && in_array($vars['elements']['#view_mode'], array('search_result'))) {
    $availability = field_view_field(
      'ting_collection',
      $vars['object']->in_collection,
      'ting_collection_types',
      array(
        'type' => 'ding_availability_with_labels',
        'weight' => 9999,
      )
    );
    $availability['#title'] = t('Borrowing options');

    if (isset($vars['content']['group_ting_right_col_search'])) {
      if (isset($vars['content']['group_ting_right_col_search']['group_info']['group_rating']['#weight'])) {
        $availability['#weight'] = $vars['content']['group_ting_right_col_search']['group_info']['group_rating']['#weight'] - 0.5;
      }
      $vars['content']['group_ting_right_col_search']['group_info']['availability'] = $availability;
    }
    else {
      $vars['content']['group_ting_right_col_collection']['availability'] = $availability;
    }
  }

  if (isset($vars['elements']['#view_mode']) && $vars['elements']['#view_mode'] == 'full') {
    switch ($vars['elements']['#entity_type']) {
      case 'ting_object':
        $content = $vars['content'];
        $vars['content'] = array();

        if (isset($content['group_ting_object_left_column']) && $content['group_ting_object_left_column']) {
          $vars['content']['ting-object'] = array(
            '#prefix' => '<div class="ting-object-wrapper">',
            '#suffix' => '</div>',
            'content' => array(
              '#prefix' => '<div class="ting-object-inner-wrapper">',
              '#suffix' => '</div>',
              'left_column' => $content['group_ting_object_left_column'],
              'right_column' => $content['group_ting_object_right_column'],
            ),
          );

          unset($content['group_ting_object_left_column']);
          unset($content['group_ting_object_right_column']);
        }

        if (isset($content['group_material_details']) && $content['group_material_details']) {
          $vars['content']['material-details'] = array(
            '#prefix' => '<div class="ting-object-wrapper">',
            '#suffix' => '</div>',
            'content' => array(
              '#prefix' => '<div class="ting-object-inner-wrapper">',
              '#suffix' => '</div>',
              'details' => $content['group_material_details'],
            ),
          );
          unset($content['group_material_details']);
        }

        if (isset($content['content']['ding_availability_holdings'])) {

          $vars['content']['holdings-available'] = array(
            '#prefix' => '<div class="ting-object-wrapper">',
            '#suffix' => '</div>',
            'content' => array(
              '#prefix' => '<div class="ting-object-inner-wrapper">',
              '#suffix' => '</div>',
              'details' => $content['group_holdings_available'],
            ),
          );
          unset($content['content']['ding_availability_holdings']);
        }

        if (isset($content['group_periodical_issues']) && $content['group_periodical_issues']) {
          $vars['content']['periodical-issues'] = array(
            '#prefix' => '<div class="ting-object-wrapper">',
            '#suffix' => '</div>',
            'content' => array(
              '#prefix' => '<div class="ting-object-inner-wrapper">',
              '#suffix' => '</div>',
              'details' => $content['group_periodical_issues'],
            ),
          );
          unset($content['group_periodical_issues']);
        }

        if (isset($content['group_on_this_site']) && $content['group_on_this_site']) {
          $vars['content']['on_this_site'] = array(
            '#prefix' => '<div class="ting-object-wrapper">',
            '#suffix' => '</div>',
            'content' => array(
              '#prefix' => '<div id="ting_reference" class="ting-object-inner-wrapper">',
              '#suffix' => '</div>',
              'details' => $content['group_on_this_site'],
            ),
          );
          unset($content['group_on_this_site']);
        }

        if (isset($content['ting_relations']) && $content['ting_relations']) {
          $vars['content']['ting-relations'] = array(
            'content' => array(
              'details' => $content['ting_relations'],
            ),
          );
          unset($content['ting_relations']);
        }


        break;
    }
  }
}
