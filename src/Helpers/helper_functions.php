<?php

/**
 * Helper Functions
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. info@websemantics.ca
 * @author    Adnan Sagar msagar@websemantics.ca
 * @copyright 2012-2015 Web Semantics, Inc.
 * @since     March 24th 2015
 */

 /**
  * Return a true if the array given is associative 
  * 
  * @param    array $array
  * @return   boolean
  */

  if (!function_exists('isAssociative')) {
    function isAssociative($array)
    {
        return ($array !== array_values($array));
    }
  }

 /**
  * Return a list of namespaces
  * 
  * @param    Module - $module
  * @return   array
  */

  if (!function_exists('ebxGetNamespaces')) {

      function ebxGetNamespaces($module){
        $namespaces = array_get(config($module->getNamespace('builder')), 'namespaces', []);
        return isAssociative($namespaces) ? array_keys($namespaces) : $namespaces;
      }

  }

/**
  * Return field config params, hide column / form field, view template etc
  * 
  * @param    Module - $module
  * @param    String - $namespace_slug
  * @param    String - $field_slug
  * @return   array
  */

  if (!function_exists('ebxGetFieldConfig')) {

      function ebxGetFieldConfig($module, $namespace_slug, $field_slug){

        $namespaces = array_get(config($module->getNamespace('builder')), 'namespaces', []);
        $namespace = array_get($namespaces, $namespace_slug, []);
        $field = array_get($namespace, $field_slug, []);

        return [
          'hide_column' => array_get($field, 'hide_column', false),
          'hide_field' => array_get($field, 'hide_field', false),
          'column_template' => array_get($field, 'column_template', null),
        ];
      }

  }

 /**
  * Return the folder name for the curent namespace or empty
  * 
  * @param    Module - $module
  * @param    String - $namespace
  * @param    Bollean - $forward, if true, use forward slash, otherwise backward slash
  * @return   string
  */

  if (!function_exists('ebxGetNamespaceFolder')) {

      function ebxGetNamespaceFolder($module, $namespace, $forward = false){
        $namespace = ($forward) ? "$namespace/": "$namespace\\";

        $namespaces = array_get(config($module->getNamespace('builder')), 'namespaces', []);

        return array_get(config($module->getNamespace('builder')), 
                            'namespace_folder', true) ? $namespace : "";
      }

  }

 /**
  * Return the folder template for the curent namespace
  * 
  * @param    Module - $module
  * @return   string
  */

  if (!function_exists('ebxGetNamespaceFolderTemplate')) {

      function ebxGetNamespaceFolderTemplate($module){
        return array_get(config($module->getNamespace('builder')), 
                            'namespace_folder', true) ? "" : "{namespace}/";
      }

  }

 /**
  * Return a list of class suffixes that won't be overwritten
  * 
  * @param    Module - $module
  * @return   string
  */

  if (!function_exists('ebxGetAvoidOverwrite')) {

      function ebxGetAvoidOverwrite($module, $extra = []){
        return array_merge(array_get(config($module->getNamespace('builder')), 
                            'avoid_overwrite', []),$extra);
      }

  }

 /**
  * Return the folder name for the curent namespace or empty
  * 
  * @param    Module - $module
  * @return   string
  */

  if (!function_exists('ebxGetDocblock')) {

      function ebxGetDocblock($module){
        return array_get(config($module->getNamespace('builder')), 
                            'docblock', '');
      }

  }

 /**
  * Return a string to display for a null relationship entry value
  * 
  * @return   string
  */

  if (!function_exists('ebxNullRelationshipEntry')) {

      function ebxNullRelationshipEntry($module){
        return array_get(config($module->getNamespace('builder')), 
                            'null_relationship_entry', 
                            '<span class="label label-default">null</span>');
      }

  }

 /**
  * Get the 'extends' string for a possible Super Class for the Entity Repository
  * 
  * @return   string
  */

  if (!function_exists('ebxExtendsRepository')) {

      function ebxExtendsRepository($module){        
        $extends_repository = array_get(config($module->getNamespace('builder')), 
                            'extends_repository', '');
        $extends_repository = explode('\\', $extends_repository);
        return (!empty($extends_repository))?"extends " . end($extends_repository) . " ":"";
      }

  }

 /**
  * Get the 'use' string for  Super Class for the Entity Repository
  * 
  * @return   string
  */

  if (!function_exists('ebxExtendsRepositoryUse')) {

      function ebxExtendsRepositoryUse($module){
        $extends_repository =  array_get(config($module->getNamespace('builder')), 
                            'extends_repository',null);        
        return (!empty($extends_repository))? "use $extends_repository;":"";
      }

  }

 /**
  * Extract the class name of the assignment filed type, i.e. TextFieldType, 
  * Use this to access the field template i.e. 
  * templates/field/table/TextFieldType.txt, if the 'column_template' is set, 
  * use templates/field/table/template/TextFieldType.txt
  * 
  * @param  AssignmentModel $assignment
  * @return   string
  */

  if (!function_exists('ebxGetFieldTypeClassName')) {

      function ebxGetFieldTypeClassName($assignment){

        $fieldTypeClassName = get_class($assignment->getFieldType());
        return substr($fieldTypeClassName,strrpos($fieldTypeClassName, '\\') + 1);

      }

  }






