# Wordlists:
Wordlists have a simple structure: One word per line.
Words should be sorted after frequency of occurrence.
Note that the wordlists' filename should match the table name when using
'php artisan words:upload <filename>' (so create a migration and migrate first).

# Development
While setting up the project, use 'php artisan words:upload all' to upload all
wordlists. To clear wordlists from the database, use
'php artisan words:clear <table_name> <table_name> ...' or
'php artisan words:clear all'.
