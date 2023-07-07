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

namespace Doctrine\DBAL\Sharding;

use Doctrine\DBAL\DBALException;

/**
 * Sharding related Exceptions
 *
 * @since 2.3
 */
class ShardingException extends DBALException
{
    /**
     * @return \Doctrine\DBAL\Sharding\ShardingException
     */
    static public function notImplemented()
    {
        return new self("This functionality is not implemented with this sharding provider.", 1_331_557_937);
    }

    /**
     * @return \Doctrine\DBAL\Sharding\ShardingException
     */
    static public function missingDefaultFederationName()
    {
        return new self("SQLAzure requires a federation name to be set during sharding configuration.", 1_332_141_280);
    }

    /**
     * @return \Doctrine\DBAL\Sharding\ShardingException
     */
    static public function missingDefaultDistributionKey()
    {
        return new self("SQLAzure requires a distribution key to be set during sharding configuration.", 1_332_141_329);
    }

    /**
     * @return \Doctrine\DBAL\Sharding\ShardingException
     */
    static public function activeTransaction()
    {
        return new self("Cannot switch shard during an active transaction.", 1_332_141_766);
    }

    /**
     * @return \Doctrine\DBAL\Sharding\ShardingException
     */
    static public function noShardDistributionValue()
    {
        return new self("You have to specify a string or integer as shard distribution value.", 1_332_142_103);
    }

    /**
     * @return \Doctrine\DBAL\Sharding\ShardingException
     */
    static public function missingDistributionType()
    {
        return new self("You have to specify a sharding distribution type such as 'integer', 'string', 'guid'.");
    }
}
