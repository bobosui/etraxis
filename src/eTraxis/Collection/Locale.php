<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Collection;

/**
 * Static collection of locales.
 */
class Locale extends AbstractStaticCollection
{
    /**
     * {@inheritdoc}
     */
    public static function getCollection()
    {
        return [
            'bg'    => 'Български',
            'cs'    => 'Čeština',
            'de'    => 'Deutsch',
            'en_AU' => 'English (Australia)',
            'en_CA' => 'English (Canada)',
            'en_NZ' => 'English (New Zealand)',
            'en_GB' => 'English (United Kingdom)',
            'en_US' => 'English (United States)',
            'es'    => 'Español',
            'fr'    => 'Français',
            'hu'    => 'Magyar',
            'it'    => 'Italiano',
            'ja'    => '日本語',
            'lv'    => 'Latviešu',
            'nl'    => 'Nederlands',
            'pl'    => 'Polski',
            'pt_BR' => 'Português do Brasil',
            'ro'    => 'Română',
            'ru'    => 'Русский',
            'sv'    => 'Svenska',
            'tr'    => 'Türkçe',
        ];
    }
}
