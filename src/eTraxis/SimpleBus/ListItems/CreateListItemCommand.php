<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\ListItems;

use SimpleBus\MessageTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Creates new list item.
 *
 * @property    int    $field ID of the item's field.
 * @property    int    $key   Item key.
 * @property    string $value Item value.
 */
class CreateListItemCommand
{
    use MessageTrait;

    /**
     * @Assert\NotBlank()
     * @Assert\EntityId()
     */
    public $field;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min = "1")
     */
    public $key;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = "50")
     */
    public $value;
}
