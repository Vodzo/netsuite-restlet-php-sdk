<?php

namespace Infostud\NetSuiteSdk\Model\SavedSearch;

use Symfony\Component\Serializer\Annotation\Groups;

class CustomerSearchResponse
	{
	/**
	 * @Groups("myPagedData")
	 * @var SearchMetadata
	 */
	private $searchMetadata;
	/**
	 * @var Customer[]
	 */
	private $customers;

	/**
	 * @return SearchMetadata
	 */
	public function getSearchMetadata()
		{
		return $this->searchMetadata;
		}

	/**
	 * @param SearchMetadata $searchMetadata
	 * @return CustomerSearchResponse
	 */
	public function setSearchMetadata($searchMetadata)
		{
		$this->searchMetadata = $searchMetadata;

		return $this;
		}

	/**
	 * @return Customer[]
	 */
	public function getCustomers()
		{
		return $this->customers;
		}

	/**
	 * @param Customer[] $customers
	 * @return self
	 */
	public function setCustomers($customers)
		{
		$this->customers = $customers;

		return $this;
		}
	}