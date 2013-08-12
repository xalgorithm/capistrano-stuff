ECHO This needs your local MySQL root password (XAMPP default is blank)
REM Just schema first - all tables
mysqldump -u root -p __safe_name__ --add-drop-table --skip-extended-insert --skip-dump-date --no-data > __safe_name__.sql

REM Just data - all except generated tables
mysqldump -u root -p __safe_name__ --skip-extended-insert --skip-dump-date --no-create-info ^
 --ignore-table=__safe_name__.access^
 --ignore-table=__safe_name__.cache^
 --ignore-table=__safe_name__.cache_block^
 --ignore-table=__safe_name__.cache_bootstrap^
 --ignore-table=__safe_name__.cache_content^
 --ignore-table=__safe_name__.cache_field^
 --ignore-table=__safe_name__.cache_filter^
 --ignore-table=__safe_name__.cache_form^
 --ignore-table=__safe_name__.cache_menu^
 --ignore-table=__safe_name__.cache_path^
 --ignore-table=__safe_name__.cache_page^
 --ignore-table=__safe_name__.cache_token^
 --ignore-table=__safe_name__.cache_update^
 --ignore-table=__safe_name__.cache_views^
 --ignore-table=__safe_name__.cache_views_data^
 --ignore-table=__safe_name__.sessions^
 --ignore-table=__safe_name__.watchdog^
 >> __safe_name__.sql
pause
