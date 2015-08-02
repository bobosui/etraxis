<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2015 Artem Rodygin
//
//  This file is part of eTraxis.
//
//  You should have received a copy of the GNU General Public License
//  along with eTraxis.  If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Collection;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Abstract static collection of key/value pairs.
 */
abstract class AbstractStaticCollection implements StaticCollectionInterface
{
    /** @var array Cached collection. */
    protected static $values = [];

    protected static function initCache()
    {
        $key = get_called_class();

        if (!array_key_exists($key, static::$values)) {
            static::$values[$key] = static::getCollection();
        }

        return static::$values[$key];
    }

    /**
     * {@inheritdoc}
     */
    public static function getAllKeys()
    {
        $collection = static::initCache();

        return array_keys($collection);
    }

    /**
     * {@inheritdoc}
     */
    public static function getAllValues()
    {
        $collection = static::initCache();

        return array_values($collection);
    }

    /**
     * {@inheritdoc}
     */
    public static function getValue($key)
    {
        $collection = static::initCache();

        return array_key_exists($key, $collection) ? $collection[$key] : null;
    }

    /**
     * Returns whole collection as array with keys, where values are translated.
     *
     * @param   TranslatorInterface $translator
     *
     * @return  array
     */
    public static function getTranslatedCollection(TranslatorInterface $translator)
    {
        return array_map(function ($value) use ($translator) {
            return $translator->trans($value);
        }, static::getCollection());
    }
}