#!/bin/bash
# watch_deploy.sh - Background Auto-Deploy Watcher for Insurio
echo "🚀 Auto-deploy watcher started!"
echo "Monitoring app/, resources/, database/ for file edits..."

LAST_CHANGE=$(find app resources database -type f -exec stat -f "%m" {} + 2>/dev/null | sort -n | tail -1)

while true; do
    sleep 2
    CURRENT_CHANGE=$(find app resources database -type f -exec stat -f "%m" {} + 2>/dev/null | sort -n | tail -1)
    if [ "$CURRENT_CHANGE" != "$LAST_CHANGE" ]; then
        echo "⚡ Change detected! Running automatic deployment to server..."
        ./deploy.sh
        LAST_CHANGE=$(find app resources database -type f -exec stat -f "%m" {} + 2>/dev/null | sort -n | tail -1)
    fi
done
