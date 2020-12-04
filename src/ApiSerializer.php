<?php

namespace Infostud\NetSuiteSdk;

use DateTimeZone;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Infostud\NetSuiteSdk\Serializer\CustomObjectNormalizer;
use Infostud\NetSuiteSdk\Serializer\SerializedNameConverter;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiSerializer
	{
	/**
	 * @var SerializerInterface
	 */
	private $serializer;

	public function __construct()
		{
		//Load annotations
		AnnotationRegistry::registerLoader(
			'class_exists'
		);

		$classMetadataFactory       = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
		$objectNormalizer           = new CustomObjectNormalizer(
			$classMetadataFactory,
			new SerializedNameConverter($classMetadataFactory),
			null,
			new PhpDocExtractor()
		);
		$dateTimeNormalizer = new DateTimeNormalizer([
			DateTimeNormalizer::FORMAT_KEY => 'd.m.Y. H:i',
			DateTimeNormalizer::TIMEZONE_KEY => new DateTimeZone('Europe/Belgrade')
		]);

		$this->serializer = new Serializer(
			[$dateTimeNormalizer, $objectNormalizer, new ArrayDenormalizer()],
			[new JsonEncoder()]
		);
		}

	/**
	 * Add other top level classes to return
	 * (explicit, to aid type-hinting)
	 *
	 * @param string $json
	 * @param string $className
	 * @return array|object
	 */
	public function deserialize($json, $className)
		{
		return $this->serializer->deserialize($json, $className, 'json');
		}
	}
