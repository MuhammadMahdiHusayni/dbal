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

namespace Doctrine\DBAL\Cache;

use Doctrine\DBAL\Driver\ResultStatement;
use PDO;

class ArrayStatement implements \IteratorAggregate, ResultStatement
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var integer
     */
    private $columnCount = 0;

    /**
     * @var integer
     */
    private $num = 0;

    /**
     * @var integer
     */
    private $defaultFetchMode = PDO::FETCH_BOTH;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        if (count($data)) {
            $this->columnCount = count($data[0]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function closeCursor()
    {
        unset ($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function columnCount()
    {
        return $this->columnCount;
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode(int $mode, mixed ...$args)
    {
        if (!empty($args)) {
            throw new \InvalidArgumentException("Caching layer does not support 2nd/3rd argument to setFetchMode()");
        }

        $this->defaultFetchMode = $mode;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $data = $this->fetchAll();

        return new \ArrayIterator($data);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchNumeric(), fetchAssociative() or fetchOne() instead.
     */
    public function fetch($fetchMode = null, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        if (! isset($this->data[$this->num])) {
            return false;
        }

        $row       = $this->data[$this->num++];
        $fetchMode = $fetchMode ?: $this->defaultFetchMode;

        if ($fetchMode === FetchMode::ASSOCIATIVE) {
            return $row;
        }

        if ($fetchMode === FetchMode::NUMERIC) {
            return array_values($row);
        }

        if ($fetchMode === FetchMode::MIXED) {
            return array_merge($row, array_values($row));
        }

        if ($fetchMode === FetchMode::COLUMN) {
            return reset($row);
        }

        throw new InvalidArgumentException('Invalid fetch-style given for fetching result.');
    }

    /**
     * {@inheritdoc}
     */
    
    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array
    {
        $rows = array();
        while ($row = $this->fetch($mode)) {
            $rows[] = $row;
        }

        if (count($args) === 0) {
            return $rows;
        }

        // not sure what to do if got $args
        return $rows;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn(int $columnIndex = 0): mixed
    {
        $row = $this->fetch(PDO::FETCH_NUM);
        if (!isset($row[$columnIndex])) {
            // TODO: verify this is correct behavior
            return false;
        }

        return $row[$columnIndex];
    }
}
