<?php
declare(ENCODING = 'utf-8');

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Contract for a validator
 *
 * @version $ID:$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
interface TX_EXTMVC_Validation_ValidatorInterface {

	/**
	 * Returns TRUE, if the given propterty ($proptertyValue) is a valid.
	 * Any errors will be stored in the given errors object.
	 * If at least one error occurred, the result is FALSE.
	 *
	 * @param  object $propertyValue: The value that should be validated
	 * @return boolean TRUE if the value could be validated. FALSE if an error occured
	 * @throws TX_EXTMVC_Validation_Exception_InvalidSubject if this validator cannot validate the given subject or the subject is not an object.
	 */
	public function isValidProperty($propertyValue, TX_EXTMVC_Validation_Errors &$errors);
}

?>