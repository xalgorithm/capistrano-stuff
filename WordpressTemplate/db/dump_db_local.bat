REM Just schema first - all tables
mysqldump -u root -p __safe_name__ --add-drop-table --skip-extended-insert --skip-dump-date --no-data > __safe_name__.sql

REM Just data - all except generated tables
mysqldump -u root -p __safe_name__ --skip-extended-insert --skip-dump-date --no-create-info >> __safe_name__.sql
pause