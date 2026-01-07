-- Fix database lock issues
-- Run this in phpMyAdmin or MySQL command line

-- Show current processes
SHOW PROCESSLIST;

-- Kill any long-running queries (replace PROCESS_ID with actual IDs from above)
-- KILL PROCESS_ID;

-- Optimize the sessions table
OPTIMIZE TABLE sessions;

-- Clear any table locks
UNLOCK TABLES;

-- Check for InnoDB lock waits
SELECT * FROM information_schema.INNODB_LOCKS;
SELECT * FROM information_schema.INNODB_LOCK_WAITS;

-- If you want to truncate sessions (this will log everyone out)
-- TRUNCATE TABLE sessions;
