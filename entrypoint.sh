#!/bin/sh -l

# Exit on failure
set -e

echo "Calling php script"
php /usr/local/bin/src/main.php
exit $?