<?php 
namespace Flowork\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Flowork\AuditLogBatch batch()
 * @method static \Flowork\AuditLogFetch fetch()
 * @method static array push()
 * @method static array send()
 * @method static \Flowork\AuditLog category(string $value)
 */

/**
 * Atlas Facade
 *
 * @method static Atlas model($class) Get a model helper class.
 */
class Auditlog extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auditlog';
    }
}
