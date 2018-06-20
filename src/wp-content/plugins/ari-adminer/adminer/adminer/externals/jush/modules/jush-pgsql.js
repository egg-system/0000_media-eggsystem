jush.tr.pgsql = { sql_apo: /'/, sqlite_quo: /"/, pgsql_eot: /\$/, one: /--/, com_nest: /\/\*/, pgsql_pgsqlset: /(\b)(SHOW|SET)(\s+)/i, num: jush.num }; // standard_conforming_strings=off
jush.tr.pgsql_eot = { pgsql_eot2: /\$/ };
jush.tr.pgsql_eot2 = { }; // pgsql_eot2._2 to be set in pgsql_eot handler
jush.tr.pgsql_pgsqlset = { sql_apo: /'/, sqlite_quo: /"/, pgsql_eot: /\$/, one: /--/, com_nest: /\/\*/, num: jush.num, _1: /;|$/ };
jush.tr.pgsqlset = { _0: /$/ };

jush.urls.pgsql_pgsqlset = 'http://www.postgresql.org/docs/current/static/$key';
jush.urls.pgsql = ['http://www.postgresql.org/docs/current/static/$key',
	'sql-$1.html', 'sql-$1.html', 'sql-alteropclass.html', 'sql-createopclass.html', 'sql-dropopclass.html',
	'functions-datetime.html', 'functions-info.html', 'functions-logical.html', 'functions-comparison.html', 'functions-matching.html', 'functions-conditional.html', 'functions-subquery.html',
	'functions-math.html', 'functions-string.html', 'functions-binarystring.html', 'functions-formatting.html', 'functions-datetime.html', 'functions-geometry.html', 'functions-net.html', 'functions-sequence.html', 'functions-array.html', 'functions-aggregate.html', 'functions-srf.html', 'functions-info.html', 'functions-admin.html'
];
jush.urls.pgsqlset = ['http://www.postgresql.org/docs/current/static/runtime-config-$key.html#GUC-$1',
	'autovacuum', 'client', 'compatible', 'connection', 'custom', 'developer', 'file-locations', 'locks', 'logging', 'preset', 'query', 'resource', 'statistics', 'wal'
];

jush.links.pgsql_pgsqlset = { 'sql-$val.html': /.+/ };

jush.links2.pgsql = /(\b)(COMMIT\s+PREPARED|DROP\s+OWNED|PREPARE\s+TRANSACTION|REASSIGN\s+OWNED|RELEASE\s+SAVEPOINT|ROLLBACK\s+PREPARED|ROLLBACK\s+TO|SET\s+CONSTRAINTS|SET\s+ROLE|SET\s+SESSION\s+AUTHORIZATION|SET\s+TRANSACTION|START\s+TRANSACTION|(ABORT|ALTER\s+AGGREGATE|ALTER\s+CONVERSION|ALTER\s+DATABASE|ALTER\s+DOMAIN|ALTER\s+FUNCTION|ALTER\s+GROUP|ALTER\s+INDEX|ALTER\s+LANGUAGE|ALTER\s+OPERATOR|ALTER\s+ROLE|ALTER\s+SCHEMA|ALTER\s+SEQUENCE|ALTER\s+TABLE|ALTER\s+TABLESPACE|ALTER\s+TRIGGER|ALTER\s+TYPE|ALTER\s+USER|ANALYZE|BEGIN|CHECKPOINT|CLOSE|CLUSTER|COMMENT|COMMIT|COPY|CREATE\s+AGGREGATE|CREATE\s+CAST|CREATE\s+CONSTRAINT|CREATE\s+CONVERSION|CREATE\s+DATABASE|CREATE\s+DOMAIN|CREATE\s+FUNCTION|CREATE\s+GROUP|CREATE\s+INDEX|CREATE\s+LANGUAGE|CREATE\s+OPERATOR|CREATE\s+ROLE|CREATE\s+RULE|CREATE\s+SCHEMA|CREATE\s+SEQUENCE|CREATE\s+TABLE|CREATE\s+TABLE\s+AS|CREATE\s+TABLESPACE|CREATE\s+TRIGGER|CREATE\s+TYPE|CREATE\s+USER|CREATE\s+VIEW|DEALLOCATE|DECLARE|DELETE|DROP\s+AGGREGATE|DROP\s+CAST|DROP\s+CONVERSION|DROP\s+DATABASE|DROP\s+DOMAIN|DROP\s+FUNCTION|DROP\s+GROUP|DROP\s+INDEX|DROP\s+LANGUAGE|DROP\s+OPERATOR|DROP\s+ROLE|DROP\s+RULE|DROP\s+SCHEMA|DROP\s+SEQUENCE|DROP\s+TABLE|DROP\s+TABLESPACE|DROP\s+TRIGGER|DROP\s+TYPE|DROP\s+USER|DROP\s+VIEW|END|EXECUTE|EXPLAIN|FETCH|GRANT|INSERT|LISTEN|LOAD|LOCK|MOVE|NOTIFY|PREPARE|REINDEX|RESET|REVOKE|ROLLBACK|SAVEPOINT|SELECT|SELECT\s+INTO|TRUNCATE|UNLISTEN|UPDATE|VACUUM|VALUES)|(ALTER\s+OPERATOR\s+CLASS)|(CREATE\s+OPERATOR\s+CLASS)|(DROP\s+OPERATOR\s+CLASS)|(current_date|current_time|current_timestamp|localtime|localtimestamp|AT\s+TIME\s+ZONE)|(current_user|session_user|user)|(AND|NOT|OR)|(BETWEEN)|(LIKE|SIMILAR\s+TO)|(CASE|WHEN|THEN|ELSE|coalesce|nullif|greatest|least)|(EXISTS|IN|ANY|SOME|ALL))\b|\b(abs|cbrt|ceil|ceiling|degrees|exp|floor|ln|log|mod|pi|power|radians|random|round|setseed|sign|sqrt|trunc|width_bucket|acos|asin|atan|atan2|cos|cot|sin|tan|(bit_length|char_length|convert|lower|octet_length|overlay|position|substring|trim|upper|ascii|btrim|chr|decode|encode|initcap|length|lpad|ltrim|md5|pg_client_encoding|quote_ident|quote_literal|regexp_replace|repeat|replace|rpad|rtrim|split_part|strpos|substr|to_ascii|to_hex|translate)|(get_bit|get_byte|set_bit|set_byte|md5)|(to_char|to_date|to_number|to_timestamp)|(age|clock_timestamp|date_part|date_trunc|extract|isfinite|justify_days|justify_hours|justify_interval|now|statement_timestamp|timeofday|transaction_timestamp)|(area|center|diameter|height|isclosed|isopen|npoints|pclose|popen|radius|width|box|circle|lseg|path|point|polygon)|(abbrev|broadcast|family|host|hostmask|masklen|netmask|network|set_masklen|text|trunc)|(currval|nextval|setval)|(array_append|array_cat|array_dims|array_lower|array_prepend|array_to_string|array_upper|string_to_array)|(avg|bit_and|bit_or|bool_and|bool_or|count|every|max|min|sum|corr|covar_pop|covar_samp|regr_avgx|regr_avgy|regr_count|regr_intercept|regr_r2|regr_slope|regr_sxx|regr_sxy|regr_syy|stddev|stddev_pop|stddev_samp|variance|var_pop|var_samp)|(generate_series)|(current_database|current_schema|current_schemas|inet_client_addr|inet_client_port|inet_server_addr|inet_server_port|pg_my_temp_schema|pg_is_other_temp_schema|pg_postmaster_start_time|version|has_database_privilege|has_function_privilege|has_language_privilege|has_schema_privilege|has_table_privilege|has_tablespace_privilege|pg_has_role|pg_conversion_is_visible|pg_function_is_visible|pg_operator_is_visible|pg_opclass_is_visible|pg_table_is_visible|pg_type_is_visible|format_type|pg_get_constraintdef|pg_get_expr|pg_get_indexdef|pg_get_ruledef|pg_get_serial_sequence|pg_get_triggerdef|pg_get_userbyid|pg_get_viewdef|pg_tablespace_databases|col_description|obj_description|shobj_description)|(current_setting|set_config|pg_cancel_backend|pg_reload_conf|pg_rotate_logfile|pg_start_backup|pg_stop_backup|pg_switch_xlog|pg_current_xlog_location|pg_current_xlog_insert_location|pg_xlogfile_name_offset|pg_xlogfile_name|pg_column_size|pg_database_size|pg_relation_size|pg_size_pretty|pg_tablespace_size|pg_total_relation_size|pg_ls_dir|pg_read_file|pg_stat_file|pg_advisory_lock|pg_advisory_lock_shared|pg_try_advisory_lock|pg_try_advisory_lock_shared|pg_advisory_unlock|pg_advisory_unlock_shared|pg_advisory_unlock_all))(\s*\(|$)/gi; // collisions: IN, ANY, SOME, ALL (array), trunc, md5, abbrev
jush.links2.pgsqlset = /(\b)(autovacuum|log_autovacuum_min_duration|autovacuum_max_workers|autovacuum_naptime|autovacuum_vacuum_threshold|autovacuum_analyze_threshold|autovacuum_vacuum_scale_factor|autovacuum_analyze_scale_factor|autovacuum_freeze_max_age|autovacuum_vacuum_cost_delay|autovacuum_vacuum_cost_limit|(search_path|default_tablespace|temp_tablespaces|check_function_bodies|default_transaction_isolation|default_transaction_read_only|session_replication_role|statement_timeout|vacuum_freeze_table_age|vacuum_freeze_min_age|xmlbinary|xmloption|datestyle|intervalstyle|timezone|timezone_abbreviations|extra_float_digits|client_encoding|lc_messages|lc_monetary|lc_numeric|lc_time|default_text_search_config|dynamic_library_path|gin_fuzzy_search_limit|local_preload_libraries)|(add_missing_from|array_nulls|backslash_quote|default_with_oids|escape_string_warning|regex_flavor|sql_inheritance|standard_conforming_strings|synchronize_seqscans|transform_null_equals)|(listen_addresses|port|max_connections|superuser_reserved_connections|unix_socket_directory|unix_socket_group|unix_socket_permissions|bonjour_name|tcp_keepalives_idle|tcp_keepalives_interval|tcp_keepalives_count|authentication_timeout|ssl|ssl_renegotiation_limit|ssl_ciphers|password_encryption|krb_server_keyfile|krb_srvname|krb_caseins_users|db_user_namespace)|(custom_variable_classes)|(allow_system_table_mods|debug_assertions|ignore_system_indexes|post_auth_delay|pre_auth_delay|trace_notify|trace_sort|wal_debug|zero_damaged_pages)|(data_directory|config_file|hba_file|ident_file|external_pid_file)|(deadlock_timeout|max_locks_per_transaction)|(log_destination|logging_collector|log_directory|log_filename|log_rotation_age|log_rotation_size|log_truncate_on_rotation|syslog_facility|syslog_ident|silent_mode|client_min_messages|log_min_messages|log_error_verbosity|log_min_error_statement|log_min_duration_statement|log_checkpoints|log_connections|log_disconnections|log_duration|log_hostname|log_line_prefix|log_lock_waits|log_statement|log_temp_files|log_timezone)|(block_size|integer_datetimes|lc_collate|lc_ctype|max_function_args|max_identifier_length|max_index_keys|segment_size|server_encoding|server_version|server_version_num|wal_block_size|wal_segment_size)|(enable_bitmapscan|enable_hashagg|enable_hashjoin|enable_indexscan|enable_mergejoin|enable_nestloop|enable_seqscan|enable_sort|enable_tidscan|seq_page_cost|random_page_cost|cpu_tuple_cost|cpu_index_tuple_cost|cpu_operator_cost|effective_cache_size|geqo|geqo_threshold|geqo_effort|geqo_pool_size|geqo_generations|geqo_selection_bias|default_statistics_target|constraint_exclusion|cursor_tuple_fraction|from_collapse_limit|join_collapse_limit)|(shared_buffers|temp_buffers|max_prepared_transactions|work_mem|maintenance_work_mem|max_stack_depth|max_files_per_process|shared_preload_libraries|vacuum_cost_delay|vacuum_cost_page_hit|vacuum_cost_page_miss|vacuum_cost_page_dirty|vacuum_cost_limit|bgwriter_delay|bgwriter_lru_maxpages|bgwriter_lru_multiplier|effective_io_concurrency)|(track_activities|track_activity_query_size|track_counts|track_functions|update_process_title|stats_temp_directory)|(fsync|synchronous_commit|wal_sync_method|full_page_writes|wal_buffers|wal_writer_delay|commit_delay|commit_siblings|checkpoint_segments|checkpoint_timeout|checkpoint_completion_target|checkpoint_warning|archive_mode|archive_command|archive_timeout))(\b)/gi;