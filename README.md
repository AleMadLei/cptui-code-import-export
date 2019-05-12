# Custom Post Types UI Import Export
This plugin exposes a hook and an admin screen to export the custom post type configuration and custom taxonomies created
with Custom Post Type UI plugin.

## Dependencies
- Custom Post Type UI has to be installed.

## Why?
I prefer to do manual things as less as possible. On some of the projects I've been working recently, it is pretty common that
after deployments, one has to go to the Custom Post Type UI admin screens and import the code from a JSON file to update changes
to custom post types.

Being that I tend to prefer version control, and I wanted to have this as automated as possible, this plugin exposes a couple hook 
where modules can define the path where the files will be located, then compared to check for changes and update if changes are found.

# How to use?
## Admin screens
- TODO

## Use trough WP-CLI
- Run `wp cptui-ie import` to import configuration from code file.
- Run `wp cptui-ie export` to export configuration to code file.

## How to integrate with plugin or theme
- From your theme or plugin you create a folder named `cptui`.
- To your theme's `functions.php` or to your plugin file, add the following code (or something similar depending on where you want the files to be exported):
```
function my_cptui_ie_folder() {
  return dirname(__FILE__) . '/cptui';
}
add_filter('cptui_ie_folder', 'my_cptui_ie_folder');
```
- Run the commands or use the admin screens.

## What's next?
- Add the admin screens to allow to export or import.