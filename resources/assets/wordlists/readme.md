# Wordlists:
Wordlists have a simple structure: One word per line.
Words should be sorted after frequency of occurrence.
A list should not contain curses, company names (or any trademarks) or political parties.
Note that the wordlists' filename should match the table name when using
'php artisan load:words --name=<filename> --name=<filename2> …' (so create a migration and migrate first).

# Development
While setting up the project, use 'php artisan load:words' to upload all
wordlists. To clear wordlists from the database, use
'php artisan unload:words --name=<table_name> --name=<table_name2> ...' or
'php artisan unload:words' (to clear all wordlist tables).
