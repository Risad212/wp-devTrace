<?php

namespace DevTrace;

class Database {

    /**
     * Create plugin tables.
     *
     * @return void
     */
    public static function createTables(): void {
        global $wpdb;

        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$wpdb->prefix}devtrace_errors (
            id             BIGINT(20)   NOT NULL AUTO_INCREMENT,
            type           VARCHAR(20)  NOT NULL DEFAULT 'fatal',
            message        TEXT         NOT NULL,
            file           VARCHAR(500) NOT NULL,
            line           INT(10)      NOT NULL,
            url            VARCHAR(500) NULL,
            active_plugins LONGTEXT     NULL,
            created_at     DATETIME     DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    /**
     * Drop plugin tables.
     *
     * @return void
     */
    public static function dropTables(): void {
        global $wpdb;
        $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}devtrace_errors" );
    }

    /**
     * Get distinct values from column.
     *
     * @param string $column
     * @return array
     */
    public static function getDistinctValues( string $column ): array {
        global $wpdb;

        return $wpdb->get_col(
            "SELECT DISTINCT {$column} FROM {$wpdb->prefix}devtrace_errors ORDER BY {$column}"
        );
    }

}

