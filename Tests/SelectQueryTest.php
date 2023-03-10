<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SqlTark\Compiler\MySqlCompiler;
use SqlTark\Query;

final class SelectQueryTest extends TestCase
{
    public function testSelectQuery()
    {
        $query = new Query;
        $query->setCompiler(new MySqlCompiler);

        $output = "SELECT * FROM `table`";
        $query->from('table');
        $this->assertEquals($output, $query->compile());

        $output .= " WHERE `a` = 1";
        $query->equals('a', 1);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `b` != 1.23";
        $query->notEquals('b', 1.23);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `c` > TRUE";
        $query->greaterThan('c', true);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `d` >= 'str'";
        $query->greaterEquals('d', 'str');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `c` < TRUE";
        $query->lesserThan('c', true);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `d` <= 'str'";
        $query->lesserEquals('d', 'str');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `e` IN (1, 1.23, TRUE, NULL, 'str')";
        $query->in('e', [1, 1.23, true, null, 'str']);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `f` IS NULL";
        $query->isNull('f');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `g` BETWEEN 1 AND 100";
        $query->between('g', 1, 100);
        $this->assertEquals($output, $query->compile());

        $output .= " AND (`c` = 'd' AND `d` = 'c')";
        $query->group(fn($q) => $q->equals('c', 'd')->equals('d', 'c'));
        $this->assertEquals($output, $query->compile());

        $output .= " AND EXISTS(SELECT * FROM `tab2`)";
        $query->exists(fn($q) => $q->from('tab2'));
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE '%zenislev%'";
        $query->like('h', '%zenislev%');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE 'zen%'";
        $query->startsWith('h', 'zen');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE '%zen'";
        $query->endsWith('h', 'zen');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE '%zen%'";
        $query->contains('h', 'zen');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE BINARY '%zen%'";
        $query->contains('h', 'zen', true);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE BINARY '%zen!%%' ESCAPE '!'";
        $query->contains('h', 'zen%', true, '!');
        $this->assertEquals($output, $query->compile());

        $output .= " AND MAX(`x`) > '2023-03-10 22:22:22'";
        $query->conditionRaw('MAX(`x`) > ?', new DateTime('2023-03-10 22:22:22'));
        $this->assertEquals($output, $query->compile());
    }
}