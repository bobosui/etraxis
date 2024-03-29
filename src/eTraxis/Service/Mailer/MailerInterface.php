<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Service\Mailer;

/**
 * Mailer interface.
 */
interface MailerInterface
{
    /**
     * Sends email as specified.
     *
     * If multiple recipients need to receive the message an array should be used.
     * Example: array('receiver@domain.org', 'other@domain.org' => 'A name')
     *
     * @param   string|array $recipients Recipient address(es).
     * @param   string       $subject    Email subject.
     * @param   string       $template   Path to Twig template of the email body.
     * @param   array        $args       Twig template parameters.
     *
     * @return  int The number of recipients who were accepted for delivery.
     */
    public function send($recipients, $subject, $template, array $args = []);
}
