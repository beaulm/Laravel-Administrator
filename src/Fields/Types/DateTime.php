<?php namespace Frozennode\Administrator\Fields\Types;

class DateTime extends Time {

	/**
	 * Abstract method that should return a field's string representation in the config files
	 *
	 * @return string
	 */
	public function getConfigName()
	{
		return 'datetime';
	}
}