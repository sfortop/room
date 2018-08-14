## Description

Sample project for room prices

api doesn't fully REST-compliant  
e.g. resource naming sometime contains .phtml   
uses various params placements (url, request body)


## Install and run

### setup
```
git clone git@github.com/sfortop/room
docker-compose up [-d]
mysql -uroot -p -e 'CREATE DATABASE room CHARACTER SET "utf8mb4"'
docker-compose restart room
```

### shutdown

```
docker-compose stop
```
or just Ctrl+C in terminal window in case of running as non-daemonized (without -d docker-compose option)

### run

```
docker-compose up
```

### access from browser

Use this link [load project](http://localhost)

## Supported api methods

Still inconsistent in using format of parameters and approaches
 
```
DELETE /api/correct-storage/delete/{id | id1,id2,...,idN}
POST  /api/correct-storage/create/{id}
PUT /api/correct-storage/update/{id}
```

### API documentation

Just run application and go to [api documentation](http://localhost/doc/)
