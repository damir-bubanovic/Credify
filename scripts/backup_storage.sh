#!/usr/bin/env bash

set -e

BACKUP_DIR="backups/storage"
TIMESTAMP="$(date +'%Y-%m-%d_%H-%M-%S')"

mkdir -p "$BACKUP_DIR"

ARCHIVE="${BACKUP_DIR}/storage_${TIMESTAMP}.tar.gz"

# Archive the storage directory (from host)
tar -czf "$ARCHIVE" storage

echo "Storage backup written to $ARCHIVE"
