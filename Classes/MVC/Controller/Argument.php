<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Jochen Rau <jochen.rau@typoplanet.de>
*  All rights reserved
*
*  This class is a backport of the corresponding class of FLOW3.
*  All credits go to the v5 team.
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * A controller argument
 *
 * @package Extbase
 * @subpackage MVC\Controller
 * @version $ID:$
 * @scope prototype
 */
class Tx_Extbase_MVC_Controller_Argument {

	/**
	 * @var Tx_Extbase_Persistence_QueryFactory
	 */
	protected $queryFactory;

	/**
	 * @var Tx_Extbase_Property_Mapper
	 */
	protected $propertyMapper;

	/**
	 * @var Tx_Extbase_Reflection_Service
	 */
	protected $reflectionService;

	/**
	 * Name of this argument
	 * @var string
	 */
	protected $name = '';

	/**
	 * Short name of this argument
	 * @var string
	 */
	protected $shortName = NULL;

	/**
	 * Data type of this argument's value
	 * @var string
	 */
	protected $dataType = 'Text';

	/**
	 * If the data type is an object, the class schema of the data type class is resolved
	 * @var Tx_Extbase_Reflection_ClassSchema
	 */
	protected $dataTypeClassSchema;

	/**
	 * TRUE if this argument is required
	 * @var boolean
	 */
	protected $isRequired = FALSE;

	/**
	 * Actual value of this argument
	 * @var object
	 */
	protected $value = NULL;

	/**
	 * Default value. Used if argument is optional.
	 * @var mixed
	 */
	protected $defaultValue = NULL;

	/**
	 * A custom validator, used supplementary to the base validation
	 * @var Tx_Extbase_Validation_Validator_ValidatorInterface
	 */
	protected $validator = NULL;

	/**
	 * If validation for this argument is temporarily disabled
	 * @var boolean
	 */
	protected $validationDisabled = FALSE;

	/**
	 * Uid for the argument, if it has one
	 * @var string
	 */
	protected $uid = NULL;

	/**
	 * Constructs this controller argument
	 *
	 * @param string $name Name of this argument
	 * @param string $dataType The data type of this argument
	 * @throws InvalidArgumentException if $name is not a string or empty
	 * @api
	 */
	public function __construct($name, $dataType = 'Text') {
		$this->reflectionService = t3lib_div::makeInstance('Tx_Extbase_Reflection_Service');
		$this->propertyMapper = t3lib_div::makeInstance('Tx_Extbase_Property_Mapper');
		$this->propertyMapper->injectReflectionService($this->reflectionService);
		if (!is_string($name) || strlen($name) < 1) throw new InvalidArgumentException('$name must be of type string, ' . gettype($name) . ' given.', 1187951688);
		$this->name = $name;
		$this->setDataType($dataType);
	}

	/**
	 * Injects the Persistence Manager
	 *
	 * @param Tx_Extbase_Persistence_ManagerInterface
	 * @return void
	 */
	public function injectPersistenceManager(Tx_Extbase_Persistence_ManagerInterface $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}

	/**
	 * Injects a QueryFactory instance
	 *
	 * @param Tx_Extbase_Persistence_QueryFactoryInterface $queryFactory
	 * @return void
	 */
	public function injectQueryFactory(Tx_Extbase_Persistence_QueryFactoryInterface $queryFactory) {
		$this->queryFactory = $queryFactory;
	}

	/**
	 * Returns the name of this argument
	 *
	 * @return string This argument's name
	 * @api
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the short name of this argument.
	 *
	 * @param string $shortName A "short name" - a single character
	 * @return Tx_Extbase_MVC_Controller_Argument $this
	 * @throws InvalidArgumentException if $shortName is not a character
	 * @api
	 */
	public function setShortName($shortName) {
		if ($shortName !== NULL && (!is_string($shortName) || strlen($shortName) !== 1)) throw new InvalidArgumentException('$shortName must be a single character or NULL', 1195824959);
		$this->shortName = $shortName;
		return $this;
	}

	/**
	 * Returns the short name of this argument
	 *
	 * @return string This argument's short name
	 * @api
	 */
	public function getShortName() {
		return $this->shortName;
	}

	/**
	 * Sets the data type of this argument's value
	 *
	 * @param string $dataType The data type. Can be either a built-in type such as "Text" or "Integer" or a fully qualified object name
	 * @return Tx_Extbase_MVC_Controller_Argument $this
	 * @api
	 */
	public function setDataType($dataType) {
		$this->dataType = $dataType;
		$this->dataTypeClassSchema = $this->reflectionService->getClassSchema($this->dataType);
		return $this;
	}

	/**
	 * Returns the data type of this argument's value
	 *
	 * @return string The data type
	 * @api
	 */
	public function getDataType() {
		return $this->dataType;
	}

	/**
	 * Marks this argument to be required
	 *
	 * @param boolean $required TRUE if this argument should be required
	 * @return Tx_Extbase_MVC_Controller_Argument $this
	 * @api
	 */
	public function setRequired($required) {
		$this->isRequired = (boolean)$required;
		return $this;
	}

	/**
	 * Returns TRUE if this argument is required
	 *
	 * @return boolean TRUE if this argument is required
	 * @api
	 */
	public function isRequired() {
		return $this->isRequired;
	}

	/**
	 * Sets the default value of the argument
	 *
	 * @param mixed $defaultValue Default value
	 * @return void
	 * @api
	 */
	public function setDefaultValue($defaultValue) {
		$this->defaultValue = $defaultValue;
	}

	/**
	 * Returns the default value of this argument
	 *
	 * @return mixed The default value
	 * @api
	 */
	public function getDefaultValue() {
		return $this->defaultValue;
	}

	/**
	 * Sets a custom validator which is used supplementary to the base validation
	 *
	 * @param Tx_Extbase_Validation_Validator_ValidatorInterface $validator The actual validator object
	 * @return Tx_Extbase_MVC_Controller_Argument Returns $this (used for fluent interface)
	 * @api
	 */
	public function setValidator(Tx_Extbase_Validation_Validator_ValidatorInterface $validator) {
		$this->validator = $validator;
		return $this;
	}

	/**
	 * Create and set a validator chain
	 *
	 * @param array Object names of the validators
	 * @return Tx_Extbase_MVC_Controller_Argument Returns $this (used for fluent interface)
	 * @api
	 */
	public function setNewValidatorConjunction(array $objectNames) {
		if ($this->validator === NULL) {
			$this->validator = t3lib_div::makeInstance('Tx_Extbase_Validation_Validator_ConjunctionValidator');
		}
		foreach ($objectNames as $objectName) {
			if (!class_exists($objectName)) $objectName = 'Tx_Extbase_Validation_Validator_' . $objectName;
			$this->validator->addValidator(t3lib_div::makeInstance($objectName));
		}
		return $this;
	}

	/**
	 * Returns the set validator
	 *
	 * @return Tx_Extbase_Validation_Validator_ValidatorInterface The set validator, NULL if none was set
	 * @api
	 */
	public function getValidator() {
 		return $this->validator;
	}

	/**
	 * Returns TRUE if validation is temporarily disabled for this argument and
	 * FALSE if it's enabled.
	 *
	 * Note that this is flag is only informational and does not have any real impact
	 * on other validation methods of this argument.
	 *
	 * @return boolean If validation is disabled
	 * @api
	 */
	public function isValidationDisabled() {
		return $this->validationDisabled;
	}

	/**
	 * Enables validation for this argument.
	 *
	 * @return void
	 * @api
	 */
	public function enableValidation() {
		$this->validationDisabled = FALSE;
	}

	/**
	 * Disables validation for this argument.
	 *
	 * @return void
	 * @api
	 */
	public function disableValidation() {
		$this->validationDisabled = TRUE;
	}

	/**
	 * Sets the value of this argument.
	 *
	 * @param mixed $value: The value of this argument
	 * @return Tx_Extbase_MVC_Controller_Argument $this
	 * @throws Tx_Extbase_MVC_Exception_InvalidArgumentValue if the argument is not a valid object of type $dataType
	 */
	public function setValue($value) {
		$this->value = $this->transformValue($value);

		return $this;
	}

	/**
	 * Checks if the value is a UUID or an array but should be an object, i.e.
	 * the argument's data type class schema is set. If that is the case, this
	 * method tries to look up the corresponding object instead.
	 *
	 * Additionally, it maps arrays to objects in case it is a normal object.
	 *
	 * @param mixed $value The value of an argument
	 * @return mixed
	 */
	protected function transformValue($value) {
		if ($value === NULL) {
			return NULL;
		}
		if (!class_exists($this->dataType)) {
			return $value;
		}
		$transformedValue = NULL;
		if ($this->dataTypeClassSchema !== NULL) {
			// It is an Entity or ValueObject.
			if (is_numeric($value)) {
				$transformedValue = $this->findObjectByUid($value);
			} elseif (is_array($value)) {
				$transformedValue = $this->propertyMapper->map(array_keys($value), $value, $this->dataType);
			}
		} else {
			if (!is_array($value)) {
				throw new Tx_Extbase_MVC_Exception_InvalidArgumentValue('The value was a simple type, so we could not map it to an object. Maybe the @entity or @valueobject annotations are missing?', 1251730701);
			}
			$transformedValue = $this->propertyMapper->map(array_keys($value), $value, $this->dataType);
		}

		if (!($transformedValue instanceof $this->dataType)) {
			throw new Tx_Extbase_MVC_Exception_InvalidArgumentValue('The value must be of type "' . $this->dataType . '", but was of type "' . get_class($transformedValue) . '".', 1251730701);
		}
		return $transformedValue;
	}

	/**
	 * Finds an object from the repository by searching for its technical UID.
	 *
	 * @param int $uid The object's uid
	 * @return mixed Either the object matching the uid or, if none or more than one object was found, FALSE
	 */
	protected function findObjectByUid($uid) {
		$query = $this->queryFactory->create($this->dataType);
		$result = $query->matching($query->withUid($uid))->execute();
		$object = NULL;
		if (count($result) > 0) {
			$object = current($result);
		}
		return $object;
	}

	/**
	 * Returns the value of this argument
	 *
	 * @return object The value of this argument - if none was set, NULL is returned
	 * @api
	 */
	public function getValue() {
		if ($this->value === NULL) {
			return $this->defaultValue;
		} else {
			return $this->value;
		}
	}

	/**
	 * Checks if this argument has a value set.
	 *
	 * @return boolean TRUE if a value was set, otherwise FALSE
	 */
	public function isValue() {
		return $this->value !== NULL;
	}

	/**
	 * Returns a string representation of this argument's value
	 *
	 * @return string
	 * @api
	 */
	public function __toString() {
		return (string)$this->value;
	}
}
?>