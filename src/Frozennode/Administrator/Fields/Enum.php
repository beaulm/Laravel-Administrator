<?php
namespace Frozennode\Administrator\Fields;

use Illuminate\Database\Query\Builder as QueryBuilder;

class Enum extends Field {

	/**
	 * The options used for the enum field
	 *
	 * @var array
	 */
	protected $rules = array(
		'options' => 'required|array|not_empty',
	);

	/**
	 * Builds a few basic options
	 */
	public function build()
	{
		parent::build();

		$options = $this->suppliedOptions;

		$overrideKeys = $this->checkIfKeysShouldBeOverwritten($options);

		$dataOptions = $options['options'];
		$options['options'] = array();

		//iterate over the options to create the options assoc array
		foreach ($dataOptions as $val => $text)
		{
			$options['options'][] = array(
				'id' => ($overrideKeys and is_numeric($val)) ? $text : $val,
				'text' => $text,
			);
		}

		$this->suppliedOptions = $options;
	}

	/**
	 * Check if the keys of an array are standard numerical vs unstandard numerical
	 *
	 * @param array									$options
	 */
	public function checkIfKeysShouldBeOverwritten($options)
	{
		$index = 0;
		foreach($options['options'] as $key => $value)
		{
			if($key != $index)
			{
				return false;
			}
			$index++;
		}
		return true;
	}

	/**
	 * Fill a model with input data
	 *
	 * @param \Illuminate\Database\Eloquent\model	$model
	 * @param mixed									$input
	 */
	public function fillModel(&$model, $input)
	{
		$model->{$this->getOption('field_name')} = $input;
	}

	/**
	 * Sets the filter options for this item
	 *
	 * @param array		$filter
	 *
	 * @return void
	 */
	public function setFilter($filter)
	{
		parent::setFilter($filter);

		$this->userOptions['value'] = $this->getOption('value') === '' ? null : $this->getOption('value');
	}

	/**
	 * Filters a query object
	 *
	 * @param \Illuminate\Database\Query\Builder	$query
	 * @param array									$selects
	 *
	 * @return void
	 */
	public function filterQuery(QueryBuilder &$query, &$selects = null)
	{
		//run the parent method
		parent::filterQuery($query, $selects);

		//if there is no value, return
		if (!$this->getOption('value'))
		{
			return;
		}

		$query->where($this->config->getDataModel()->getTable().'.'.$this->getOption('field_name'), '=', $this->getOption('value'));
	}
}