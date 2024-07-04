<?php declare(strict_types = 1);

namespace Contributte\Redis\Serializer;

final class DefaultSerializer implements Serializer
{

	/**
	 * {@inheritDoc}
	 */
	public function serialize($data, array &$meta): string
	{
		return @serialize($data);
	}


	/**
	 * {@inheritDoc}
	 */
	public function unserialize(string $data, array $meta)
	{
		return @unserialize($data);
	}

}
