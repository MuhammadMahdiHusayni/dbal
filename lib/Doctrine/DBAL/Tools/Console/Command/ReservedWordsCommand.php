<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\DBAL\Tools\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Platforms\Keywords\ReservedKeywordsValidator;

class ReservedWordsCommand extends Command
{
    private array $keywordListClasses = ['mysql'         => \Doctrine\DBAL\Platforms\Keywords\MySQLKeywords::class, 'sqlserver'     => \Doctrine\DBAL\Platforms\Keywords\SQLServerKeywords::class, 'sqlserver2005' => \Doctrine\DBAL\Platforms\Keywords\SQLServer2005Keywords::class, 'sqlserver2008' => \Doctrine\DBAL\Platforms\Keywords\SQLServer2008Keywords::class, 'sqlserver2012' => \Doctrine\DBAL\Platforms\Keywords\SQLServer2012Keywords::class, 'sqlite'        => \Doctrine\DBAL\Platforms\Keywords\SQLiteKeywords::class, 'pgsql'         => \Doctrine\DBAL\Platforms\Keywords\PostgreSQLKeywords::class, 'oracle'        => \Doctrine\DBAL\Platforms\Keywords\OracleKeywords::class, 'db2'           => \Doctrine\DBAL\Platforms\Keywords\DB2Keywords::class];

    /**
     * If you want to add or replace a keywords list use this command.
     *
     * @param string $name
     * @param string $class
     *
     * @return void
     */
    public function setKeywordListClass($name, $class)
    {
        $this->keywordListClasses[$name] = $class;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
        ->setName('dbal:reserved-words')
        ->setDescription('Checks if the current database contains identifiers that are reserved.')
        ->setDefinition([new InputOption(
            'list', 'l', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Keyword-List name.'
        )])
        ->setHelp(<<<EOT
Checks if the current database contains tables and columns
with names that are identifiers in this dialect or in other SQL dialects.

By default SQLite, MySQL, PostgreSQL, Microsoft SQL Server and Oracle
keywords are checked:

    <info>%command.full_name%</info>

If you want to check against specific dialects you can
pass them to the command:

    <info>%command.full_name% mysql pgsql</info>

The following keyword lists are currently shipped with Doctrine:

    * mysql
    * pgsql
    * sqlite
    * oracle
    * sqlserver
    * sqlserver2005
    * sqlserver2008
    * sqlserver2012
    * db2 (Not checked by default)
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $conn \Doctrine\DBAL\Connection */
        $conn = $this->getHelper('db')->getConnection();

        $keywordLists = (array)$input->getOption('list');
        if ( ! $keywordLists) {
            $keywordLists = ['mysql', 'pgsql', 'sqlite', 'oracle', 'sqlserver', 'sqlserver2005', 'sqlserver2008', 'sqlserver2012'];
        }

        $keywords = [];
        foreach ($keywordLists as $keywordList) {
            if (!isset($this->keywordListClasses[$keywordList])) {
                throw new \InvalidArgumentException(
                    "There exists no keyword list with name '" . $keywordList . "'. ".
                    "Known lists: " . implode(", ", array_keys($this->keywordListClasses))
                );
            }
            $class = $this->keywordListClasses[$keywordList];
            $keywords[] = new $class;
        }

        $output->write('Checking keyword violations for <comment>' . implode(", ", $keywordLists) . "</comment>...", true);

        /* @var $schema \Doctrine\DBAL\Schema\Schema */
        $schema = $conn->getSchemaManager()->createSchema();
        $visitor = new ReservedKeywordsValidator($keywords);
        $schema->visit($visitor);

        $violations = $visitor->getViolations();
        if (count($violations) == 0) {
            $output->write("No reserved keywords violations have been found!", true);
        } else {
            $output->write('There are <error>' . count($violations) . '</error> reserved keyword violations in your database schema:', true);
            foreach ($violations as $violation) {
                $output->write('  - ' . $violation, true);
            }

            return 1;
        }
    }
}
