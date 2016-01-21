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
 * Updates specified "text" field.
 *
 * @property    int    $maxLength    Maximum allowed length of field values.
 * @property    string $default      Default value of the field.
 * @property    string $regexCheck   Perl-compatible regular expression which values of the field must conform to.
 * @property    string $regexSearch  Perl-compatible regular expression to modify values of the field before display them (search for).
 * @property    string $regexReplace Perl-compatible regular expression to modify values of the field before display them (replace with).
 */
class UpdateTextFieldCommand extends UpdateFieldBaseCommand
{
    /**
     * @Assert\NotBlank()
     * @Assert\Range(min = "1", max = "4000")
     */
    public $maxLength;

    /**
     * @Assert\Length(max = "4000")
     */
    public $default;

    /**
     * @Assert\Length(max = "500")
     */
    public $regexCheck;

    /**
     * @Assert\Length(max = "500")
     */
    public $regexSearch;

    /**
     * @Assert\Length(max = "500")
     */
    public $regexReplace;
}
