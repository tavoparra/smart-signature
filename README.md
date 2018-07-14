# Smart Signature

## Endpoints description

  **Login**
----

* **URL**

  /login

* **Method:**
  
  `POST`

*  **URL Params**

   n/a


* **Data Params**

  username: The username of the user trying to login
  password: The password of the user trying to login

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** `{ user : "Fulanito Pérez", "token": "456sa45d6a54d6" }`
 
* **Sample Call:**

  `POST http://localhost/smart-signature/api/v1/login`

  ```json
  {
    "username": "fulanito",
    "password": "XXXXXXXXX"
  }
  ```

* **Notes:**
  Login endpoint will return a token in its parameters that need to be added as a header for subsequent requests in order to gain access. The key for the header must be `Authorization`
  
  
  
  **My submitted documents**
----

* **URL**

  /documents

* **Method:**
  
  `GET`

*  **URL Params**

   n/a


* **Data Params**

  n/a

* **Success Response:**

  * **Code:** 200 <br />
    **Content:**
```json
  [
    {
        "id": 1,
        "document": "http://localhost.com/documents/factura20180713.pdf",
        "owner_id": "1",
        "authorizer_id": "3",
        "created_at": "2018-07-13 21:29:47",
        "updated_at": "2018-07-13 22:36:02",
        "status": "PENDING",
        "signature": "",
        "authorizer": {
            "id": 3,
            "name": "Enrique Flores"
        }
    },
    {
        "id": 2,
        "document": "http://localhost.com/documents/invoice-167428513795.pdf",
        "owner_id": "1",
        "authorizer_id": "2",
        "created_at": "2018-07-13 21:30:18",
        "updated_at": "2018-07-13 21:30:18",
        "status": "PENDING",
        "signature": null,
        "authorizer": {
            "id": 2,
            "name": "Juan José Hermosillo"
        }
    }
]
```

* **Sample Call:**

  `GET http://localhost/smart-signature/api/v1/documents`


* **Notes:**
  My submitted documents endpoint will return an array with the document that you have requested to be signed off, including the actual status, the signature string (If applies) and the information of the authorizer (The person who was requested to sign the document)
  

 
  **My pending documents**
----
 
* **URL**

  /documents/pending

* **Method:**
  
  `GET`

*  **URL Params**

   n/a


* **Data Params**

  n/a

* **Success Response:**

  * **Code:** 200 <br />
    **Content:**
```json
[
    {
        "id": 3,
        "document": "http://localhost/documents/18526760.pdf",
        "owner_id": "3",
        "authorizer_id": "1",
        "created_at": "2018-07-13 22:37:59",
        "updated_at": "2018-07-13 22:37:59",
        "status": "PENDING",
        "signature": null,
        "owner": {
            "id": 3,
            "name": "Enrique Flores Ramírez"
        }
    }
]
```

* **Sample Call:**

  `GET http://localhost/smart-signature/api/v1/documents/pending`


* **Notes:**
  My pending documents endpoint will return an array with the documents that require your signature, including the information of the owner (The person who requested you to sign the document)
  
  

 
  **Sign document**
----
   

* **URL**

  /documents/:id/sign

* **Method:**
  
  `POST`

*  **URL Params**

   id: The id of the document that you want to sign off


* **Data Params**

  n/a

* **Success Response:**

  * **Code:** 200 <br />
    **Content:**
```json
    {
        "id": 3,
        "document": "http://localhost/documents/18526760.pdf",
        "owner_id": "3",
        "authorizer_id": "1",
        "created_at": "2018-07-13 22:37:59",
        "updated_at": "2018-07-13 22:37:59",
        "status": "SIGNED",
        "signature": "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
        "owner": {
            "id": 3,
            "name": "Enrique Flores Ramírez"
        }
    }
```

* **Sample Call:**

  `GET http://localhost/smart-signature/api/v1/documents/3/sign`


* **Notes:**
  This endpoint will sign the document by the user related to the token sent in the authorization header if it has the ability to do so. The response will include the updated information of the document including the signature string that will serve as the smart signature for the document
