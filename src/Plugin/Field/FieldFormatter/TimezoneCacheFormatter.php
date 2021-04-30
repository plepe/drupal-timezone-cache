<?php
/**
 * @file
 * Create a PublDB formatter for fields.
 */

namespace Drupal\timezone_cache\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implementation of the publdb formatter
 *
 * @FieldFormatter(
 *   id = "timezone_cache",
 *   label = @Translation("Timezone Cache Formatter"),
 *   field_types = {
 *     "string_long"
 *   }
 * )
 */
class TimezoneCacheFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public function viewElements (FieldItemListInterface $items, $langcode) {
    $elements = array();

    $user = \Drupal::currentUser();

    $timezone = $user->getTimeZone() ?? date_default_timezone_get();

    $offset = new \DateTime('2021-05-03T12:00:00Z');
    $offset->setTimeZone(new \DateTimezone($timezone));
    $offset = $offset->format('O');

    foreach ($items as $delta => $item) {
      list($itemOffset, $content) = explode(':', $item->value, 2);

      if ($itemOffset === $offset) {
        $elements[$delta] = [
          '#children' => $content,
          '#cache' => [
            'contexts' => [ 'timezone' ],
          ],
        ];
      }
    }

    return $elements;
  }
}
