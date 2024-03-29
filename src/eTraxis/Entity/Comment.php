<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment.
 *
 * @ORM\Table(name="tbl_comments",
 *            uniqueConstraints={
 *                @ORM\UniqueConstraint(name="ix_comments", columns={"event_id"})
 *            })
 * @ORM\Entity
 */
class Comment
{
    /**
     * @var int Unique ID.
     *
     * @ORM\Column(name="comment_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int Event ID.
     *
     * @ORM\Column(name="event_id", type="integer")
     */
    private $eventId;

    /**
     * @var int Whether comment is private.
     *
     * @ORM\Column(name="is_confidential", type="integer")
     */
    private $isPrivate;

    /**
     * @var string Comment body.
     *
     * @ORM\Column(name="comment_body", type="text")
     */
    private $comment;

    /**
     * @var Event Event.
     *
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     */
    private $event;

    /**
     * Standard getter.
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Standard setter.
     *
     * @param   int $eventId
     *
     * @return  self
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Standard getter.
     *
     * @return  int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Standard setter.
     *
     * @param   bool $isPrivate
     *
     * @return  self
     */
    public function setPrivate($isPrivate)
    {
        $this->isPrivate = $isPrivate ? 1 : 0;

        return $this;
    }

    /**
     * Standard getter.
     *
     * @return  bool
     */
    public function isPrivate()
    {
        return (bool) $this->isPrivate;
    }

    /**
     * Standard setter.
     *
     * @param   string $comment
     *
     * @return  self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Standard getter.
     *
     * @return  string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Standard setter.
     *
     * @param   Event $event
     *
     * @return  self
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Standard getter.
     *
     * @return  Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
