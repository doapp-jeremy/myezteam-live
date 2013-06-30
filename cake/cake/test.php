<?php
/* SVN FILE: $Id: basics.php 6311 2008-01-02 06:33:52Z phpnut $ */
/**
 * Basic Cake functionality.
 *
 * Core functions for including other source files, loading models and so forth.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *                1785 E. Sahara Avenue, Suite 490-204
 *                Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright   Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link        http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package     cake
 * @subpackage    cake.cake
 * @since     CakePHP(tm) v 0.2.9
 * @version     $Revision: 6311 $
 * @modifiedby    $LastChangedBy: phpnut $
 * @lastmodified  $Date: 2008-01-02 00:33:52 -0600 (Wed, 02 Jan 2008) $
 * @license     http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Basic defines for timing functions.
 */
  define('SECOND', 1);
  define('MINUTE', 60 * SECOND);
  define('HOUR', 60 * MINUTE);
  define('DAY', 24 * HOUR);
  define('WEEK', 7 * DAY);
  define('MONTH', 30 * DAY);
  define('YEAR', 365 * DAY);
/**
 * Patch for PHP < 5.0
 */
if (!function_exists('clone')) {
  if (version_compare(phpversion(), '5.0') < 0) {
    eval ('
    function clone($object)
    {
      return $object;
    }');
  }
}
/**
 * Get CakePHP basic paths as an indexed array.
 * Resulting array will contain array of paths
 * indexed by: Models, Behaviors, Controllers,
 * Components, and Helpers.
 *
 * @return array Array of paths indexed by type
 */
  function paths() {
    $directories = Configure::getInstance();
    $paths = array();

    foreach ($directories->modelPaths as $path) {
      $paths['Models'][] = $path;
    }
    foreach ($directories->behaviorPaths as $path) {
      $paths['Behaviors'][] = $path;
    }
    foreach ($directories->controllerPaths as $path) {
      $paths['Controllers'][] = $path;
    }
    foreach ($directories->componentPaths as $path) {
      $paths['Components'][] = $path;
    }
    foreach ($directories->helperPaths as $path) {
      $paths['Helpers'][] = $path;
    }

    if (!class_exists('Folder')) {
      App::import('Core', 'Folder');
    }

    $folder =& new Folder(APP.'plugins'.DS);
    $plugins = $folder->ls();
    $classPaths = array('models', 'models'.DS.'behaviors',  'controllers', 'controllers'.DS.'components', 'views'.DS.'helpers');

    foreach ($plugins[0] as $plugin) {
      foreach ($classPaths as $path) {
        if (strpos($path, DS) !== false) {
          $key = explode(DS, $path);
          $key = $key[1];
        } else {
          $key = $path;
        }
        $folder->path = APP.'plugins'.DS.$plugin.DS.$path;
        $paths[Inflector::camelize($plugin)][Inflector::camelize($key)][] = $folder->path;
      }
    }
    return $paths;
  }
/**
 * Loads configuration files. Receives a set of configuration files
 * to load.
 * Example:
 * <code>
 * config('config1', 'config2');
 * </code>
 *
 * @return boolean Success
 */
  function config() {
    $args = func_get_args();
    foreach ($args as $arg) {
      if (('database' == $arg) && file_exists(CONFIGS . $arg . '.php')) {
        include_once(CONFIGS . $arg . '.php');
      } elseif (file_exists(CONFIGS . $arg . '.php')) {
        include_once(CONFIGS . $arg . '.php');

        if (count($args) == 1) {
          return true;
        }
      } else {
        if (count($args) == 1) {
          return false;
        }
      }
    }
    return true;
  }
  /**
 * Loads component/components from LIBS. Takes optional number of parameters.
 *
 * Example:
 * <code>
 * uses('flay', 'time');
 * </code>
 *
 * @param string $name Filename without the .php part
 */
  function uses() {
    $args = func_get_args();
    foreach ($args as $file) {
      require_once(LIBS . strtolower($file) . '.php');
    }
  }
/**
 * Require given files in the VENDORS directory. Takes optional number of parameters.
 *
 * @param string $name Filename without the .php part.
 */
  function vendor() {
    $args = func_get_args();
    $c = func_num_args();

    for ($i = 0; $i < $c; $i++) {
      $arg = $args[$i];

      if (strpos($arg, '.') !== false) {
        $file = explode('.', $arg);
        $plugin = Inflector::underscore($file[0]);
        unset($file[0]);
        $file = implode('.', $file);
        if (file_exists(APP . 'plugins' . DS . $plugin . DS . 'vendors' . DS . $file . '.php')) {
          require_once(APP . 'plugins' . DS . $plugin . DS . 'vendors' . DS . $file . '.php');
          continue;
        }
      }

      if (file_exists(APP . 'vendors' . DS . $arg . '.php')) {
        require_once(APP . 'vendors' . DS . $arg . '.php');
      } elseif (file_exists(VENDORS . $arg . '.php')) {
        require_once(VENDORS . $arg . '.php');
      } else {
        return false;
      }
    }
    return true;
  }
 ?>