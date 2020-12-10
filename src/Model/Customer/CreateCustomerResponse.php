<?php

namespace Infostud\NetSuiteSdk\Model\Customer;

use Doctrine\Common\Annotations\Annotation\Enum;
use Symfony\Component\Serializer\Annotation\Groups;

class CreateCustomerResponse
	{
	/**
	 * @Enum({"ok", "error"})
	 * @var string
	 */
	private $result;
	/**
	 * @Groups("internalid")
	 * @var int|null
	 */
	private $customerId;
	/**
	 * @var string|null
	 */
	private $errorName;
	/**
	 * @Groups("message")
	 * @var string|null
	 */
	private $errorMessage;

	/**
	 * @return string
	 */
	public function getResult()
		{
		return $this->result;
		}

	/**
	 * @param string $result
	 * @return self
	 */
	public function setResult($result)
		{
		$this->result = $result;

		return $this;
		}

	/**
	 * @return bool
	 */
	public function isSuccessful()
		{
		return $this->result === 'ok';
		}

	/**
	 * @return int|null
	 */
	public function getCustomerId()
		{
		return $this->customerId;
		}

	/**
	 * @param int|null $customerId
	 * @return self
	 */
	public function setCustomerId($customerId)
		{
		$this->customerId = $customerId;

		return $this;
		}

	/**
	 * @return string|null
	 */
	public function getErrorName()
		{
		return $this->errorName;
		}

	/**
	 * @param string|null $errorName
	 * @return self
	 */
	public function setErrorName($errorName)
		{
		$this->errorName = $errorName;

		return $this;
		}

	/**
	 * @return string|null
	 */
	public function getErrorMessage()
		{
		return $this->errorMessage;
		}

	/**
	 * @param string|null $errorMessage
	 * @return self
	 */
	public function setErrorMessage($errorMessage)
		{
		$this->errorMessage = $errorMessage;

		return $this;
		}
	}
