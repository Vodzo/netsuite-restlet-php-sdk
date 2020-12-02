<?php

use Infostud\NetSuiteSdk\Model\SavedSearch\ColumnDefinition;
use Infostud\NetSuiteSdk\Model\SavedSearch\Customer;
use Infostud\NetSuiteSdk\Model\CustomerForm;
use Infostud\NetSuiteSdk\Model\CustomerFormAddress;
use Infostud\NetSuiteSdk\Model\SuiteQL\GetSubsidiariesResponse;
use Infostud\NetSuiteSdk\Model\SavedSearch\SavedSearchCustomersResponse;
use Infostud\NetSuiteSdk\ApiSerializer;
use Infostud\NetSuiteSdk\Model\SuiteQL\GetDepartmentsResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ApiSerializerTest extends TestCase
	{
	/**
	 * @throws ExceptionInterface
	 */
	public function testCustomerFormRequest(): void
		{
		$serializer = new ApiSerializer();
		$customerForm = (new CustomerForm())
			->setExternalId('PIB-123456')
			->setCompanyName('Test item')
			->setSubsidiary(9)
			->setVatIdentifier('101696893')
			->setRegistryIdentifier('01234567')
			->addAddress(
				(new CustomerFormAddress())
					->setLabel('Nazor')
					->setCity('Subotica')
					->setAddressLine1('Vladimira Nazora 7')
					->setAddressLine2('(u pasažu)')
					->setPostalCode('24000')
					->setCountry(CustomerFormAddress::COUNTRY_SERBIA)
			);
		$normalized = $serializer->normalize($customerForm);
		self::assertEquals('PIB-123456', $normalized['externalId']);
		self::assertEquals('Test item', $normalized['companyname']);
		self::assertEquals(9, $normalized['subsidiary']);
		self::assertEquals('101696893', $normalized['custentity_pib']);
		self::assertEquals('01234567', $normalized['custentity_matbrpred']);
		self::assertCount(1, $normalized['address']);
		$address = $normalized['address'][0];
		self::assertEquals('Nazor', $address['label']);
		self::assertEquals('Subotica', $address['city']);
		self::assertEquals('Vladimira Nazora 7', $address['addr1']);
		self::assertEquals('(u pasažu)', $address['addr2']);
		self::assertEquals('24000', $address['zip']);
		self::assertEquals(CustomerFormAddress::COUNTRY_SERBIA, $address['country']);
		}

	public function testSingleCustomerSearchResult(): void
		{
		$serializer = new ApiSerializer();
		$json = file_get_contents(__DIR__.'/single_customer_search_response.json');
		$response = $serializer->deserialize($json, SavedSearchCustomersResponse::class);
		self::assertInstanceOf(SavedSearchCustomersResponse::class, $response);
		$searchMetadata = $response->getSearchMetadata();
		self::assertEquals(1, $searchMetadata->getCount());
		$searchDefinition = $searchMetadata->getSearchDefinition();
		self::assertCount(13, $searchDefinition->getColumns());
		self::assertContainsOnlyInstancesOf(ColumnDefinition::class, $searchDefinition->getColumns());
		foreach ($searchDefinition->getColumns() as $columnDefinition)
			{
			self::assertNotEmpty($columnDefinition->getName());
			self::assertNotEmpty($columnDefinition->getLabel());
			self::assertNotEmpty($columnDefinition->getType());
			self::assertNotEmpty($columnDefinition->getSortDirection());
			}

		self::assertCount(1, $response->getCustomers());
		$customer = $response->getCustomers()[0];
		self::assertInstanceOf(Customer::class, $customer);
		self::assertEquals('16099', $customer->getId());
		self::assertEquals('3DH Real Estate PR Dino Hatibović', $customer->getAttributes()->getName());
		self::assertEquals('3dhoglasavanje@gmail.com', $customer->getAttributes()->getEmail());
		self::assertEquals('109121175', $customer->getAttributes()->getVatIdentifier());
		self::assertEquals('63944017', $customer->getAttributes()->getRegistryIdentifier());
		self::assertEquals(
			'2020-08-07T11:36:00+02:00',
			$customer->getAttributes()->getCreatedAt()->format(DateTimeInterface::ATOM)
		);
		self::assertEquals(
			'2020-11-20T11:55:00+01:00',
			$customer->getAttributes()->getLastModifiedAt()->format(DateTimeInterface::ATOM)
		);
		}

	public function testGetSubsidiariesResult(): void
		{
		$serializer = new ApiSerializer();
		$json = file_get_contents(__DIR__.'/subsidiaries_suiteql_response.json');
		$response = $serializer->deserialize($json, GetSubsidiariesResponse::class);
		self::assertInstanceOf(GetSubsidiariesResponse::class, $response);
		self::assertNotEmpty($response->getRows());
		$subsidiaryIds = [];
		foreach ($response->getRows() as $subsidiary)
			{
			self::assertNotEmpty($subsidiary->getId());
			self::assertNotEmpty($subsidiary->getName());
			$subsidiaryIds[] = $subsidiary->getId();
			}
		// Parent consistency
		foreach ($response->getRows() as $subsidiary)
			{
			if ($subsidiary->getParentId())
				{
				self::assertContains($subsidiary->getParentId(), $subsidiaryIds);
				}
			}
		}

	public function testGetDepartmentsResult(): void
		{
		$serializer = new ApiSerializer();
		$json = file_get_contents(__DIR__.'/departments_suiteql_response.json');
		$response = $serializer->deserialize($json, GetDepartmentsResponse::class);
		self::assertInstanceOf(GetDepartmentsResponse::class, $response);
		self::assertNotEmpty($response->getRows());
		$departmentIds = [];
		foreach ($response->getRows() as $department)
			{
			self::assertNotEmpty($department->getId());
			self::assertNotEmpty($department->getName());
			$departmentIds[] = $department->getId();
			}
		// Parent consistency
		foreach ($response->getRows() as $department)
			{
			if ($department->getParentId())
				{
				self::assertContains($department->getParentId(), $departmentIds);
				}
			}
		}
	}
