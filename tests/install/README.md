# How to install it?
- Open your terminal and navigate to this directory.
- Make sure that your docker is ready to up.
- Execute: docker-compose up -d
- Now, you can open a new tab on your explorer to: http://localhost:8080 A wordpress default page should be displayed.

## Default credentials for wp-login:

- user: root
- password: root

## Quality of life if you are getting "lower_case_table_names settings for server ('2') and data dictionary ('0')" ERROR on Windows

- Go to ./mysql_data directory.
- Execute in terminal: fsutil.exe file queryCaseSensitiveInfo ./ 
- If case sensitive is disabled, enable with the following command: fsutil.exe file setCaseSensitiveInfo ./ enable 
