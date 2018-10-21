<?php
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 20.5.18 19:36
 */

namespace Czubehead\BootstrapForms\Traits;


use Nette\NotImplementedException;


/**
 * Trait FakeControlTrait.
 * Implements absolute minimum of functionality to be used as a control
 * @package Czubehead\BootstrapForms\Traits
 */
trait FakeControlTrait
{
	/**
	 * Always returns an empty array
	 * @internal
	 */
	function getErrors()
	{
		return [];
	}

	/**
	 * Not supported
	 * @internal
	 */
	function getValue()
	{
		return NULL;
	}

	public function isDisabled()
	{
		return TRUE;
	}

	/**
	 * Is control value excluded from $form->getValues() result?
	 * @return true
	 */
	function isOmitted()
	{
		return TRUE;
	}

	/**
	 * Not supported
	 * @param $value
	 */
	function setValue($value)
	{
		throw new NotImplementedException;
	}

	/**
	 * Do nothing
	 * @internal
	 */
	function validate()
	{

	}
}