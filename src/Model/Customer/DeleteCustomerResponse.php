<?php

namespace Infostud\NetSuiteSdk\Model\Customer;

use Doctrine\Common\Annotations\Annotation\Enum;

class DeleteCustomerResponse
	{
	/**
	 * @Enum({"ok", "error"})
	 * @var string
	 */
	private $result;

	/**
	 * @return string
	 */
	public function getResult(): string
		{
		return $this->result;
		}

	/**
	 * @param string $result
	 * @return self
	 */
	public function setResult(string $result): self
		{
		$this->result = $result;

		return $this;
		}

	public function isSuccessful(): bool
		{
		return $this->result === 'ok';
		}
	}
