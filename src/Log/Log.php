<?php

declare(strict_types=1);

namespace GiveGoogleAnalytics\Log;

/**
 * @since 1.0.0
 */
class Log extends \Give\Log\Log {
    /**
     * @inheritDoc
     * @since 1.0.0
     *
     * @param  string $name
     * @param  array  $arguments
     */
    public static function __callStatic( $name, $arguments ) {
        $arguments[1]['source'] = 'Google Analytics';

        parent::__callStatic( $name, $arguments );
    }
}
