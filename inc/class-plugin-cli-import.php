<?php
/**
 * Implements example command.
 */
class CPTUI_Import_Export_Command {

  /**
   * Imports custom post types and taxonomies defined from a JSON file.
   *
   * ## EXAMPLES
   *
   *     wp cptui-ie import
   *
   * @when after_wp_load
   */
  function import($args, $assoc_args) {

    // Confirmation is skipped.
    $skip_confirmation = FALSE;
    if (in_array('-y', $args)) {
      $skip_confirmation = TRUE;
    }

    // If configuration folder is not set...
    $config_folder = apply_filters('cptui_ie_folder', NULL);
    if (empty($config_folder)) {
      WP_CLI::error(__('Configuration folder for Custom Post Type not set.'));
    }

    // Checks if the configuration folder exists...
    if (!file_exists($config_folder)) {
      WP_CLI::error(__('Configuration folder for Custom Post Type does not exist.'));
    }

    // Notifies user of file location.
    WP_CLI::line(sprintf(__('Looking for configuration files at %s.', 'cptui-ie'), $config_folder));

    // Tries to load data for both post types and taxonomies.
    foreach (['post_type' => __('Post types'), 'taxonomy' => __('Taxonomies')] as $key => $label) {

      $loaded_entity_type_content = NULL;
      $entity_types_file = "{$config_folder}/{$key}.json";
      if (file_exists($entity_types_file)) {
        $loaded_entity_type_content = file_get_contents($entity_types_file);
      }

      // If file contents were loaded...
      if (!empty($loaded_entity_type_content)) {

        // Loads current entity type data.
        $function = "cptui_get_{$key}_data";
        $current_content = $function();
        if (!empty($current_content)) {
          $current_content = json_encode($current_content);
        }

        // If content differs, loads the configuration from file.
        if ($current_content != $loaded_entity_type_content) {

          $message = sprintf(__('Do you want to load custom %s from configuration file? (This cannot be reverted) (y/n)', 'cptui-ie'), $label);
          do {

            // If confirmation is not skipped, reads the value from user input.
            if (!$skip_confirmation) {
              $continue = readline($message);
            }
            else {
              $continue = 'y';
              WP_CLI::line($message);
            }
          }
          while (strtolower($continue) != 'n' && strtolower($continue) != 'y');

          // Runs the import command if confirmed.
          if ($continue == 'y') {
            WP_CLI::runcommand("cptui import --type={$key} --data-path={$entity_types_file}");
          }

        }
        else {
          WP_CLI::warning(__('Configuration in file matches configuration in database. Skipping import.', 'cptui-ie'));
        }

      }

    }

  }

  /**
   * Export custom post types and taxonomies defined from a JSON file.
   *
   * ## EXAMPLES
   *
   *     wp cptui-ie export
   *
   * @when after_wp_load
   */
  function export($args, $assoc_args) {

    // Confirmation is skipped.
    $skip_confirmation = FALSE;
    if (in_array('-y', $args)) {
      $skip_confirmation = TRUE;
    }

    // If configuration folder is not set...
    $config_folder = apply_filters('cptui_ie_folder', NULL);
    if (empty($config_folder)) {
      WP_CLI::error(__('Configuration folder for Custom Post Type not set.'));
    }

    // Checks if the configuration folder exists...
    if (!file_exists($config_folder)) {
      WP_CLI::error(__('Configuration folder for Custom Post Type does not exist.'));
    }

    // Tries to load data for both post types and taxonomies.
    foreach (['post_type' => __('Post Types'), 'taxonomy' => __('Taxonomies')] as $key => $label) {

      // Loads corresponding data.
      $function = "cptui_get_{$key}_data";
      $content = $function();

      // If no content exists, continues to next element.
      if (empty($content)) {
        WP_CLI::line(sprintf(__('No content for %s. Skipping...', 'cptui-ie'), $label));
        break;
      }

      // Reads confirmation from the user.
      $entity_types_file = "{$config_folder}/{$key}.json";
      $continue = '';
      do {
        $message = NULL;
        if (!file_exists($entity_types_file)) {
          $message = sprintf(__('Do you want to create the %s file? (This cannot be reverted) (y/n)', 'cptui-ie'), $label);
        } else {
          $message = sprintf(__('Do you want to overwrite the %s file? (This cannot be reverted) (y/n)', 'cptui-ie'), $label);
        }

        // If confirmation is not skipped, reads the value from user input.
        if (!$skip_confirmation) {
          $continue = readline($message);
        }
        else {
          $continue = 'y';
          WP_CLI::line($message);
        }
      }
      while (strtolower($continue) != 'n' && strtolower($continue) != 'y');

      // Runs the export command if confirmed.
      if ($continue == 'y') {
        $result = WP_CLI::runcommand("cptui export --type={$key} --dest-path={$entity_types_file}");
      }

    }

  }
}

// Adds our commands.
WP_CLI::add_command( 'cptui-ie', 'CPTUI_Import_Export_Command' );