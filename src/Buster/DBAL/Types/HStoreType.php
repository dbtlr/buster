<?php
namespace Buster\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Buster\HStore\HStoreParser;

class HStoreType extends Type
{
    const HSTORE = 'hstore'; // modify to match your type name
    const ESCAPE = '"\\';

    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return self::HSTORE;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return array|mixed|null
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $parser = new HStoreParser;

        try {
            $value = $parser->parse($value);
        } catch (\Exception $e) {
            throw ConversionException::conversionFailed($e->getMessage(), $this->getName());
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return null|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }
        if (!is_array($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        $parts = array();

        foreach ($value as $key => $val) {
            $parts[] =
                '"' . addcslashes($key, self::ESCAPE) . '"' .
                '=>' .
                ($val === null? 'NULL' : '"' . addcslashes($value, self::ESCAPE) . '"');
        }
        return join(',', $parts);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::HSTORE;
    }
}