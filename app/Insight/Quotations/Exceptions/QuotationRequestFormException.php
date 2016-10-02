<?php 

namespace Insight\Quotations\Exceptions;

use Laracasts\Validation\FormValidationException;

class QuotationRequestFormException extends FormValidationException
{

	/**
	 * @var mixed
	 */
	protected $errors;

	/**
	 * @param string $message
	 * @param mixed  $errors
	 */
	function __construct($message, $errors)
	{
		$this->errors = $errors;

		parent::__construct($message, $errors);
	}

	/**
	 * Get form command handler errors
	 *
	 * @return mixed
	 */
	public function getErrors()
	{
		return $this->errors;
	}

}
 