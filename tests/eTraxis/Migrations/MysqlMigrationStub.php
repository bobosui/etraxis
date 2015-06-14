<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014 Artem Rodygin
//
//  This file is part of eTraxis.
//
//  You should have received a copy of the GNU General Public License
//  along with eTraxis.  If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------


namespace eTraxis\Migrations;

use Doctrine\DBAL\Schema\Schema;

class MysqlMigrationStub extends BaseMigrationStub implements MysqlMigrationInterface
{
    public function __construct()
    {
        $this->connection = new ConnectionStub('mysql');
    }

    public function mysqlUp(Schema $schema)
    {
        print('mysql up');
    }

    public function mysqlDown(Schema $schema)
    {
        print('mysql down');
    }
}