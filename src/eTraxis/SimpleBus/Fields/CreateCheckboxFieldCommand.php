<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Fields;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Creates new "checkbox" field.
 *
 * @property    bool $defaultValue Default value of the field.
 */
class CreateCheckboxFieldCommand extends CreateFieldBaseCommand
{
    /**
     * @Assert\NotNull()
     */
    public $defaultValue;
}
