define({ "api": [
  {
    "type": "post",
    "url": "/api/correct-storage/create/",
    "title": "Create interval",
    "name": "correct_storage_create",
    "version": "0.1.0",
    "group": "Price",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "interval",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "interval.price",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "interval.dateStart",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "interval.dateEnd",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Boolean[]",
            "optional": false,
            "field": "interval.dow",
            "description": "<p>days of week when price enabled</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "request",
          "content": "{\n     \"price\":\"0.01\",\n     \"dateStart\":\"2018-08-01\",\n     \"dateEnd\":\"2018-08-10\",\n     \"dow\":[true, true, false, false, false, true, true]\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "response",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "response.msg",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "response.url",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Success",
          "content": "HTTP/1.1 200 OK\n{\n  \"url\": \"/success/url\",\n  \"msg\": \"some message\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Object",
            "optional": false,
            "field": "response",
            "description": "<p>Error response object</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "response.url",
            "description": ""
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "response.msg",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Not Found",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"url\": \"/not-found-id/url\",\n  \"msg\": \"some error message\"\n}",
          "type": "json"
        },
        {
          "title": "Other error",
          "content": "HTTP/1.1 422\n{\n  \"url\": \"/other-error/url\",\n  \"msg\": \"some error message\"\n}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/api/correct-storage/create/"
      }
    ],
    "filename": "src/App/Action/Api/CreateCorrect.php",
    "groupTitle": "Price"
  },
  {
    "type": "delete",
    "url": "/correct-storage/delete/{id}",
    "title": "Delete price",
    "name": "correct_storage_delete",
    "version": "0.1.0",
    "group": "Price",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>one or multiple ids separated by comma</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "object",
            "optional": false,
            "field": "response",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "response.url",
            "description": "<p>used only for html version to receive redirect url on success</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "response.msg",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Success",
          "content": "HTTP/1.1 200 OK\n{\n  \"url\": \"/success/url\",\n  \"msg\": \"affected [number]\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "object",
            "optional": false,
            "field": "response",
            "description": ""
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "response.url",
            "description": "<p>used only for html version to receive redirect url on error</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "response.msg",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Not Found",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"url\": \"/not-found-id/url\",\n  \"msg\": \"not found\"\n}",
          "type": "json"
        },
        {
          "title": "Other",
          "content": "HTTP/1.1 422\n{\n  \"url\": \"/url-to-go-on-error\",\n  \"msg\": \"error message\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/App/Action/Api/DeleteCorrect.php",
    "groupTitle": "Price"
  },
  {
    "type": "put",
    "url": "/api/correct-storage/update/",
    "title": "Update price",
    "name": "correct_storage_update",
    "version": "0.1.0",
    "group": "Price",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "object",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "object.date",
            "description": "<p>Used to identify which price should be updated</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "object.price",
            "description": "<p>Price value</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "request",
          "content": "{\n     \"date\":\"2018-08-01\"\n     \"price\":\"0.01\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "response",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "response.msg",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "response.url",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Success",
          "content": "HTTP/1.1 200 OK\n{\n  \"affected\": 1\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Object",
            "optional": false,
            "field": "response",
            "description": "<p>Error response object</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "response.url",
            "description": ""
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "response.msg",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Not Found",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"msg\": \"price with Date: [2018-08-18] not found\"\n}",
          "type": "json"
        },
        {
          "title": "Other error",
          "content": "HTTP/1.1 422\n{\n  \"msg\": \"some error message\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/App/Action/Api/UpdateCorrect.php",
    "groupTitle": "Price"
  }
] });
