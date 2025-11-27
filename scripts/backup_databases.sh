#!/usr/bin/env bash

set -e

# Where to store dumps (relative to project root)
BACKUP_DIR="backups/db"
TIMESTAMP="$(date +'%Y-%m-%d_%H-%M-%S')"

mkdir -p "$BACKUP_DIR"

# Sail alias (adjust if you don't use the "sail" alias)
SAIL="./vendor/bin/sail"

# MySQL credentials (same as .env)
MYSQL_USER="sail"
MYSQL_PASS="password"

# 1) Backup central DB (laravel)
$SAIL exec mysql sh -c "mysqldump -u$MYSQL_USER -p$MYSQL_PASS laravel" \
  > "$BACKUP_DIR/central_laravel_${TIMESTAMP}.sql"

# 2) Backup each tenant DB whose name starts with 'tenant'
TENANT_DBS=$($SAIL exec mysql sh -c "mysql -N -u$MYSQL_USER -p$MYSQL_PASS -e \"SHOW DATABASES LIKE 'tenant%';\"")

for DB in $TENANT_DBS; do
  echo "Backing up tenant DB: $DB"
  $SAIL exec mysql sh -c "mysqldump -u$MYSQL_USER -p$MYSQL_PASS $DB" \
    > "$BACKUP_DIR/${DB}_${TIMESTAMP}.sql"
done

echo "Backups completed at $TIMESTAMP"
